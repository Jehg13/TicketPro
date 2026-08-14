<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TicketPro - Solicitudes de cambio</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="bg-[#070b19] text-white font-sans min-h-screen antialiased">

    <aside
        class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0a0f24] border-r border-slate-800/60 p-6 hidden md:flex flex-col justify-between">

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
                        {{Auth::user()->name ?? 'Desconocido' }}
                    </h4>

                    <p class="text-xs text-slate-400 truncate">
                        {{Auth::user()->departamento->nombre}}
                    </p>

                </div>

            </div>

            <nav class="space-y-2">

                <a href="{{ route('tecnologias')}}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>

                    <span class="font-medium text-sm">
                        Inicio
                    </span>
                </a>

                <a href="{{ route('tickettecnologias')}}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">
                    <i data-lucide="ticket-check" class="w-5 h-5"></i>

                    <span class="font-medium text-sm">
                        Tickets
                    </span>
                </a>

                <a href="{{ route('cambiostecnologias')}}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold shadow-lg shadow-blue-600/30 transition">
                    <i data-lucide="git-compare-arrows" class="w-5 h-5"></i>

                    <span class="text-sm">
                        Cambios
                    </span>
                </a>

                <a href="{{ route('avisostecnologias')}}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">
                    <i data-lucide="megaphone" class="w-5 h-5"></i>

                    <span class="font-medium text-sm">
                        Avisos
                    </span>
                </a>

                <a href="{{ route('perfiltecnologias')}}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">
                    <i data-lucide="circle-user-round" class="w-5 h-5"></i>

                    <span class="font-medium text-sm">
                        Mi perfil
                    </span>
                </a>

            </nav>

        </div>

        <div>

           <form method="POST" action="{{ route('logout') }}" class="mt-6">
                @csrf
            <button type="submit"
                class="
                    flex
                    items-center
                    gap-3
                    px-4
                    py-3
                    rounded-xl
                    text-slate-400
                    hover:bg-red-500/10
                    hover:text-red-400
                    transition
                ">
                <i data-lucide="log-out" class="w-5 h-5"></i>
                <span class="font-medium text-sm">
                    Cerrar sesión
                </span>
            </button>
            </form>

        </div>

    </aside>

    <main class="md:ml-64 min-h-screen p-6 md:p-8">

        <div class="max-w-[1400px] mx-auto">

            <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">

                <div>

                    <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-white">
                        Solicitudes de cambio
                    </h1>

                    <p class="text-sm text-slate-400 mt-1">
                        Consulta y da seguimiento a las solicitudes de cambio de información de cuentas
                    </p>

                </div>

                <div class="flex items-center gap-4 self-end md:self-auto">

