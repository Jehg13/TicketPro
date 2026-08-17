<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TicketPro - Tecnologías / Soporte</title>
        <link rel="icon" type="image/png" href="{{ asset('storage/images/logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="bg-[#050814] text-white font-sans min-h-screen antialiased">

    <!-- SIDEBAR -->
    <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0a0f24] border-r border-slate-800/60 p-6 hidden md:flex flex-col justify-between">
        <div>
            <div class="flex items-center gap-2 mb-10">
                <span class="text-3xl font-extrabold tracking-wide text-white">
                    Ticket<span class="text-blue-500">Pro</span>
                </span>
            </div>

            <div class="flex items-center gap-3 mb-8 p-2 rounded-xl bg-slate-900/40 border border-slate-800/50">
                <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="{{ auth()->user()->name }}"
                    class="w-10 h-10 rounded-full object-cover ring-2 ring-blue-500/30">

                <div class="overflow-hidden">
                    <h4 class="text-sm font-semibold text-slate-200 truncate">
                        {{ Auth::user()->name ?? 'Juan Pérez' }}
                    </h4>
                    <p class="text-xs text-slate-400 truncate">
                        {{ Auth::user()->departamento->nombre ?? 'jperez@cymez.com' }}
                    </p>
                </div>
            </div>

            <nav class="space-y-2">
                <a href="{{ route('tecnologias')}}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold shadow-lg shadow-blue-600/30 transition">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span class="text-sm">Inicio</span>
                </a>

                <a href="{{ route('tickettecnologias')}}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">
                    <i data-lucide="ticket-check" class="w-5 h-5"></i>
                    <span class="font-medium text-sm">Tickets</span>
                </a>

                <a href="{{ route('cambiostecnologias')}}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">
                    <i data-lucide="git-compare-arrows" class="w-5 h-5"></i>
                    <span class="font-medium text-sm">Cambios</span>
                </a>

                <a href="{{ route('avisostecnologias')}}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">
                    <i data-lucide="megaphone" class="w-5 h-5"></i>
                    <span class="font-medium text-sm">Avisos</span>
                </a>

                <a href="{{ route('perfiltecnologias')}}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">
                    <i data-lucide="circle-user-round" class="w-5 h-5"></i>
                    <span class="font-medium text-sm">Mi perfil</span>
                </a>
            </nav>
        </div>

        <div>
            <form method="POST" action="{{ route('logout') }}" class="mt-6">
                @csrf
                <button type="submit"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-red-500/10 hover:text-red-400 transition">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                    <span class="font-medium text-sm">Cerrar sesión</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="md:ml-64 min-h-screen p-6 md:p-8">
        <div class="max-w-[1400px] mx-auto space-y-6">

            <!-- HEADER Y PANEL SUPERIOR -->
            <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-white">
                        Tecnologías / Soporte
                    </h1>
                    <p class="text-sm text-slate-400 mt-1">
                        Dashboard de estadísticas y métricas del soporte técnico.
                    </p>
                </div>

                <div class="flex items-center gap-4 self-end md:self-auto">
   
                        {{-- NOTIFICACIONES --}}

                        <div class="relative inline-block text-left">

                            <button id="notif-button" type="button"
                                class="relative p-2 text-gray-300 hover:text-white bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700/50 rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 group shadow-lg"
                                aria-label="Ver notificaciones">

                                <svg class="w-6 h-6 transition-transform group-hover:scale-110 duration-200"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />

                                </svg>


                                <span class="absolute top-1.5 right-1.5 flex h-3 w-3">

                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>

                                    <span
                                        class="relative inline-flex rounded-full h-3 w-3 bg-rose-500 border-2 border-slate-900"></span>

                                </span>

                            </button>


                            <div id="notif-dropdown"
                                class="hidden absolute right-0 mt-3 w-80 sm:w-96 rounded-2xl bg-slate-900/95 backdrop-blur-md border border-slate-800 shadow-2xl z-50 overflow-hidden divide-y divide-slate-800">

                                <div class="p-4 flex items-center justify-between">

                                    <div class="flex items-center gap-2">

                                        <h3 class="text-sm font-semibold text-white">
                                            Notificaciones
                                        </h3>

                                        <span
                                            class="px-2 py-0.5 text-xs font-medium bg-indigo-500/10 text-indigo-400 rounded-full border border-indigo-500/20">
                                            3 nuevas
                                        </span>

                                    </div>

                                    <button type="button"
                                        class="text-xs text-indigo-400 hover:text-indigo-300 font-medium transition-colors">
                                        Marcar leídas
                                    </button>

                                </div>


                                <div class="max-h-80 overflow-y-auto divide-y divide-slate-800/50">

                                    <a href="#"
                                        class="flex gap-3 p-4 bg-slate-800/40 hover:bg-slate-800/80 transition-colors group">

                                        <div class="relative shrink-0">

                                            <img class="w-10 h-10 rounded-full object-cover ring-2 ring-indigo-500/30"
                                                src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80"
                                                alt="Avatar">

                                            <span
                                                class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-slate-900 rounded-full"></span>

                                        </div>

                                        <div class="flex-1 min-w-0">

                                            <p class="text-xs text-slate-300 leading-relaxed">

                                                <strong
                                                    class="font-semibold text-white group-hover:text-indigo-400 transition-colors">
                                                    Elena Rostova
                                                </strong>

                                                comentó en tu proyecto

                                                <span class="text-slate-400">
                                                    Dashboard UI
                                                </span>.

                                            </p>

                                            <span class="text-[10px] text-slate-500 mt-1 block">
                                                Hace 2 minutos
                                            </span>

                                        </div>

                                        <span class="w-2 h-2 rounded-full bg-indigo-500 shrink-0 self-center"></span>

                                    </a>


                                    <a href="#"
                                        class="flex gap-3 p-4 hover:bg-slate-800/50 transition-colors group">

                                        <div
                                            class="w-10 h-10 rounded-full bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 shrink-0">

                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">

                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 10V3L4 14h7v7l9-11h-7z" />

                                            </svg>

                                        </div>

                                        <div class="flex-1 min-w-0">

                                            <p class="text-xs text-slate-300 leading-relaxed">

                                                Tu despliegue en

                                                <strong class="font-semibold text-white">
                                                    Vite/Production
                                                </strong>

                                                se completó con éxito.

                                            </p>

                                            <span class="text-[10px] text-slate-500 mt-1 block">
                                                Hace 1 hora
                                            </span>

                                        </div>

                                    </a>

                                </div>


                                <a href="#"
                                    class="block p-3 text-center text-xs font-medium text-slate-400 hover:text-white hover:bg-slate-800/50 transition-colors">
                                    Ver todas las notificaciones
                                </a>

                            </div>

                        </div>


        <!-- USUARIO -->
