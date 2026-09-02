<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" href="{{ asset('storage/images/logo.png') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>

    <title>{{ $ticket->folio }} | TicketPro</title>
</head>

<body x-data="{ menuMovilAbierto: false }" class="bg-[#060818] text-white font-sans antialiased flex h-screen overflow-hidden">
    <aside
        class="hidden md:flex flex-col w-64 bg-[#0a0e27] border-r border-[#1e295d] px-4 py-6 overflow-y-auto [::-webkit-scrollbar]:w-2 [::-webkit-scrollbar-track]:bg-[#060818] [::-webkit-scrollbar-thumb]:bg-[#1e295d] [::-webkit-scrollbar-thumb]:rounded-full">
        <div class="text-3xl font-bold mb-8 px-2 tracking-wide">Ticket<span class="text-blue-500">Pro</span></div>
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
    <div x-show="menuMovilAbierto" x-transition.opacity @click="menuMovilAbierto = false"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[9998] md:hidden" style="display:none;"></div>
    <aside x-show="menuMovilAbierto" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed inset-y-0 left-0 z-[9999] flex flex-col w-72 max-w-[85vw] bg-[#0a0e27] border-r border-[#1e295d] px-4 py-6 overflow-y-auto md:hidden"
        style="display:none;">
        <div class="flex items-center justify-between mb-8 px-2">
            <div class="text-3xl font-bold tracking-wide">Ticket<span class="text-blue-500">Pro</span></div>
            <button type="button" @click="menuMovilAbierto = false"
                class="flex items-center justify-center w-9 h-9 rounded-lg bg-slate-900 border border-slate-800 text-slate-400 hover:text-white hover:bg-slate-800 transition">
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
            <div>
                <h3 class="text-sm font-semibold text-white">{{ Auth::user()->name ?? 'Desconocido' }}</h3>
                <p class="text-xs text-gray-400">{{ optional(Auth::user()->departamento)->nombre ?? 'Desconocido' }}
                </p>
            </div>
        </div>
        <nav class="flex-1 space-y-2">
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
  <main class="flex-1 flex flex-col h-screen overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-track]:bg-[#060818] [&::-webkit-scrollbar-thumb]:bg-[#1e295d] [&::-webkit-scrollbar-thumb]:rounded-full">
        @if (session('success'))
            <div id="successMessage"
                class="fixed left-4 right-4 top-5 z-[9999] w-auto rounded-2xl border border-green-500/30 bg-[#0f1535] p-4 shadow-[0_0_30px_rgba(34,197,94,0.20)] sm:left-auto sm:right-5 sm:w-full sm:max-w-sm">
                <div class="flex items-start gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-green-500/15 text-green-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-bold text-white">¡Éxito!</p>
                        <p class="mt-1 break-words text-sm text-slate-400">{{ session('success') }}</p>
                    </div>
                    <button type="button" onclick="document.getElementById('successMessage')?.remove()" class="shrink-0 text-slate-500 hover:text-white">
                        ✕
                    </button>
                </div>
            </div>
        @endif
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center px-6 py-6 md:py-8 gap-4">
                <div class="flex items-center gap-3 w-full md:w-auto">
                <button type="button" @click="menuMovilAbierto = !menuMovilAbierto"
                    class="md:hidden flex items-center justify-center w-10 h-10 rounded-xl bg-slate-900/80 border border-slate-800 text-slate-400 hover:text-white hover:bg-slate-800 transition-all duration-200 focus:outline-none">
                    <svg x-show="!menuMovilAbierto" x-transition class="w-5 h-5" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg x-show="menuMovilAbierto" x-transition class="w-5 h-5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" style="display:none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-white">Detalles de ticket</h1>
                    <p class="text-xs sm:text-sm text-gray-400 mt-1">Consulta la informaciòn y los detalles del ticket.
                    </p>
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
        <div class="px-6 pb-8">

<div class="overflow-hidden rounded-2xl border border-[#1e295d] bg-[#0f1535] shadow-xl mb-6">
                <div class="p-6">

                    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                        <div class="flex items-start gap-4">

                            <div
                                class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-blue-600/10 ring-1 ring-blue-500/20">

                                <svg class="h-7 w-7 text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                                    </path>

                                </svg>

                            </div>

                            <div>

                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Ticket
                                </p>

                                <h2 class="mt-1 text-2xl font-extrabold text-white sm:text-3xl">
                                    {{ $ticket->folio }}
                                </h2>

                                <p class="mt-1 text-sm text-gray-400">
                                    {{ $ticket->titulo ?? 'Sin título' }}
                                </p>

                            </div>

                        </div>

                        @php
                            $estadoClases = [
                                'pendiente' => 'border-yellow-500/30 bg-yellow-500/10 text-yellow-400',
                                'en proceso' => 'border-blue-500/30 bg-blue-500/10 text-blue-400',
                                'solucionado' => 'border-green-500/30 bg-green-500/10 text-green-400',
                                'cancelado' => 'border-red-500/30 bg-red-500/10 text-red-400',
                            ];

                            $estadoClase =
                                $estadoClases[strtolower($ticket->estado ?? '')] ??
                                'border-gray-500/30 bg-gray-500/10 text-gray-400';
                        @endphp

                        <div
                            class="inline-flex w-fit items-center gap-2 rounded-xl border px-4 py-2 text-sm font-bold {{ $estadoClase }}">

                            <span class="h-2 w-2 rounded-full bg-current"></span>

                            {{ ucfirst($ticket->estado ?? 'Sin estado') }}

                        </div>

                    </div>

                </div>

            </div>

            <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

                <div class="space-y-6 xl:col-span-2">

                    <div class="rounded-2xl border border-[#1e295d] bg-[#0f1535] p-6 shadow-xl">

                        <div class="mb-6 flex items-center gap-3">

                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-500/10">

                                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z">
                                    </path>

                                </svg>

                            </div>

                            <div>

                                <h3 class="text-lg font-bold text-white">
                                    Información del ticket
                                </h3>

                                <p class="text-xs text-gray-500">
                                    Información general del reporte
                                </p>

                            </div>

                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                            <div class="rounded-xl border border-[#1e295d] bg-[#0b1026] p-4">
                                <p class="text-xs font-medium text-gray-500">
                                    Tipo de falla
                                </p>
                                <p class="mt-2 font-semibold text-white">
                                    {{ $ticket->tipo_falla ?? 'No especificado' }}
                                </p>
                            </div>

                            <div class="rounded-xl border border-[#1e295d] bg-[#0b1026] p-4">
                                <p class="text-xs font-medium text-gray-500">
                                    Prioridad
                                </p>
                                <p class="mt-2 font-semibold text-white">
                                    {{ ucfirst($ticket->prioridad ?? 'No especificada') }}
                                </p>
                            </div>

                            <div class="rounded-xl border border-[#1e295d] bg-[#0b1026] p-4">
                                <p class="text-xs font-medium text-gray-500">
                                    Fecha de reporte
                                </p>
                                <p class="mt-2 font-semibold text-white">
                                    {{ $ticket->created_at?->timezone('America/Matamoros')->format('d M Y, H:i') ?? 'Sin fecha' }}
                                </p>
                            </div>

                            <div class="rounded-xl border border-[#1e295d] bg-[#0b1026] p-4">
                                <p class="text-xs font-medium text-gray-500">
                                    ¿Es recurrente?
                                </p>
                                <p class="mt-2 font-semibold text-white">
                                    {{ $ticket->es_recurrente ? 'Sí' : 'No' }}
                                </p>
                            </div>

                            <div class="rounded-xl border border-[#1e295d] bg-[#0b1026] p-4">
                                <p class="text-xs font-medium text-gray-500">
                                    Departamento
                                </p>
                                <p class="mt-2 font-semibold text-white">
                                    {{ $ticket->user?->departamento?->nombre ?? 'Sin departamento' }}
                                </p>
                            </div>

                            <div class="rounded-xl border border-[#1e295d] bg-[#0b1026] p-4">
                                <p class="text-xs font-medium text-gray-500">
                                    Sucursal / Oficina
                                </p>
                                <p class="mt-2 font-semibold text-white">
                                    {{ $ticket->user?->departamento?->oficina?->nombre ?? 'Sin oficina' }}
                                </p>
                            </div>

                            <div class="rounded-xl border border-[#1e295d] bg-[#0b1026] p-4">
                                <p class="text-xs font-medium text-gray-500">
                                    Tomado por
                                </p>
                                <p class="mt-2 font-semibold text-white">
                                    {{ $ticket->tomadoPor?->name ?? 'Sin asignar' }}
                                </p>
                            </div>

                            <div class="rounded-xl border border-[#1e295d] bg-[#0b1026] p-4">
                                <p class="text-xs font-medium text-gray-500">
                                    Fecha de asignación
                                </p>
                                <p class="mt-2 font-semibold text-white">
                                    @if ($ticket->fecha_tomado)
                                        {{ $ticket->fecha_tomado->timezone('America/Matamoros')->format('d M Y, H:i') }}
                                    @else
                                        Sin asignar
                                    @endif
                                </p>
                            </div>

                        </div>

                        <div class="mt-6 border-t border-[#1e295d] pt-6">

                            <div class="mb-3 flex items-center gap-2">

                                <svg class="h-4 w-4 text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M8 10h8M8 14h5m5-9H6a2 2 0 00-2 2v14l4-3h10a2 2 0 002-2V7a2 2 0 00-2-2z">
                                    </path>

                                </svg>

                                <p class="text-sm font-bold text-white">
                                    Descripción
                                </p>

                            </div>

                            <div class="rounded-xl border border-[#1e295d] bg-[#0b1026] p-5">

                                <p class="whitespace-pre-line text-sm leading-7 text-gray-300">
                                    {{ $ticket->descripcion ?? 'Sin descripción.' }}
                                </p>

                            </div>

                        </div>

                        @if (!empty($ticket->informacion_adicional))
                            <div class="mt-5">

                                <div class="mb-3 flex items-center gap-2">

                                    <svg class="h-4 w-4 text-purple-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M13 16h-1v-4h-1m1-4h.01M12 21a9 9 0 100-18 9 9 0 000 18z">
                                        </path>

                                    </svg>

                                    <p class="text-sm font-bold text-white">
                                        Información adicional
                                    </p>

                                </div>

                                <div class="rounded-xl border border-[#1e295d] bg-[#0b1026] p-5">

                                    <p class="whitespace-pre-line text-sm leading-7 text-gray-300">
                                        {{ $ticket->informacion_adicional }}
                                    </p>

                                </div>

                            </div>
                        @endif

                        @if (!empty($ticket->comentarios))
                            <div class="mt-5">

                                <div class="mb-3 flex items-center gap-2">

                                    <svg class="h-4 w-4 text-cyan-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M8 10h8M8 14h5m5-9H6a2 2 0 00-2 2v14l4-3h10a2 2 0 002-2V7a2 2 0 00-2-2z">
                                        </path>

                                    </svg>

                                    <p class="text-sm font-bold text-white">
                                        Comentarios
                                    </p>

                                </div>

                                <div class="rounded-xl border border-[#1e295d] bg-[#0b1026] p-5">

                                    <p class="whitespace-pre-line text-sm leading-7 text-gray-300">
                                        {{ $ticket->comentarios }}
                                    </p>

                                </div>

                            </div>
                        @endif

                    </div>

                </div>

                <div class="space-y-6">

                    <div class="rounded-2xl border border-[#1e295d] bg-[#0f1535] p-6 shadow-xl">

                        <div class="mb-5 flex items-center gap-3">

                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-500/10">

                                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                    </path>

                                </svg>

                            </div>

                            <div>

                                <h3 class="font-bold text-white">
                                    Solicitante
                                </h3>

                                <p class="text-xs text-gray-500">
                                    Usuario que reportó el ticket
                                </p>

                            </div>

                        </div>

                        <div class="flex items-center gap-4">

                            <div
                                class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-full bg-blue-600 ring-2 ring-blue-500/20">

                                @if ($ticket->user?->picture)
                                    <img src="{{ asset('storage/' . ltrim($ticket->user->picture, '/')) }}"
                                        alt="{{ $ticket->user->name ?? 'Usuario' }}"
                                        class="h-full w-full object-cover"
                                        onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($ticket->user->name ?? 'Usuario') }}&background=0D8ABC&color=fff';">
                                @else
                                    <img src="{{ asset('storage/profile-photos/user.png') }}"
                                        alt="{{ $ticket->user->name ?? 'Usuario' }}"
                                        class="h-full w-full object-cover"
                                        onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($ticket->user->name ?? 'Usuario') }}&background=0D8ABC&color=fff';">
                                @endif

                            </div>

                            <div class="min-w-0">

                                <p class="truncate font-bold text-white">
                                    {{ $ticket->user?->name ?? 'Usuario' }}
                                </p>

                                <p class="truncate text-xs text-gray-500">
                                    {{ $ticket->user?->email ?? 'Sin correo' }}
                                </p>

                            </div>

                        </div>

                    </div>

                    <div class="rounded-2xl border border-[#1e295d] bg-[#0f1535] p-6 shadow-xl">

                        <div class="mb-6 flex items-center gap-3">

                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-500/10">

                                <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>

                                </svg>

                            </div>

                            <div>

                                <h3 class="font-bold text-white">
                                    Solución
                                </h3>

                                <p class="text-xs text-gray-500">
                                    Información registrada por Tecnologías
                                </p>

                            </div>

                        </div>

                        @if ($ticket->solucion)

                            <div
                                class="mb-5 flex items-center gap-2 rounded-xl border border-green-500/20 bg-green-500/5 px-4 py-3">

                                <span class="h-2.5 w-2.5 rounded-full bg-green-400"></span>

                                <span class="text-xs font-bold uppercase tracking-wider text-green-400">
                                    Ticket solucionado
                                </span>

                            </div>

                            <div class="space-y-4">

                                <div class="rounded-xl border border-[#1e295d] bg-[#0b1026] p-5">

                                    <div class="mb-3 flex items-center gap-2">

                                        <svg class="h-4 w-4 text-orange-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z">
                                            </path>

                                        </svg>

                                        <p class="text-sm font-bold text-white">
                                            ¿El problema fue solucionado?
                                        </p>

                                    </div>

                                    @if ($ticket->solucion->problema_solucionado)
                                        <div class="flex items-center gap-2">

                                            <span class="h-2.5 w-2.5 rounded-full bg-green-400"></span>

                                            <span class="text-sm font-semibold text-green-400">
                                                Sí, el problema fue solucionado.
                                            </span>

                                        </div>
                                    @else
                                        <div class="flex items-center gap-2">

                                            <span class="h-2.5 w-2.5 rounded-full bg-red-400"></span>

                                            <span class="text-sm font-semibold text-red-400">
                                                No, el problema no fue solucionado.
                                            </span>

                                        </div>
                                    @endif

                                </div>

                                @if (!empty($ticket->solucion->solucion))
                                    <div class="rounded-xl border border-green-500/20 bg-green-500/5 p-5">

                                        <div class="mb-3 flex items-center gap-2">

                                            <svg class="h-4 w-4 text-green-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">

                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.8"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                                </path>

                                            </svg>

                                            <p class="text-sm font-bold text-white">
                                                Solución aplicada
                                            </p>

                                        </div>

                                        <p class="whitespace-pre-line text-sm leading-7 text-gray-300">
                                            {{ $ticket->solucion->solucion }}
                                        </p>

                                    </div>
                                @endif

                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                                    <div class="rounded-xl border border-[#1e295d] bg-[#0b1026] p-4">

                                        <p class="text-xs font-medium text-gray-500">
                                            Solucionado por
                                        </p>

                                        <p class="mt-2 font-semibold text-white">
                                            {{ $ticket->solucion->solucionadoPor?->name ?? ($ticket->solucion->solucionado_por ?? 'Tecnologías') }}
                                        </p>

                                    </div>

                                    <div class="rounded-xl border border-[#1e295d] bg-[#0b1026] p-4">

                                        <p class="text-xs font-medium text-gray-500">
                                            Fecha de solución
                                        </p>

                                        <p class="mt-2 font-semibold text-white">

                                            @if ($ticket->solucion->fecha_solucion)
                                                {{ \Carbon\Carbon::parse($ticket->solucion->fecha_solucion)->timezone('America/Matamoros')->format('d M Y, H:i') }}
                                            @else
                                                Sin fecha
                                            @endif

                                        </p>

                                    </div>

                                    <div class="rounded-xl border border-[#1e295d] bg-[#0b1026] p-4">

                                        <p class="text-xs font-medium text-gray-500">
                                            Firmado por
                                        </p>

                                        <p class="mt-2 font-semibold text-white">
                                            {{ $ticket->solucion->nombre_firmante ?? 'Sin información' }}
                                        </p>

                                    </div>

                                    <div class="rounded-xl border border-[#1e295d] bg-[#0b1026] p-4">

                                        <p class="text-xs font-medium text-gray-500">
                                            Fecha de firma
                                        </p>

                                        <p class="mt-2 font-semibold text-white">

                                            @if ($ticket->solucion->fecha_firma)
                                                {{ \Carbon\Carbon::parse($ticket->solucion->fecha_firma)->timezone('America/Matamoros')->format('d M Y, H:i') }}
                                            @else
                                                Sin fecha
                                            @endif

                                        </p>

                                    </div>

                                </div>

                                @if (!empty($ticket->solucion->firma))
                                    <div class="rounded-xl border border-[#1e295d] bg-[#0b1026] p-5">

                                        <div class="mb-4 flex items-center gap-2">

                                            <svg class="h-4 w-4 text-purple-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">

                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.8"
                                                    d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM14.06 4.94l3.75 3.75">
                                                </path>

                                            </svg>

                                            <p class="text-sm font-bold text-white">
                                                Firma
                                            </p>

                                        </div>

                                        <div
                                            class="flex min-h-40 items-center justify-center rounded-xl border border-[#1e295d] bg-white p-4">

                                            <img src="{{ Storage::url($ticket->solucion->firma) }}" alt="Firma"
                                                class="max-h-40 max-w-full object-contain">

                                        </div>

                                    </div>
                                @endif

                            </div>
                        @else
                            <div class="rounded-xl border border-yellow-500/20 bg-yellow-500/5 p-5">

                                <div class="flex items-start gap-3">

                                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-yellow-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>

                                    </svg>

                                    <div>

                                        <p class="text-sm font-semibold text-yellow-300">
                                            Este ticket todavía no tiene una solución registrada.
                                        </p>

                                        <p class="mt-1 text-xs text-yellow-400/70">
                                            La información de la solución aparecerá aquí cuando Tecnologías finalice el
                                            ticket.
                                        </p>

                                    </div>

                                </div>

                            </div>

                        @endif

                    </div>

                    <div class="rounded-2xl border border-[#1e295d] bg-[#0f1535] p-6 shadow-xl">

                        <p class="mb-4 text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Acciones
                        </p>

                        <a href="{{ route('misticketusuario') }}"
                            class="flex w-full items-center justify-center gap-2 rounded-xl border border-[#1e295d] bg-[#0b1026] px-4 py-3 text-sm font-bold text-gray-300 transition hover:border-blue-500/40 hover:bg-[#151b3b] hover:text-white">

                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M4 6h16M4 12h16M4 18h16">
                                </path>

                            </svg>

                            Ver mis tickets

                        </a>

                    </div>

                </div>

            </div>

        </div>

        </div>

    </main>

    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) {
                lucide.createIcons();
            }
        });
    </script>

</body>

</html>
