```blade
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>TicketPro - Backups</title>

    <link rel="icon" type="image/png" href="{{ asset('storage/images/logo.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body
    x-data="{ menuMovil: false }"
    class="min-h-screen bg-[#070b19] text-white font-sans antialiased">

    <aside
        :class="menuMovil ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
        class="fixed inset-y-0 left-0 z-[99999] w-[280px] max-w-[85vw] border-r border-slate-800/60 bg-[#0a0f24] p-5 sm:p-6 flex flex-col justify-between transition-transform duration-300">

        <div>
            <div class="flex items-center justify-between mb-8 sm:mb-10">
                <span class="text-2xl sm:text-3xl font-extrabold tracking-wide text-white">
                    Ticket<span class="text-blue-500">Pro</span>
                </span>

                <button
                    type="button"
                    @click="menuMovil = false"
                    class="flex md:hidden h-9 w-9 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="flex items-center gap-3 mb-8 p-2 rounded-xl bg-slate-900/40 border border-slate-800/50">
                <img
                    src="{{ auth()->user()->picture
                        ? asset('storage/' . auth()->user()->picture)
                        : asset('storage/profile-photos/user.png') }}"
                    alt="{{ auth()->user()->name }}"
                    class="h-11 w-11 sm:h-12 sm:w-12 shrink-0 rounded-full border-2 border-gray-500 object-cover">

                <div class="overflow-hidden min-w-0">
                    <h4 class="text-sm font-semibold text-slate-200 truncate">
                        {{ auth()->user()->name ?? 'Desconocido' }}
                    </h4>

                    <p class="text-xs text-slate-400 truncate">
                        {{ auth()->user()->role ?? 'Desconocido' }}
                    </p>
                </div>
            </div>

            <nav class="space-y-2">
                <a
                    href="{{ route('tecnologias') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 shrink-0"></i>
                    <span class="font-medium text-sm">
                        Inicio
                    </span>
                </a>

                <a
                    href="{{ route('tickettecnologias') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">
                    <i data-lucide="ticket-check" class="w-5 h-5 shrink-0"></i>
                    <span class="font-medium text-sm">
                        Tickets
                    </span>
                </a>

                @if (
                    auth()->check() &&
                    auth()->user()->role === 'Gerente Ti' &&
                    auth()->user()->priv_admin === 'Y'
                )
                    <a
                        href="{{ route('cambiostecnologias') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">
                        <i data-lucide="git-compare-arrows" class="w-5 h-5 shrink-0"></i>
                        <span class="text-sm">
                            Cambios
                        </span>
                    </a>

                    <a
                        href="{{ route('usuarios.tecnologias') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">
                        <i data-lucide="users" class="w-5 h-5 shrink-0"></i>
                        <span class="text-sm">
                            Usuarios
                        </span>
                    </a>
                @endif

                <a
                    href="{{ route('dispositivos') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">
                    <i data-lucide="monitor-smartphone" class="w-5 h-5 shrink-0"></i>
                    <span class="text-sm font-medium">
                        Dispositivos
                    </span>
                </a>

                <a
                    href="{{ route('avisostecnologias') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">
                    <i data-lucide="megaphone" class="w-5 h-5 shrink-0"></i>
                    <span class="font-medium text-sm">
                        Avisos
                    </span>
                </a>

                <a
                    href="{{ route('perfiltecnologias') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">
                    <i data-lucide="circle-user-round" class="w-5 h-5 shrink-0"></i>
                    <span class="font-medium text-sm">
                        Mi perfil
                    </span>
                </a>
            </nav>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button
                type="submit"
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-red-500/10 hover:text-red-400 transition">
                <i data-lucide="log-out" class="w-5 h-5"></i>

                <span class="font-medium text-sm">
                    Cerrar sesión
                </span>
            </button>
        </form>
    </aside>

    <div
        x-show="menuMovil"
        x-cloak
        x-transition.opacity
        @click="menuMovil = false"
        class="fixed inset-0 z-[99998] bg-black/70 backdrop-blur-sm md:hidden">
    </div>

    <main class="md:ml-[280px] min-h-screen px-4 py-5 sm:px-6 sm:py-6 lg:px-8 lg:py-8 pt-20 md:pt-8">

        <div class="max-w-[1500px] mx-auto">

            <button
                type="button"
                @click="menuMovil = true"
                class="md:hidden fixed top-4 left-4 z-[99997] flex h-10 w-10 items-center justify-center rounded-xl bg-[#0f1535] border border-slate-800 text-slate-300 hover:bg-slate-800 hover:text-white transition">
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>

            <header class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between mb-7 sm:mb-8">

                <div>
                    <div class="flex items-center gap-3">

                        <div class="w-11 h-11 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center">
                            <i
                                data-lucide="database-backup"
                                class="w-6 h-6 text-emerald-400">
                            </i>
                        </div>

                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                                Backups
                            </h1>

                            <p class="text-xs sm:text-sm text-slate-400 mt-1">
                                Administra y programa las copias de seguridad del sistema.
                            </p>
                        </div>

                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2 px-3 py-2 rounded-xl bg-emerald-500/10 border border-emerald-500/20">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>

                        <span class="text-xs font-medium text-emerald-300">
                            Sistema protegido
                        </span>
                    </div>
                </div>

            </header>

            <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

                <div class="rounded-2xl border border-slate-800/80 bg-[#0f1535] p-5">
                    <div class="flex items-center justify-between">

                        <div class="w-10 h-10 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center">
                            <i data-lucide="database" class="w-5 h-5 text-blue-400"></i>
                        </div>

                        <span class="text-[10px] text-slate-500">
                            ALMACENAMIENTO
                        </span>

                    </div>

                    <p class="mt-4 text-2xl font-bold text-white">
                        {{ $espacioUsado ?? '0 GB' }}
                    </p>

                    <p class="text-xs text-slate-500 mt-1">
                        Espacio utilizado por backups
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-800/80 bg-[#0f1535] p-5">
                    <div class="flex items-center justify-between">

                        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center">
                            <i data-lucide="shield-check" class="w-5 h-5 text-emerald-400"></i>
                        </div>

                        <span class="text-[10px] text-slate-500">
                            ÚLTIMO BACKUP
                        </span>

                    </div>

                    <p class="mt-4 text-lg font-bold text-white">
                        {{ $ultimoBackup ?? 'Nunca' }}
                    </p>

                    <p class="text-xs text-slate-500 mt-1">
                        Última copia completada
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-800/80 bg-[#0f1535] p-5">
                    <div class="flex items-center justify-between">

                        <div class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center">
                            <i data-lucide="calendar-clock" class="w-5 h-5 text-indigo-400"></i>
                        </div>

                        <span class="text-[10px] text-slate-500">
                            PRÓXIMO BACKUP
                        </span>

                    </div>

                    <p class="mt-4 text-lg font-bold text-white">
                        {{ $proximoBackup ?? 'No programado' }}
                    </p>

                    <p class="text-xs text-slate-500 mt-1">
                        Próxima ejecución automática
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-800/80 bg-[#0f1535] p-5">
                    <div class="flex items-center justify-between">

                        <div class="w-10 h-10 rounded-xl bg-violet-500/10 border border-violet-500/20 flex items-center justify-center">
                            <i data-lucide="archive" class="w-5 h-5 text-violet-400"></i>
                        </div>

                        <span class="text-[10px] text-slate-500">
                            COPIAS
                        </span>

                    </div>

                    <p class="mt-4 text-2xl font-bold text-white">
                        {{ $totalBackups ?? 0 }}
                    </p>

                    <p class="text-xs text-slate-500 mt-1">
                        Backups almacenados
                    </p>
                </div>

            </section>

            <div
                class="grid grid-cols-1 xl:grid-cols-12 gap-6"
                x-data="{
                    frecuencia: @js($configuracion->frecuencia ?? 'diario'),
                    automatico: {{ ($configuracion->activo ?? false) ? 'true' : 'false' }}
                }">

                <section class="xl:col-span-5">

                    <div class="rounded-2xl border border-slate-800/80 bg-[#0f1535] overflow-hidden">

                        <div class="px-5 sm:px-6 py-5 border-b border-slate-800/80">

                            <div class="flex items-center gap-3">

                                <div class="w-10 h-10 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center">
                                    <i
                                        data-lucide="settings-2"
                                        class="w-5 h-5 text-blue-400">
                                    </i>
                                </div>

                                <div>
                                    <h2 class="text-sm font-semibold text-white">
                                        Configuración automática
                                    </h2>

                                    <p class="text-[11px] text-slate-500 mt-1">
                                        Define cuándo se ejecutarán los backups.
                                    </p>
                                </div>

                            </div>

                        </div>

                        <form
                            method="POST"
                            action="{{ route('backups.configurar') }}"
                            class="p-5 sm:p-6 space-y-5">

                            @csrf

                            <div class="flex items-center justify-between gap-4 rounded-xl border border-slate-800 bg-[#0b1026] p-4">

                                <div class="flex items-center gap-3">

                                    <div class="w-9 h-9 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                                        <i
                                            data-lucide="power"
                                            class="w-4 h-4 text-emerald-400">
                                        </i>
                                    </div>

                                    <div>
                                        <p class="text-sm font-medium text-white">
                                            Backups automáticos
                                        </p>

                                        <p class="text-[10px] text-slate-500">
                                            Ejecutar backups automáticamente
                                        </p>
                                    </div>

                                </div>

                                <button
                                    type="button"
                                    @click="automatico = !automatico"
                                    :class="automatico ? 'bg-blue-600' : 'bg-slate-700'"
                                    class="relative w-11 h-6 rounded-full transition">

                                    <span
                                        :class="automatico ? 'translate-x-5' : 'translate-x-1'"
                                        class="absolute top-1 left-0 w-4 h-4 rounded-full bg-white transition-transform">
                                    </span>

                                </button>

                                <input
                                    type="hidden"
                                    name="activo"
                                    :value="automatico ? 1 : 0">

                            </div>

                            <div>

                                <label class="block text-xs font-semibold text-slate-300 mb-2">
                                    Frecuencia
                                </label>

                                <select
                                    name="frecuencia"
                                    x-model="frecuencia"
                                    :disabled="!automatico"
                                    class="w-full bg-[#060818] border border-[#1e295d] rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-blue-500 disabled:opacity-40 transition">

                                    <option value="diario">
                                        Diario
                                    </option>

                                    <option value="semanal">
                                        Semanal
                                    </option>

                                    <option value="mensual">
                                        Mensual
                                    </option>

                                </select>

                            </div>

                            <div
                                x-show="frecuencia === 'semanal'"
                                x-cloak>

                                <label class="block text-xs font-semibold text-slate-300 mb-2">
                                    Día de la semana
                                </label>

                                <select
                                    name="dia_semana"
                                    :disabled="!automatico"
                                    class="w-full bg-[#060818] border border-[#1e295d] rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-blue-500 disabled:opacity-40 transition">

                                    <option value="1" {{ ($configuracion->dia_semana ?? 1) == 1 ? 'selected' : '' }}>
                                        Lunes
                                    </option>

                                    <option value="2" {{ ($configuracion->dia_semana ?? 1) == 2 ? 'selected' : '' }}>
                                        Martes
                                    </option>

                                    <option value="3" {{ ($configuracion->dia_semana ?? 1) == 3 ? 'selected' : '' }}>
                                        Miércoles
                                    </option>

                                    <option value="4" {{ ($configuracion->dia_semana ?? 1) == 4 ? 'selected' : '' }}>
                                        Jueves
                                    </option>

                                    <option value="5" {{ ($configuracion->dia_semana ?? 1) == 5 ? 'selected' : '' }}>
                                        Viernes
                                    </option>

                                    <option value="6" {{ ($configuracion->dia_semana ?? 1) == 6 ? 'selected' : '' }}>
                                        Sábado
                                    </option>

                                    <option value="7" {{ ($configuracion->dia_semana ?? 1) == 7 ? 'selected' : '' }}>
                                        Domingo
                                    </option>

                                </select>

                            </div>

                            <div
                                x-show="frecuencia === 'mensual'"
                                x-cloak>

                                <label class="block text-xs font-semibold text-slate-300 mb-2">
                                    Día del mes
                                </label>

                                <select
                                    name="dia_mes"
                                    :disabled="!automatico"
                                    class="w-full bg-[#060818] border border-[#1e295d] rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-blue-500 disabled:opacity-40 transition">

                                    @for ($i = 1; $i <= 31; $i++)
                                        <option
                                            value="{{ $i }}"
                                            {{ ($configuracion->dia_mes ?? 1) == $i ? 'selected' : '' }}>
                                            Día {{ $i }}
                                        </option>
                                    @endfor

                                </select>

                            </div>

                            <div>

                                <label class="block text-xs font-semibold text-slate-300 mb-2">
                                    Hora de ejecución
                                </label>

                                <div class="relative">

                                    <i
                                        data-lucide="clock"
                                        class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500">
                                    </i>

                                    <input
                                        type="time"
                                        name="hora"
                                        value="{{ $configuracion->hora ?? '02:00' }}"
                                        :disabled="!automatico"
                                        class="w-full bg-[#060818] border border-[#1e295d] rounded-xl pl-11 pr-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-blue-500 disabled:opacity-40 transition">

                                </div>

                                <p class="text-[10px] text-slate-500 mt-2">
                                    La hora corresponde al horario del servidor.
                                </p>

                            </div>

                            <div class="rounded-xl border border-blue-500/10 bg-blue-500/[0.04] p-4">

                                <div class="flex gap-3">

                                    <i
                                        data-lucide="info"
                                        class="w-4 h-4 text-blue-400 shrink-0 mt-0.5">
                                    </i>

                                    <p class="text-[11px] leading-relaxed text-slate-400">
                                        Los backups automáticos almacenarán una copia de la información del sistema según la frecuencia configurada.
                                    </p>

                                </div>

                            </div>

                            <button
                                type="submit"
                                class="w-full flex items-center justify-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-500 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/20 transition">

                                <i data-lucide="save" class="w-4 h-4"></i>

                                Guardar configuración

                            </button>

                        </form>

                    </div>

                </section>

                <section class="xl:col-span-7 space-y-6">

                    <div class="rounded-2xl border border-emerald-500/20 bg-gradient-to-br from-[#10183b] to-[#0d132e] p-5 sm:p-6">

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">

                            <div class="flex items-center gap-4">

                                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center">
                                    <i
                                        data-lucide="database-zap"
                                        class="w-6 h-6 text-emerald-400">
                                    </i>
                                </div>

                                <div>
                                    <h2 class="text-base font-semibold text-white">
                                        Backup manual
                                    </h2>

                                    <p class="text-xs text-slate-400 mt-1">
                                        Crea una copia de seguridad inmediatamente.
                                    </p>
                                </div>

                            </div>

                            <form
                                method="POST"
                                action="{{ route('backups.crear') }}"
                                class="w-full sm:w-auto">

                                @csrf

                                <button
                                    type="submit"
                                    class="w-full sm:w-auto flex items-center justify-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-600/20 transition">

                                    <i data-lucide="play" class="w-4 h-4"></i>

                                    Crear backup ahora

                                </button>

                            </form>

                        </div>

                    </div>

                    <div class="rounded-2xl border border-slate-800/80 bg-[#0f1535] overflow-hidden">

                        <div class="px-5 sm:px-6 py-5 border-b border-slate-800/80">

                            <div class="flex items-center justify-between gap-4">

                                <div>
                                    <h2 class="text-sm font-semibold text-white">
                                        Historial de backups
                                    </h2>

                                    <p class="text-[11px] text-slate-500 mt-1">
                                        Copias de seguridad generadas por el sistema.
                                    </p>
                                </div>

                                <div class="hidden sm:flex items-center gap-2 text-[10px] text-slate-500">
                                    <i data-lucide="history" class="w-4 h-4"></i>
                                    Historial
                                </div>

                            </div>

                        </div>

                        <div class="overflow-x-auto">

                            <table class="w-full text-left text-xs">

                                <thead>
                                    <tr class="text-slate-500 border-b border-slate-800/80">

                                        <th class="px-5 sm:px-6 py-4 font-semibold">
                                            Backup
                                        </th>

                                        <th class="px-5 py-4 font-semibold">
                                            Tipo
                                        </th>

                                        <th class="px-5 py-4 font-semibold">
                                            Tamaño
                                        </th>

                                        <th class="px-5 py-4 font-semibold">
                                            Estado
                                        </th>

                                        <th class="px-5 py-4 font-semibold">
                                            Fecha
                                        </th>

                                        <th class="px-5 sm:px-6 py-4 font-semibold text-right">
                                            Acción
                                        </th>

                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-slate-800/60">

                                    @forelse ($backups ?? [] as $backup)

                                        <tr class="hover:bg-slate-800/20 transition">

                                            <td class="px-5 sm:px-6 py-4">

                                                <div class="flex items-center gap-3">

                                                    <div class="w-9 h-9 rounded-lg bg-blue-500/10 border border-blue-500/20 flex items-center justify-center shrink-0">
                                                        <i
                                                            data-lucide="file-archive"
                                                            class="w-4 h-4 text-blue-400">
                                                        </i>
                                                    </div>

                                                    <div class="min-w-0">

                                                        <p class="font-medium text-slate-200 truncate max-w-[180px]">
                                                            {{ $backup->nombre ?? 'backup.sql' }}
                                                        </p>

                                                        <p class="text-[10px] text-slate-500 mt-0.5">
                                                            #{{ $backup->id ?? '—' }}
                                                        </p>

                                                    </div>

                                                </div>

                                            </td>

                                            <td class="px-5 py-4">

                                                @if (($backup->tipo ?? '') === 'manual')

                                                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-blue-500/10 border border-blue-500/20 px-2.5 py-1 text-[10px] font-medium text-blue-300">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                                                        Manual
                                                    </span>

                                                @else

                                                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1 text-[10px] font-medium text-emerald-300">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                                        Automático
                                                    </span>

                                                @endif

                                            </td>

                                            <td class="px-5 py-4 text-slate-400 whitespace-nowrap">
                                                {{ $backup->tamaño ?? '—' }}
                                            </td>

                                            <td class="px-5 py-4">

                                                @if (($backup->estado ?? '') === 'completado')

                                                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1 text-[10px] font-medium text-emerald-300">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                                        Completado
                                                    </span>

                                                @elseif (($backup->estado ?? '') === 'error')

                                                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-red-500/10 border border-red-500/20 px-2.5 py-1 text-[10px] font-medium text-red-300">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                                                        Error
                                                    </span>

                                                @else

                                                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-amber-500/10 border border-amber-500/20 px-2.5 py-1 text-[10px] font-medium text-amber-300">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                                        {{ ucfirst($backup->estado ?? 'Pendiente') }}
                                                    </span>

                                                @endif

                                            </td>

                                            <td class="px-5 py-4 text-slate-400 whitespace-nowrap">
                                                {{ $backup->created_at ?? '—' }}
                                            </td>

                                            <td class="px-5 sm:px-6 py-4">

                                                <div class="flex items-center justify-end gap-2">

                                                    @if (
                                                        !empty($backup->id) &&
                                                        ($backup->estado ?? '') === 'completado'
                                                    )

                                                        <a
                                                            href="{{ route('backups.descargar', ['id' => $backup->id]) }}"
                                                            title="Descargar backup"
                                                            class="group w-9 h-9 rounded-lg bg-blue-600/10 border border-blue-500/20 hover:bg-blue-600 hover:border-blue-500 text-blue-400 hover:text-white flex items-center justify-center transition">

                                                            <i
                                                                data-lucide="download"
                                                                class="w-4 h-4 group-hover:scale-110 transition-transform">
                                                            </i>

                                                        </a>

                                                    @endif

                                                    @if (!empty($backup->id))

                                                        <form
                                                            method="POST"
                                                            action="{{ route('backups.eliminar', $backup->id) }}">

                                                            @csrf
                                                            @method('DELETE')

                                                            <button
                                                                type="submit"
                                                                title="Eliminar backup"
                                                                onclick="return confirm('¿Eliminar este backup?')"
                                                                class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-red-500/10 text-slate-400 hover:text-red-400 flex items-center justify-center transition">

                                                                <i
                                                                    data-lucide="trash-2"
                                                                    class="w-4 h-4">
                                                                </i>

                                                            </button>

                                                        </form>

                                                    @endif

                                                </div>

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td colspan="6" class="px-6 py-12">

                                                <div class="text-center">

                                                    <div class="mx-auto mb-4 w-14 h-14 rounded-2xl bg-slate-800/50 border border-slate-800 flex items-center justify-center">
                                                        <i
                                                            data-lucide="database-backup"
                                                            class="w-6 h-6 text-slate-500">
                                                        </i>
                                                    </div>

                                                    <p class="text-sm font-medium text-slate-400">
                                                        No hay backups registrados
                                                    </p>

                                                    <p class="text-xs text-slate-600 mt-1">
                                                        Los backups generados aparecerán aquí.
                                                    </p>

                                                </div>

                                            </td>

                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                        @if (isset($backups) && method_exists($backups, 'links'))

                            <div class="px-5 sm:px-6 py-4 border-t border-slate-800/80">
                                {{ $backups->links() }}
                            </div>

                        @endif

                    </div>

                </section>

            </div>

            <section class="mt-6 rounded-2xl border border-amber-500/10 bg-amber-500/[0.025] p-5 sm:p-6">

                <div class="flex gap-4">

                    <div class="w-10 h-10 shrink-0 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center">
                        <i
                            data-lucide="shield-alert"
                            class="w-5 h-5 text-amber-400">
                        </i>
                    </div>

                    <div>

                        <h3 class="text-sm font-semibold text-slate-200">
                            Seguridad de los backups
                        </h3>

                        <p class="text-xs leading-relaxed text-slate-500 mt-1 max-w-4xl">
                            Las copias de seguridad contienen información importante del sistema.
                            Se recomienda conservarlas en una ubicación protegida y limitar el acceso
                            únicamente al personal autorizado.
                        </p>

                    </div>

                </div>

            </section>

        </div>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>

</body>

</html>
```
