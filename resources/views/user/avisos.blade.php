<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avisos - TicketPro</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="bg-[#060818] text-white font-sans antialiased flex h-screen overflow-hidden">

    <!-- SIDEBAR (Oculto en móviles, visible desde 'md:') -->
    <aside
        class="hidden md:flex flex-col w-64 bg-[#0a0e27] border-r border-[#1e295d] px-4 py-6 overflow-y-auto [::-webkit-scrollbar]:w-2 [::-webkit-scrollbar-track]:bg-[#060818] [::-webkit-scrollbar-thumb]:bg-[#1e295d] [::-webkit-scrollbar-thumb]:rounded-full">
        <!-- Logo -->
        <div class="text-3xl font-bold mb-8 px-2 tracking-wide">
            Ticket<span class="text-blue-500">Pro</span>
        </div>

        <!-- Usuario Sidebar -->
        <div class="flex items-center gap-3 mb-10 px-2">
            <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="{{ auth()->user()->name }}"
                class="w-12 h-12 rounded-full border-2 border-gray-500 object-cover">
            <div>
                <h3 class="text-sm font-semibold text-white">{{ Auth::User()->name ?? 'Desconocido' }}</h3>
                <p class="text-xs text-gray-400">{{ Auth::User()->departamento->nombre ?? 'Desconocido' }}</p>
            </div>
        </div>

        <!-- Navegación -->
        <nav class="flex-1 space-y-2">
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-[#151b3b] hover:text-white rounded-lg transition">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                <span class="text-sm font-medium">Inicio</span>
            </a>

            <!-- Opción Activa: Mis tickets -->
            <a href="{{ route('misticketusuario') }}"
                class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-[#151b3b] hover:text-white rounded-lg transition">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                    </path>
                </svg>
                <span class="text-sm font-medium">Mis tickets</span>
            </a>

            <a href="{{ route('ticketusuario') }}"
                class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-[#151b3b] hover:text-white rounded-lg transition">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z">
                    </path>
                </svg>
                <span class="text-sm font-medium">Crear ticket</span>
            </a>

            <a href="{{ route('avisosusuario') }}"
                class="flex items-center gap-3 px-4 py-3 bg-blue-600 text-white rounded-lg font-medium shadow-[0_0_15px_rgba(37,99,235,0.4)]">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
                <span class="text-sm font-medium">Avisos</span>
            </a>

            <a href="{{ route('perfilusuario') }}"
                class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-[#151b3b] hover:text-white rounded-lg transition">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="text-sm font-medium">Mi perfil</span>
            </a>
        </nav>

        <!-- Cerrar Sesión -->
        <div class="mt-auto pt-6">
            <form method="POST" action="{{ route('logout') }}" class="mt-6">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-[#151b3b] hover:text-white rounded-lg transition text-left">

                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>

                    <span class="text-sm font-medium">Cerrar sesión</span>
                </button>
            </form>
        </div>

    </aside>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="flex-1 flex flex-col h-screen overflow-y-auto">

        <!-- Header Superior -->
        <header
            class="flex flex-wrap justify-between items-center px-6 py-6 gap-4 border-b border-[#1e295d]/50 bg-[#060818]/80 backdrop-blur sticky top-0 z-10">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-white">Avisos</h1>
                <p class="text-xs sm:text-sm text-gray-400 mt-1">
                    Mantente informado sobre mantenimientos, fallas y actualizaciones
                </p>
            </div>

            <div class="flex items-center gap-6 self-end md:self-auto">

                <!-- Notificaciones -->
                <div class="relative inline-block text-left">
                    <!-- Botón de Notificaciones -->
                    <button id="notif-button" type="button"
                        class="relative p-2 text-gray-300 hover:text-white bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700/50 rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 group shadow-lg"
                        aria-label="Ver notificaciones">
                        <!-- Icono Campana -->
                        <svg class="w-6 h-6 transition-transform group-hover:scale-110 duration-200" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
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

                            <a href="#" class="flex gap-3 p-4 hover:bg-slate-800/50 transition-colors group">
                                <div
                                    class="w-10 h-10 rounded-full bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-slate-300 leading-relaxed">
                                        Tu despliegue en <strong
                                            class="font-semibold text-white">Vite/Production</strong> se completó con
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



                <!-- Usuario -->
                <div class="flex items-center gap-3 cursor-pointer">

                    <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="{{ auth()->user()->name }}"
                        class="w-10 h-10 rounded-full border border-gray-600 object-cover">

                    <div class="hidden md:block text-right">
                        <p class="text-sm font-semibold leading-tight">
                            {{ Auth::user()->name ?? 'Usuario' }}
                        </p>

                        <p class="text-xs text-gray-400">
                            {{ optional(Auth::user()->departamento)->nombre ?? 'Administración' }}
                        </p>
                    </div>

                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </div>
            </div>
        </header>

        <!-- FILTROS Y BÚSQUEDA -->
        <div class="px-6 my-6">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">

                <!-- Botones de Categorías -->
                <div class="flex items-center gap-2 overflow-x-auto pb-2 lg:pb-0">
                    <!-- Todos (Activo) -->
                    <button
                        class="px-5 py-2 rounded-lg bg-blue-600 text-white font-medium text-xs sm:text-sm shadow-md shadow-blue-600/30 whitespace-nowrap">
                        Todos
                    </button>
                    <!-- Mantenimiento -->
                    <button
                        class="px-4 py-2 rounded-lg bg-[#3b2900] border border-amber-600/60 text-amber-500 hover:bg-[#4d3600] transition font-medium text-xs sm:text-sm flex items-center gap-1.5 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                            </path>
                        </svg>
                        Mantenimiento
                    </button>
                    <!-- Falla / Incidente -->
                    <button
                        class="px-4 py-2 rounded-lg bg-[#3f0e0e] border border-red-600/60 text-red-500 hover:bg-[#541313] transition font-medium text-xs sm:text-sm flex items-center gap-1.5 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Falla / Incidente
                    </button>
                    <!-- Informativo -->
                    <button
                        class="px-4 py-2 rounded-lg bg-[#092c42] border border-cyan-500/60 text-cyan-400 hover:bg-[#0c3956] transition font-medium text-xs sm:text-sm flex items-center gap-1.5 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Informativo
                    </button>
                    <!-- General -->
                    <button
                        class="px-4 py-2 rounded-lg bg-[#060818] border border-[#1e295d] text-gray-300 hover:border-gray-500 transition font-medium text-xs sm:text-sm flex items-center gap-1.5 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                        General
                    </button>
                </div>

                <!-- Input Búsqueda -->
                <div class="relative w-full lg:w-64">
                    <input type="text" placeholder="Buscar aviso..."
                        class="w-full bg-[#060818] border border-[#1e295d] rounded-full py-2 pl-4 pr-10 text-xs sm:text-sm text-gray-200 placeholder-gray-500 focus:outline-none focus:border-blue-500 transition">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

            </div>
        </div>

        <!-- LISTADO DE AVISOS -->
        <div class="px-6 pb-10">
            <div class="bg-[#0f1535] rounded-2xl border border-[#1e295d] p-5 sm:p-6 space-y-4">

                <!-- TARJETA 1: MANTENIMIENTO PROGRAMADO -->
                <div
                    class="bg-[#0b102b] border border-[#1e295d] rounded-xl p-5 flex flex-col lg:flex-row lg:items-center justify-between gap-5 hover:border-amber-500/40 transition">
                    <div class="flex flex-col sm:flex-row items-start gap-4 flex-1">
                        <!-- Icono Mantenimiento -->
                        <div
                            class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl bg-[#3b2900] border border-amber-600/50 flex items-center justify-center shrink-0 text-amber-500">
                            <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>

                        <!-- Texto -->
                        <div class="space-y-1.5">
                            <span
                                class="inline-block px-2.5 py-0.5 rounded text-[11px] font-bold bg-[#3b2900] border border-amber-600 text-amber-500 uppercase tracking-wider">
                                MANTENIMIENTO PROGRAMADO
                            </span>
                            <h3 class="text-sm sm:text-base font-bold text-white uppercase tracking-wide">
                                MANTENIMIENTO EN SERVIDORES DE APLICACIONES
                            </h3>
                            <p class="text-xs sm:text-sm text-gray-300 leading-relaxed max-w-2xl">
                                Se realizara mantenimiento preventivo a los servidores de aplicaciones el dia sabado 10
                                de agosto apartir de las 20:00 PM a 22:00 PM
                            </p>
                            <p class="text-xs text-gray-400 pt-1">
                                <span class="font-semibold text-gray-300">Afecta a:</span> Sistemas internos, Portal de
                                empleados
                            </p>
                        </div>
                    </div>

                    <!-- Lado Derecho (Meta / Status) -->
                    <div
                        class="flex lg:flex-col justify-between items-end shrink-0 border-t lg:border-t-0 border-[#1e295d] pt-3 lg:pt-0 gap-2">
                        <span
                            class="px-3 py-1 rounded-md text-xs font-semibold bg-[#092c42] border border-cyan-500 text-cyan-400">
                            Importante
                        </span>

                        <div class="text-right text-[11px] text-gray-400 space-y-0.5">
                            <p class="flex items-center justify-end gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                08 ago 2026
                            </p>
                            <p class="flex items-center justify-end gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                13:45 pm
                            </p>
                        </div>

                        <a href="#"
                            class="text-xs font-semibold text-blue-400 hover:text-blue-300 flex items-center gap-1 transition">
                            Ver mas <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- TARJETA 2: FALLA / INCIDENTE -->
                <div
                    class="bg-[#0b102b] border border-[#1e295d] rounded-xl p-5 flex flex-col lg:flex-row lg:items-center justify-between gap-5 hover:border-red-500/40 transition">
                    <div class="flex flex-col sm:flex-row items-start gap-4 flex-1">
                        <!-- Icono Falla -->
                        <div
                            class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl bg-[#3f0e0e] border border-red-600/50 flex items-center justify-center shrink-0 text-red-500">
                            <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                        </div>

                        <!-- Texto -->
                        <div class="space-y-1.5">
                            <span
                                class="inline-block px-2.5 py-0.5 rounded text-[11px] font-bold bg-[#3f0e0e] border border-red-600 text-red-500 uppercase tracking-wider">
                                FALLA / INCIDENTE
                            </span>
                            <h3 class="text-sm sm:text-base font-bold text-white uppercase tracking-wide">
                                INTERMITENCIA EN LA RED INTERNA
                            </h3>
                            <p class="text-xs sm:text-sm text-gray-300 leading-relaxed max-w-2xl">
                                Estamos presentando intermitencia en la red interna en algunas areas. El equipo de TI ya
                                esta trabajando para restablecer el servicio.
                            </p>
                            <p class="text-xs text-gray-400 pt-1">
                                <span class="font-semibold text-gray-300">Afecta a:</span> Reynosa, Centro Edificio A,
                                piso 2
                            </p>
                        </div>
                    </div>

                    <!-- Lado Derecho (Meta / Status) -->
                    <div
                        class="flex lg:flex-col justify-between items-end shrink-0 border-t lg:border-t-0 border-[#1e295d] pt-3 lg:pt-0 gap-2">
                        <span
                            class="px-3 py-1 rounded-md text-xs font-semibold bg-[#3f0e0e] border border-red-600 text-red-400">
                            En proceso
                        </span>

                        <div class="text-right text-[11px] text-gray-400 space-y-0.5">
                            <p class="flex items-center justify-end gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                08 ago 2026
                            </p>
                            <p class="flex items-center justify-end gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                13:45 pm
                            </p>
                        </div>

                        <a href="#"
                            class="text-xs font-semibold text-blue-400 hover:text-blue-300 flex items-center gap-1 transition">
                            Ver mas <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- TARJETA 3: INFORMATIVO -->
                <div
                    class="bg-[#0b102b] border border-[#1e295d] rounded-xl p-5 flex flex-col lg:flex-row lg:items-center justify-between gap-5 hover:border-cyan-500/40 transition">
                    <div class="flex flex-col sm:flex-row items-start gap-4 flex-1">
                        <!-- Icono Informativo -->
                        <div
                            class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl bg-[#092c42] border border-cyan-500/50 flex items-center justify-center shrink-0 text-cyan-400">
                            <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>

                        <!-- Texto -->
                        <div class="space-y-1.5">
                            <span
                                class="inline-block px-2.5 py-0.5 rounded text-[11px] font-bold bg-[#092c42] border border-cyan-500 text-cyan-400 uppercase tracking-wider">
                                INFORMATIVO
                            </span>
                            <h3 class="text-sm sm:text-base font-bold text-white uppercase tracking-wide">
                                NUEVA POLITICA DE CONTRASEÑAS
                            </h3>
                            <p class="text-xs sm:text-sm text-gray-300 leading-relaxed max-w-2xl">
                                Apartir del 15 de agosto entrara en vigor la nueva politica de contraseñas. Consulta el
                                documento adjunto para mas información
                            </p>
                            <p class="text-xs text-gray-400 pt-1">
                                <span class="font-semibold text-gray-300">Afecta a:</span> Todos los usuarios
                            </p>
                        </div>
                    </div>

                    <!-- Lado Derecho (Meta / Status) -->
                    <div
                        class="flex lg:flex-col justify-between items-end shrink-0 border-t lg:border-t-0 border-[#1e295d] pt-3 lg:pt-0 gap-2">
                        <span
                            class="px-3 py-1 rounded-md text-xs font-semibold bg-[#092c42] border border-cyan-500 text-cyan-400">
                            Informativo
                        </span>

                        <div class="text-right text-[11px] text-gray-400 space-y-0.5">
                            <p class="flex items-center justify-end gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                08 ago 2026
                            </p>
                            <p class="flex items-center justify-end gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                13:45 pm
                            </p>
                        </div>

                        <a href="#"
                            class="text-xs font-semibold text-blue-400 hover:text-blue-300 flex items-center gap-1 transition">
                            Ver mas <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </main>

</body>

</html>
<style>
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    ::-webkit-scrollbar-track {
        background: #060818;
    }

    ::-webkit-scrollbar-thumb {
        background: #1e295d;
        border-radius: 9999px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #2563eb;
    }
</style>
