<x-admin-layout>
    <div class="mb-8">
        <h2 class="font-black text-2xl text-slate-800 dark:text-white leading-tight tracking-tight">
            {{ __('Aplicar Nueva') }} <span class="text-rose-600">Sanción</span>
        </h2>
        <p class="text-slate-500 dark:text-slate-400 text-[10px] uppercase font-black tracking-widest mt-1">Restricción de acceso al sistema de préstamos</p>
    </div>

    <div>
        <div class="max-w-3xl mx-auto">
            <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-sm rounded-[2.5rem] p-10 border border-slate-100 dark:border-slate-800 relative group">
                <div class="absolute top-0 right-0 p-8 opacity-[0.03] group-hover:opacity-[0.05] transition-opacity">
                    <svg class="w-32 h-32 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.366zM7.5 4.805a6 6 0 017.39 7.39L7.5 4.805zM10 18a8 8 0 100-16 8 8 0 000 16z" clip-rule="evenodd" /></svg>
                </div>

                <form action="{{ route('admin.sanciones.store') }}" method="POST">
                    @csrf
                    <div class="space-y-8 relative">
                        <div class="grid grid-cols-1 gap-8">
                            <div class="space-y-2">
                                <label for="user_id" class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-700 dark:text-slate-300 ml-1">Usuario Sena a Sancionar</label>
                                <select name="user_id" id="user_id" class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-2xl shadow-sm focus:ring-amber-500 focus:border-amber-500 text-sm font-bold text-slate-800 dark:text-white transition-all py-3 px-4" required>
                                    <option value="" class="dark:bg-slate-900">Seleccione un usuario...</option>
                                    @foreach($usuarios as $usuario)
                                        <option value="{{ $usuario->id }}" class="dark:bg-slate-900">{{ $usuario->name }} ({{ $usuario->documento }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label for="motivo" class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-700 dark:text-slate-300 ml-1">Motivo Detallado</label>
                                <textarea id="motivo" name="motivo" class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-2xl shadow-sm focus:ring-amber-500 focus:border-amber-500 text-sm font-bold text-slate-800 dark:text-white transition-all py-3 px-4" rows="4" required placeholder="Ej: No entregó el portátil en la fecha acordada o presenta daños..."></textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="fecha_inicio" class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-700 dark:text-slate-300 ml-1">Fecha de Inicio</label>
                                    <input id="fecha_inicio" type="date" name="fecha_inicio" class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-2xl shadow-sm focus:ring-amber-500 focus:border-amber-500 text-sm font-bold text-slate-800 dark:text-white transition-all py-3 px-4" required value="{{ date('Y-m-d') }}" />
                                </div>
                                <div class="space-y-2">
                                    <label for="fecha_fin" class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-700 dark:text-slate-300 ml-1">Fecha de Expiración</label>
                                    <input id="fecha_fin" type="date" name="fecha_fin" class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-2xl shadow-sm focus:ring-amber-500 focus:border-amber-500 text-sm font-bold text-slate-800 dark:text-white transition-all py-3 px-4" required />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-12 gap-6">
                        <a href="{{ route('admin.sanciones.index') }}" class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">Cancelar</a>
                        <button type="submit" class="bg-rose-600 text-white px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-700 transition-all shadow-xl shadow-rose-900/20 active:scale-95 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.366zM7.5 4.805a6 6 0 017.39 7.39L7.5 4.805zM10 18a8 8 0 100-16 8 8 0 000 16z" /></svg>
                            Confirmar Bloqueo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>