<!-- USUARIO -->
<div class="relative z-[100]">

    <!-- BOTÓN DEL USUARIO -->
    <button
        id="profile-button"
        type="button"
        class="relative flex items-center gap-3 bg-slate-900/80 border border-slate-800 rounded-full p-1.5 pr-4 hover:bg-slate-800 transition-all duration-200 focus:outline-none">

        <img
            src="{{ auth()->user()->foto
                ? asset('storage/' . auth()->user()->foto)
                : asset('images/default-avatar.png') }}"
            alt="{{ auth()->user()->name }}"
            class="w-8 h-8 rounded-full object-cover">

        <div class="text-left leading-tight hidden sm:block">

            <p class="text-xs font-semibold text-white">
                {{ auth()->user()->name ?? 'Desconocido' }}
            </p>

            <p class="text-[10px] text-blue-400 font-medium">
                {{ optional(auth()->user()->departamento)->nombre ?? 'Sin departamento' }}
            </p>

        </div>

        <svg
            id="profile-arrow"
            class="w-4 h-4 text-slate-400 ml-1 transition-transform duration-200"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24">

            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 9l-7 7-7-7">
            </path>

        </svg>

    </button>


    <!-- DROPDOWN -->
    <div
        id="profile-dropdown"
        class="hidden absolute right-0 top-full mt-3 w-56 bg-[#0f1535] border border-[#1e295d] rounded-xl shadow-2xl overflow-hidden z-[99999]">

        <!-- PERFIL -->
        <a
            href="{{ route('perfilusuario') }}"
            class="flex items-center gap-3 px-4 py-3 text-sm text-slate-300 hover:bg-[#151b3b] hover:text-white transition-colors">

            <svg
                class="w-5 h-5 text-slate-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                </path>

            </svg>

            <span>Perfil</span>

        </a>


        <!-- SEPARADOR -->
        <div class="border-t border-[#1e295d]"></div>


        <!-- CERRAR SESIÓN -->
        <form
            method="POST"
            action="{{ route('logout') }}">

            @csrf

            <button
                type="submit"
                class="w-full flex items-center gap-3 px-4 py-3 text-sm text-slate-300 hover:bg-red-500/10 hover:text-red-400 transition-colors text-left">

                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                    </path>

                </svg>

                <span>Cerrar sesión</span>

            </button>

        </form>

    </div>

