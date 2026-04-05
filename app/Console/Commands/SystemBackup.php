<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SystemBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a system backup SQL automatically';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Starting automated system backup (SQL)...");
        Log::info("Scheduled backup started.");

        try {
            $dbName = env('DB_DATABASE');
            $dbUser = env('DB_USERNAME');
            $dbPass = env('DB_PASSWORD');
            $fileName = "backup_auto_" . now()->format('Y-m-d_H-i-s') . ".sql";
            $folderPath = storage_path("app/backups");

            if (!is_dir($folderPath)) {
                mkdir($folderPath, 0755, true);
            }

            $path = $folderPath . '/' . $fileName;
            
            // Reutilizar lógica de búsqueda de binarios si fuera necesario, 
            // pero para CLI usualmente mysqldump está en el PATH.
            $command = "mysqldump -u $dbUser " . ($dbPass ? "-p$dbPass " : "") . "$dbName > \"$path\"";
            
            exec($command, $output, $returnVar);

            if ($returnVar !== 0) {
                throw new \Exception("mysqldump failed with return code $returnVar");
            }

            $this->info("SQL Backup completed: $fileName");
            Log::info("Scheduled backup finished successfully.");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Backup failed: " . $e->getMessage());
            Log::error("Scheduled backup failed: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
