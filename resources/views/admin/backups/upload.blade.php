<x-admin-layout>
    <div class="mb-10">
        <h2 class="font-black text-3xl text-slate-800 dark:text-white leading-tight tracking-tight">
            {{ __('Restaurar') }} <span class="text-rose-600">Base de Datos</span>
        </h2>
        <p class="text-slate-500 dark:text-slate-400 text-[10px] uppercase font-black tracking-widest mt-1">Carga de archivos de respaldo</p>
    </div>
    <div>
        <div class="max-w-3xl mx-auto pb-10">
            <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-sm rounded-[2.5rem] border border-slate-100 dark:border-slate-800 relative group">
                <div class="absolute top-0 right-0 p-10 opacity-[0.03] group-hover:opacity-[0.05] transition-opacity">
                    <svg class="w-32 h-32 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                </div>

                <div class="p-10">
                    <div class="bg-rose-50 dark:bg-rose-900/10 p-6 rounded-2xl mb-10 border border-rose-100 dark:border-rose-900/30 flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 flex items-center justify-center shadow-sm flex-shrink-0">
                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div>
                            <p class="text-[11px] font-black uppercase tracking-widest text-rose-600 mb-1">Advertencia Crítica</p>
                            <p class="text-[10px] font-bold text-rose-800 dark:text-rose-300 leading-relaxed">
                                Al cargar un archivo de backup, se borrarán todos los datos actuales (usuarios, préstamos, elementos) y se reemplazarán por los del archivo. Esta acción <span class="text-rose-600 uppercase font-black">no se puede deshacer</span>.
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('admin.backups.restore') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-8">
                            <div class="space-y-4">
                                <label for="backup_file" class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-700 dark:text-slate-300 ml-1">Seleccionar Archivo de Respaldo</label>
                                <div class="relative group/input">
                                    <input type="file" name="backup_file" id="backup_file" class="hidden" required onchange="updateFileName(this)" />
                                    <label for="backup_file" class="flex flex-col items-center justify-center w-full h-32 px-4 transition bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 border-dashed rounded-3xl hover:bg-slate-100 dark:hover:bg-slate-700 hover:border-[#39A900] dark:hover:border-[#39A900] cursor-pointer group-hover/input:scale-[1.01]">
                                        <div class="flex flex-center items-center gap-3">
                                            <svg class="w-6 h-6 text-slate-400 group-hover:text-[#39A900]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                            <span id="file-name" class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Click para seleccionar .sql</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="bg-gray-50 dark:bg-slate-800/50 p-4 rounded-xl border border-dashed border-gray-200 dark:border-slate-700 text-[9px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-600 text-center">
                                Asegúrese de que el archivo sea una copia válida generada por este sistema.
                            </div>

                            <div class="flex items-center justify-end gap-6 pt-6">
                                <a href="{{ route('admin.backups.index') }}" class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">Cancelar</a>
                                <button type="submit" class="bg-rose-600 text-white px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-700 transition-all shadow-xl shadow-rose-900/20 active:scale-95 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    Iniciar Restauración
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function updateFileName(input) {
            const fileName = input.files[0]?.name || 'No se seleccionó archivo';
            document.getElementById('file-name').innerText = fileName;
        }
    </script>
</x-admin-layout>