<div class="flex items-center gap-6 self-end md:self-auto">
                        <div class="relative inline-block text-left">
                            <!-- Botón de Notificaciones -->
                            <button id="notif-button" type="button"
                                class="relative p-2 text-gray-300 hover:text-white bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700/50 rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 group shadow-lg"
                                aria-label="Ver notificaciones">
                                <!-- Icono Campana -->
                                <svg class="w-6 h-6 transition-transform group-hover:scale-110 duration-200"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                    </path>
                                </svg>

                                <!-- Punto Rojo Animado (Ping) -->
                                <span class="absolute top-1.5 right-1.5 flex h-3 w-3">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                    <span
                                        class="relative inline-flex rounded-full h-3 w-3 bg-rose-500 border-2 border-slate-900"></span>
                                </span>
                            </button>

                            <!-- Panel Desplegable (Dropdown) con la clase 'hidden' por defecto -->
                            <div id="notif-dropdown"
                                class="hidden absolute right-0 mt-3 w-80 sm:w-96 rounded-2xl bg-slate-900/95 backdrop-blur-md border border-slate-800 shadow-2xl z-50 overflow-hidden divide-y divide-slate-800">
                                <!-- Encabezado -->
                                <div class="p-4 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-sm font-semibold text-white">Notificaciones</h3>
                                        <span
                                            class="px-2 py-0.5 text-xs font-medium bg-indigo-500/10 text-indigo-400 rounded-full border border-indigo-500/20">3
                                            nuevas</span>
                                    </div>
                                    <button
                                        class="text-xs text-indigo-400 hover:text-indigo-300 font-medium transition-colors">
                                        Marcar leídas
                                    </button>
                                </div>

                                <!-- Lista de Notificaciones -->
                                <div class="max-h-80 overflow-y-auto divide-y divide-slate-800/50">
                                    <a href="#"
                                        class="flex gap-3 p-4 bg-slate-800/40 hover:bg-slate-800/80 transition-colors group">
                                        <div class="relative shrink-0">
                                            <img class="w-10 h-10 rounded-full object-cover ring-2 ring-indigo-500/30"
                                                src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80"
                                                alt="Avatar" />
                                            <span
                                                class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-slate-900 rounded-full"></span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs text-slate-300 leading-relaxed">
                                                <strong
                                                    class="font-semibold text-white group-hover:text-indigo-400 transition-colors">Elena
                                                    Rostova</strong> comentó en tu proyecto <span
                                                    class="text-slate-400">Dashboard UI</span>.
                                            </p>
                                            <span class="text-[10px] text-slate-500 mt-1 block">Hace 2 minutos</span>
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
                                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs text-slate-300 leading-relaxed">
                                                Tu despliegue en <strong
                                                    class="font-semibold text-white">Vite/Production</strong> se
                                                completó con
                                                éxito.
                                            </p>
                                            <span class="text-[10px] text-slate-500 mt-1 block">Hace 1 hora</span>
                                        </div>
                                    </a>
                                </div>

                                <!-- Pie de panel -->
                                <a href="#"
                                    class="block p-3 text-center text-xs font-medium text-slate-400 hover:text-white hover:bg-slate-800/50 transition-colors">
                                    Ver todas las notificaciones
                                </a>
                            </div>
                        </div>

                        <div
                            class="
                            flex
                            items-center
                            gap-3
                            bg-slate-900/80
                            border
                            border-slate-800
                            rounded-full
                            p-1.5
                            pr-4
                        ">
                            <img src="{{ asset('storage/' . auth()->user()->foto) }}"
                                alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full object-cover">
                            <div class="text-left leading-tight hidden sm:block">
                                <p class="text-xs font-semibold text-white">
                                    {{ Auth::User()->name ?? 'Desconocido' }}
                                </p>
                                <p class="text-[10px] text-slate-400">
                                    {{ Auth::User()->departamento->nombre ?? 'Desconocido' }}
                                </p>
                            </div>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 ml-1"></i>
                        </div>
                    </div>
                </div>

            </header>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

                <div
                    class="bg-[#0b1026]/90 border border-blue-900/40 rounded-2xl p-5 flex items-center justify-between shadow-xl">

                    <div>

                        <p class="text-xs text-slate-400 font-medium mb-1">
                            Total de solicitudes
                        </p>

                        <h3 class="text-2xl font-bold text-white">
                            12
                        </h3>

                        <span class="text-[11px] text-slate-400">
                            +3 este mes
                        </span>

                    </div>

                    <div
                        class="w-11 h-11 bg-blue-500/10 text-blue-400 rounded-xl flex items-center justify-center border border-blue-500/20 shrink-0">
                        <i data-lucide="folder-open" class="w-5 h-5"></i>
                    </div>

                </div>

                <div
                    class="bg-[#0b1026]/90 border border-blue-900/40 rounded-2xl p-5 flex items-center justify-between shadow-xl">

                    <div>

                        <p class="text-xs text-slate-400 font-medium mb-1">
                            En revisión
                        </p>

                        <h3 class="text-2xl font-bold text-white">
                            4
                        </h3>

                        <span class="text-[11px] text-slate-400">
                            33% del total
                        </span>

                    </div>

                    <div
                        class="w-11 h-11 bg-amber-500/10 text-amber-400 rounded-xl flex items-center justify-center border border-amber-500/20 shrink-0">
                        <i data-lucide="clock-3" class="w-5 h-5"></i>
                    </div>

                </div>

                <div
                    class="bg-[#0b1026]/90 border border-blue-900/40 rounded-2xl p-5 flex items-center justify-between shadow-xl">

                    <div>

                        <p class="text-xs text-slate-400 font-medium mb-1">
                            Aprobadas
                        </p>

                        <h3 class="text-2xl font-bold text-white">
                            6
                        </h3>

                        <span class="text-[11px] text-slate-400">
                            50% del total
                        </span>

                    </div>

                    <div
                        class="w-11 h-11 bg-emerald-500/10 text-emerald-400 rounded-xl flex items-center justify-center border border-emerald-500/20 shrink-0">
                        <i data-lucide="circle-check" class="w-5 h-5"></i>
                    </div>

                </div>

                <div
                    class="bg-[#0b1026]/90 border border-blue-900/40 rounded-2xl p-5 flex items-center justify-between shadow-xl">

                    <div>

                        <p class="text-xs text-slate-400 font-medium mb-1">
                            Rechazadas
                        </p>

                        <h3 class="text-2xl font-bold text-white">
                            2
                        </h3>

                        <span class="text-[11px] text-slate-400">
                            17% del total
                        </span>

                    </div>

                    <div
                        class="w-11 h-11 bg-rose-500/10 text-rose-400 rounded-xl flex items-center justify-center border border-rose-500/20 shrink-0">
                        <i data-lucide="circle-x" class="w-5 h-5"></i>
                    </div>

                </div>

            </div>

            <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4 mb-6">

                <div class="flex flex-wrap items-center gap-1 bg-[#0b1026] p-1.5 rounded-xl border border-slate-800">

                    <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-xs font-semibold">
                        Todos
                    </button>

                    <button
                        class="px-4 py-2 text-slate-400 hover:text-white rounded-lg text-xs font-semibold flex items-center gap-2 transition">

                        <span>
                            En revisión
                        </span>

                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>

                    </button>

                    <button
                        class="px-4 py-2 text-slate-400 hover:text-white rounded-lg text-xs font-semibold flex items-center gap-2 transition">

                        <span>
                            Aprobadas
                        </span>

                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>

                    </button>

                    <button
                        class="px-4 py-2 text-slate-400 hover:text-white rounded-lg text-xs font-semibold flex items-center gap-2 transition">

                        <span>
                            Rechazadas
                        </span>

                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>

                    </button>

                </div>

                <div class="relative w-full xl:w-72">

                    <i data-lucide="search" class="absolute left-3.5 top-3 w-4 h-4 text-slate-500"></i>

                    <input type="text" placeholder="Buscar campo..."
                        class="w-full bg-[#0b1026] border border-slate-800 text-slate-200 text-xs rounded-xl pl-10 pr-4 py-2.5 focus:outline-none focus:border-blue-500 transition">

                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div
                    class="lg:col-span-2 bg-[#070e27] border border-slate-800/80 rounded-2xl p-5 flex flex-col min-h-[620px]">

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="text-slate-400 border-b border-slate-800/80">
                                    <th class="pb-3 font-semibold">Folio</th>
                                    <th class="pb-3 font-semibold">Campo solicitado</th>
                                    <th class="pb-3 font-semibold text-center">Estado</th>
                                    <th class="pb-3 font-semibold">Fecha solicitud</th>
                                    <th class="pb-3 font-semibold text-center">Acción</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-800/50 text-slate-300">

                                <tr class="hover:bg-slate-800/20 transition">
                                    <td class="py-3 font-semibold text-white">
                                        TKT-2026-00125
                                    </td>

                                    <td class="py-3">
                                        Correo electrónico
                                    </td>

                                    <td class="py-3 text-center">
                                        <span
                                            class="px-2.5 py-1 rounded-full text-[10px] font-medium bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                            En revisión
                                        </span>
                                    </td>

                                    <td class="py-3">
                                        05 Ago 2026
                                        <br>
                                        <span class="text-[10px] text-slate-500">
                                            10:30 AM
                                        </span>
                                    </td>

                                    <td class="py-3 text-center">
                                        <button class="text-slate-400 hover:text-blue-400 transition">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </button>
                                    </td>
                                </tr>

                                <tr class="hover:bg-slate-800/20 transition">
                                    <td class="py-3 font-semibold text-white">
                                        TKT-2026-00124
                                    </td>

                                    <td class="py-3">
                                        Departamento
                                    </td>

                                    <td class="py-3 text-center">
                                        <span
                                            class="px-2.5 py-1 rounded-full text-[10px] font-medium bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                            En revisión
                                        </span>
                                    </td>

                                    <td class="py-3">
                                        04 Ago 2026
                                        <br>
                                        <span class="text-[10px] text-slate-500">
                                            11:20 AM
                                        </span>
                                    </td>

                                    <td class="py-3 text-center">
                                        <button class="text-slate-400 hover:text-blue-400 transition">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </button>
                                    </td>
                                </tr>

                                <tr class="hover:bg-slate-800/20 transition">
                                    <td class="py-3 font-semibold text-white">
                                        TKT-2026-00123
                                    </td>

                                    <td class="py-3">
                                        Oficina
                                    </td>

                                    <td class="py-3 text-center">
                                        <span
                                            class="px-2.5 py-1 rounded-full text-[10px] font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                            Aprobada
                                        </span>
                                    </td>

                                    <td class="py-3">
                                        03 Ago 2026
                                        <br>
                                        <span class="text-[10px] text-slate-500">
                                            09:45 AM
                                        </span>
                                    </td>

                                    <td class="py-3 text-center">
                                        <button class="text-slate-400 hover:text-blue-400 transition">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </button>
                                    </td>
                                </tr>

                                <tr class="hover:bg-slate-800/20 transition">
                                    <td class="py-3 font-semibold text-white">
                                        TKT-2026-00122
                                    </td>

                                    <td class="py-3">
                                        Nombre completo
                                    </td>

                                    <td class="py-3 text-center">
                                        <span
                                            class="px-2.5 py-1 rounded-full text-[10px] font-medium bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                            Rechazada
                                        </span>
                                    </td>

                                    <td class="py-3">
                                        02 Ago 2026
                                        <br>
                                        <span class="text-[10px] text-slate-500">
                                            16:30 PM
                                        </span>
                                    </td>

                                    <td class="py-3 text-center">
                                        <button class="text-slate-400 hover:text-blue-400 transition">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </button>
                                    </td>
                                </tr>

                                <tr class="hover:bg-slate-800/20 transition">
                                    <td class="py-3 font-semibold text-white">
                                        TKT-2026-00121
                                    </td>

                                    <td class="py-3">
                                        Empresa
                                    </td>

                                    <td class="py-3 text-center">
                                        <span
                                            class="px-2.5 py-1 rounded-full text-[10px] font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                            Aprobada
                                        </span>
                                    </td>

                                    <td class="py-3">
                                        01 Ago 2026
                                        <br>
                                        <span class="text-[10px] text-slate-500">
                                            13:00 PM
                                        </span>
                                    </td>

                                    <td class="py-3 text-center">
                                        <button class="text-slate-400 hover:text-blue-400 transition">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </button>
                                    </td>
                                </tr>

                                <tr class="hover:bg-slate-800/20 transition">
                                    <td class="py-3 font-semibold text-white">
                                        TKT-2026-00120
                                    </td>

                                    <td class="py-3">
                                        Correo electrónico
                                    </td>

                                    <td class="py-3 text-center">
                                        <span
                                            class="px-2.5 py-1 rounded-full text-[10px] font-medium bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                            Rechazada
                                        </span>
                                    </td>

                                    <td class="py-3">
                                        30 Jul 2026
                                        <br>
                                        <span class="text-[10px] text-slate-500">
                                            09:30 AM
                                        </span>
                                    </td>

                                    <td class="py-3 text-center">
                                        <button class="text-slate-400 hover:text-blue-400 transition">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </button>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                    <div class="mt-auto pt-6 border-t border-slate-800/80 flex justify-between items-center">

                        <span class="text-xs text-slate-400">
                            Mostrando 1 a 6 de 28 solicitudes
                        </span>

                        <div class="flex items-center gap-1">

                            <button
                                class="w-8 h-8 text-xs bg-slate-800 text-slate-400 rounded-lg hover:bg-slate-700 transition flex items-center justify-center">
                                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                            </button>

                            <button
                                class="w-8 h-8 text-xs bg-blue-600 text-white font-bold rounded-lg flex items-center justify-center">
                                1
                            </button>

                            <button
                                class="w-8 h-8 text-xs bg-slate-800 text-slate-300 rounded-lg hover:bg-slate-700 transition flex items-center justify-center">
                                2
                            </button>

                            <button
                                class="w-8 h-8 text-xs bg-slate-800 text-slate-300 rounded-lg hover:bg-slate-700 transition flex items-center justify-center">
                                3
                            </button>

                            <button
                                class="w-8 h-8 text-xs bg-slate-800 text-slate-300 rounded-lg hover:bg-slate-700 transition flex items-center justify-center">
                                4
                            </button>

                            <button
                                class="w-8 h-8 text-xs bg-slate-800 text-slate-400 rounded-lg hover:bg-slate-700 transition flex items-center justify-center">
                                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                            </button>

                        </div>

                    </div>

                </div>

                <div
                    class="bg-[#0b1026]/90 border border-blue-900/40 rounded-2xl p-5 shadow-xl backdrop-blur-md space-y-4">

                    <div class="flex justify-between items-center pb-3 border-b border-slate-800">

                        <h2 class="text-sm font-semibold text-white">
                            Detalle de la solicitud
                        </h2>

                        <span
                            class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                            Aprobada
                        </span>

                    </div>

                    <div class="grid grid-cols-2 gap-3 text-xs">

                        <div>

                            <p class="text-slate-400 text-[10px]">
                                Folio
                            </p>

                            <p class="text-white font-medium">
                                SQL-2026-0011
                            </p>

                        </div>

                        <div>

                            <p class="text-slate-400 text-[10px]">
                                Fecha solicitud
                            </p>

                            <p class="text-white font-medium">
                                04 Ago 2026 - 10:30 AM
                            </p>

                        </div>

                    </div>

                    <div class="text-xs space-y-3">

                        <div>

                            <p class="text-slate-400 text-[10px]">
                                Campo solicitado
                            </p>

                            <p class="text-white font-medium">
                                Nombre
                            </p>

                        </div>

                        <div>

                            <p class="text-slate-400 text-[10px]">
                                Información actual
                            </p>

                            <p class="text-white font-medium">
                                Juan Hernandez
                            </p>

                        </div>

                        <div>

                            <p class="text-slate-400 text-[10px]">
                                Información solicitada
                            </p>

                            <p class="text-white font-medium">
                                Juan Perez
                            </p>

                        </div>

                        <div>

                            <p class="text-slate-400 text-[10px]">
                                Motivo de solicitud
                            </p>

                            <p class="text-slate-300 text-[11px] leading-relaxed">
                                Mi apellido está mal, es Perez y en el sistema aparece como Hernandez.
                            </p>

                        </div>

                    </div>

                    <div class="pt-2 border-t border-slate-800">

                        <p class="text-slate-400 text-[10px] mb-2">
                            Revisada por
                        </p>

                        <div class="flex items-center justify-between gap-2 bg-slate-800/40 p-2 rounded-xl">

                            <div class="flex items-center gap-2">

                                <img class="w-7 h-7 rounded-full object-cover" src="https://i.pravatar.cc/100?img=33"
                                    alt="Carlos Martinez">

                                <div>

                                    <p class="text-xs font-medium text-white">
                                        Carlos Martinez
                                    </p>

                                    <p class="text-[9px] text-slate-400">
                                        05 Ago 2026 - 03:32 PM
                                    </p>

                                </div>

                            </div>

                            <span
                                class="px-2 py-0.5 rounded-full text-[9px] bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                Tecnología
                            </span>

                        </div>

                    </div>

                    <div class="text-xs">

                        <p class="text-slate-400 text-[10px]">
                            Observaciones
                        </p>

                        <p class="text-slate-300 text-[11px] mt-1">
                            Se verificó la información y fue aprobada.
                        </p>

                    </div>

                    <div class="pt-3 border-t border-slate-800 text-xs">

                        <p class="text-slate-400 text-[10px] mb-3">
                            Historial
                        </p>

                        <div class="relative pl-4 space-y-4 border-l border-slate-800">

                            <div class="relative">

                                <div
                                    class="absolute -left-[21px] top-1 w-2.5 h-2.5 rounded-full border-2 border-emerald-500 bg-[#0b1026]">
                                </div>

                                <p class="text-emerald-400 text-[11px] font-medium">
                                    Solicitud aprobada
                                </p>

                                <p class="text-[9px] text-slate-500">
                                    05 Ago 2026 - 11:05 AM
                                </p>

                            </div>

                            <div class="relative">

                                <div class="absolute -left-[21px] top-1 w-2.5 h-2.5 rounded-full bg-blue-600"></div>

                                <p class="text-slate-300 text-[11px]">
                                    En revisión
                                </p>

                                <p class="text-[9px] text-slate-500">
                                    04 Ago 2026 - 04:20 PM
                                </p>

                            </div>

                            <div class="relative">

                                <div class="absolute -left-[21px] top-1 w-2.5 h-2.5 rounded-full bg-blue-600"></div>

                                <p class="text-slate-300 text-[11px]">
                                    Solicitud creada
                                </p>

                                <p class="text-[9px] text-slate-500">
                                    04 Ago 2026 - 02:05 PM
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </main>

    <script>
        lucide.createIcons();
    </script>

</body>

</html>
