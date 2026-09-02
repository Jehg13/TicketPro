<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mi Perfil - TicketPro</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/images/logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/mfa.js', 'resources/js/fotoperfil.js', 'resources/js/app.js', 'alpine.js', 'resources/js/modalcambio.js'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body x-data="{ ...perfilSeguridad(), menuMovilAbierto: false }" class="bg-[#060818] text-white font-sans antialiased flex h-screen overflow-hidden">
    <aside
        class="hidden md:flex flex-col w-64 bg-[#0a0e27] border-r border-[#1e295d] px-4 py-6 overflow-y-auto [::-webkit-scrollbar]:w-2 [::-webkit-scrollbar-track]:bg-[#060818] [::-webkit-scrollbar-thumb]:bg-[#1e295d] [::-webkit-scrollbar-thumb]:rounded-full">
        <div class="text-3xl font-bold mb-8 px-2 tracking-wide">
            Ticket<span class="text-blue-500">Pro</span>
        </div>
        <div class="flex items-center gap-3 mb-10 px-2">
            <img src="{{ auth()->user()->picture ? asset('storage/' . auth()->user()->picture) : asset('storage/profile-photos/user.png') }}"
                alt="{{ auth()->user()->name }}" class="w-12 h-12 rounded-full border-2 border-gray-500 object-cover">
            <div>
                <h3 class="text-sm font-semibold text-white">{{ Auth::user()->name ?? 'Desconocido' }}</h3>
                <p class="text-xs text-gray-400">{{ optional(Auth::user()->departamento)->nombre ?? 'Desconocido' }}</p>
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
                <span class="text-sm font-medium">Inicio</span>
            </a>
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
    <div x-show="menuMovilAbierto" x-transition.opacity class="fixed inset-0 bg-black/60 z-[9998] md:hidden"
        @click="menuMovilAbierto = false" style="display:none;"></div>
    <aside x-show="menuMovilAbierto" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed inset-y-0 left-0 z-[9999] w-72 max-w-[85vw] bg-[#0a0e27] border-r border-[#1e295d] px-4 py-6 overflow-y-auto md:hidden"
        style="display:none;">
        <div class="flex items-center justify-between mb-8">
            <div class="text-3xl font-bold tracking-wide">
                Ticket<span class="text-blue-500">Pro</span>
            </div>
            <button type="button" @click="menuMovilAbierto = false"
                class="flex items-center justify-center w-10 h-10 rounded-xl bg-slate-900/80 border border-slate-800 text-slate-400 hover:text-white hover:bg-slate-800 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
        <div class="flex items-center gap-3 mb-8 px-2">
            <img src="{{ auth()->user()->picture ? asset('storage/' . auth()->user()->picture) : asset('storage/profile-photos/user.png') }}"
                alt="{{ auth()->user()->name }}"
                class="w-12 h-12 rounded-full border-2 border-gray-500 object-cover">
            <div class="min-w-0">
                <h3 class="text-sm font-semibold text-white truncate">{{ Auth::user()->name ?? 'Desconocido' }}</h3>
                <p class="text-xs text-gray-400 truncate">
                    {{ optional(Auth::user()->departamento)->nombre ?? 'Desconocido' }}</p>
            </div>
        </div>
        <nav class="space-y-2">
            <a @click="menuMovilAbierto = false" href="{{ route('dashboard') }}"
                class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-[#151b3b] hover:text-white rounded-lg transition">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                <span class="text-sm font-medium">Inicio</span>
            </a>
            <a @click="menuMovilAbierto = false" href="{{ route('misticketusuario') }}"
                class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-[#151b3b] hover:text-white rounded-lg transition">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                    </path>
                </svg>
                <span class="text-sm font-medium">Mis tickets</span>
            </a>
            <a @click="menuMovilAbierto = false" href="{{ route('ticketusuario') }}"
                class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-[#151b3b] hover:text-white rounded-lg transition">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z">
                    </path>
                </svg>
                <span class="text-sm font-medium">Crear ticket</span>
            </a>
            <a @click="menuMovilAbierto = false" href="{{ route('avisosusuario') }}"
                class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-[#151b3b] hover:text-white rounded-lg transition">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
                <span class="text-sm font-medium">Avisos</span>
            </a>
            <a @click="menuMovilAbierto = false" href="{{ route('perfilusuario') }}"
                class="flex items-center gap-3 px-4 py-3 bg-blue-600 text-white rounded-lg font-medium shadow-[0_0_15px_rgba(37,99,235,0.4)]">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="text-sm font-medium">Mi perfil</span>
            </a>
        </nav>
        <div class="mt-auto pt-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-red-500/10 hover:text-red-400 rounded-lg transition text-left">
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
   <main class="flex-1 flex flex-col h-screen overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-track]:bg-[#060818] [&::-webkit-scrollbar-thumb]:bg-[#1e295d] [&::-webkit-scrollbar-thumb]:rounded-full">
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center px-6 py-6 md:py-8 gap-4">
            <div class="flex items-center gap-3 min-w-0">
                <button type="button" @click="menuMovilAbierto = !menuMovilAbierto"
                    class="md:hidden shrink-0 flex items-center justify-center w-10 h-10 rounded-xl bg-slate-900/80 border border-slate-800 text-slate-400 hover:text-white hover:bg-slate-800 transition-all duration-200 focus:outline-none">
                    <svg x-show="!menuMovilAbierto" x-transition class="w-5 h-5" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg x-show="menuMovilAbierto" x-transition class="w-5 h-5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <div class="min-w-0">
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white truncate">Mi perfil</h1>
                    <p class="text-xs sm:text-sm text-gray-400 mt-1 truncate">Consulta de tu información personal y de
                        tu cuenta.</p>
                </div>
            </div>
             <div class="flex items-center gap-4 sm:gap-6 self-end md:self-auto">
                    <div class="relative" x-data="{ notificacionesAbiertas: false }">

                        <button type="button" @click="notificacionesAbiertas = !notificacionesAbiertas"
                            class="relative
                                   flex items-center justify-center
                                   w-10 h-10
                                   rounded-xl
                                   bg-slate-900/80
                                   border border-slate-800
                                   text-slate-400
                                   hover:text-white
                                   hover:bg-slate-800
                                   transition">

                            <i data-lucide="bell" class="w-5 h-5"></i>

                            @if ($notificacionesNoLeidas > 0)
                                <span
                                    class="absolute -top-1 -right-1
                                           min-w-[18px] h-[18px]
                                           px-1
                                           flex items-center justify-center
                                           rounded-full
                                           bg-indigo-600
                                           border-2 border-[#050814]
                                           text-[9px]
                                           font-bold text-white">
                                    {{ $notificacionesNoLeidas > 99 ? '99+' : $notificacionesNoLeidas }}
                                </span>
                            @endif

                        </button>


                        {{-- DROPDOWN NOTIFICACIONES --}}
                        <div x-show="notificacionesAbiertas" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                            x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                            @click.outside="notificacionesAbiertas = false"
                            class="fixed
                                   left-3 right-3
                                   top-16
                                   sm:absolute
                                   sm:left-auto
                                   sm:right-0
                                   sm:top-full
                                   sm:mt-3
                                   w-auto
                                   sm:w-[360px]
                                   max-h-[calc(100vh-80px)]
                                   bg-[#0f1535]
                                   border border-[#1e295d]
                                   rounded-2xl
                                   shadow-2xl
                                   shadow-black/40
                                   overflow-hidden
                                   z-[99999]"
                            style="display:none;">

                            <div
                                class="flex items-center justify-between
                                       px-4 py-4
                                       border-b border-slate-800/80
                                       gap-3">

                                <div class="flex items-center gap-2 min-w-0">

                                    <div
                                        class="w-8 h-8 shrink-0
                                               rounded-lg
                                               bg-indigo-500/10
                                               border border-indigo-500/20
                                               flex items-center justify-center">
                                        <i data-lucide="bell" class="w-4 h-4 text-indigo-400"></i>
                                    </div>

                                    <div class="min-w-0">

                                        <h3
                                            class="text-sm font-semibold
                                                   text-white truncate">
                                            Notificaciones
                                        </h3>

                                        <p
                                            class="text-[10px]
                                                   text-slate-500 truncate">
                                            Tienes {{ $notificacionesNoLeidas }} nuevas
                                        </p>

                                    </div>

                                </div>

                                @if ($notificacionesNoLeidas > 0)
                                    <form method="POST" action="{{ route('notificaciones.marcarLeidas') }}"
                                        class="shrink-0">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                            class="text-[11px]
                                                   font-medium
                                                   text-indigo-400
                                                   hover:text-indigo-300
                                                   transition
                                                   whitespace-nowrap">
                                            Marcar leídas
                                        </button>

                                    </form>
                                @endif

                            </div>


                            <div class="max-h-[400px] overflow-y-auto">

                                @forelse ($notificaciones as $notificacion)
                                    <a href="{{ $notificacion->url ?? '#' }}"
                                        class="group flex gap-3
                                               px-4 py-4
                                               border-b border-slate-800/50
                                               transition-colors
                                               hover:bg-slate-800/40
                                               {{ !$notificacion->leida ? 'bg-indigo-500/[0.04]' : '' }}">

                                        <div
                                            class="w-10 h-10 shrink-0
                                                   rounded-xl
                                                   border border-indigo-500/20
                                                   bg-indigo-500/10
                                                   flex items-center justify-center">

                                            <i data-lucide="{{ $notificacion->icono ?? 'bell' }}"
                                                class="w-5 h-5 text-indigo-400"></i>

                                        </div>

                                        <div class="flex-1 min-w-0">

                                            <div
                                                class="flex items-start
                                                       justify-between
                                                       gap-2">

                                                <p
                                                    class="text-xs
                                                           font-semibold
                                                           text-white
                                                           group-hover:text-indigo-400
                                                           transition
                                                           break-words
                                                           min-w-0">
                                                    {{ $notificacion->titulo }}
                                                </p>

                                                @if (!$notificacion->leida)
                                                    <span
                                                        class="w-2 h-2
                                                               shrink-0
                                                               mt-1.5
                                                               rounded-full
                                                               bg-indigo-500"></span>
                                                @endif

                                            </div>

                                            <p
                                                class="mt-1
                                                       text-[11px]
                                                       leading-relaxed
                                                       text-slate-400
                                                       break-words">
                                                {{ $notificacion->mensaje }}
                                            </p>

                                            <p
                                                class="mt-2
                                                       text-[10px]
                                                       text-slate-500">
                                                {{ $notificacion->created_at->diffForHumans() }}
                                            </p>

                                        </div>

                                    </a>

                                @empty

                                    <div class="px-6 py-10 text-center">

                                        <div
                                            class="mx-auto mb-3
                                                   w-12 h-12
                                                   rounded-full
                                                   bg-slate-800/50
                                                   border border-slate-800
                                                   flex items-center justify-center">
                                            <i data-lucide="bell-off" class="w-5 h-5 text-slate-500"></i>
                                        </div>

                                        <p
                                            class="text-xs
                                                   font-medium
                                                   text-slate-400">
                                            No tienes notificaciones
                                        </p>

                                        <p
                                            class="text-[10px]
                                                   text-slate-600
                                                   mt-1">
                                            Aquí aparecerán tus nuevas notificaciones.
                                        </p>

                                    </div>
                                @endforelse

                            </div>


                            @if ($notificaciones->count() > 0)
                                <div
                                    class="px-4 py-3
                                           border-t border-slate-800/80
                                           bg-[#0b1026]">
                                    <p
                                        class="text-[10px]
                                               text-center
                                               text-slate-500">
                                        Mostrando tus notificaciones recientes
                                    </p>
                                </div>
                            @endif

                        </div>

                    </div>
                    <div class="relative" x-data="{ perfilAbierto: false }">
                        <button type="button" @click="perfilAbierto = !perfilAbierto"
                            class="flex items-center gap-3 cursor-pointer rounded-xl px-2 py-1.5 hover:bg-[#151b3b] transition-all duration-200 focus:outline-none">
                            <img src="{{ auth()->user()->picture ? asset('storage/' . auth()->user()->picture) : asset('storage/profile-photos/user.png') }}"
                                alt="{{ auth()->user()->name }}"
                                class="w-10 h-10 rounded-full border border-gray-600 object-cover">
                            <div class="hidden md:block text-right">
                                <p class="text-sm font-semibold leading-tight">{{ Auth::user()->name ?? 'Usuario' }}
                                </p>
                                <p class="text-xs text-gray-400">
                                    {{ optional(Auth::user()->departamento)->nombre ?? 'Administración' }}</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200"
                                :class="{ 'rotate-180': perfilAbierto }" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="perfilAbierto" @click.outside="perfilAbierto = false" x-transition
                            class="absolute right-0 mt-3 w-56 bg-[#0f1535]/95 backdrop-blur-xl border border-[#1e295d] rounded-xl shadow-2xl shadow-black/40 overflow-hidden z-[99999]"
                            style="display:none;">
                            <div class="flex items-center justify-between px-4 py-3 border-b border-[#1e295d]">
                                <span class="text-xs font-semibold text-white">Cuenta</span>
                                <button type="button" @click="perfilAbierto = false"
                                    class="flex items-center justify-center w-7 h-7 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition"><i
                                        data-lucide="x" class="w-4 h-4"></i></button>
                            </div>
                            <a href="{{ route('perfilusuario') }}"
                                class="flex items-center gap-3 px-4 py-3 text-sm text-gray-300 hover:bg-[#151b3b] hover:text-white transition-colors">
                                <i data-lucide="circle-user-round" class="w-5 h-5 text-slate-400"></i>
                                <span>Ver perfil</span>
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
                                    <span>Cerrar sesión</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
        </header>

        <div class="px-6 py-6">

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

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <div class="lg:col-span-8 space-y-6">

                    <div class="bg-[#0f1535] rounded-2xl border border-[#1e295d] p-6 shadow-lg">

                        <div class="flex items-center gap-3 mb-1">

                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                </path>

                            </svg>

                            <h2 class="text-lg font-bold text-white">
                                Información personal y laboral
                            </h2>

                        </div>

                        <p class="text-xs text-gray-400 mb-6">
                            Esta información es proporcionada por la empresa
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- ========================================================= --}}
                            {{-- COLUMNA IZQUIERDA - INFORMACIÓN PERSONAL --}}
                            {{-- ========================================================= --}}

                            <div class="bg-[#030712]/40 border border-slate-800/80 rounded-2xl p-5">

                                {{-- TÍTULO --}}
                                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-800/80">

                                    <div class="p-2 rounded-lg bg-blue-500/10 text-blue-400">
                                        <i data-lucide="user" class="w-4 h-4"></i>
                                    </div>

                                    <div>
                                        <h3 class="text-sm font-bold text-white">
                                            Información personal
                                        </h3>

                                        <p class="text-[10px] text-slate-500 mt-0.5">
                                            Datos personales y medios de contacto
                                        </p>
                                    </div>

                                </div>


                                <div class="space-y-5">

                                    {{-- ================================================= --}}
                                    {{-- NOMBRE COMPLETO --}}
                                    {{-- ================================================= --}}

                                    <div class="space-y-1.5">

                                        <label
                                            class="flex items-center justify-between text-xs font-semibold text-slate-500">

                                            <span class="flex items-center gap-1.5">

                                                <i data-lucide="user" class="w-3.5 h-3.5 text-slate-500"></i>

                                                Nombre completo

                                            </span>

                                            <i data-lucide="lock" class="w-3.5 h-3.5 text-slate-500"></i>

                                        </label>


                                        <div
                                            class="w-full bg-[#030712]/50 border border-slate-800/80 rounded-xl px-4 py-2.5 text-xs text-slate-400 flex items-center justify-between gap-3">

                                            <span class="truncate">
                                                {{ Auth::user()->name ?? 'No registrado' }}
                                            </span>

                                            <span
                                                class="text-[9px] uppercase tracking-wider text-slate-500 bg-slate-900 px-2 py-0.5 rounded border border-slate-800 whitespace-nowrap">

                                                Fijo

                                            </span>

                                        </div>

                                    </div>


                                    {{-- ================================================= --}}
                                    {{-- CORREO ELECTRÓNICO --}}
                                    {{-- ================================================= --}}

                                    <div class="space-y-1.5">

                                        <label
                                            class="flex items-center justify-between text-xs font-semibold text-slate-500">

                                            <span class="flex items-center gap-1.5">

                                                <i data-lucide="mail" class="w-3.5 h-3.5 text-slate-500"></i>

                                                Correo electrónico

                                            </span>

                                            <i data-lucide="lock" class="w-3.5 h-3.5 text-slate-500"></i>

                                        </label>


                                        <div
                                            class="w-full bg-[#030712]/50 border border-slate-800/80 rounded-xl px-4 py-2.5 text-xs text-slate-400 flex items-center justify-between gap-3">

                                            <span class="truncate">
                                                {{ Auth::user()->email ?? 'No registrado' }}
                                            </span>

                                            <span
                                                class="text-[9px] uppercase tracking-wider text-slate-500 bg-slate-900 px-2 py-0.5 rounded border border-slate-800 whitespace-nowrap">

                                                Fijo

                                            </span>

                                        </div>

                                    </div>


                                    {{-- ================================================= --}}
                                    {{-- TELÉFONO --}}
                                    {{-- ================================================= --}}

                                    <div class="space-y-1.5">

                                        <label
                                            class="flex items-center justify-between text-xs font-semibold text-slate-500">

                                            <span class="flex items-center gap-1.5">

                                                <i data-lucide="phone" class="w-3.5 h-3.5 text-slate-500"></i>

                                                Teléfono

                                            </span>

                                            <i data-lucide="lock" class="w-3.5 h-3.5 text-slate-500"></i>

                                        </label>


                                        <div
                                            class="w-full bg-[#030712]/50 border border-slate-800/80 rounded-xl px-4 py-2.5 text-xs text-slate-400 flex items-center justify-between gap-3">

                                            <span class="truncate">
                                                {{ Auth::user()->phone ?? 'No registrado' }}
                                            </span>

                                            <span
                                                class="text-[9px] uppercase tracking-wider text-slate-500 bg-slate-900 px-2 py-0.5 rounded border border-slate-800 whitespace-nowrap">

                                                Fijo

                                            </span>

                                        </div>

                                    </div>


                                    {{-- ================================================= --}}
                                    {{-- USUARIO / LOGIN --}}
                                    {{-- ================================================= --}}

                                    <div class="space-y-1.5">

                                        <label
                                            class="flex items-center justify-between text-xs font-semibold text-slate-500">

                                            <span class="flex items-center gap-1.5">

                                                <i data-lucide="at-sign" class="w-3.5 h-3.5 text-slate-500"></i>

                                                Usuario de acceso

                                            </span>

                                            <i data-lucide="lock" class="w-3.5 h-3.5 text-slate-500"></i>

                                        </label>


                                        <div
                                            class="w-full bg-[#030712]/50 border border-slate-800/80 rounded-xl px-4 py-2.5 text-xs text-slate-400 flex items-center justify-between gap-3">

                                            <span class="truncate">
                                                {{ Auth::user()->login ?? 'No registrado' }}
                                            </span>

                                            <span
                                                class="text-[9px] uppercase tracking-wider text-slate-500 bg-slate-900 px-2 py-0.5 rounded border border-slate-800 whitespace-nowrap">

                                                Fijo

                                            </span>

                                        </div>

                                    </div>

                                </div>

                            </div>



                            {{-- ========================================================= --}}
                            {{-- COLUMNA DERECHA - INFORMACIÓN LABORAL --}}
                            {{-- ========================================================= --}}

                            <div class="bg-[#030712]/40 border border-slate-800/80 rounded-2xl p-5">

                                {{-- TÍTULO --}}
                                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-800/80">

                                    <div class="p-2 rounded-lg bg-indigo-500/10 text-indigo-400">
                                        <i data-lucide="briefcase-business" class="w-4 h-4"></i>
                                    </div>

                                    <div>
                                        <h3 class="text-sm font-bold text-white">
                                            Información laboral
                                        </h3>

                                        <p class="text-[10px] text-slate-500 mt-0.5">
                                            Información correspondiente a tu puesto y organización
                                        </p>
                                    </div>

                                </div>


                                <div class="space-y-5">

                                    {{-- ================================================= --}}
                                    {{-- EMPRESA --}}
                                    {{-- ================================================= --}}

                                    <div class="space-y-1.5">

                                        <label
                                            class="flex items-center justify-between text-xs font-semibold text-slate-500">

                                            <span class="flex items-center gap-1.5">

                                                <i data-lucide="building-2" class="w-3.5 h-3.5 text-slate-500"></i>

                                                Empresa

                                            </span>

                                            <i data-lucide="lock" class="w-3.5 h-3.5 text-slate-500"></i>

                                        </label>


                                        <div
                                            class="w-full bg-[#030712]/50 border border-slate-800/80 rounded-xl px-4 py-2.5 text-xs text-slate-400 flex items-center justify-between gap-3">

                                            <span class="truncate">

                                                {{ Auth::user()->departamento?->oficina?->empresa?->empresa ?? 'No registrada' }}

                                            </span>

                                            <span
                                                class="text-[9px] uppercase tracking-wider text-slate-500 bg-slate-900 px-2 py-0.5 rounded border border-slate-800 whitespace-nowrap">

                                                Fijo

                                            </span>

                                        </div>

                                    </div>


                                    {{-- ================================================= --}}
                                    {{-- DEPARTAMENTO --}}
                                    {{-- ================================================= --}}

                                    <div class="space-y-1.5">

                                        <label
                                            class="flex items-center justify-between text-xs font-semibold text-slate-500">

                                            <span class="flex items-center gap-1.5">

                                                <i data-lucide="briefcase-business"
                                                    class="w-3.5 h-3.5 text-slate-500"></i>

                                                Departamento

                                            </span>

                                            <i data-lucide="lock" class="w-3.5 h-3.5 text-slate-500"></i>

                                        </label>


                                        <div
                                            class="w-full bg-[#030712]/50 border border-slate-800/80 rounded-xl px-4 py-2.5 text-xs text-slate-400 flex items-center justify-between gap-3">

                                            <span class="truncate">

                                                {{ Auth::user()->departamento?->nombre ?? 'No registrado' }}

                                            </span>

                                            <span
                                                class="text-[9px] uppercase tracking-wider text-slate-500 bg-slate-900 px-2 py-0.5 rounded border border-slate-800 whitespace-nowrap">

                                                Fijo

                                            </span>

                                        </div>

                                    </div>


                                    {{-- ================================================= --}}
                                    {{-- ROL --}}
                                    {{-- ================================================= --}}

                                    <div class="space-y-1.5">

                                        <label
                                            class="flex items-center justify-between text-xs font-semibold text-slate-500">

                                            <span class="flex items-center gap-1.5">

                                                <i data-lucide="shield-check" class="w-3.5 h-3.5 text-slate-500"></i>

                                                Rol

                                            </span>

                                            <i data-lucide="lock" class="w-3.5 h-3.5 text-slate-500"></i>

                                        </label>


                                        <div
                                            class="w-full bg-[#030712]/50 border border-slate-800/80 rounded-xl px-4 py-2.5 text-xs text-slate-400 flex items-center justify-between gap-3">

                                            <span class="truncate capitalize">

                                                {{ Auth::user()->role ?? 'No asignado' }}

                                            </span>

                                            <span
                                                class="text-[9px] uppercase tracking-wider text-slate-500 bg-slate-900 px-2 py-0.5 rounded border border-slate-800 whitespace-nowrap">

                                                Fijo

                                            </span>

                                        </div>

                                    </div>


                                    {{-- ================================================= --}}
                                    {{-- OFICINA / SUCURSAL --}}
                                    {{-- ================================================= --}}

                                    <div class="space-y-1.5">

                                        <label
                                            class="flex items-center justify-between text-xs font-semibold text-slate-500">

                                            <span class="flex items-center gap-1.5">

                                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-500"></i>

                                                Oficina / Sucursal

                                            </span>

                                            <i data-lucide="lock" class="w-3.5 h-3.5 text-slate-500"></i>

                                        </label>


                                        <div
                                            class="w-full bg-[#030712]/50 border border-slate-800/80 rounded-xl px-4 py-2.5 text-xs text-slate-400 flex items-center justify-between gap-3">

                                            <span class="truncate">

                                                {{ Auth::user()->departamento?->oficina?->nombre ?? 'No registrada' }}

                                            </span>

                                            <span
                                                class="text-[9px] uppercase tracking-wider text-slate-500 bg-slate-900 px-2 py-0.5 rounded border border-slate-800 whitespace-nowrap">

                                                Fijo

                                            </span>

                                        </div>

                                    </div>

                                    {{-- ================================================= --}}
                                    {{-- NÚMERO DE EMPLEADO --}}
                                    {{-- ================================================= --}}

                                    <div class="space-y-1.5">

                                        <label
                                            class="flex items-center justify-between text-xs font-semibold text-slate-500">

                                            <span class="flex items-center gap-1.5">

                                                <i data-lucide="badge" class="w-3.5 h-3.5 text-slate-500"></i>

                                                Número de empleado

                                            </span>

                                            <i data-lucide="lock" class="w-3.5 h-3.5 text-slate-500"></i>

                                        </label>


                                        <div
                                            class="w-full bg-[#030712]/50 border border-slate-800/80 rounded-xl px-4 py-2.5 text-xs text-slate-400 flex items-center justify-between gap-3">

                                            <span class="truncate">
                                                {{ Auth::user()->numero_empleado->numero_empleado ?? 'No registrado' }}
                                            </span>

                                            <span
                                                class="text-[9px] uppercase tracking-wider text-slate-500 bg-slate-900 px-2 py-0.5 rounded border border-slate-800 whitespace-nowrap">

                                                Fijo

                                            </span>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div
                        class="bg-[#0f1535] rounded-xl border border-[#1e295d] p-4 flex flex-col sm:flex-row items-center justify-between gap-4">

                        <div class="flex items-center gap-3 text-center sm:text-left">

                            <div
                                class="w-10 h-10 rounded-full bg-white text-[#0f1535] flex items-center justify-center shrink-0 font-bold">
                                i
                            </div>

                            <p class="text-xs text-gray-300 leading-snug">
                                Si alguna de tu información es incorrecta o requiere actualización,
                                solicita un cambio a través del botón solicitar cambio.
                            </p>

                        </div>

                        <button id="openModalBtn" type="button"
                            class="px-4 py-2 rounded-lg border border-[#1e295d] bg-[#0b102b] hover:bg-[#151b3b] text-gray-200 text-xs font-medium flex items-center gap-2 whitespace-nowrap transition shrink-0">

                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>

                            </svg>

                            Solicitar cambio

                        </button>

                    </div>

                    <div class="bg-[#0f1535] rounded-2xl border border-[#1e295d] p-6 shadow-lg">

                        <div class="flex items-center gap-3 mb-1">

                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>

                            </svg>

                            <h2 class="text-lg font-bold text-white">
                                Seguridad de tu cuenta
                            </h2>

                        </div>

                        <p class="text-xs text-gray-400 mb-6">
                            Administra tu información relacionada con la seguridad de tu cuenta
                        </p>

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

                                    <p class="text-sm font-semibold text-white">
                                        Contraseña
                                    </p>

                                    <p class="text-xs text-gray-400 mt-0.5">
                                        Última actualización:
                                        {{ Auth::user()->password_updated_at
                                            ? Auth::user()->password_updated_at->locale('es')->translatedFormat('d M Y')
                                            : 'No registrada' }}
                                    </p>

                                </div>

                            </div>
                            <button type="button" @click="modalPassword = true"
                                class="flex items-center gap-1.5 px-4 py-2
                   rounded-xl text-xs font-semibold
                   bg-slate-900 text-slate-200
                   border border-slate-700
                   hover:bg-slate-800 transition shrink-0">

                                <i data-lucide="shield" class="w-4 h-4 text-blue-400">
                                </i>

                                Actualizar contraseña

                            </button>

                        </div>
                        <div
                            class="mt-3 bg-[#030712] border border-slate-800 rounded-xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2.5 rounded-lg bg-slate-900 border border-slate-800 text-slate-400">
                                    <i data-lucide="shield-check" class="w-5 h-5"></i>
                                </div>

                                <div>
                                    <h4 class="text-xs font-bold text-white">
                                        Verificación en dos pasos
                                    </h4>

                                    <p class="text-xs text-gray-400 mt-0.5">
                                        Agrega una capa adicional de seguridad a tu cuenta.
                                    </p>

                                    <div class="mt-1">
                                        @if (Auth::user()->mfa === 'Y')
                                            <span class="text-[11px] text-emerald-400">
                                                ● Activada
                                            </span>
                                        @else
                                            <span class="text-[11px] text-slate-500">
                                                ● Desactivada
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <button type="button" @click="abrirMFA()"
                                class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold transition shrink-0
        {{ Auth::user()->mfa === 'Y'
            ? 'bg-rose-500/10 text-rose-400 border border-rose-500/30 hover:bg-rose-500/20'
            : 'bg-blue-600/10 text-blue-400 border border-blue-500/30 hover:bg-blue-600/20' }}">

                                @if (Auth::user()->mfa === 'Y')
                                    <i data-lucide="shield-off" class="w-4 h-4"></i>
                                    Desactivar
                                @else
                                    <i data-lucide="shield-plus" class="w-4 h-4"></i>
                                    Activar
                                @endif

                            </button>
                        </div>

                    </div>


                </div>

                <div class="lg:col-span-4 space-y-6">

                    <div
                        class="bg-[#0f1535] rounded-2xl border border-[#1e295d] p-6 shadow-lg flex flex-col items-center text-center">

                        <div class="w-full text-left mb-4">

                            <div class="flex items-center gap-2">

                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0118.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                    </path>

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z">
                                    </path>

                                </svg>

                                <h2 class="text-base font-bold text-white">
                                    Foto de perfil
                                </h2>

                            </div>

                            <p class="text-xs text-gray-400 mt-0.5">
                                Actualización del perfil
                            </p>

                        </div>

                        <form method="POST" enctype="multipart/form-data" action="{{ route('actualizarfoto') }}">

                            @csrf
                            @method('PUT')

                            <div class="relative my-4">

                                <img src="{{ auth()->user()->picture
                                    ? asset('storage/' . auth()->user()->picture)
                                    : asset('storage/profile-photos/user.png') }}"
                                    alt="{{ auth()->user()->name }}"
                                    class="w-44 h-44 rounded-full object-cover border-4 border-[#1e295d] shadow-xl"
                                    id="profileImage">

                                <input type="file" name="picture" id="photoInput" accept=".jpg,.jpeg,.png"
                                    class="hidden">

                                <button type="button" id="cameraButton"
                                    class="absolute bottom-2 right-2 bg-white text-[#060818] p-2.5 rounded-full shadow-lg hover:bg-gray-200 transition">

                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0118.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                        </path>

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z">
                                        </path>

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

                        @if (auth()->user()->picture)
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
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>

                            </svg>

                            <h2 class="text-base font-bold text-white">
                                Información de la cuenta
                            </h2>

                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>

                                <p class="text-xs font-medium text-gray-400 mb-1.5">
                                    Estado de la cuenta
                                </p>

                                @if (Auth::user()->active === 'Y')
                                    <span
                                        class="inline-block px-3 py-1 rounded-md text-xs font-bold bg-[#06331e] border border-emerald-600 text-emerald-400">
                                        Activa
                                    </span>
                                @else
                                    <span
                                        class="inline-block px-3 py-1 rounded-md text-xs font-bold bg-red-950/40 border border-red-600 text-red-400">
                                        Inactiva
                                    </span>
                                @endif

                            </div>
                            <div>

                                <p class="text-xs font-medium text-gray-400">
                                    Rol en el sistema
                                </p>

                                <p class="text-sm font-bold text-white mt-1">
                                    {{ Auth::user()->role ?? 'Desconocido' }}
                                </p>

                            </div>

                        </div>

                        <div class="bg-[#0b102b] border border-[#1e295d] rounded-xl p-4 flex items-start gap-3">

                            <svg class="w-6 h-6 text-gray-300 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>

                            </svg>

                            <div>

                                <p class="text-xs font-bold text-white">
                                    Mantén tu información actualizada
                                </p>

                                <p class="text-[11px] text-gray-400 leading-relaxed mt-1">
                                    Una información correcta nos ayuda a darte un mejor soporte y atención.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </main>

    <div id="changeModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">

        <div id="modalBackdrop" class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity">
        </div>

        <div
            class="relative w-full max-w-lg mx-4 bg-[#0a0e27] border border-[#1e295d] rounded-2xl shadow-2xl overflow-hidden z-10">

            <div class="flex items-center justify-between px-6 py-4 border-b border-[#1e295d]/80 bg-[#0f1535]/50">

                <div class="flex items-center gap-2.5">

                    <div class="p-2 rounded-lg bg-blue-600/10 border border-blue-500/20 text-blue-400">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
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

                <button id="closeModalBtn" type="button"
                    class="text-gray-400 hover:text-white p-1 rounded-lg hover:bg-[#151b3b] transition">

                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12">
                        </path>

                    </svg>

                </button>

            </div>

            <form action="{{ route('solicitar.cambio.store') }}" method="POST" class="p-6 space-y-4">

                @csrf

                <div>

                    <label class="block text-xs font-semibold text-gray-300 mb-1.5">
                        Campo a modificar
                        <span class="text-rose-500">*</span>
                    </label>

                    <select name="campo" id="campoCambio" required
                        class="w-full bg-[#060818] border border-[#1e295d] text-gray-200 text-sm rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-blue-500 transition">

                        <option value="" disabled selected>
                            Selecciona el dato a actualizar
                        </option>

                        <option value="nombre" data-valor="{{ Auth::user()->name }}">
                            Nombre completo
                        </option>

                        <option value="correo" data-valor="{{ Auth::user()->email }}">
                            Correo electrónico
                        </option>

                        <option value="oficina"
                            data-valor="{{ Auth::user()->departamento?->oficina?->nombre ?? 'No proporcionada' }}">
                            Oficina / Sucursal
                        </option>

                        <option value="departamento"
                            data-valor="{{ Auth::user()->departamento?->nombre ?? 'No proporcionada' }}">
                            Departamento
                        </option>
                        <option value="telefono" data-valor="{{ Auth::user()->phone ?? 'No proporcionada' }}">
                            Telefono
                        </option>
                        <option value="usuario" data-valor="{{ Auth::user()->login ?? 'No proporcionada' }}">
                            Usuario
                        </option>
                        <option value="numeroempleado"
                            data-valor="{{ Auth::user()->numeroempleado ?? 'No proporcionada' }}">
                            Numero de empleado
                        </option>
                        <option value="role" data-valor="{{ Auth::user()->role ?? 'No proporcionada' }}">
                            Role
                        </option>

                    </select>

                </div>

                <div>

                    <label class="block text-xs font-semibold text-gray-300 mb-1.5">
                        Valor actual
                    </label>

                    <input type="text" id="valorActualVisible" readonly
                        placeholder="Selecciona primero el campo..."
                        class="w-full bg-[#060818] border border-[#1e295d] text-gray-400 text-sm rounded-xl px-3.5 py-2.5 focus:outline-none">

                    <input type="hidden" name="valor_actual" id="valorActual">

                </div>

                <div>

                    <label class="block text-xs font-semibold text-gray-300 mb-1.5">
                        Nuevo valor o dato correcto
                        <span class="text-rose-500">*</span>
                    </label>

                    <input type="text" name="nuevo_valor" required placeholder="Escribe aquí el dato correcto..."
                        class="w-full bg-[#060818] border border-[#1e295d] text-gray-200 text-sm rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-blue-500 transition placeholder:text-gray-600">

                </div>

                <div>

                    <label class="block text-xs font-semibold text-gray-300 mb-1.5">
                        Motivo o justificación del cambio
                        <span class="text-rose-500">*</span>
                    </label>

                    <textarea name="motivo" rows="3" required placeholder="Describe brevemente la razón de la corrección..."
                        class="w-full bg-[#060818] border border-[#1e295d] text-gray-200 text-sm rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-blue-500 transition placeholder:text-gray-600 resize-none"></textarea>

                </div>

                <div class="flex items-center justify-end gap-3 pt-2">

                    <button type="button" id="cancelModalBtn"
                        class="px-4 py-2.5 rounded-xl border border-[#1e295d] bg-[#0b102b] hover:bg-[#151b3b] text-gray-300 text-xs font-medium transition">
                        Cancelar
                    </button>

                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold shadow-[0_0_15px_rgba(37,99,235,0.4)] transition">
                        Enviar solicitud
                    </button>

                </div>

            </form>

        </div>


    </div>
    <div x-show="modalPassword" x-cloak x-transition.opacity
        class="fixed inset-0 z-[999] flex items-center justify-center p-4">

        <!-- Fondo oscuro -->
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" @click="modalPassword = false">
        </div>


        <!-- Contenido -->
        <div x-show="modalPassword" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95" @click.stop
            class="relative w-full max-w-md bg-[#0b1026] border border-slate-700 rounded-2xl shadow-2xl overflow-hidden">

            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-800">

                <div class="flex items-center gap-3">

                    <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center">
                        <i data-lucide="shield" class="w-5 h-5 text-blue-400"></i>
                    </div>

                    <div>
                        <h3 class="text-white font-semibold">
                            Actualizar contraseña
                        </h3>

                        <p class="text-xs text-slate-400">
                            Cambia tu contraseña de acceso
                        </p>
                    </div>

                </div>


                <!-- Cerrar -->
                <button type="button" @click="modalPassword = false"
                    class="text-slate-400 hover:text-white transition">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>

            </div>


            <!-- Body -->
            <div class="p-6">

                <form action="{{ route('perfil.password.update') }}" method="POST"
                    x-data="{ passwordNueva: '', passwordConfirmacion: '' }">
                    @csrf
                    @method('PUT')
                    <!-- Contraseña actual -->
                    <!-- Contraseña actual -->
                    <div class="mb-4">

                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Contraseña actual
                        </label>

                        <input type="password" name="password_actual" required
                            class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            placeholder="Ingresa tu contraseña actual">

                        @error('password_actual')
                            <p class="text-xs text-red-400 mt-2">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    <!-- Nueva contraseña -->
                    <div class="mb-4">

                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Nueva contraseña
                        </label>

                        <input type="password" name="password" required minlength="8"
                            x-model="passwordNueva"
                            pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,}"
                            title="Debe tener al menos 8 caracteres, una mayúscula, una minúscula y un número."
                            class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            placeholder="Ingresa tu nueva contraseña">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-1 mt-2 text-[10px]">
                            <p class="flex items-center gap-1.5"
                                :class="passwordNueva.length >= 8 ? 'text-emerald-400' : 'text-slate-500'">
                                <span x-text="passwordNueva.length >= 8 ? '✓' : '○'"></span>
                                8 caracteres mínimo
                            </p>
                            <p class="flex items-center gap-1.5"
                                :class="/[A-Z]/.test(passwordNueva) ? 'text-emerald-400' : 'text-slate-500'">
                                <span x-text="/[A-Z]/.test(passwordNueva) ? '✓' : '○'"></span>
                                Una mayúscula
                            </p>
                            <p class="flex items-center gap-1.5"
                                :class="/[a-z]/.test(passwordNueva) ? 'text-emerald-400' : 'text-slate-500'">
                                <span x-text="/[a-z]/.test(passwordNueva) ? '✓' : '○'"></span>
                                Una minúscula
                            </p>
                            <p class="flex items-center gap-1.5"
                                :class="/[0-9]/.test(passwordNueva) ? 'text-emerald-400' : 'text-slate-500'">
                                <span x-text="/[0-9]/.test(passwordNueva) ? '✓' : '○'"></span>
                                Un número
                            </p>
                        </div>

                        @error('password')
                            <p class="text-xs text-red-400 mt-2">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    <!-- Confirmar contraseña -->
                    <div class="mb-6">

                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Confirmar nueva contraseña
                        </label>

                        <input type="password" name="password_confirmation" required minlength="8"
                            x-model="passwordConfirmacion"
                            pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,}"
                            title="Debe tener al menos 8 caracteres, una mayúscula, una minúscula y un número."
                            class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            placeholder="Confirma tu nueva contraseña">

                        <p class="flex items-center gap-1.5 text-[10px] mt-2"
                            :class="passwordConfirmacion && passwordConfirmacion === passwordNueva ? 'text-emerald-400' : 'text-slate-500'">
                            <span
                                x-text="passwordConfirmacion && passwordConfirmacion === passwordNueva ? '✓' : '○'"></span>
                            Las contraseñas coinciden
                        </p>

                    </div>


                    <!-- Botones -->
                    <div class="flex items-center justify-end gap-3">

                        <button type="button" @click="modalPassword = false"
                            class="px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-300 bg-slate-800 hover:bg-slate-700 transition">
                            Cancelar
                        </button>

                        <button type="submit"
                            class="px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-blue-600 hover:bg-blue-500 transition">
                            Actualizar contraseña
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
    <div x-show="modalMFA" x-cloak x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm px-4">

        <div @click.outside="modalMFA = false" x-transition
            class="relative w-full max-w-md bg-[#0b1026] border border-blue-900/40 rounded-2xl shadow-[0_0_40px_rgba(37,99,235,0.20)] p-6">

            <button type="button" @click="cerrarMFA()"
                class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>

            <div class="text-center">
                <div
                    class="mx-auto flex items-center justify-center w-14 h-14 rounded-2xl bg-blue-600/10 border border-blue-500/20">
                    <i data-lucide="shield-check" class="w-7 h-7 text-blue-400"></i>
                </div>

                <h3 class="mt-4 text-lg font-bold text-white">
                    Verificación en dos pasos
                </h3>

                <p class="mt-1.5 text-sm text-slate-400 leading-relaxed">
                    Protege tu cuenta utilizando
                    <span class="text-white font-medium">
                        Google Authenticator
                    </span>.
                </p>
            </div>

            @if (Auth::user()->mfa !== 'Y')
                <div class="mt-6">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex items-center justify-center w-7 h-7 rounded-full bg-blue-600 text-white text-xs font-bold shrink-0">
                            1
                        </div>

                        <div>
                            <p class="text-sm font-semibold text-white">
                                Instala Google Authenticator
                            </p>

                            <p class="text-xs text-slate-500 mt-0.5">
                                Abre la aplicación en tu teléfono.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-5">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex items-center justify-center w-7 h-7 rounded-full bg-blue-600 text-white text-xs font-bold shrink-0">
                            2
                        </div>

                        <div>
                            <p class="text-sm font-semibold text-white">
                                Escanea el código QR
                            </p>

                            <p class="text-xs text-slate-500 mt-0.5">
                                Utiliza Google Authenticator para escanearlo.
                            </p>
                        </div>
                    </div>

                    <div
                        class="mt-4 flex items-center justify-center min-h-[220px] rounded-xl bg-white border border-slate-700 p-4">
                        <div id="mfaQr" class="w-[220px] h-[220px] flex items-center justify-center"></div>
                    </div>
                </div>

                <div class="mt-5">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex items-center justify-center w-7 h-7 rounded-full bg-blue-600 text-white text-xs font-bold shrink-0">
                            3
                        </div>

                        <div>
                            <p class="text-sm font-semibold text-white">
                                Introduce el código
                            </p>

                            <p class="text-xs text-slate-500 mt-0.5">
                                Escribe el código de 6 dígitos que aparece en Google Authenticator.
                            </p>
                        </div>
                    </div>

                    <form @submit.prevent="confirmarMFA()" class="mt-4">
                        @csrf

                        <label for="codigo_mfa" class="block text-xs font-semibold text-slate-300 mb-1.5">
                            Código de verificación
                        </label>

                        <input id="codigo_mfa" name="codigo" type="text" x-model="mfaCodigo"
                            inputmode="numeric" autocomplete="one-time-code" maxlength="6" pattern="[0-9]{6}"
                            placeholder="000000" required
                            class="w-full bg-[#030712] border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white text-center tracking-[0.4em] focus:outline-none focus:border-blue-500">

                        <p x-show="mfaMensaje" x-text="mfaMensaje" class="mt-2 text-xs text-red-400">
                        </p>

                        <div class="flex items-center gap-3 mt-5">
                            <button type="button" @click="cerrarMFA()"
                                class="flex-1 py-2.5 rounded-xl text-xs font-semibold text-slate-300 bg-slate-900 border border-slate-700 hover:bg-slate-800 transition">
                                Cancelar
                            </button>

                            <button type="submit" :disabled="cargandoMFA"
                                class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl text-xs font-semibold text-white bg-blue-600 hover:bg-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition">

                                <i x-show="!cargandoMFA" data-lucide="shield-check" class="w-4 h-4"></i>

                                <svg x-show="cargandoMFA" class="animate-spin w-4 h-4" viewBox="0 0 24 24"
                                    fill="none">
                                    <circle cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4" class="opacity-25">
                                    </circle>

                                    <path fill="currentColor" class="opacity-75"
                                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                                    </path>
                                </svg>

                                <span x-text="cargandoMFA ? 'Verificando...' : 'Verificar y activar'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="mt-6 rounded-xl border border-emerald-500/20 bg-emerald-500/5 p-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center">
                            <i data-lucide="shield-check" class="w-5 h-5 text-emerald-400"></i>
                        </div>

                        <div>
                            <p class="text-sm font-semibold text-white">
                                Verificación en dos pasos activada
                            </p>

                            <p class="text-xs text-slate-500 mt-0.5">
                                Tu cuenta está protegida con Google Authenticator.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-5">
                    <label for="codigo_desactivar_mfa" class="block text-xs font-semibold text-slate-300 mb-1.5">
                        Código de Google Authenticator
                    </label>

                    <input id="codigo_desactivar_mfa" type="text" x-model="mfaCodigo" inputmode="numeric"
                        autocomplete="one-time-code" maxlength="6" pattern="[0-9]{6}" placeholder="000000"
                        class="w-full bg-[#030712] border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white text-center tracking-[0.4em] focus:outline-none focus:border-red-500">

                    <p class="mt-2 text-[11px] text-slate-500">
                        Para desactivar la verificación en dos pasos debes confirmar tu identidad
                        utilizando el código actual.
                    </p>

                    <p x-show="mfaMensaje" x-text="mfaMensaje" class="mt-2 text-xs text-red-400">
                    </p>
                </div>

                <div class="flex items-center gap-3 mt-5">
                    <button type="button" @click="cerrarMFA()"
                        class="flex-1 py-2.5 rounded-xl text-xs font-semibold text-slate-300 bg-slate-900 border border-slate-700 hover:bg-slate-800 transition">
                        Cancelar
                    </button>

                    <button type="button" @click="desactivarMFA()" :disabled="cargandoMFA || mfaCodigo.length !== 6"
                        class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl text-xs font-semibold text-white bg-red-600 hover:bg-red-500 disabled:opacity-50 disabled:cursor-not-allowed transition">

                        <i x-show="!cargandoMFA" data-lucide="shield-off" class="w-4 h-4"></i>

                        <svg x-show="cargandoMFA" class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"
                                class="opacity-25">
                            </circle>

                            <path fill="currentColor" class="opacity-75" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                            </path>
                        </svg>

                        <span x-text="cargandoMFA ? 'Desactivando...' : 'Desactivar MFA'"></span>
                    </button>
                </div>

                <div class="mt-5 flex items-start gap-2 rounded-xl bg-red-500/5 border border-red-500/10 p-3">
                    <i data-lucide="triangle-alert" class="w-4 h-4 text-red-400 shrink-0 mt-0.5"></i>

                    <p class="text-[11px] text-slate-500 leading-relaxed">
                        Al desactivar esta opción, tu cuenta dejará de solicitar el código de Google
                        Authenticator al iniciar sesión.
                    </p>
                </div>
            @endif

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.4/build/qrcode.min.js"></script>
    <script>
        window.perfilSeguridadConfig = {
            loginOriginal: @js(auth()->user()->login),
            emailOriginal: @js(auth()->user()->email),

            mfaConfigurar: @js(route('mfa.configurar')),
            mfaActivar: @js(route('usuario.mfa.verificar.activacion')),
            mfaDesactivar: @js(route('mfa.desactivar')),
        };
    </script>
</body>

</html>
