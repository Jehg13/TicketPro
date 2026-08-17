<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - TicketPro</title>

    <!-- Tailwind CSS vía Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
                class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-[#151b3b] hover:text-white rounded-lg transition">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
                <span class="text-sm font-medium">Avisos</span>
            </a>

            <a href="{{ route('perfilusuario') }}"
                class="flex items-center gap-3 px-4 py-3 bg-blue-600 text-white rounded-lg font-medium shadow-[0_0_15px_rgba(37,99,235,0.4)]">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="text-sm font-medium">Mi perfil</span>
            </a>
        </nav>

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
                <h1 class="text-2xl font-bold tracking-tight text-white">Mi perfil</h1>
                <p class="text-xs sm:text-sm text-gray-400 mt-1">
                    Consulta de tu información personal y de tu cuenta.
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
                <div class="relative">
                    <button id="profile-button" type="button"
                        class="flex items-center gap-3 cursor-pointer rounded-xl px-2 py-1.5 hover:bg-[#151b3b] transition-all duration-200 focus:outline-none">

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

                        <svg id="profile-arrow" class="w-4 h-4 text-gray-400 transition-transform duration-200"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>

                    <!-- DROPDOWN PERFIL -->
                    <div id="profile-dropdown"
                        class="hidden absolute right-0 mt-3 w-56 bg-[#0f1535]/95 backdrop-blur-xl border border-[#1e295d] rounded-xl shadow-2xl shadow-black/40 overflow-hidden z-50">

                        <!-- Ver perfil -->
                        <a href="{{ route('perfilusuario') }}"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-gray-300 hover:bg-[#151b3b] hover:text-white transition-colors">

                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                </path>
                            </svg>

                            <span>Ver perfil</span>
                        </a>

                        <div class="border-t border-[#1e295d]"></div>

                        <!-- Cerrar sesión -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-3 text-sm text-gray-300 hover:bg-red-500/10 hover:text-red-400 transition-colors text-left">

                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
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

        <!-- SECCIÓN PERFIL (GRID DE 2 COLUMNAS) -->
        <div class="px-6 py-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- COLUMNA IZQUIERDA (INFORMACIÓN Y SEGURIDAD) -->
                <div class="lg:col-span-8 space-y-6">

                    <!-- TARJETA 1: INFORMACIÓN PERSONAL Y LABORAL -->
                    <div class="bg-[#0f1535] rounded-2xl border border-[#1e295d] p-6 shadow-lg">
                        <div class="flex items-center gap-3 mb-1">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <h2 class="text-lg font-bold text-white">Información personal y laboral</h2>
                        </div>
                        <p class="text-xs text-gray-400 mb-6">Esta información es proporcionada por la empresa</p>

                        <!-- Datos en Grid 2 columnas -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">

                            <!-- Nombre completo -->
                            <div class="flex items-start gap-3">
                                <div class="p-2.5 rounded-lg bg-[#0b102b] border border-[#1e295d] text-gray-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-400">Nombre completo</p>
                                    <p class="text-sm font-semibold text-white mt-0.5">
                                        {{ Auth::user()->name ?? 'Juan Perez' }}</p>
                                </div>
                            </div>

                            <!-- Empresa -->
                            <div class="flex items-start gap-3">
                                <div class="p-2.5 rounded-lg bg-[#0b102b] border border-[#1e295d] text-gray-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4m-4 0H9m4 0V7m0 0h4m-4 0H9">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-400">Empresa</p>
                                    <p class="text-sm font-semibold text-white mt-0.5">
                                        {{ Auth::user()->departamento->oficina->empresa->empresa ?? 'Juan Perez' }}</p>
                                </div>
                            </div>

                            <!-- Correo electrónico -->
                            <div class="flex items-start gap-3">
                                <div class="p-2.5 rounded-lg bg-[#0b102b] border border-[#1e295d] text-gray-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-400">Correo electronico</p>
                                    <p class="text-sm font-semibold text-white mt-0.5">
                                        {{ Auth::user()->email ?? 'juanp@cymez.com' }}</p>
                                </div>
                            </div>

                            <!-- Oficina / Sucursal -->
                            <div class="flex items-start gap-3">
                                <div class="p-2.5 rounded-lg bg-[#0b102b] border border-[#1e295d] text-gray-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-400">Oficina / Sucursal</p>
                                    <p class="text-sm font-semibold text-white mt-0.5">
                                        {{ Auth::User()->departamento->oficina->nombre ?? 'Desconocido' }}</p>
                                </div>
                            </div>

                            <!-- Departamento -->
                            <div class="flex items-start gap-3">
                                <div class="p-2.5 rounded-lg bg-[#0b102b] border border-[#1e295d] text-gray-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4m-4 0H9m4 0V7m0 0h4m-4 0H9">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-400">Departamento</p>
                                    <p class="text-sm font-semibold text-white mt-0.5">
                                        {{ Auth::User()->departamento->nombre ?? 'Desconocido' }}</p>
                                </div>
                            </div>

                            <!-- Ubicación -->
                            <div class="flex items-start gap-3">
                                <div class="p-2.5 rounded-lg bg-[#0b102b] border border-[#1e295d] text-gray-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-400">Ubicaciòn</p>
                                    <p class="text-sm font-semibold text-white mt-0.5">Edificio A, piso 2</p>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- TARJETA 2: BANNER AVISO SOLICITAR CAMBIO -->
                    <div
                        class="bg-[#0f1535] rounded-xl border border-[#1e295d] p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-3 text-center sm:text-left">
                            <div
                                class="w-10 h-10 rounded-full bg-white text-[#0f1535] flex items-center justify-center shrink-0 font-bold">
                                i
                            </div>
                            <p class="text-xs text-gray-300 leading-snug">
                                Si alguna de tu información es incorrecta o requiere actualizacion, solicita un cambio a
                                traves del boton solicitar cambio
                            </p>
                        </div>
                        <button id="openModalBtn"
                            class="px-4 py-2 rounded-lg border border-[#1e295d] bg-[#0b102b] hover:bg-[#151b3b] text-gray-200 text-xs font-medium flex items-center gap-2 whitespace-nowrap transition shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Solicitar cambio
                        </button>
                    </div>

                    <!-- TARJETA 3: SEGURIDAD DE TU CUENTA -->
                    <div class="bg-[#0f1535] rounded-2xl border border-[#1e295d] p-6 shadow-lg">
                        <div class="flex items-center gap-3 mb-1">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                            <h2 class="text-lg font-bold text-white">Seguridad de tu cuenta</h2>
                        </div>
                        <p class="text-xs text-gray-400 mb-6">Administra tu informacion relacionada con la seguridad de
                            tu cuenta</p>

                        <!-- Box Contraseña -->
                        <div
                            class="bg-[#0b102b] border border-[#1e295d] rounded-xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2.5 rounded-lg bg-[#060818] border border-[#1e295d] text-gray-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-white">Contraseña</p>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        Última actualización:
                                        {{ Auth::user()->password_updated_at
                                            ? Auth::user()->password_updated_at->locale('es')->translatedFormat('d M Y')
                                            : 'No registrada' }}
                                    </p>
                                </div>
                            </div>
                            {{-- <button
                                class="px-4 py-2 rounded-lg border border-[#1e295d] bg-[#060818] hover:bg-[#151b3b] text-gray-200 text-xs font-medium flex items-center gap-2 transition shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                                Actualizar contraseña
                            </button> --}}
                        </div>
                    </div>

                </div>

                <!-- COLUMNA DERECHA (FOTO DE PERFIL E INFORMACIÓN DE CUENTA) -->
                <div class="lg:col-span-4 space-y-6">

                    <!-- TARJETA 1: FOTO DE PERFIL -->
                    <div
                        class="bg-[#0f1535] rounded-2xl border border-[#1e295d] p-6 shadow-lg flex flex-col items-center text-center">
                        <div class="w-full text-left mb-4">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <h2 class="text-base font-bold text-white">Foto de perfil</h2>
                            </div>
                            <p class="text-xs text-gray-400 mt-0.5">Actualización del perfil</p>
                        </div>

                        <!-- FORMULARIO ACTUALIZAR FOTO -->
                        <form method="POST" enctype="multipart/form-data" action="{{ route('actualizarfoto') }}">
                            @csrf
                            @method('PUT')

                            <div class="relative my-4">

                                <img src="{{ asset('storage/' . auth()->user()->foto) }}"
                                    alt="{{ auth()->user()->name }}"
                                    class="w-44 h-44 rounded-full object-cover border-4 border-[#1e295d] shadow-xl"
                                    id="profileImage">

                                <!-- Input de archivo -->
                                <input type="file" name="foto" id="photoInput" accept=".jpg,.jpeg,.png"
                                    class="hidden">

                                <!-- Botón cámara -->
                                <button type="button" id="cameraButton"
                                    class="absolute bottom-2 right-2 bg-white text-[#060818] p-2.5 rounded-full shadow-lg hover:bg-gray-200 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0118.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                        </path>

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </button>

                            </div>

                            <p class="text-xs text-gray-400 mt-2">
                                Formatos permitidos: JPG, PNG
                            </p>

                            <p class="text-xs text-gray-400 mb-6">
                                Tamaño máximo: 2 MB
                            </p>

                            <div class="flex items-center gap-3 w-full">

                                <button type="submit"
                                    class="flex-1 py-2.5 px-3 rounded-lg bg-blue-600 hover:bg-blue-500 text-white font-medium text-xs shadow-md shadow-blue-600/30 transition">
                                    Actualizar foto
                                </button>

                            </div>

                        </form>
                        <!-- FORMULARIO ELIMINAR -->
                        @if (auth()->user()->foto)
                            <form action="{{ route('eliminarfoto') }}" method="POST" class="w-full mt-3">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="w-full py-2.5 px-3 rounded-lg border border-[#1e295d] bg-[#0b102b] hover:bg-[#151b3b] text-gray-300 font-medium text-xs transition">
                                    Eliminar foto
                                </button>

                            </form>
                        @endif

                    </div>
                    <div class="bg-[#0f1535] rounded-2xl border border-[#1e295d] p-6 shadow-lg space-y-5">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h2 class="text-base font-bold text-white">Informacion de la cuenta</h2>
                        </div>

                        <!-- Meta Datos Grid -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs font-medium text-gray-400">Fecha de creación</p>
                                <p class="text-sm font-bold text-white mt-1">
                                    {{ Auth::User()->created_at ?? 'Desconocido' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-400">Rol en el sistema</p>
                                <p class="text-sm font-bold text-white mt-1">{{ Auth::User()->rol ?? 'Desconocido' }}
                                </p>
                            </div>
                        </div>

                        <!-- Estado de la cuenta -->
                        <div>
                            <p class="text-xs font-medium text-gray-400 mb-1.5">Estado de la cuenta</p>
                            <span
                                class="inline-block px-3 py-1 rounded-md text-xs font-bold bg-[#06331e] border border-emerald-600 text-emerald-400">
                                Activa
                            </span>
                        </div>

                        <!-- Inner Box Recordatorio -->
                        <div class="bg-[#0b102b] border border-[#1e295d] rounded-xl p-4 flex items-start gap-3">
                            <svg class="w-6 h-6 text-gray-300 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            <div>
                                <p class="text-xs font-bold text-white">Manten tu informacion actualizada</p>
                                <p class="text-[11px] text-gray-400 leading-relaxed mt-1">
                                    Una información correcta nos ayuda a darte un mejor soporte y atención
                                </p>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>

    </main>
   <!-- MODAL SOLICITAR CAMBIO DE INFORMACIÓN -->
<div id="changeModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">

    <!-- Backdrop -->
    <div id="modalBackdrop"
        class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity">
    </div>

    <!-- Contenido -->
    <div
        class="relative w-full max-w-lg mx-4 bg-[#0a0e27] border border-[#1e295d] rounded-2xl shadow-2xl overflow-hidden z-10 transition-all transform">

        <!-- Header -->
        <div
            class="flex items-center justify-between px-6 py-4 border-b border-[#1e295d]/80 bg-[#0f1535]/50">

            <div class="flex items-center gap-2.5">

                <div class="p-2 rounded-lg bg-blue-600/10 border border-blue-500/20 text-blue-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                </div>

                <div>
                    <h3 class="text-base font-bold text-white">
                        Solicitar cambio de información
                    </h3>

                    <p class="text-xs text-gray-400">
                        Los cambios requerirán aprobación administrativa
                    </p>
                </div>

            </div>

            <button
                id="closeModalBtn"
                type="button"
                class="text-gray-400 hover:text-white p-1 rounded-lg hover:bg-[#151b3b] transition">

                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>

            </button>

        </div>


        <!-- FORMULARIO -->
        <form
            action="{{ route('solicitar.cambio.store') }}"
            method="POST"
            class="p-6 space-y-4">

            @csrf

            <!-- Campo a modificar -->
            <div>

                <label class="block text-xs font-semibold text-gray-300 mb-1.5">
                    Campo a modificar
                    <span class="text-rose-500">*</span>
                </label>

                <select
                    name="campo"
                    id="campoCambio"
                    required
                    class="w-full bg-[#060818] border border-[#1e295d] text-gray-200 text-sm rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-blue-500 transition">

                    <option value="" disabled selected>
                        Selecciona el dato a actualizar
                    </option>

                    <option
                        value="nombre"
                        data-valor="{{ Auth::User()->name }}">
                        Nombre completo
                    </option>

                    <option
                        value="correo"
                        data-valor="{{ Auth::User()->email }}">
                        Correo electrónico
                    </option>

                    <option
                        value="oficina"
                        data-valor="{{ Auth::User()->departamento?->oficina?->nombre ?? '' }}">
                        Oficina / Sucursal
                    </option>

                    <option
                        value="departamento"
                        data-valor="{{ Auth::User()->departamento?->nombre ?? '' }}">
                        Departamento
                    </option>
                </select>

            </div>


            <!-- Valor actual -->
            <div>

                <label class="block text-xs font-semibold text-gray-300 mb-1.5">
                    Valor actual
                </label>

                <input
                    type="text"
                    id="valorActualVisible"
                    readonly
                    placeholder="Selecciona primero el campo..."
                    class="w-full bg-[#060818] border border-[#1e295d] text-gray-400 text-sm rounded-xl px-3.5 py-2.5 focus:outline-none">

                <!-- Este es el que realmente se envía -->
                <input
                    type="hidden"
                    name="valor_actual"
                    id="valorActual">

            </div>


            <!-- Nuevo valor -->
            <div>

                <label class="block text-xs font-semibold text-gray-300 mb-1.5">
                    Nuevo valor o dato correcto
                    <span class="text-rose-500">*</span>
                </label>

                <input
                    type="text"
                    name="nuevo_valor"
                    required
                    placeholder="Escribe aquí el dato correcto..."
                    class="w-full bg-[#060818] border border-[#1e295d] text-gray-200 text-sm rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-blue-500 transition placeholder:text-gray-600">

            </div>


            <!-- Motivo -->
            <div>

                <label class="block text-xs font-semibold text-gray-300 mb-1.5">
                    Motivo o justificación del cambio
                    <span class="text-rose-500">*</span>
                </label>

                <textarea
                    name="motivo"
                    rows="3"
                    required
                    placeholder="Describe brevemente la razón de la corrección..."
                    class="w-full bg-[#060818] border border-[#1e295d] text-gray-200 text-sm rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-blue-500 transition placeholder:text-gray-600 resize-none"></textarea>

            </div>


            <!-- Acciones -->
            <div class="flex items-center justify-end gap-3 pt-2">

                <button
                    type="button"
                    id="cancelModalBtn"
                    class="px-4 py-2.5 rounded-xl border border-[#1e295d] bg-[#0b102b] hover:bg-[#151b3b] text-gray-300 text-xs font-medium transition">

                    Cancelar

                </button>

                <button
                    type="submit"
                    class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold shadow-[0_0_15px_rgba(37,99,235,0.4)] transition">

                    Enviar solicitud

                </button>

            </div>

        </form>

    </div>
</div>


<script>

    const campoCambio = document.getElementById('campoCambio');
    const valorActual = document.getElementById('valorActual');
    const valorActualVisible = document.getElementById('valorActualVisible');

    campoCambio.addEventListener('change', function () {

        const opcionSeleccionada =
            this.options[this.selectedIndex];

        const valor =
            opcionSeleccionada.dataset.valor || '';

        // Valor que se envía al controlador
        valorActual.value = valor;

        // Valor que se muestra visualmente
        valorActualVisible.value =
            valor || 'No disponible';

    });

</script>
</body>

</html>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const openBtn = document.getElementById('openModalBtn');
        const closeBtn = document.getElementById('closeModalBtn');
        const cancelBtn = document.getElementById('cancelModalBtn');
        const backdrop = document.getElementById('modalBackdrop');
        const modal = document.getElementById('changeModal');

        const toggleModal = () => {
            modal.classList.toggle('hidden');
        };

        if (openBtn) openBtn.addEventListener('click', toggleModal);
        if (closeBtn) closeBtn.addEventListener('click', toggleModal);
        if (cancelBtn) cancelBtn.addEventListener('click', toggleModal);
        if (backdrop) backdrop.addEventListener('click', toggleModal);
    });
</script>
