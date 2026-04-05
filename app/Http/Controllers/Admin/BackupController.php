<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class BackupController extends Controller
{
    /**
     * Obtiene la ruta al ejecutable de mysql o mysqldump.
     */
    private function getBinaryPath($binary = 'mysqldump')
    {
        // 1. Intentar desde .env
        $envPath = env('DB_DUMP_PATH');
        if ($envPath) {
            $path = rtrim($envPath, '/\\') . DIRECTORY_SEPARATOR . $binary . (PHP_OS_FAMILY === 'Windows' ? '.exe' : '');
            if (File::exists($path)) {
                return '"' . $path . '"';
            }
        }

        // 2. Intentar en el PATH del sistema
        $command = PHP_OS_FAMILY === 'Windows' ? "where $binary" : "which $binary";
        exec($command, $output, $returnVar);
        if ($returnVar === 0 && !empty($output)) {
            return $binary;
        }

        // 3. Rutas comunes en Laragon (Solo para Windows)
        if (PHP_OS_FAMILY === 'Windows') {
            $laragonPath = 'C:\\laragon\\bin\\mysql';
            if (File::exists($laragonPath)) {
                $dirs = File::directories($laragonPath);
                foreach ($dirs as $dir) {
                    $path = $dir . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR . $binary . '.exe';
                    if (File::exists($path)) {
                        return '"' . $path . '"';
                    }
                }
            }
        }

        return $binary; // Fallback al nombre simple
    }

    /**
     * Muestra el listado de archivos de backup guardados en el servidor.
     */
    public function index()
    {
        $path = storage_path('app/backups');
        $backups = [];

        if (File::exists($path)) {
            $files = File::files($path);
            foreach ($files as $file) {
                $backups[] = [
                    'file_name' => $file->getFilename(),
                    'file_size' => round($file->getSize() / 1024 / 1024, 2) . ' MB',
                    'last_modified' => Carbon::createFromTimestamp($file->getMTime())->format('d/m/Y H:i:s'),
                ];
            }
        }

        // Ordenar por fecha (más reciente primero)
        usort($backups, fn($a, $b) => strcmp($b['last_modified'], $a['last_modified']));

        return view('admin.backups.index', compact('backups'));
    }

    /**
     * Genera un nuevo backup SQL.
     */
    public function create()
    {
        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');
        $fileName = "backup_sena_" . Carbon::now()->format('Y-m-d_H-i-s') . ".sql";
        $folderPath = storage_path("app/backups");

        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true);
        }

        $path = $folderPath . '/' . $fileName;
        $mysqldump = $this->getBinaryPath('mysqldump');

        // Comando mysqldump con rutas protegidas por comillas
        $command = "$mysqldump -u $dbUser " . ($dbPass ? "-p$dbPass " : "") . "$dbName > \"$path\"";
        
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            return back()->with('error', 'Error al generar el backup. Verifique la configuración de mysqldump en el servidor.');
        }

        return redirect()->route('admin.backups.index')->with('success', 'Copia de seguridad generada: ' . $fileName);
    }

    /**
     * Descarga un archivo específico.
     */
    public function download($fileName)
    {
        $path = storage_path("app/backups/" . $fileName);
        if (File::exists($path)) {
            return response()->download($path);
        }
        return back()->with('error', 'El archivo no existe.');
    }

    /**
     * Elimina un archivo de backup del servidor.
     */
    public function destroy($fileName)
    {
        $path = storage_path("app/backups/" . $fileName);
        if (File::exists($path)) {
            File::delete($path);
            return back()->with('success', 'Archivo eliminado correctamente.');
        }
        return back()->with('error', 'No se pudo encontrar el archivo.');
    }

    /**
     * Muestra la vista para subir y restaurar un backup.
     */
    public function upload()
    {
        return view('admin.backups.upload');
    }

    /**
     * Restaura la base de datos usando un archivo subido.
     */
    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file'
        ]);

        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');
        
        $file = $request->file('backup_file');
        $path = $file->getRealPath();
        $mysql = $this->getBinaryPath('mysql');

        // Comando para importar SQL con rutas protegidas
        $command = "$mysql -u $dbUser " . ($dbPass ? "-p$dbPass " : "") . "$dbName < \"$path\"";
        
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            return back()->with('error', 'Error al restaurar la base de datos. Verifique el ejecutable mysql y el archivo SQL.');
        }

        return redirect()->route('admin.backups.index')->with('success', 'Base de datos restaurada con éxito.');
    }
}