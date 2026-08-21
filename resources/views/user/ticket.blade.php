<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Crear nuevo ticket - TicketPro</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/images/logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="bg-[#060818] text-white font-sans antialiased flex h-screen overflow-hidden">

    <aside
        class="hidden md:flex flex-col w-64 bg-[#0a0e27] border-r border-[#1e295d] px-4 py-6 overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-track]:bg-[#060818] [&::-webkit-scrollbar-thumb]:bg-[#1e295d] [&::-webkit-scrollbar-thumb]:rounded-full">

        <div class="text-3xl font-bold mb-8 px-2 tracking-wide">
            Ticket<span class="text-blue-500">Pro</span>
        </div>

        <div class="flex items-center gap-3 mb-10 px-2">
            <img src="{{ auth()->user()->picture
                ? asset('storage/' . auth()->user()->picture)
                : asset('storage/profile-photos/user.png') }}"
                alt="{{ auth()->user()->name }}" class="w-12 h-12 rounded-full border-2 border-gray-500 object-cover">

            <div>
                <h3 class="text-sm font-semibold text-white">
                    {{ Auth::user()->name ?? 'Usuario' }}
                </h3>

                <p class="text-xs text-gray-400">
                    {{ optional(Auth::user()->departamento)->nombre ?? 'Administración' }}
                </p>
            </div>
        </div>

        <nav class="flex-1 space-y-2">

            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-[#151b3b] hover:text-white rounded-lg transition">

                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>

                <span class="text-sm font-medium">
                    Inicio
                </span>
            </a>

            <a href="{{ route('misticketusuario') }}"
                class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-[#151b3b] hover:text-white rounded-lg transition">

                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                    </path>
                </svg>

                <span class="text-sm font-medium">
                    Mis tickets
                </span>
            </a>

            <a href="{{ route('ticketusuario') }}"
                class="flex items-center gap-3 px-4 py-3 bg-blue-600 text-white rounded-lg font-medium shadow-[0_0_15px_rgba(37,99,235,0.4)]">

                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z">
                    </path>
                </svg>

                <span class="text-sm font-medium">
                    Crear ticket
                </span>
            </a>

            <a href="{{ route('avisosusuario') }}"
                class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-[#151b3b] hover:text-white rounded-lg transition">

                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>

                <span class="text-sm font-medium">
                    Avisos
                </span>
            </a>

            <a href="{{ route('perfilusuario') }}"
                class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-[#151b3b] hover:text-white rounded-lg transition">

                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                    </path>
                </svg>

                <span class="text-sm font-medium">
                    Mi perfil
                </span>
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

                    <span class="text-sm font-medium">
                        Cerrar sesión
                    </span>

                </button>
            </form>

        </div>

    </aside>

    <main
        class="flex-1 flex flex-col h-screen overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-track]:bg-[#060818] [&::-webkit-scrollbar-thumb]:bg-[#1e295d] [&::-webkit-scrollbar-thumb]:rounded-full">

        <header class="flex flex-col md:flex-row justify-between items-start md:items-center px-6 py-6 md:py-8 gap-4">

            <div>
                <h1 class="text-2xl font-bold mb-1 tracking-tight">
                    Crear nuevo ticket
                </h1>

                <p class="text-sm text-gray-400">
                    <span class="text-gray-200 font-medium">
                        Nuevo ticket
                    </span>
                    / Dashboard
                </p>
            </div>

            <div class="flex items-center gap-6 self-end md:self-auto">

                <div class="relative inline-block text-left">

                    <!-- =========================================================
                        NOTIFICACIONES
                     ========================================================== -->

                    <div class="relative" x-data="{ notificacionesAbiertas: false }">

                        <!-- BOTÓN DE NOTIFICACIONES -->
                        <button type="button" @click="notificacionesAbiertas = !notificacionesAbiertas"
                            @click.outside="notificacionesAbiertas = false"
                            class="relative flex items-center justify-center w-10 h-10 rounded-xl
                       bg-slate-900/80 border border-slate-800
                       text-slate-400 hover:text-white hover:bg-slate-800
                       transition-all duration-200 focus:outline-none">

                            <i data-lucide="bell" class="w-5 h-5"></i>


                            <!-- INDICADOR DE NOTIFICACIONES NUEVAS -->
                            @if ($notificacionesNoLeidas > 0)
                                <span
                                    class="absolute -top-1 -right-1 min-w-[18px] h-[18px]
                               px-1 flex items-center justify-center
                               rounded-full bg-indigo-600
                               border-2 border-[#050814]
                               text-[9px] font-bold text-white">

                                    {{ $notificacionesNoLeidas > 99 ? '99+' : $notificacionesNoLeidas }}

                                </span>
                            @endif

                        </button>


                        <!-- =====================================================
                 DROPDOWN DE NOTIFICACIONES
            ====================================================== -->

                        <div x-show="notificacionesAbiertas" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                            x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                            @click.outside="notificacionesAbiertas = false"
                            class="absolute right-0 top-full mt-3
                       w-[360px] max-w-[calc(100vw-2rem)]
                       bg-[#0f1535]
                       border border-[#1e295d]
                       rounded-2xl
                       shadow-2xl shadow-black/40
                       overflow-hidden z-[99999]"
                            style="display: none;">

                            <!-- =================================================
                     CABECERA
                ================================================== -->

                            <div
                                class="flex items-center justify-between
                           px-4 py-4
                           border-b border-slate-800/80">

                                <div class="flex items-center gap-2">

                                    <div
                                        class="w-8 h-8 rounded-lg
                                   bg-indigo-500/10
                                   border border-indigo-500/20
                                   flex items-center justify-center">

                                        <i data-lucide="bell" class="w-4 h-4 text-indigo-400">
                                        </i>

                                    </div>

                                    <div>

                                        <h3 class="text-sm font-semibold text-white">
                                            Notificaciones
                                        </h3>

                                        <p class="text-[10px] text-slate-500">
                                            Tienes {{ $notificacionesNoLeidas }} nuevas
                                        </p>

                                    </div>

                                </div>


                                <!-- MARCAR COMO LEÍDAS -->
                                @if ($notificacionesNoLeidas > 0)
                                    <form method="POST" action="{{ route('notificaciones.marcarLeidas') }}">

                                        @csrf

                                        @method('PATCH')

                                        <button type="submit"
                                            class="text-[11px] font-medium
                                       text-indigo-400
                                       hover:text-indigo-300
                                       transition-colors">

                                            Marcar leídas

                                        </button>

                                    </form>
                                @endif

                            </div>


                            <!-- =================================================
                     LISTA DE NOTIFICACIONES
                ================================================== -->

                            <div class="max-h-[400px] overflow-y-auto">

                                @forelse ($notificaciones as $notificacion)
                                    <a href="{{ $notificacion->url ?? '#' }}"
                                        class="group flex gap-3 px-4 py-4
                                   border-b border-slate-800/50
                                   transition-colors
                                   hover:bg-slate-800/40
                                   {{ !$notificacion->leida ? 'bg-indigo-500/[0.04]' : '' }}">

                                        <!-- ICONO -->
                                        <div
                                            class="w-10 h-10 shrink-0
                                       rounded-xl
                                       border border-indigo-500/20
                                       bg-indigo-500/10
                                       flex items-center justify-center">

                                            <i data-lucide="{{ $notificacion->icono ?? 'bell' }}"
                                                class="w-5 h-5 text-indigo-400">
                                            </i>

                                        </div>


                                        <!-- CONTENIDO -->
                                        <div class="flex-1 min-w-0">

                                            <div class="flex items-start justify-between gap-2">

                                                <p
                                                    class="text-xs font-semibold
                                               text-white
                                               group-hover:text-indigo-400
                                               transition-colors">

                                                    {{ $notificacion->titulo }}

                                                </p>


                                                <!-- PUNTO DE NO LEÍDA -->
                                                @if (!$notificacion->leida)
                                                    <span
                                                        class="w-2 h-2 shrink-0 mt-1.5
                                                   rounded-full
                                                   bg-indigo-500">
                                                    </span>
                                                @endif

                                            </div>


                                            <p
                                                class="mt-1 text-[11px]
                                           leading-relaxed
                                           text-slate-400">

                                                {{ $notificacion->mensaje }}

                                            </p>


                                            <p
                                                class="mt-2 text-[10px]
                                           text-slate-500">

                                                {{ $notificacion->created_at->diffForHumans() }}

                                            </p>

                                        </div>

                                    </a>

                                @empty

                                    <!-- SIN NOTIFICACIONES -->
                                    <div class="px-6 py-10 text-center">

                                        <div
                                            class="mx-auto mb-3
                                       w-12 h-12
                                       rounded-full
                                       bg-slate-800/50
                                       border border-slate-800
                                       flex items-center justify-center">

                                            <i data-lucide="bell-off" class="w-5 h-5 text-slate-500">
                                            </i>

                                        </div>

                                        <p class="text-xs font-medium text-slate-400">
                                            No tienes notificaciones
                                        </p>

                                        <p class="text-[10px] text-slate-600 mt-1">
                                            Aquí aparecerán tus nuevas notificaciones.
                                        </p>

                                    </div>
                                @endforelse

                            </div>


                            <!-- =================================================
                     PIE DEL DROPDOWN
                ================================================== -->

                            @if ($notificaciones->count() > 0)
                                <div
                                    class="px-4 py-3
                               border-t border-slate-800/80
                               bg-[#0b1026]">

                                    <p class="text-[10px] text-center text-slate-500">
                                        Mostrando tus notificaciones recientes
                                    </p>

                                </div>
                            @endif

                        </div>

                    </div>
                </div>

                <div class="relative" x-data="{ perfilAbierto: false }">

                    <button type="button" @click="perfilAbierto = !perfilAbierto"
                        class="flex items-center gap-3 cursor-pointer rounded-xl px-2 py-1.5 hover:bg-[#151b3b] transition-all duration-200 focus:outline-none">

                        <img src="{{ auth()->user()->picture
                            ? asset('storage/' . auth()->user()->picture)
                            : asset('storage/profile-photos/user.png') }}"
                            alt="{{ auth()->user()->name }}"
                            class="w-10 h-10 rounded-full border border-gray-600 object-cover">

                        <div class="hidden md:block text-right">

                            <p class="text-sm font-semibold leading-tight">
                                {{ Auth::user()->name ?? 'Usuario' }}
                            </p>

                            <p class="text-xs text-gray-400">
                                {{ optional(Auth::user()->departamento)->nombre ?? 'Administración' }}
                            </p>

                        </div>

                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200"
                            :class="{ 'rotate-180': perfilAbierto }" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />

                        </svg>

                    </button>


                    <div x-show="perfilAbierto" @click.outside="perfilAbierto = false" x-transition
                        class="absolute right-0 mt-3 w-56
               bg-[#0f1535]/95 backdrop-blur-xl
               border border-[#1e295d]
               rounded-xl shadow-2xl shadow-black/40
               overflow-hidden z-[99999]"
                        style="display: none;">

                        <a href="{{ route('perfilusuario') }}"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-gray-300 hover:bg-[#151b3b] hover:text-white transition-colors">

                            <i data-lucide="circle-user-round" class="w-5 h-5 text-slate-400">
                            </i>


                            <span>
                                Ver perfil
                            </span>

                        </a>


                        <div class="border-t border-[#1e295d]"></div>


                        <form method="POST" action="{{ route('logout') }}">

                            @csrf

                            <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-3 text-sm text-gray-300 hover:bg-red-500/10 hover:text-red-400 transition-colors text-left">

                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />

                                </svg>

                                <span>
                                    Cerrar sesión
                                </span>

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </header>

        <div class="px-6 pb-8">

            @if (session('success'))
                <div id="successMessage"
                    class="fixed right-5 top-5 z-[9999] w-full max-w-sm rounded-2xl border border-green-500/30 bg-[#0f1535] p-4 shadow-[0_0_30px_rgba(34,197,94,0.20)]">

                    <div class="flex items-start gap-3">

                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-green-500/15 text-green-400">

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">

                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7">
                                </path>

                            </svg>

                        </div>

                        <div class="flex-1">

                            <p class="font-bold text-white">
                                ¡Éxito!
                            </p>

                            <p class="mt-1 text-sm text-slate-400">
                                {{ session('success') }}
                            </p>

                        </div>

                        <button type="button" onclick="document.getElementById('successMessage')?.remove()"
                            class="text-slate-500 hover:text-white transition">

                            ✕

                        </button>

                    </div>

                </div>
            @endif

            @if (session('error'))
                <div id="errorMessage"
                    class="fixed right-5 top-5 z-[9999] w-full max-w-sm rounded-2xl border border-red-500/30 bg-[#0f1535] p-4 shadow-[0_0_30px_rgba(239,68,68,0.20)]">

                    <div class="flex items-start gap-3">

                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-500/15 text-red-400">

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">

                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12">
                                </path>

                            </svg>

                        </div>

                        <div class="flex-1">

                            <p class="font-bold text-white">
                                ¡Error!
                            </p>

                            <p class="mt-1 text-sm text-slate-400">
                                {{ session('error') }}
                            </p>

                        </div>

                        <button type="button" onclick="document.getElementById('errorMessage')?.remove()"
                            class="text-slate-500 hover:text-white transition">

                            ✕

                        </button>

                    </div>

                </div>
            @endif

            <form action="{{ route('ticketusuario.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">

                @csrf

                <section class="bg-[#0f1535] rounded-xl border border-[#1e295d] p-5 shadow-lg">

                    <div class="flex items-center gap-2 mb-4">

                        <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                            </path>

                        </svg>

                        <h2 class="text-lg font-semibold">
                            Información del usuario
                        </h2>

                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                        <div class="flex items-center gap-3 bg-[#060818] border border-[#1e295d] rounded-lg p-3">

                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                </path>

                            </svg>

                            <div>

                                <p class="text-xs text-blue-400 font-semibold mb-0.5">
                                    Nombre
                                </p>

                                <p class="text-sm font-medium">
                                    {{ Auth::user()->name ?? 'Desconocido' }}
                                </p>

                            </div>

                        </div>

                        <div class="flex items-center gap-3 bg-[#060818] border border-[#1e295d] rounded-lg p-3">

                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h2m0 0h2m-4 0v-4m0 0h4m-4 0V9m0 4h4m-4-4h4m-4 0V5">
                                </path>

                            </svg>

                            <div>

                                <p class="text-xs text-blue-400 font-semibold mb-0.5">
                                    Departamento
                                </p>

                                <p class="text-sm font-medium">
                                    {{ optional(Auth::user()->departamento)->nombre ?? 'Desconocido' }}
                                </p>

                            </div>

                        </div>

                        <div class="flex items-center gap-3 bg-[#060818] border border-[#1e295d] rounded-lg p-3">

                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                                </path>

                            </svg>

                            <div>

                                <p class="text-xs text-blue-400 font-semibold mb-0.5">
                                    Oficina / Sucursal
                                </p>

                                <p class="text-sm font-medium">
                                    {{ optional(optional(Auth::user()->departamento)->oficina)->nombre ?? 'Desconocido' }}
                                </p>

                            </div>

                        </div>

                        <div class="flex items-center gap-3 bg-[#060818] border border-[#1e295d] rounded-lg p-3">

                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z">
                                </path>

                            </svg>

                            <div>

                                <p class="text-xs text-blue-400 font-semibold mb-0.5">
                                    Empresa
                                </p>

                                <p class="text-sm font-medium">
                                    {{ optional(optional(optional(Auth::user()->departamento)->oficina)->empresa)->empresa ?? 'Desconocido' }}
                                </p>

                            </div>

                        </div>

                    </div>

                </section>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <section
                        class="lg:col-span-2 bg-[#0f1535] rounded-xl border border-[#1e295d] p-6 shadow-lg flex flex-col gap-5">

                        <div class="flex items-center gap-2">

                            <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                                </path>

                            </svg>

                            <h2 class="text-lg font-semibold">
                                Información del ticket
                            </h2>

                        </div>

                        <div>

                            <label class="block text-sm font-medium mb-2">
                                Título del ticket
                            </label>

                            <input type="text" name="titulo" value="{{ old('titulo') }}"
                                placeholder="Ej. Impresora de administración sin conexión" required
                                class="w-full bg-[#060818] border border-[#1e295d] rounded-lg p-3 text-sm text-gray-200 focus:outline-none focus:border-blue-500 placeholder-gray-500 transition">

                            @error('titulo')
                                <p class="text-red-400 text-xs mt-1">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>
                                <label class="block text-sm font-medium mb-2">
                                    Tipo de falla
                                </label>

                                <div class="relative">
                                    <select name="tipo_falla" id="tipo_falla" required
                                        onchange="mostrarCampoEquipo()"
                                        class="w-full bg-[#060818] border border-[#1e295d] rounded-lg p-3 text-sm text-gray-400 focus:outline-none focus:border-blue-500 appearance-none transition">

                                        <option value="">
                                            Selecciona el tipo de falla
                                        </option>

                                        <option value="Hardware"
                                            {{ old('tipo_falla') == 'Hardware' ? 'selected' : '' }}>
                                            Hardware
                                        </option>

                                        <option value="Software"
                                            {{ old('tipo_falla') == 'Software' ? 'selected' : '' }}>
                                            Software
                                        </option>

                                        <option value="Redes" {{ old('tipo_falla') == 'Redes' ? 'selected' : '' }}>
                                            Redes
                                        </option>

                                        <option value="Equipo" {{ old('tipo_falla') == 'Equipo' ? 'selected' : '' }}>
                                            Equipo
                                        </option>

                                    </select>

                                    <div
                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">

                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7">
                                            </path>

                                        </svg>

                                    </div>
                                </div>

                                @error('tipo_falla')
                                    <p class="text-red-400 text-xs mt-1">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div>

                                <label class="block text-sm font-medium mb-2">
                                    Prioridad
                                </label>

                                <div class="flex flex-wrap gap-2">

                                    <label
                                        class="flex items-center gap-1.5 px-3 py-2 border border-red-500/50 rounded-lg cursor-pointer hover:bg-red-500/10 transition">

                                        <input type="radio" name="prioridad" class="hidden" value="Critica"
                                            {{ old('prioridad') == 'Critica' ? 'checked' : '' }}>

                                        <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                            </path>

                                        </svg>

                                        <span class="text-red-500 text-xs font-semibold">
                                            Crítica
                                        </span>

                                    </label>

                                    <label
                                        class="flex items-center gap-1.5 px-3 py-2 border border-orange-500/50 rounded-lg cursor-pointer hover:bg-orange-500/10 transition">

                                        <input type="radio" name="prioridad" class="hidden" value="Alta"
                                            {{ old('prioridad') == 'Alta' ? 'checked' : '' }}>

                                        <svg class="w-3.5 h-3.5 text-orange-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M5 10l7-7m0 0l7 7m-7-7v18">
                                            </path>

                                        </svg>

                                        <span class="text-orange-500 text-xs font-semibold">
                                            Alta
                                        </span>

                                    </label>

                                    <label
                                        class="flex items-center gap-1.5 px-3 py-2 border border-yellow-500/50 rounded-lg cursor-pointer hover:bg-yellow-500/10 transition">

                                        <input type="radio" name="prioridad" class="hidden" value="Media"
                                            {{ old('prioridad') == 'Media' ? 'checked' : '' }}>

                                        <svg class="w-3.5 h-3.5 text-yellow-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M20 12H4">
                                            </path>

                                        </svg>

                                        <span class="text-yellow-500 text-xs font-semibold">
                                            Media
                                        </span>

                                    </label>

                                    <label
                                        class="flex items-center gap-1.5 px-3 py-2 border border-green-500/50 rounded-lg cursor-pointer hover:bg-green-500/10 transition">

                                        <input type="radio" name="prioridad" class="hidden" value="Normal"
                                            {{ old('prioridad', 'Normal') == 'Normal' ? 'checked' : '' }}>

                                        <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>

                                        </svg>

                                        <span class="text-green-500 text-xs font-semibold">
                                            Normal
                                        </span>

                                    </label>

                                </div>

                                @error('prioridad')
                                    <p class="text-red-400 text-xs mt-1">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>
                            <div id="campoEquipo" class="hidden">
                                <label class="block text-sm font-medium mb-2">
                                    ¿Qué equipo presenta la falla?
                                </label>

                                <input type="text" name="equipo" id="equipo" value="{{ old('equipo') }}"
                                    placeholder="Ej. Laptop - Lenovo"
                                    class="w-full bg-[#060818] border border-[#1e295d] rounded-lg p-3 text-sm text-gray-200 focus:outline-none focus:border-blue-500 placeholder-gray-500 transition">

                                <p class="text-xs text-gray-500 mt-2">
                                    Especifica qué equipo presenta la falla. Si es una computadora,
                                    PC, laptop o celular, indica también la marca.
                                </p>

                                <p class="text-xs text-blue-400 mt-1">
                                    Ejemplos: Laptop - Lenovo, PC - HP, Impresora - Epson, Celular - Samsung.
                                </p>

                                @error('equipo')
                                    <p class="text-red-400 text-xs mt-1">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <div>

                            <label class="block text-sm font-medium mb-2">
                                Descripción del problema
                            </label>

                            <textarea name="descripcion" rows="4"
                                placeholder="Describe detalladamente el problema que estás experimentando..." required
                                class="w-full bg-[#060818] border border-[#1e295d] rounded-lg p-3 text-sm text-gray-200 focus:outline-none focus:border-blue-500 placeholder-gray-500 resize-none transition">{{ old('descripcion') }}</textarea>

                            @error('descripcion')
                                <p class="text-red-400 text-xs mt-1">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>

                                <label class="block text-sm font-medium mb-2">
                                    ¿Afecta a otros usuarios?
                                </label>

                                <div
                                    class="flex bg-[#060818] border border-[#1e295d] rounded-lg overflow-hidden p-0.5">

                                    <label
                                        class="flex-1 py-2 text-sm flex justify-center items-center cursor-pointer transition has-[:checked]:bg-[#1b1c55] has-[:checked]:text-white text-gray-400">

                                        <input type="radio" name="afecta_otros" value="1" class="hidden"
                                            {{ old('afecta_otros', '0') == '1' ? 'checked' : '' }}>

                                        Sí

                                    </label>

                                    <label
                                        class="flex-1 py-2 text-sm flex justify-center items-center cursor-pointer transition has-[:checked]:bg-[#1b1c55] has-[:checked]:text-white text-gray-400">

                                        <input type="radio" name="afecta_otros" value="0" class="hidden"
                                            {{ old('afecta_otros', '0') == '0' ? 'checked' : '' }}>

                                        No

                                    </label>

                                </div>

                                @error('afecta_otros')
                                    <p class="text-red-400 text-xs mt-1">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                            <div>

                                <label class="block text-sm font-medium mb-2">
                                    ¿Es una falla recurrente?
                                </label>

                                <div
                                    class="flex bg-[#060818] border border-[#1e295d] rounded-lg overflow-hidden p-0.5">

                                    <label
                                        class="flex-1 py-2 text-sm flex justify-center items-center cursor-pointer transition has-[:checked]:bg-[#1b1c55] has-[:checked]:text-white text-gray-400">

                                        <input type="radio" name="es_recurrente" value="1" class="hidden"
                                            {{ old('es_recurrente', '0') == '1' ? 'checked' : '' }}>

                                        Sí

                                    </label>

                                    <label
                                        class="flex-1 py-2 text-sm flex justify-center items-center cursor-pointer transition has-[:checked]:bg-[#1b1c55] has-[:checked]:text-white text-gray-400">

                                        <input type="radio" name="es_recurrente" value="0" class="hidden"
                                            {{ old('es_recurrente', '0') == '0' ? 'checked' : '' }}>

                                        No

                                    </label>

                                </div>

                                @error('es_recurrente')
                                    <p class="text-red-400 text-xs mt-1">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                        </div>

                        <div>

                            <label class="block text-sm font-medium mb-2">
                                Comentarios adicionales
                            </label>

                            <textarea name="comentarios" rows="2"
                                placeholder="Información adicional que pueda ayudar a resolver el problema (opcional)..."
                                class="w-full bg-[#060818] border border-[#1e295d] rounded-lg p-3 text-sm text-gray-200 focus:outline-none focus:border-blue-500 placeholder-gray-500 resize-none transition">{{ old('comentarios') }}</textarea>

                            @error('comentarios')
                                <p class="text-red-400 text-xs mt-1">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </section>

                    <section
                        class="bg-[#0f1535] rounded-xl border border-[#1e295d] p-6 shadow-lg flex flex-col justify-between">

                        <div>

                            <div class="flex items-center gap-2 mb-6">

                                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                    </path>

                                </svg>

                                <h2 class="text-lg font-semibold">
                                    Evidencia
                                </h2>

                            </div>

                            <div class="mb-5">

                                <label class="block text-sm font-medium mb-2">
                                    Adjunta evidencia de la falla
                                </label>

                                <label
                                    class="bg-[#060818] border-2 border-dashed border-[#1e295d] rounded-xl p-6 flex flex-col items-center justify-center text-center cursor-pointer hover:border-blue-500 transition group">

                                    <svg class="w-8 h-8 text-gray-400 mb-3 group-hover:text-blue-500 transition"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12">
                                        </path>

                                    </svg>

                                    <p class="text-sm font-medium text-gray-300 mb-1">
                                        Haz click para seleccionar archivos
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        JPG, JPEG, PNG, PDF o MP4
                                    </p>

                                    <input type="file" name="evidencia[]" multiple class="hidden"
                                        accept=".jpg,.jpeg,.png,.pdf,.mp4">

                                </label>

                                @error('evidencia.*')
                                    <p class="text-red-400 text-xs mt-1">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                        </div>

                        <div class="mt-8 flex flex-col sm:flex-row justify-end gap-3">

                            <a href="{{ route('dashboard') }}"
                                class="px-6 py-2.5 rounded-lg border border-[#1e295d] text-white hover:bg-[#1e295d] transition font-medium text-sm text-center">

                                Cancelar

                            </a>

                            <button type="submit"
                                class="px-6 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white transition flex items-center justify-center gap-2 font-medium text-sm shadow-lg shadow-blue-600/30">

                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8">
                                    </path>

                                </svg>

                                Enviar ticket

                            </button>

                        </div>

                    </section>

                </div>

            </form>

        </div>

    </main>
    <script>
        function mostrarCampoEquipo() {
            const tipoFalla = document.getElementById('tipo_falla');
            const campoEquipo = document.getElementById('campoEquipo');
            const equipo = document.getElementById('equipo');

            if (tipoFalla.value === 'Equipo') {
                campoEquipo.classList.remove('hidden');
                equipo.required = true;
            } else {
                campoEquipo.classList.add('hidden');
                equipo.required = false;
                equipo.value = '';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            mostrarCampoEquipo();
        });
    </script>
</body>

</html>
