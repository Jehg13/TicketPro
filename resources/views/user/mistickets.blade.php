<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis tickets - TicketPro</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/images/logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/filtrostecnologias.js', 'resources/js/modalticketusuario.js', 'resources/js/mensajes.js', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        window.usuarioActualId = @json(Auth::id());
        window.usuarioActualLogin = @json(Auth::user()->login);
    </script>
</head>

<body x-data="ticketModal()" class="bg-[#060818] text-white font-sans antialiased flex h-screen overflow-hidden">
    <div x-data="{ menuMovilAbierto: false }" class="contents">
        <div x-show="menuMovilAbierto" x-transition:enter="transition-opacity ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="menuMovilAbierto = false"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[9998] md:hidden" style="display:none;"></div>
        <aside :class="menuMovilAbierto ? 'translate-x-0' : '-translate-x-full'"
            class="fixed md:static inset-y-0 left-0 z-[9999] flex flex-col w-64 shrink-0 bg-[#0a0e27] border-r border-[#1e295d] px-4 py-6 overflow-y-auto transform transition-transform duration-300 ease-in-out md:translate-x-0 md:flex [::-webkit-scrollbar]:w-2 [::-webkit-scrollbar-track]:bg-[#060818] [::-webkit-scrollbar-thumb]:bg-[#1e295d] [::-webkit-scrollbar-thumb]:rounded-full">
            <div class="flex items-center justify-between mb-8 px-2">
                <div class="text-3xl font-bold tracking-wide">Ticket<span class="text-blue-500">Pro</span></div>
                <button type="button" @click="menuMovilAbierto = false"
                    class="md:hidden flex items-center justify-center w-9 h-9 rounded-lg text-slate-400 hover:text-white hover:bg-[#151b3b] transition"><i
                        data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <div class="flex items-center gap-3 mb-10 px-2">
                <img src="{{ auth()->user()->picture ? asset('storage/' . auth()->user()->picture) : asset('storage/profile-photos/user.png') }}"
                    alt="{{ auth()->user()->name }}"
                    class="w-12 h-12 rounded-full border-2 border-gray-500 object-cover">
                <div>
                    <h3 class="text-sm font-semibold text-white">{{ Auth::User()->name ?? 'Desconocido' }}</h3>
                    <p class="text-xs text-gray-400">{{ Auth::User()->departamento->nombre ?? 'Desconocido' }}</p>
                </div>
            </div>
            <nav class="flex-1 space-y-2">
                <a href="{{ route('dashboard') }}" @click="menuMovilAbierto = false"
                    class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-[#151b3b] hover:text-white rounded-lg transition">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="text-sm font-medium">Inicio</span>
                </a>
                <a href="{{ route('misticketusuario') }}" @click="menuMovilAbierto = false"
                    class="flex items-center gap-3 px-4 py-3 bg-blue-600 text-white rounded-lg font-medium shadow-[0_0_15px_rgba(37,99,235,0.4)]">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                    <span class="text-sm font-medium">Mis tickets</span>
                </a>
                <a href="{{ route('ticketusuario') }}" @click="menuMovilAbierto = false"
                    class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-[#151b3b] hover:text-white rounded-lg transition">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                    </svg>
                    <span class="text-sm font-medium">Crear ticket</span>
                </a>
                <a href="{{ route('avisosusuario') }}" @click="menuMovilAbierto = false"
                    class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-[#151b3b] hover:text-white rounded-lg transition">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span class="text-sm font-medium">Avisos</span>
                </a>
                <a href="{{ route('perfilusuario') }}" @click="menuMovilAbierto = false"
                    class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-[#151b3b] hover:text-white rounded-lg transition">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
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
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="text-sm font-medium">Cerrar sesión</span>
                    </button>
                </form>
            </div>
        </aside>
        <main
            class="flex-1 min-w-0 flex flex-col h-screen overflow-y-auto [::-webkit-scrollbar]:w-2 [::-webkit-scrollbar-track]:bg-[#060818] [::-webkit-scrollbar-thumb]:bg-[#1e295d] [::-webkit-scrollbar-thumb]:rounded-full">
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
            <header
                class="flex flex-col md:flex-row justify-between items-start md:items-center px-4 sm:px-6 py-4 md:py-8 gap-4">
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <button type="button" @click="menuMovilAbierto = !menuMovilAbierto"
                        class="md:hidden flex items-center justify-center w-10 h-10 shrink-0 rounded-xl bg-slate-900/80 border border-slate-800 text-slate-400 hover:text-white hover:bg-slate-800 transition-all duration-200 focus:outline-none">
                        <i x-show="!menuMovilAbierto" data-lucide="menu" class="w-5 h-5"></i>
                        <i x-show="menuMovilAbierto" data-lucide="x" class="w-5 h-5"></i>
                    </button>
                    <div>
                        <h1 class="text-2xl font-bold mb-1 tracking-tight">Mis tickets</h1>
                        <p class="text-sm text-gray-400"><span class="text-gray-200 font-medium">Mis tickets</span> /
                            Dashboard</p>
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

                <section class="bg-[#0f1535] rounded-xl border border-[#1e295d] p-6 shadow-lg">

                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">

                        <div>
                            <h2 class="text-xl font-bold tracking-wide text-white">
                                Mis tickets
                            </h2>

                            <p class="text-sm text-gray-400 mt-1">
                                Consulta y da seguimiento a todos tus tickets registrados
                            </p>
                        </div>

                        <form action="{{ route('misticketusuario') }}" method="GET"
                            class="relative w-full md:w-72">
                            <input type="text" name="buscar" value="{{ request('buscar') }}"
                                placeholder="Buscar folio, título o fecha..."
                                class="w-full bg-[#060818] border border-[#1e295d] rounded-lg py-2 pl-9 pr-10 text-sm text-gray-200 placeholder-gray-500 focus:outline-none focus:border-blue-500 transition">

                            <button type="submit"
                                class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 hover:text-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>

                            @if (request('buscar'))
                                <a href="{{ route('misticketusuario') }}"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-400"
                                    title="Limpiar búsqueda">
                                    ✕
                                </a>
                            @endif

                        </form>

                    </div>

                    <div
                        class="overflow-x-auto [::-webkit-scrollbar]:h-2 [::-webkit-scrollbar-track]:bg-[#060818] [::-webkit-scrollbar-thumb]:bg-[#1e295d] [::-webkit-scrollbar-thumb]:rounded-full">
                        <div
                            class="overflow-hidden rounded-2xl border border-[#1e295d]/80 bg-[#0b1024]/40 shadow-2xl shadow-black/20">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse min-w-[760px]">
                                    <thead>
                                        <tr class="border-b border-[#1e295d]/80 bg-[#0d1430]/80">
                                            <th
                                                class="py-5 px-6 w-[17%] text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">
                                                Folio
                                            </th>
                                            <th
                                                class="py-5 px-6 w-[38%] text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">
                                                Título del ticket
                                            </th>
                                            <th
                                                class="py-5 px-6 w-[17%] text-center text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">
                                                Estado
                                            </th>
                                            <th
                                                class="py-5 px-6 w-[17%] text-center text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">
                                                Fecha de creación
                                            </th>
                                            <th
                                                class="py-5 px-6 w-[11%] text-center text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">
                                                Acciones
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-[#1e295d]/50">
                                        @forelse ($tickets as $ticket)
                                            @php
                                                $ticket->load([
                                                    'user',
                                                    'user.departamento',
                                                    'user.departamento.oficina',
                                                    'user.departamento.oficina.empresa',
                                                    'historialComentarios.usuario',
                                                    'tomadoPor',
                                                    'solucion',
                                                ]);

                                                $ticketData = $ticket->toArray();

                                                $comentariosData = $ticket->historialComentarios
                                                    ->map(function ($comentario) {
                                                        $usuarioComentario = $comentario->usuario;

                                                        return [
                                                            'id' => $comentario->id,
                                                            'mensaje' => $comentario->mensaje,
                                                            'archivo' => $comentario->archivo,
                                                            'url_archivo' => $comentario->archivo
                                                                ? Storage::url($comentario->archivo)
                                                                : null,
                                                            'nombre_archivo' => $comentario->archivo
                                                                ? basename($comentario->archivo)
                                                                : null,
                                                            'extension' => $comentario->archivo
                                                                ? strtoupper(
                                                                    pathinfo($comentario->archivo, PATHINFO_EXTENSION),
                                                                )
                                                                : null,
                                                            'usuario' => [
                                                                'id' => $usuarioComentario?->id,
                                                                'login' => $usuarioComentario?->login,
                                                                'name' => $usuarioComentario?->name ?? 'Usuario',
                                                                'role' => $usuarioComentario?->role ?? 'Usuario',
                                                                'picture' => $usuarioComentario?->picture
                                                                    ? Storage::url($usuarioComentario->picture)
                                                                    : null,
                                                            ],
                                                            'fecha' => $comentario->created_at
                                                                ? $comentario->created_at->format('d M Y h:i A')
                                                                : '',
                                                        ];
                                                    })
                                                    ->values();
                                            @endphp

                                            <tr x-data="{
                                                ticket: {{ Js::from([
                                                    'id' => $ticket->id,
                                                    'folio' => $ticket->folio,
                                                    'titulo' => $ticket->titulo,
                                                    'tipo_falla' => $ticket->tipo_falla,
                                                    'equipo' => $ticket->equipo,
                                                    'prioridad' => $ticket->prioridad,
                                                    'descripcion' => $ticket->descripcion,
                                                    'estado' => strtolower($ticket->estado ?? ''),
                                                    'tomado_por' => $ticket->tomadoPor
                                                        ? [
                                                            'id' => $ticket->tomadoPor->id,
                                                            'name' => $ticket->tomadoPor->name,
                                                            'login' => $ticket->tomadoPor->login,
                                                            'picture' => $ticket->tomadoPor->picture ? Storage::url($ticket->tomadoPor->picture) : null,
                                                        ]
                                                        : null,
                                                    'user' => $ticket->user
                                                        ? [
                                                            'id' => $ticket->user->id,
                                                            'name' => $ticket->user->name,
                                                            'login' => $ticket->user->login,
                                                            'picture' => $ticket->user->picture ? Storage::url($ticket->user->picture) : null,
                                                        ]
                                                        : null,
                                                    'comentarios' => $comentariosData,
                                                    'solucion' => $ticket->solucion
                                                        ? [
                                                            'id' => $ticket->solucion->id,
                                                            'ticket_id' => $ticket->solucion->ticket_id,
                                                            'solucionado_por' => $ticket->solucion->solucionado_por,
                                                            'solucion' => $ticket->solucion->solucion,
                                                            'problema_solucionado' => (bool) $ticket->solucion->problema_solucionado,
                                                            'firma' => $ticket->solucion->firma,
                                                            'url_firma' => $ticket->solucion->firma ? Storage::url($ticket->solucion->firma) : null,
                                                            'nombre_firmante' => $ticket->solucion->nombre_firmante,
                                                            'fecha_solucion' => $ticket->solucion->fecha_solucion,
                                                            'fecha_firma' => $ticket->solucion->fecha_firma,
                                                            'evidencia' => $ticket->solucion->evidencia,
                                                        ]
                                                        : null,
                                                ]) }}
                                            }" x-show="mostrarTicket(ticket.estado, ticket)"
                                                x-transition
                                                class="group relative transition-all duration-200 hover:bg-[#111a3a]/60">
                                                <td class="relative py-6 px-6">
                                                    <div class="flex items-center gap-3">
                                                        <div
                                                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-500/10 border border-blue-500/20 text-blue-400 transition group-hover:bg-blue-500/15 group-hover:border-blue-400/30">
                                                            <i data-lucide="ticket" class="w-4 h-4"></i>
                                                        </div>

                                                        <span
                                                            class="font-bold text-slate-200 tracking-wide whitespace-nowrap">
                                                            TKT-{{ $ticket->created_at->format('Y') }}-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}
                                                        </span>
                                                    </div>
                                                </td>

                                                <td class="py-6 px-6">
                                                    <div class="max-w-xl">
                                                        <h4
                                                            class="font-semibold text-[15px] text-white truncate transition group-hover:text-blue-300">
                                                            {{ $ticket->titulo }}
                                                        </h4>

                                                        <p
                                                            class="mt-1.5 text-xs leading-relaxed text-slate-500 line-clamp-2">
                                                            {{ Str::limit($ticket->descripcion, 100) }}
                                                        </p>
                                                    </div>
                                                </td>

                                                <td class="py-6 px-6 text-center whitespace-nowrap">
                                                    @if ($ticket->estado === 'solucionado')
                                                        <span
                                                            class="inline-flex items-center gap-2 rounded-full border border-emerald-500/20 bg-emerald-500/10 px-3.5 py-1.5 text-[11px] font-bold text-emerald-300 shadow-sm shadow-emerald-950/20">
                                                            <span
                                                                class="h-1.5 w-1.5 rounded-full bg-emerald-400 shadow-[0_0_6px_rgba(52,211,153,0.7)]"></span>
                                                            Solucionado
                                                        </span>
                                                    @elseif ($ticket->estado === 'pendiente')
                                                        <span
                                                            class="inline-flex items-center gap-2 rounded-full border border-yellow-500/20 bg-yellow-500/10 px-3.5 py-1.5 text-[11px] font-bold text-yellow-300 shadow-sm shadow-yellow-950/20">
                                                            <span
                                                                class="h-1.5 w-1.5 rounded-full bg-yellow-400 shadow-[0_0_6px_rgba(250,204,21,0.7)]"></span>
                                                            Pendiente
                                                        </span>
                                                    @elseif ($ticket->estado === 'en proceso')
                                                        <span
                                                            class="inline-flex items-center gap-2 rounded-full border border-blue-500/20 bg-blue-500/10 px-3.5 py-1.5 text-[11px] font-bold text-blue-300 shadow-sm shadow-blue-950/20">
                                                            <span
                                                                class="h-1.5 w-1.5 rounded-full bg-blue-400 shadow-[0_0_6px_rgba(96,165,250,0.7)]"></span>
                                                            En proceso
                                                        </span>
                                                    @elseif ($ticket->estado === 'cancelado')
                                                        <span
                                                            class="inline-flex items-center gap-2 rounded-full border border-red-500/20 bg-red-500/10 px-3.5 py-1.5 text-[11px] font-bold text-red-300 shadow-sm shadow-red-950/20">
                                                            <span
                                                                class="h-1.5 w-1.5 rounded-full bg-red-400 shadow-[0_0_6px_rgba(248,113,113,0.7)]"></span>
                                                            Cancelado
                                                        </span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center gap-2 rounded-full border border-yellow-500/20 bg-yellow-500/10 px-3.5 py-1.5 text-[11px] font-bold text-yellow-300">
                                                            <span
                                                                class="h-1.5 w-1.5 rounded-full bg-yellow-400"></span>
                                                            {{ $ticket->estado ?? 'Abierto' }}
                                                        </span>
                                                    @endif
                                                </td>

                                                <td class="py-6 px-6 text-center whitespace-nowrap">
                                                    <div class="inline-flex flex-col items-center">
                                                        <span class="text-sm font-semibold text-slate-200">
                                                            {{ $ticket->created_at->format('d M Y') }}
                                                        </span>

                                                        <span class="mt-1 text-[11px] text-slate-500">
                                                            {{ $ticket->created_at->format('h:i A') }}
                                                        </span>
                                                    </div>
                                                </td>

                                                <td class="py-6 px-6">
                                                    <div class="flex items-center justify-center gap-1.5">
                                                        <button type="button"
                                                            @click="abrirTicket({{ Js::from($ticketData) }})"
                                                            class="group/action flex h-9 w-9 items-center justify-center rounded-xl border border-transparent text-slate-400 transition-all duration-200 hover:border-blue-500/20 hover:bg-blue-500/10 hover:text-blue-400"
                                                            title="Ver ticket">
                                                            <i data-lucide="eye"
                                                                class="w-4 h-4 transition-transform duration-200 group-hover/action:scale-110"></i>
                                                        </button>

                                                        <button type="button"
                                                            @click="abrirModalSolucion(
                                                                {{ Js::from($ticketData) }},
                                                                {{ in_array($ticket->estado, ['solucionado', 'cancelado']) ? 'true' : 'false' }}
                                                            )"
                                                            @if (!in_array($ticket->estado, ['solucionado', 'cancelado'])) disabled @endif
                                                            class="group/action flex h-9 w-9 items-center justify-center rounded-xl border border-transparent transition-all duration-200
                                                        {{ !in_array($ticket->estado, ['solucionado', 'cancelado'])
                                                            ? 'cursor-not-allowed text-slate-700'
                                                            : 'text-emerald-400 hover:border-emerald-500/20 hover:bg-emerald-500/10 hover:text-emerald-300' }}"
                                                            title="{{ in_array($ticket->estado, ['solucionado', 'cancelado'])
                                                                ? 'Ver solución'
                                                                : 'El ticket aún no está solucionado o cancelado' }}">

                                                            <i data-lucide="hand"
                                                                class="w-4 h-4 transition-transform duration-200
                                                            {{ in_array($ticket->estado, ['solucionado', 'cancelado']) ? 'group-hover/action:scale-110' : '' }}">
                                                            </i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="py-20 text-center">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <div
                                                            class="flex h-14 w-14 items-center justify-center rounded-2xl border border-[#1e295d] bg-[#111a3a] text-slate-500">
                                                            <i data-lucide="inbox" class="w-6 h-6"></i>
                                                        </div>

                                                        <p class="mt-4 text-sm font-medium text-slate-400">
                                                            No tienes tickets registrados.
                                                        </p>

                                                        <p class="mt-1 text-xs text-slate-600">
                                                            Los tickets aparecerán aquí cuando sean registrados.
                                                        </p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div
                        class="mt-6 pt-4 border-t border-[#1e295d] flex flex-col sm:flex-row justify-between items-center gap-4">

                        <p class="text-sm font-semibold text-gray-200">
                            Mostrando {{ $tickets->firstItem() ?? 0 }}
                            a {{ $tickets->lastItem() ?? 0 }}
                            de {{ $tickets->total() }} tickets
                        </p>

                        <div class="flex items-center gap-2">

                            @if ($tickets->onFirstPage())
                                <span
                                    class="w-9 h-9 rounded-lg bg-[#060818] border border-[#1e295d] flex items-center justify-center text-gray-600 cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7" />
                                    </svg>
                                </span>
                            @else
                                <a href="{{ $tickets->previousPageUrl() }}"
                                    class="w-9 h-9 rounded-lg bg-[#060818] border border-[#1e295d] flex items-center justify-center text-gray-400 hover:text-white hover:border-blue-500 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7" />
                                    </svg>
                                </a>
                            @endif

                            @foreach ($tickets->getUrlRange(1, $tickets->lastPage()) as $page => $url)
                                @if ($page == $tickets->currentPage())
                                    <span
                                        class="w-9 h-9 rounded-lg bg-blue-600 text-white font-semibold text-sm shadow-[0_0_10px_rgba(37,99,235,0.4)] flex items-center justify-center">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}"
                                        class="w-9 h-9 rounded-lg bg-[#060818] border border-[#1e295d] text-gray-300 font-semibold text-sm hover:border-blue-500 transition flex items-center justify-center">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach

                            @if ($tickets->hasMorePages())
                                <a href="{{ $tickets->nextPageUrl() }}"
                                    class="w-9 h-9 rounded-lg bg-[#060818] border border-[#1e295d] flex items-center justify-center text-gray-400 hover:text-white hover:border-blue-500 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @else
                                <span
                                    class="w-9 h-9 rounded-lg bg-[#060818] border border-[#1e295d] flex items-center justify-center text-gray-600 cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                            @endif

                        </div>

                    </div>

                </section>

            </div>

        </main>
        <template x-teleport="body">
            <div x-show="openModalSolucion" x-cloak x-transition.opacity
                class="fixed inset-0 z-[100000] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4 overflow-y-auto"
                @keydown.escape.window="cerrarModalSolucion()" @click.self="cerrarModalSolucion()">
                <div x-show="openModalSolucion" x-transition @click.stop
                    class="relative w-full max-w-3xl max-h-[90vh] bg-[#030712] border border-emerald-500/30 rounded-3xl shadow-2xl overflow-hidden flex flex-col">
                    <div class="shrink-0 px-6 py-5 border-b border-slate-800 bg-[#030712]">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <div class="flex items-center gap-3 mb-2">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-500/10 border border-blue-500/30 text-blue-400 text-[10px] font-bold uppercase tracking-wider">
                                        <i data-lucide="ticket" class="w-3.5 h-3.5"></i>
                                        Ticket
                                    </span>
                                    <span class="text-sm font-bold text-white"
                                        x-text="'#' + (ticketSolucion?.folio ?? '—')">
                                    </span>
                                </div>
                                <h2 class="text-xl font-bold text-white truncate"
                                    x-text="ticketSolucion?.titulo ?? 'Ticket'">
                                </h2>
                                <p class="text-xs text-slate-400 mt-1">
                                    Consulta la información y solución registrada para este ticket.
                                </p>
                            </div>
                            <button type="button" @click="cerrarModalSolucion()"
                                class="p-2 text-slate-400 hover:text-white rounded-xl hover:bg-slate-800 transition shrink-0">
                                <i data-lucide="x" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </div>
                    <div class="flex-1 overflow-y-auto custom-scrollbar">
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div class="p-4 rounded-xl bg-[#060c21] border border-slate-800">
                                    <span class="text-[10px] text-slate-500 uppercase font-semibold">
                                        Folio
                                    </span>
                                    <p class="text-sm font-bold text-white mt-1"
                                        x-text="ticketSolucion?.folio ?? '—'">
                                    </p>
                                </div>
                                <div class="p-4 rounded-xl bg-[#060c21] border border-slate-800">
                                    <span class="text-[10px] text-slate-500 uppercase font-semibold">
                                        Título
                                    </span>
                                    <p class="text-sm font-bold text-white mt-1 truncate"
                                        x-text="ticketSolucion?.titulo ?? '—'">
                                    </p>
                                </div>
                                <div class="p-4 rounded-xl bg-[#060c21] border border-slate-800">
                                    <span class="text-[10px] text-slate-500 uppercase font-semibold">
                                        Tomado por
                                    </span>
                                    <p class="text-sm font-bold text-white mt-1"
                                        x-text="ticketSolucion?.tomado_por?.name ?? ticketSolucion?.tomado_por?.login ?? ticketSolucion?.tomado_por ?? '—'">
                                    </p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 mb-2">
                                    Estado
                                </label>
                                <div class="w-full bg-[#060c21] border border-slate-800 rounded-xl px-4 py-3 text-xs"
                                    :class="ticketSolucion?.estado === 'solucionado' ?
                                        'text-emerald-400' :
                                        ticketSolucion?.estado === 'cancelado' ?
                                        'text-red-400' :
                                        ticketSolucion?.estado === 'en proceso' ?
                                        'text-amber-400' :
                                        'text-slate-400'"
                                    x-text="ticketSolucion?.estado === 'solucionado'
                                ? 'Solucionado'
                                : ticketSolucion?.estado === 'cancelado'
                                    ? 'Cancelado'
                                    : ticketSolucion?.estado === 'en proceso'
                                        ? 'En proceso'
                                        : 'Pendiente'">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 mb-2">
                                    Solución aplicada
                                </label>
                                <div class="w-full min-h-[130px] bg-[#060c21] border border-slate-800 rounded-xl px-4 py-3 text-xs text-white whitespace-pre-wrap break-words"
                                    x-text="(solucionForm?.solucion || 'No se ha registrado una solución.').trim()">
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-xs font-semibold text-slate-300">
                                        Evidencias de la solución
                                    </label>
                                    <span x-show="Array.isArray(evidenciasSolucion) && evidenciasSolucion.length > 0"
                                        class="text-[10px] font-bold text-emerald-400"
                                        x-text="evidenciasSolucion.length + (evidenciasSolucion.length === 1 ? ' archivo' : ' archivos')">
                                    </span>
                                </div>
                                <div x-show="Array.isArray(evidenciasSolucion) && evidenciasSolucion.length > 0"
                                    class="grid grid-cols-1 gap-3">
                                    <template x-for="(archivo, index) in evidenciasSolucion" :key="index">
                                        <div class="rounded-2xl bg-[#060c21] border border-slate-800 overflow-hidden">
                                            <div x-show="esImagen(archivo)"
                                                class="border-b border-slate-800 bg-black/20 p-3">
                                                <div
                                                    class="rounded-xl overflow-hidden bg-black/40 flex items-center justify-center min-h-[160px]">
                                                    <img :src="archivo.url || archivo.url_archivo || archivoUrl(archivo)"
                                                        :alt="archivo.nombre || archivo.name || nombreArchivo(archivo)"
                                                        class="max-w-full max-h-[420px] object-contain">
                                                </div>
                                            </div>
                                            <div x-show="esPDF(archivo)" class="border-b border-slate-800">
                                                <iframe :src="archivo.url || archivo.url_archivo || archivoUrl(archivo)"
                                                    class="w-full h-[420px] bg-white" title="Vista previa del PDF">
                                                </iframe>
                                            </div>
                                            <div x-show="esVideo(archivo)"
                                                class="border-b border-slate-800 bg-black/20 p-3">
                                                <div
                                                    class="rounded-xl overflow-hidden bg-black flex items-center justify-center">
                                                    <video
                                                        :src="archivo.url || archivo.url_archivo || archivoUrl(archivo)"
                                                        controls preload="metadata"
                                                        class="w-full max-h-[420px] rounded-xl">
                                                    </video>
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-between gap-3 p-4">
                                                <div class="flex items-center gap-3 min-w-0">
                                                    <div
                                                        class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center shrink-0">
                                                        <i :data-lucide="esVideo(archivo) ?
                                                            'video' :
                                                            esPDF(archivo) ?
                                                            'file-text' :
                                                            esImagen(archivo) ?
                                                            'image' :
                                                            'file'"
                                                            class="w-5 h-5 text-emerald-400">
                                                        </i>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p class="text-xs text-white font-medium truncate"
                                                            x-text="archivo.nombre || archivo.name || nombreArchivo(archivo)">
                                                        </p>
                                                        <p class="text-[10px] text-slate-500 mt-1"
                                                            x-text="tipoArchivo(archivo)">
                                                        </p>
                                                    </div>
                                                </div>
                                                <a :href="archivo.url || archivo.url_archivo || archivoUrl(archivo)"
                                                    target="_blank" rel="noopener noreferrer"
                                                    class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-slate-800 hover:bg-emerald-500/10 border border-slate-700 hover:border-emerald-500/40 text-[10px] font-semibold text-slate-300 hover:text-emerald-400 transition shrink-0">
                                                    <span>Ver archivo</span>
                                                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                <div x-show="!Array.isArray(evidenciasSolucion) || evidenciasSolucion.length === 0"
                                    class="p-5 rounded-xl bg-[#060c21] border border-slate-800 text-center">
                                    <i data-lucide="image-off" class="w-7 h-7 mx-auto mb-2 text-slate-600"></i>
                                    <p class="text-xs text-slate-500">
                                        No hay evidencias registradas.
                                    </p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 mb-3">
                                    ¿El problema fue solucionado?
                                </label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border"
                                        :class="Number(solucionForm?.solucionado) === 1 ?
                                            'bg-emerald-500/15 border-emerald-500/50 text-emerald-300' :
                                            'bg-[#060c21] border-slate-800 text-slate-500'">
                                        <i data-lucide="circle-check" class="w-4 h-4"></i>
                                        <span>Sí, fue solucionado</span>
                                    </div>

                                    <div class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border"
                                        :class="Number(solucionForm?.solucionado) === 0 ?
                                            'bg-red-500/15 border-red-500/50 text-red-300' :
                                            'bg-[#060c21] border-slate-800 text-slate-500'">
                                        <i data-lucide="circle-x" class="w-4 h-4"></i>
                                        <span>No fue solucionado</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 mb-2">
                                    Fecha de solución
                                </label>
                                <div
                                    class="w-full bg-[#060c21] border border-slate-800 rounded-xl px-4 py-3 text-xs text-white">
                                    <span
                                        x-text="solucionForm?.fecha_solucion
                                ? formatearFecha(solucionForm.fecha_solucion)
                                : 'Sin fecha registrada'">
                                    </span>
                                </div>
                            </div>
                            <div class="border-t border-slate-800 pt-6">
                                <div class="mb-5">
                                    <h3 class="text-sm font-bold text-white mb-1">
                                        Conformidad del usuario
                                    </h3>
                                    <p class="text-[11px] text-slate-500">
                                        Información registrada al momento de cerrar el ticket.
                                    </p>
                                </div>
                                <div class="mb-5">
                                    <label class="block text-xs font-semibold text-slate-300 mb-2">
                                        Persona que levantó el ticket
                                    </label>
                                    <div
                                        class="w-full bg-[#060c21] border border-slate-800 rounded-xl px-4 py-3 text-xs text-white">
                                        <span
                                            x-text="ticketSolucion?.user?.name ?? solucionForm?.nombre_firmante ?? 'Sin nombre'"
                                            class="font-medium">
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-300 mb-2">
                                        Firma
                                    </label>
                                    <div
                                        class="bg-white rounded-xl overflow-hidden border border-slate-700 p-3 min-h-[180px] flex items-center justify-center">
                                        <img x-show="ticketSolucion?.solucion?.firma || ticketSolucion?.solucion?.url_firma"
                                            :src="ticketSolucion?.solucion?.url_firma ?? archivoUrl(ticketSolucion?.solucion
                                                ?.firma)"
                                            alt="Firma registrada" class="max-w-full max-h-40 object-contain"
                                            x-on:error="console.error('No se pudo cargar la firma:', $event.target.src)">
                                        <span
                                            x-show="!ticketSolucion?.solucion?.firma && !ticketSolucion?.solucion?.url_firma"
                                            class="text-slate-500 text-xs">
                                            No hay una firma registrada.
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-5">
                                    <label class="block text-xs font-semibold text-slate-300 mb-2">
                                        Fecha de firma
                                    </label>
                                    <div
                                        class="w-full bg-[#060c21] border border-slate-800 rounded-xl px-4 py-3 text-xs text-white">
                                        <span
                                            x-text="solucionForm?.fecha_firma
                                    ? formatearFecha(solucionForm.fecha_firma)
                                    : 'Sin fecha registrada'">
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="shrink-0 flex items-center justify-end px-6 py-4 border-t border-slate-800 bg-[#030712]">
                        <button type="button" @click="cerrarModalSolucion()"
                            class="inline-flex items-center justify-center gap-2 min-w-[100px] px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 bg-slate-800/60 border border-slate-700 hover:bg-slate-700 hover:text-white transition">
                            <i data-lucide="x" class="w-4 h-4"></i>
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </template>
        <template x-teleport="body">

            <div x-show="openModal" x-cloak x-transition.opacity
                class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4 sm:p-6 overflow-y-auto"
                @keydown.escape.window="cerrarModal()" @click.self="cerrarModal()">


                {{-- CORREGIDO: class en lugar de calass --}}

                <div x-show="openModal" x-transition @click.stop
                    class="relative w-full max-w-7xl bg-[#030712] border border-blue-600/40 rounded-3xl shadow-2xl flex flex-col max-h-[92vh] text-slate-200 overflow-hidden">


                    {{-- HEADER MODAL --}}

                    <div class="flex items-center justify-between p-6 pb-4 border-b border-slate-800/80 shrink-0">

                        <div>

                            <h2 class="text-2xl font-bold text-white tracking-wide">

                                Detalle del ticket

                            </h2>

                            <p class="text-xs text-slate-400 mt-0.5">

                                Consulta toda la información y el seguimiento de este ticket.

                            </p>

                        </div>


                        <button type="button" @click="cerrarModal()"
                            class="p-2 text-slate-400 hover:text-white rounded-xl hover:bg-slate-800/60 transition">

                            <i data-lucide="x" class="w-6 h-6">
                            </i>

                        </button>

                    </div>


                    {{-- CONTENIDO --}}

                    <div class="p-6 overflow-y-auto custom-scrollbar flex-1 space-y-6">


                        {{-- RESUMEN SUPERIOR --}}

                        <div
                            class="grid grid-cols-2 md:grid-cols-5 gap-4 p-4 rounded-2xl bg-[#060c21] border border-blue-500/40">


                            <div class="border-r border-slate-800/60 pr-2">

                                <span class="text-[11px] font-semibold text-blue-400 block mb-1">

                                    Folio

                                </span>

                                <span class="text-sm font-bold text-white" x-text="selectedTicket?.folio ?? '—'">
                                </span>

                            </div>


                            <div class="border-r border-slate-800/60 pr-2">

                                <span class="text-[11px] font-semibold text-blue-400 block mb-1">

                                    Prioridad

                                </span>

                                <span class="text-sm font-bold text-white"
                                    x-text="capitalizar(selectedTicket?.prioridad)">
                                </span>

                            </div>


                            <div class="border-r border-slate-800/60 pr-2">

                                <span class="text-[11px] font-semibold text-blue-400 block mb-1">

                                    Estado

                                </span>

                                <span class="text-sm font-bold text-white"
                                    x-text="capitalizar(selectedTicket?.estado)">
                                </span>

                            </div>


                            <div class="border-r border-slate-800/60 pr-2">

                                <span class="text-[11px] font-semibold text-blue-400 block mb-1">

                                    Tomado por

                                </span>

                                <span class="text-sm font-bold text-white" x-text="nombreTomadoPor(selectedTicket)">
                                </span>

                            </div>


                            <div>

                                <span class="text-[11px] font-semibold text-blue-400 block mb-1">

                                    Fecha

                                </span>

                                <span class="text-xs font-bold text-slate-200"
                                    x-text="formatearFecha(selectedTicket?.created_at)">
                                </span>

                            </div>

                        </div>


                        {{-- COLUMNAS --}}

                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">


                            {{-- ================================================= --}}
                            {{-- IZQUIERDA --}}
                            {{-- ================================================= --}}

                            <div class="lg:col-span-6 space-y-5">


                                {{-- RESUMEN --}}

                                <div class="p-5 rounded-2xl bg-[#060c21] border border-blue-500/30 space-y-4">

                                    <div class="flex items-center gap-2 border-b border-slate-800 pb-3">

                                        <i data-lucide="receipt" class="w-4 h-4 text-slate-300">
                                        </i>

                                        <h3 class="text-sm font-bold text-white">

                                            Resumen del ticket

                                        </h3>

                                    </div>


                                    <div class="space-y-3 text-xs">


                                        {{-- TITULO --}}

                                        <div class="flex justify-between items-start">

                                            <span class="text-slate-400 font-semibold">

                                                Título

                                            </span>

                                            <span class="text-white font-medium text-right max-w-[220px]"
                                                x-text="selectedTicket?.titulo ?? '—'">
                                            </span>

                                        </div>


                                        {{-- TIPO --}}

                                        {{-- TIPO DE FALLA --}}

                                        <div class="flex justify-between items-center">

                                            <span class="text-slate-400 font-semibold">
                                                Tipo de falla:
                                            </span>

                                            <div class="flex items-center gap-1.5 text-slate-200 font-medium">

                                                <i data-lucide="ticket" class="w-3.5 h-3.5 text-slate-400"></i>

                                                <span x-text="selectedTicket?.tipo_falla ?? 'Sin especificar'"></span>

                                            </div>

                                        </div>

                                        {{-- EQUIPO --}}

                                        <div x-show="selectedTicket?.tipo_falla === 'Equipo'" x-cloak
                                            class="flex justify-between items-center">

                                            <span class="text-slate-400 font-semibold">
                                                Equipo:
                                            </span>

                                            <div class="flex items-center gap-1.5 text-slate-200 font-medium">

                                                <i data-lucide="laptop" class="w-3.5 h-3.5 text-slate-400"></i>

                                                <span x-text="selectedTicket?.equipo ?? 'No especificado'"></span>

                                            </div>

                                        </div>

                                        {{-- LEVANTADO POR --}}

                                        <div class="flex justify-between items-start pt-1">
                                            <span class="text-slate-400 font-semibold">Levantado por:</span>
                                            <div class="flex items-center gap-2 text-right">
                                                <div>
                                                    <div class="text-white font-medium"
                                                        x-text="selectedTicket?.user?.name ?? selectedTicket?.usuario?.name ?? 'Usuario'">
                                                    </div>
                                                    <div class="text-[10px] text-slate-400"
                                                        x-text="selectedTicket?.user?.email ?? selectedTicket?.usuario?.email ?? ''">
                                                    </div>
                                                </div>
                                                <img :src="selectedTicket?.user?.picture ?
                                                    '/storage/' + selectedTicket.user.picture.replace(/^\/?storage\//,
                                                        '') :
                                                    '{{ asset('storage/profile-photos/user.png') }}'"
                                                    :alt="selectedTicket?.user?.name ?? selectedTicket?.usuario?.name ??
                                                        'Usuario'"
                                                    class="w-8 h-8 rounded-full object-cover border border-blue-400/40"
                                                    x-on:error="
                                                        if ($event.target.dataset.fallback === 'avatar') {
                                                            return;
                                                        }

                                                        if ($event.target.dataset.fallback !== 'user') {
                                                            $event.target.dataset.fallback = 'user';
                                                            $event.target.src = '{{ asset('storage/profile-photos/user.png') }}';
                                                        } else {
                                                            $event.target.dataset.fallback = 'avatar';
                                                            $event.target.src = avatarUsuario(
                                                                selectedTicket?.user?.name ??
                                                                selectedTicket?.usuario?.name ??
                                                                'Usuario'
                                                            );
                                                        }
                                                    ">
                                            </div>
                                        </div>

                                        {{-- DEPARTAMENTO --}}

                                        <div class="flex justify-between items-center">

                                            <span class="text-slate-400 font-semibold">

                                                Departamento:

                                            </span>

                                            <span class="text-slate-300"
                                                x-text="selectedTicket?.user?.departamento?.nombre ?? 'Sin especificar'">
                                            </span>

                                        </div>


                                        {{-- EMPRESA --}}

                                        <div class="flex justify-between items-center">

                                            <span class="text-slate-400 font-semibold">

                                                Empresa:

                                            </span>

                                            <span class="text-slate-300"
                                                x-text="selectedTicket?.user?.departamento?.oficina?.empresa?.empresa ?? 'Sin especificar'">
                                            </span>

                                        </div>


                                        {{-- OFICINA --}}

                                        <div class="flex justify-between items-center">

                                            <span class="text-slate-400 font-semibold">

                                                Oficina:

                                            </span>

                                            <span class="text-slate-300"
                                                x-text="selectedTicket?.user?.departamento?.oficina?.nombre ?? 'Sin especificar'">
                                            </span>

                                        </div>


                                        {{-- UBICACION --}}

                                        <div class="flex justify-between items-center">

                                            <span class="text-slate-400 font-semibold">

                                                Ubicación:

                                            </span>

                                            <span class="text-slate-300"
                                                x-text="selectedTicket?.ubicacion ?? 'Sin especificar'">
                                            </span>

                                        </div>


                                        {{-- FECHAS --}}

                                        <div class="pt-2 space-y-2">

                                            <div class="p-2.5 rounded-xl bg-[#030712] border border-slate-800/80">

                                                <div class="text-[10px] text-slate-400 font-semibold mb-0.5">

                                                    Fecha en que fue levantado

                                                </div>

                                                <div class="text-xs text-slate-200 font-medium"
                                                    x-text="formatearFecha(selectedTicket?.created_at)">
                                                </div>

                                            </div>


                                            <div class="p-2.5 rounded-xl bg-[#030712] border border-slate-800/80">

                                                <div
                                                    class="flex items-center gap-1.5 text-[10px] text-slate-400 font-semibold mb-0.5">

                                                    <i data-lucide="alarm-clock" class="w-3 h-3 text-slate-400">
                                                    </i>

                                                    <span>
                                                        Fecha en que fue tomado
                                                    </span>

                                                </div>

                                                <div class="text-xs text-slate-400"
                                                    x-text="selectedTicket?.fecha_tomado
                                                                ? formatearFecha(selectedTicket.fecha_tomado)
                                                                : 'Aún sin tomar'">
                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>


                                {{-- DESCRIPCION --}}

                                <div class="p-5 rounded-2xl bg-[#060c21] border border-blue-500/30">

                                    <h3 class="text-xs font-bold text-white mb-2">

                                        Descripción del problema

                                    </h3>

                                    <p class="text-xs text-slate-300 leading-relaxed whitespace-pre-line"
                                        x-text="selectedTicket?.descripcion ?? 'Sin descripción'">
                                    </p>

                                </div>


                                {{-- INFORMACION ADICIONAL --}}

                                <div class="p-5 rounded-2xl bg-[#060c21] border border-blue-500/30">

                                    <h3 class="text-xs font-bold text-white mb-2">

                                        Información adicional

                                    </h3>

                                    <p class="text-xs text-slate-300 leading-relaxed whitespace-pre-line"
                                        x-text="selectedTicket?.informacion_adicional ?? selectedTicket?.comentarios ?? 'Sin información adicional'">
                                    </p>

                                </div>


                                {{-- EVIDENCIA --}}

                                <div class="p-5 rounded-2xl bg-[#060c21] border border-blue-500/30">

                                    <h3 class="text-xs font-bold text-white mb-3">

                                        Evidencia proporcionada

                                    </h3>


                                    <template x-if="evidencias.length > 0">

                                        <div class="flex items-center gap-3 overflow-x-auto pb-1">

                                            <template x-for="(archivo, index) in evidencias" :key="index">

                                                <a :href="archivoUrl(archivo)" target="_blank"
                                                    class="relative w-28 h-20 rounded-xl bg-slate-900 border border-slate-700/80 overflow-hidden flex flex-col justify-between p-2 shrink-0 hover:border-blue-500/60 hover:bg-slate-800 transition">


                                                    <div class="space-y-1">

                                                        <template x-if="esImagen(archivo)">

                                                            <img :src="archivoUrl(archivo)"
                                                                :alt="nombreArchivo(archivo)"
                                                                class="w-full h-10 object-cover rounded">

                                                        </template>


                                                        <template x-if="!esImagen(archivo)">

                                                            <div class="flex items-center justify-center h-10">

                                                                <i data-lucide="file-text"
                                                                    class="w-7 h-7 text-slate-500">
                                                                </i>

                                                            </div>

                                                        </template>

                                                    </div>


                                                    <div
                                                        class="flex items-center justify-between gap-1 text-[9px] text-slate-300 pt-2 border-t border-slate-800">

                                                        <span class="truncate" x-text="nombreArchivo(archivo)">
                                                        </span>

                                                        <span
                                                            class="px-1 py-0.5 rounded bg-blue-600 text-white font-bold text-[8px] shrink-0"
                                                            x-text="extensionArchivo(archivo)">
                                                        </span>

                                                    </div>

                                                </a>

                                            </template>

                                        </div>

                                    </template>


                                    <template x-if="evidencias.length === 0">

                                        <div class="flex items-center justify-center py-6 text-slate-500 text-xs">

                                            <div class="flex items-center gap-2">

                                                <i data-lucide="file-x" class="w-4 h-4">
                                                </i>

                                                <span>
                                                    No se proporcionó evidencia.
                                                </span>

                                            </div>

                                        </div>

                                    </template>

                                </div>

                            </div>


                            {{-- ================================================= --}}
                            {{-- DERECHA --}}
                            {{-- ================================================= --}}

                            <div
                                class="lg:col-span-6 p-5 rounded-2xl bg-[#060c21] border border-blue-500/30 flex flex-col min-h-[600px]">


                                <div class="flex items-center gap-2 pb-4 border-b border-slate-800/80">

                                    <i data-lucide="message-square" class="w-4 h-4 text-slate-300">
                                    </i>

                                    <h3 class="text-sm font-bold text-white">

                                        Comentarios y seguimiento

                                    </h3>

                                </div>


                                {{-- FORMULARIO --}}
                                @if (isset($ticket))
                                    <form id="formComentario"
                                        action="{{ route('tickets.comentarios.store', $ticket->id) }}" method="POST"
                                        enctype="multipart/form-data" class="flex items-end gap-3">

                                        @csrf

                                        {{-- FOTO DEL USUARIO --}}
                                        <div class="shrink-0">

                                            <img src="{{ auth()->user()->picture ? Storage::url(auth()->user()->picture) : asset('storage/profile-photos/user.png') }}"
                                                class="w-10 h-10 rounded-full object-cover border border-blue-400/40"
                                                alt="{{ auth()->user()->name ?? 'Usuario' }}"
                                                onerror="
        if (this.dataset.fallback === 'user') {
            this.dataset.fallback = 'avatar';
            this.src = 'https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Usuario') }}&background=0D8ABC&color=fff';
        } else {
            this.dataset.fallback = 'user';
            this.src = '{{ asset('storage/profile-photos/user.png') }}';
        }
    ">

                                        </div>


                                        {{-- CONTENEDOR DEL COMENTARIO --}}
                                        <div class="relative flex-1">

                                            {{-- ARCHIVO --}}
                                            <input type="file" name="archivo" x-ref="fileInputModal"
                                                @change="seleccionarArchivo($event)" class="hidden">


                                            {{-- CAMPO --}}
                                            <input type="text" name="mensaje"
                                                placeholder="Escribe un comentario..." autocomplete="off"
                                                class="w-full h-11 pl-4 pr-24 text-xs
                   bg-[#030712]
                   border border-slate-800
                   rounded-xl
                   text-white
                   placeholder-slate-500
                   focus:outline-none
                   focus:border-blue-500
                   focus:ring-1
                   focus:ring-blue-500/30
                   transition">


                                            {{-- BOTONES --}}
                                            <div
                                                class="absolute right-2 top-1/2 -translate-y-1/2 flex items-center gap-1">

                                                {{-- ADJUNTAR --}}
                                                <button type="button" @click="$refs.fileInputModal.click()"
                                                    class="w-8 h-8 flex items-center justify-center
                       rounded-lg
                       text-slate-400
                       hover:text-blue-400
                       hover:bg-blue-500/10
                       transition"
                                                    title="Adjuntar archivo">

                                                    <i data-lucide="paperclip" class="w-4 h-4"></i>

                                                </button>


                                                {{-- ENVIAR --}}
                                                <button type="submit" id="btnEnviarComentario"
                                                    class="h-8 px-3
                       flex items-center justify-center
                       rounded-lg
                       bg-blue-600
                       hover:bg-blue-500
                       text-white
                       text-[11px]
                       font-semibold
                       transition
                       shadow-lg shadow-blue-900/20">

                                                    <i data-lucide="send" class="w-3.5 h-3.5 mr-1.5"></i>

                                                    Enviar

                                                </button>

                                            </div>

                                        </div>

                                    </form>
                                @endif
                                {{-- ARCHIVO SELECCIONADO --}}

                                <template x-if="archivoAdjunto">

                                    <div
                                        class="mt-3 flex items-center gap-2 px-3 py-2 rounded-xl bg-blue-500/10 border border-blue-500/20">

                                        <i data-lucide="paperclip" class="w-4 h-4 text-blue-400">
                                        </i>

                                        <span class="text-[10px] text-slate-300 truncate"
                                            x-text="archivoAdjunto?.name">
                                        </span>

                                        <button type="button" @click="quitarArchivo()"
                                            class="ml-auto text-slate-500 hover:text-red-400">

                                            <i data-lucide="x" class="w-3.5 h-3.5">
                                            </i>

                                        </button>

                                    </div>

                                </template>


                                {{-- COMENTARIOS --}}

                                <div id="listaComentarios"
                                    class="flex-1 space-y-5 mt-4 overflow-y-auto max-h-[460px] pr-2 custom-scrollbar">


                                    <template x-if="comentarios.length === 0">

                                        <div class="flex flex-col items-center justify-center py-16 text-slate-500">

                                            <div
                                                class="w-12 h-12 rounded-full bg-slate-800/60 flex items-center justify-center mb-3">

                                                <i data-lucide="message-square-off" class="w-5 h-5">
                                                </i>

                                            </div>

                                            <p class="text-xs">
                                                Aún no hay comentarios.
                                            </p>

                                            <span class="text-[10px] text-slate-600 mt-1">

                                                Sé el primero en agregar un comentario.

                                            </span>

                                        </div>

                                    </template>


                                    <template x-for="comentario in comentarios" :key="comentario.id">


                                        <div class="flex items-start gap-3">


                                            <img src="{{ auth()->user()->picture ? Storage::url(auth()->user()->picture) : asset('storage/profile-photos/user.png') }}"
                                                class="w-10 h-10 rounded-full object-cover border border-blue-400/40"
                                                alt="{{ auth()->user()->name ?? 'Usuario' }}"
                                                onerror="
        if (this.dataset.fallback === 'user') {
            this.dataset.fallback = 'avatar';
            this.src = 'https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Usuario') }}&background=0D8ABC&color=fff';
        } else {
            this.dataset.fallback = 'user';
            this.src = '{{ asset('storage/profile-photos/user.png') }}';
        }
    ">


                                            <div class="flex-1 min-w-0">


                                                <div class="flex items-center gap-2 mb-1 flex-wrap">

                                                    <span class="text-xs font-bold text-white"
                                                        x-text="comentario.usuario?.name ?? 'Usuario'">
                                                    </span>


                                                    <span
                                                        class="px-2 py-0.5 rounded-full text-[9px] font-semibold bg-indigo-600/30 text-indigo-300 border border-indigo-500/40"
                                                        x-text="comentario.usuario?.rol ?? 'Usuario'">
                                                    </span>


                                                    <span class="text-[10px] text-slate-500 ml-auto"
                                                        x-text="comentario.fecha ?? ''">
                                                    </span>

                                                </div>


                                                <template x-if="comentario.mensaje">

                                                    <p class="text-xs text-slate-300 mb-2 whitespace-pre-line"
                                                        x-text="comentario.mensaje">
                                                    </p>

                                                </template>


                                                {{-- IMAGEN --}}

                                                <template x-if="comentario.archivo && esImagen(comentario.archivo)">

                                                    <a :href="comentario.url_archivo" target="_blank"
                                                        class="block w-36 rounded-xl overflow-hidden bg-slate-900 border border-slate-700/80 hover:border-blue-500/60 transition">

                                                        <img :src="comentario.url_archivo"
                                                            :alt="comentario.nombre_archivo"
                                                            class="w-36 h-20 object-cover">


                                                        <div
                                                            class="px-2 py-1.5 flex items-center justify-between gap-2 border-t border-slate-800">

                                                            <span class="text-[9px] text-slate-300 truncate"
                                                                x-text="comentario.nombre_archivo">
                                                            </span>

                                                            <span
                                                                class="px-1 py-0.5 rounded bg-blue-600 text-white font-bold text-[8px] shrink-0"
                                                                x-text="comentario.extension">
                                                            </span>

                                                        </div>

                                                    </a>

                                                </template>


                                                {{-- ARCHIVO NORMAL --}}

                                                <template x-if="comentario.archivo && !esImagen(comentario.archivo)">

                                                    <a :href="comentario.url_archivo" target="_blank"
                                                        class="group block w-40 h-20 rounded-xl bg-slate-900 border border-slate-700/80 p-2 hover:border-blue-500/60 hover:bg-slate-800 transition">


                                                        <div class="flex items-center gap-2 mb-2">

                                                            <div
                                                                class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center">

                                                                <i data-lucide="file" class="w-4 h-4 text-blue-400">
                                                                </i>

                                                            </div>

                                                        </div>


                                                        <div
                                                            class="flex items-center justify-between gap-2 text-[9px] text-slate-300 pt-1 border-t border-slate-800">

                                                            <span class="truncate" x-text="comentario.nombre_archivo">
                                                            </span>

                                                            <span
                                                                class="px-1 py-0.5 rounded bg-blue-600 text-white font-bold text-[8px] shrink-0"
                                                                x-text="comentario.extension">
                                                            </span>

                                                        </div>

                                                    </a>

                                                </template>

                                            </div>

                                        </div>

                                    </template>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- FOOTER --}}

                    <div
                        class="p-5 border-t border-slate-800/80 bg-[#030712] flex items-center justify-between shrink-0">


                        <button type="button" @click="cerrarModal()"
                            class="px-5 py-2.5 text-xs font-semibold text-slate-400 hover:text-white hover:bg-slate-800/60 rounded-xl transition-all duration-200">

                            Cerrar

                        </button>

                    </div>

                </div>

            </div>

        </template>
</body>

</html>
```