</div>
                </div>
            </header>

            <!-- BARRA DE ACCIONES (FECHA Y EXPORTAR) -->
            <div class="flex justify-end gap-3">
                <button class="flex items-center gap-2 bg-[#0b1026] border border-slate-800/80 px-4 py-2 rounded-xl text-xs text-slate-300 hover:bg-slate-800 transition">
                    <i data-lucide="calendar" class="w-4 h-4 text-slate-400"></i>
                    <span>8 – 15 Mayo 2026</span>
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-slate-400 ml-1"></i>
                </button>
                <button class="flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-xl text-xs font-medium transition shadow-lg shadow-blue-600/20">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    <span>Exportar reporte</span>
                </button>
            </div>

            <!-- TARJETAS DE MÉTRICAS -->
            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Tickets abiertos -->
                <div class="p-4 rounded-2xl bg-[#0b1026] border border-slate-800/80">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 rounded-xl bg-blue-900/30 text-blue-400">
                            <i data-lucide="ticket" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400">Tickets abiertos</p>
                            <h3 class="text-2xl font-bold text-white mt-0.5">48</h3>
                        </div>
                    </div>
                    <p class="text-[11px] text-emerald-400 font-medium mt-3 flex items-center gap-1">
                        +12% <span class="text-slate-400 font-normal">vs semana pasada</span> <i data-lucide="arrow-up-right" class="w-3 h-3"></i>
                    </p>
                </div>

                <!-- Tickets pendientes -->
                <div class="p-4 rounded-2xl bg-[#0b1026] border border-slate-800/80">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 rounded-xl bg-purple-900/30 text-purple-400">
                            <i data-lucide="clock" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400">Tickets pendientes</p>
                            <h3 class="text-2xl font-bold text-white mt-0.5">17</h3>
                        </div>
                    </div>
                    <p class="text-[11px] text-emerald-400 font-medium mt-3 flex items-center gap-1">
                        +5% <span class="text-slate-400 font-normal">vs semana pasada</span>
                    </p>
                </div>

                <!-- Tickets resueltos -->
                <div class="p-4 rounded-2xl bg-[#0b1026] border border-slate-800/80">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 rounded-xl bg-emerald-900/30 text-emerald-400">
                            <i data-lucide="check-circle" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400">Tickets resueltos</p>
                            <h3 class="text-2xl font-bold text-white mt-0.5">126</h3>
                        </div>
                    </div>
                    <p class="text-[11px] text-emerald-400 font-medium mt-3 flex items-center gap-1">
                        +18% <span class="text-slate-400 font-normal">vs semana pasada</span> <i data-lucide="arrow-up-right" class="w-3 h-3"></i>
                    </p>
                </div>

                <!-- Tiempo promedio -->
                <div class="p-4 rounded-2xl bg-[#0b1026] border border-slate-800/80">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 rounded-xl bg-amber-900/30 text-amber-400">
                            <i data-lucide="timer" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400">Tiempo promedio de atención</p>
                            <h3 class="text-2xl font-bold text-white mt-0.5">2h 34m</h3>
                        </div>
                    </div>
                    <p class="text-[11px] text-emerald-400 font-medium mt-3 flex items-center gap-1">
                        -8% <span class="text-slate-400 font-normal">vs semana pasada</span> <i data-lucide="arrow-down-right" class="w-3 h-3"></i>
                    </p>
                </div>

                <!-- Tickets del mes -->
                <div class="p-4 rounded-2xl bg-[#0b1026] border border-slate-800/80">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 rounded-xl bg-blue-900/30 text-blue-400">
                            <i data-lucide="bar-chart-2" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400">Tickets del mes</p>
                            <h3 class="text-2xl font-bold text-white mt-0.5">174</h3>
                        </div>
                    </div>
                    <p class="text-[11px] text-emerald-400 font-medium mt-3 flex items-center gap-1">
                        +15% <span class="text-slate-400 font-normal">vs mes pasado</span> <i data-lucide="arrow-up-right" class="w-3 h-3"></i>
                    </p>
                </div>
            </section>

            <!-- SECCIÓN CENTRAL DE REPORTES Y TABLA DE EQUIPOS -->
            <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Quejas recurrentes -->
                <div class="p-5 rounded-2xl bg-[#0b1026] border border-slate-800/80 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <i data-lucide="alert-triangle" class="w-4 h-4 text-blue-400"></i>
                            <h3 class="text-sm font-semibold text-white">Quejas recurrentes</h3>
                        </div>
                        <p class="text-xs text-slate-400 mb-6">Problemas más reportados por los usuarios.</p>

                        <div class="space-y-3.5 text-xs">
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-slate-300">Problemas de red</span>
                                    <span class="font-medium text-white">32</span>
                                </div>
                                <div class="w-full bg-slate-800/60 h-2 rounded-full overflow-hidden">
                                    <div class="bg-blue-600 h-full rounded-full" style="width: 80%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-slate-300">Equipo lento</span>
                                    <span class="font-medium text-white">27</span>
                                </div>
                                <div class="w-full bg-slate-800/60 h-2 rounded-full overflow-hidden">
                                    <div class="bg-blue-600 h-full rounded-full" style="width: 67.5%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-slate-300">Problemas de impresora</span>
                                    <span class="font-medium text-white">21</span>
                                </div>
                                <div class="w-full bg-slate-800/60 h-2 rounded-full overflow-hidden">
                                    <div class="bg-blue-600 h-full rounded-full" style="width: 52.5%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-slate-300">Acceso / contraseña</span>
                                    <span class="font-medium text-white">18</span>
                                </div>
                                <div class="w-full bg-slate-800/60 h-2 rounded-full overflow-hidden">
                                    <div class="bg-blue-600 h-full rounded-full" style="width: 45%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-slate-300">Software</span>
                                    <span class="font-medium text-white">14</span>
                                </div>
                                <div class="w-full bg-slate-800/60 h-2 rounded-full overflow-hidden">
                                    <div class="bg-blue-600 h-full rounded-full" style="width: 35%"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Regla inferior de números -->
                        <div class="flex justify-between text-[10px] text-slate-500 mt-4 px-1">
                            <span>0</span>
                            <span>10</span>
                            <span>20</span>
                            <span>30</span>
                            <span>40</span>
                        </div>
                    </div>

                    <a href="#" class="mt-6 pt-4 border-t border-slate-800/60 flex items-center justify-center gap-1.5 text-xs text-slate-400 hover:text-white transition">
                        <span>Ver todas las quejas</span>
                        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>

                <!-- Equipo con más fallas -->
                <div class="p-5 rounded-2xl bg-[#0b1026] border border-slate-800/80 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <i data-lucide="monitor" class="w-4 h-4 text-blue-400"></i>
                            <h3 class="text-sm font-semibold text-white">Equipo con más fallas</h3>
                        </div>
                        <p class="text-xs text-slate-400 mb-4">Equipos con mayor número de incidencias.</p>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead>
                                    <tr class="text-slate-400 border-b border-slate-800/80">
                                        <th class="pb-2 font-normal">Equipo</th>
                                        <th class="pb-2 font-normal">Tipo</th>
                                        <th class="pb-2 font-normal text-center">Fallas</th>
                                        <th class="pb-2 font-normal text-right">Última incidencia</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-800/40 text-slate-300">
                                    <tr>
                                        <td class="py-2.5 flex items-center gap-2 font-medium text-white">
                                            <i data-lucide="monitor" class="w-3.5 h-3.5 text-slate-400"></i> PC-ADM-024
                                        </td>
                                        <td class="py-2.5 text-slate-400">Desktop</td>
                                        <td class="py-2.5 text-center">12</td>
                                        <td class="py-2.5 text-right text-rose-400 font-medium">Hoy</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2.5 flex items-center gap-2 font-medium text-white">
                                            <i data-lucide="laptop" class="w-3.5 h-3.5 text-slate-400"></i> LAP-SOP-008
                                        </td>
                                        <td class="py-2.5 text-slate-400">Laptop</td>
                                        <td class="py-2.5 text-center">9</td>
                                        <td class="py-2.5 text-right text-amber-400 font-medium">Ayer</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2.5 flex items-center gap-2 font-medium text-white">
                                            <i data-lucide="monitor" class="w-3.5 h-3.5 text-slate-400"></i> PC-RH-015
                                        </td>
                                        <td class="py-2.5 text-slate-400">Desktop</td>
                                        <td class="py-2.5 text-center">8</td>
                                        <td class="py-2.5 text-right text-slate-400">08 Ago</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2.5 flex items-center gap-2 font-medium text-white">
                                            <i data-lucide="printer" class="w-3.5 h-3.5 text-slate-400"></i> IMP-ADM-002
                                        </td>
                                        <td class="py-2.5 text-slate-400">Impresora</td>
                                        <td class="py-2.5 text-center">7</td>
                                        <td class="py-2.5 text-right text-slate-400">07 Ago</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2.5 flex items-center gap-2 font-medium text-white">
                                            <i data-lucide="laptop" class="w-3.5 h-3.5 text-slate-400"></i> LAP-DIR-001
                                        </td>
                                        <td class="py-2.5 text-slate-400">Laptop</td>
                                        <td class="py-2.5 text-center">6</td>
                                        <td class="py-2.5 text-right text-slate-400">06 Ago</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-4 p-2.5 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center gap-2 text-xs text-amber-400">
                        <i data-lucide="alert-triangle" class="w-4 h-4 shrink-0"></i>
                        <span>Equipo con mayor recurrencia: <strong class="text-blue-400">PC-ADM-024</strong></span>
                    </div>
                </div>

                <!-- ¿Dónde hay más tickets? -->
                <div class="p-5 rounded-2xl bg-[#0b1026] border border-slate-800/80 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <i data-lucide="map-pin" class="w-4 h-4 text-blue-400"></i>
                            <h3 class="text-sm font-semibold text-white">¿Dónde hay más tickets?</h3>
                        </div>
                        <p class="text-xs text-slate-400 mb-6">Tickets generados por ubicación / sucursal.</p>

                        <div class="space-y-3.5 text-xs">
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-slate-300">Reynosa Centro</span>
                                    <span class="font-medium text-white">82</span>
                                </div>
                                <div class="w-full bg-slate-800/60 h-2 rounded-full overflow-hidden">
                                    <div class="bg-cyan-500 h-full rounded-full" style="width: 82%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-slate-300">Reynosa Norte</span>
                                    <span class="font-medium text-white">56</span>
                                </div>
                                <div class="w-full bg-slate-800/60 h-2 rounded-full overflow-hidden">
                                    <div class="bg-cyan-500 h-full rounded-full" style="width: 56%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-slate-300">Matamoros</span>
                                    <span class="font-medium text-white">35</span>
                                </div>
                                <div class="w-full bg-slate-800/60 h-2 rounded-full overflow-hidden">
                                    <div class="bg-cyan-500 h-full rounded-full" style="width: 35%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-slate-300">Monterrey</span>
                                    <span class="font-medium text-white">24</span>
                                </div>
                                <div class="w-full bg-slate-800/60 h-2 rounded-full overflow-hidden">
                                    <div class="bg-cyan-500 h-full rounded-full" style="width: 24%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-slate-300">Administración</span>
                                    <span class="font-medium text-white">17</span>
                                </div>
                                <div class="w-full bg-slate-800/60 h-2 rounded-full overflow-hidden">
                                    <div class="bg-cyan-500 h-full rounded-full" style="width: 17%"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Regla inferior de números -->
                        <div class="flex justify-between text-[10px] text-slate-500 mt-4 px-1">
                            <span>0</span>
                            <span>20</span>
                            <span>40</span>
                            <span>60</span>
                            <span>80</span>
                            <span>100</span>
                        </div>
                    </div>

                    <a href="#" class="mt-6 pt-4 border-t border-slate-800/60 flex items-center justify-center gap-1.5 text-xs text-slate-400 hover:text-white transition">
                        <span>Ver todas las ubicaciones</span>
                        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>

            </section>

            <!-- SECCIÓN INFERIOR: EVOLUCIÓN DE TICKETS Y ALERTAS -->
            <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Evolución de tickets (Gráfica SVG) -->
                <div class="lg:col-span-2 p-5 rounded-2xl bg-[#0b1026] border border-slate-800/80 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center gap-2">
                                <i data-lucide="trending-up" class="w-4 h-4 text-blue-400"></i>
                                <h3 class="text-sm font-semibold text-white">Evolución de tickets</h3>
                            </div>

                            <!-- Selector de período -->
                            <div class="flex items-center bg-slate-900 border border-slate-800 rounded-lg p-0.5 text-xs text-slate-400">
                                <button class="px-2.5 py-1 rounded-md hover:text-white transition">Hoy</button>
                                <button class="px-2.5 py-1 rounded-md bg-blue-600 text-white font-medium">Semana</button>
                                <button class="px-2.5 py-1 rounded-md hover:text-white transition">Mes</button>
                                <button class="px-2.5 py-1 rounded-md hover:text-white transition">Año</button>
                            </div>
                        </div>

                        <p class="text-xs text-slate-400 mb-6">Comportamiento de tickets en el periodo seleccionado.</p>

                        <!-- Área del Gráfico SVG lineal -->
                        <div class="relative w-full h-44">
                            <!-- Líneas de fondo -->
                            <div class="absolute inset-0 flex flex-col justify-between text-[10px] text-slate-600 pointer-events-none">
                                <div class="border-b border-slate-800/60 w-full flex justify-between"><span>80</span></div>
                                <div class="border-b border-slate-800/60 w-full flex justify-between"><span>60</span></div>
                                <div class="border-b border-slate-800/60 w-full flex justify-between"><span>40</span></div>
                                <div class="border-b border-slate-800/60 w-full flex justify-between"><span>20</span></div>
                                <div class="w-full flex justify-between"><span>0</span></div>
                            </div>

                            <!-- Trazado SVG -->
                            <svg class="absolute inset-0 w-full h-full overflow-visible" preserveAspectRatio="none" viewBox="0 0 600 120">
                                <path d="M 10 100 L 100 70 L 190 85 L 280 50 L 370 70 L 460 30 L 550 55 L 590 50" fill="none" stroke="#2563eb" stroke-width="3" />
                                <!-- Puntos -->
                                <circle cx="10" cy="100" r="4" fill="#2563eb" />
                                <circle cx="100" cy="70" r="4" fill="#2563eb" />
                                <circle cx="190" cy="85" r="4" fill="#2563eb" />
                                <circle cx="280" cy="50" r="4" fill="#2563eb" />
                                <circle cx="370" cy="70" r="4" fill="#2563eb" />
                                <circle cx="460" cy="30" r="4" fill="#2563eb" />
                                <circle cx="550" cy="55" r="4" fill="#2563eb" />
                            </svg>
                        </div>

                        <!-- Días del eje X -->
                        <div class="flex justify-between text-[11px] text-slate-400 mt-2 px-6">
                            <span>9 May</span>
                            <span>10 May</span>
                            <span>11 May</span>
                            <span>12 May</span>
                            <span>13 May</span>
                            <span>14 May</span>
                            <span>15 May</span>
                        </div>
                    </div>

                    <!-- Métricas inferiores del gráfico -->
                    <div class="grid grid-cols-3 gap-4 pt-4 mt-6 border-t border-slate-800/60 text-xs">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-xl bg-blue-500/10 text-blue-400">
                                <i data-lucide="target" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <p class="text-slate-400 text-[11px]">Promedio diario</p>
                                <p class="text-lg font-bold text-white">24.9</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-xl bg-blue-500/10 text-blue-400">
                                <i data-lucide="trending-up" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <p class="text-slate-400 text-[11px]">Máximo</p>
                                <p class="text-lg font-bold text-white">63</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-xl bg-rose-500/10 text-rose-400">
                                <i data-lucide="trending-down" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <p class="text-slate-400 text-[11px]">Mínimo</p>
                                <p class="text-lg font-bold text-white">12</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </main>

</body>

</html>