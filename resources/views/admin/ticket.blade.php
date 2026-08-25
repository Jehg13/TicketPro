<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>TicketPro - Dashboard</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/images/logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/filtrostecnologias.js', 'resources/js/mensajes.js', 'resources/js/modalticketusuario.js', 'resources/js/app.js'])
    <script>
        window.usuarioActualLogin = @json(Auth::user()->login);
    </script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="bg-[#070b19] text-white font-sans h-screen flex antialiased overflow-hidden">

    <aside
        class="w-64 bg-[#0a0f24] border-r border-slate-800/60 p-6 flex flex-col justify-between hidden md:flex h-screen shrink-0 overflow-hidden">
        <div class="min-h-0">
            <div class="flex items-center gap-2 mb-10">
                <span class="text-3xl font-extrabold tracking-wide text-white">
                    Ticket<span class="text-blue-500">Pro</span>
                </span>
            </div>

            <div class="flex items-center gap-3 mb-8 p-2 rounded-xl bg-slate-900/40 border border-slate-800/50">
                <img src="{{ auth()->user()->picture ? asset('storage/' . auth()->user()->picture) : asset('images/default-avatar.png') }}"
                    alt="{{ auth()->user()->name ?? 'Usuario' }}"
                    class="w-10 h-10 rounded-full object-cover ring-2 ring-blue-500/30">
                <div class="overflow-hidden">
                    <h4 class="text-sm font-semibold text-slate-200 truncate">{{ Auth::user()->name ?? 'Desconocido' }}
                    </h4>
                    <p class="text-xs text-slate-400 truncate">
                        {{ auth()->user()->role ?? 'Desconocido' }}
                </div>
            </div>

            <nav class="space-y-2">
                <a href="{{ route('tecnologias') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span class="font-medium text-sm">Inicio</span>
                </a>

                <a href="{{ route('tickettecnologias') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold shadow-lg shadow-blue-600/30 transition">
                    <i data-lucide="ticket-check" class="w-5 h-5"></i>
                    <span class="text-sm">Tickets</span>
                </a>

                @if (auth()->check() && auth()->user()->role === 'Gerente Ti' && auth()->user()->priv_admin === 'Y')
                    <a href="{{ route('cambiostecnologias') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">

                        <i data-lucide="git-compare-arrows" class="w-5 h-5"></i>

                        <span class="text-sm">
                            Cambios
                        </span>

                    </a>
                @endif

                @if (auth()->check() && auth()->user()->role === 'Gerente Ti' && auth()->user()->priv_admin === 'Y')
                    <a href="{{ route('usuarios.tecnologias') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">

                        <i data-lucide="users" class="w-5 h-5"></i>

                        <span class="text-sm">
                            Usuarios
                        </span>

                    </a>
                @endif


                <a href="{{ route('avisostecnologias') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">
                    <i data-lucide="megaphone" class="w-5 h-5"></i>
                    <span class="font-medium text-sm">Avisos</span>
                </a>

                <a href="{{ route('perfiltecnologias') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">
                    <i data-lucide="circle-user-round" class="w-5 h-5"></i>
                    <span class="font-medium text-sm">Mi perfil</span>
                </a>
            </nav>
        </div>

        <div class="shrink-0 pt-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-red-500/10 hover:text-red-400 transition">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                    <span class="font-medium text-sm">Cerrar sesión</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 h-screen min-w-0 overflow-y-auto overflow-x-hidden p-6 md:p-8">
        <div class="max-w-7xl mx-auto" x-data="ticketModal()">

            <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
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
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-white">Tickets</h1>
                    <p class="text-sm text-slate-400 mt-1">Consulta y da seguimiento a todos los tickets que se han
                        creado</p>
                </div>

                <div class="flex items-center gap-4 self-end md:self-auto">
                    <div class="flex items-center gap-6">

                        <div class="relative" x-data="{ notificacionesAbiertas: false }">
                            <button type="button" @click="notificacionesAbiertas = !notificacionesAbiertas"
                                @click.outside="notificacionesAbiertas = false"
                                class="relative flex items-center justify-center w-10 h-10 rounded-xl bg-slate-900/80 border border-slate-800 text-slate-400 hover:text-white hover:bg-slate-800 transition-all duration-200 focus:outline-none">
                                <i data-lucide="bell" class="w-5 h-5"></i>
                                @if ($notificacionesNoLeidas > 0)
                                    <span
                                        class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 flex items-center justify-center rounded-full bg-indigo-600 border-2 border-[#050814] text-[9px] font-bold text-white">
                                        {{ $notificacionesNoLeidas > 99 ? '99+' : $notificacionesNoLeidas }}
                                    </span>
                                @endif
                            </button>

                            <div x-show="notificacionesAbiertas" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                                @click.outside="notificacionesAbiertas = false"
                                class="absolute right-0 top-full mt-3 w-[360px] max-w-[calc(100vw-2rem)] bg-[#0f1535] border border-[#1e295d] rounded-2xl shadow-2xl shadow-black/40 overflow-hidden z-[99999]"
                                style="display: none;">

                                <div class="flex items-center justify-between px-4 py-4 border-b border-slate-800/80">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center">
                                            <i data-lucide="bell" class="w-4 h-4 text-indigo-400"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-semibold text-white">Notificaciones</h3>
                                            <p class="text-[10px] text-slate-500">Tienes {{ $notificacionesNoLeidas }}
                                                nuevas</p>
                                        </div>
                                    </div>

                                    @if ($notificacionesNoLeidas > 0)
                                        <form method="POST" action="{{ route('notificaciones.marcarLeidas') }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="text-[11px] font-medium text-indigo-400 hover:text-indigo-300 transition-colors">
                                                Marcar leídas
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                <div class="max-h-[400px] overflow-y-auto">
                                    @forelse ($notificaciones as $notificacion)
                                        <a href="{{ $notificacion->url ?? '#' }}"
                                            class="group flex gap-3 px-4 py-4 border-b border-slate-800/50 transition-colors hover:bg-slate-800/40 {{ !$notificacion->leida ? 'bg-indigo-500/[0.04]' : '' }}">
                                            <div
                                                class="w-10 h-10 shrink-0 rounded-xl border border-indigo-500/20 bg-indigo-500/10 flex items-center justify-center">
                                                <i data-lucide="{{ $notificacion->icono ?? 'bell' }}"
                                                    class="w-5 h-5 text-indigo-400"></i>
                                            </div>

                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-start justify-between gap-2">
                                                    <p
                                                        class="text-xs font-semibold text-white group-hover:text-indigo-400 transition-colors">
                                                        {{ $notificacion->titulo }}
                                                    </p>
                                                    @if (!$notificacion->leida)
                                                        <span
                                                            class="w-2 h-2 shrink-0 mt-1.5 rounded-full bg-indigo-500"></span>
                                                    @endif
                                                </div>
                                                <p class="mt-1 text-[11px] leading-relaxed text-slate-400">
                                                    {{ $notificacion->mensaje }}</p>
                                                <p class="mt-2 text-[10px] text-slate-500">
                                                    {{ $notificacion->created_at->diffForHumans() }}</p>
                                            </div>
                                        </a>
                                    @empty
                                        <div class="px-6 py-10 text-center">
                                            <div
                                                class="mx-auto mb-3 w-12 h-12 rounded-full bg-slate-800/50 border border-slate-800 flex items-center justify-center">
                                                <i data-lucide="bell-off" class="w-5 h-5 text-slate-500"></i>
                                            </div>
                                            <p class="text-xs font-medium text-slate-400">No tienes notificaciones</p>
                                            <p class="text-[10px] text-slate-600 mt-1">Aquí aparecerán tus nuevas
                                                notificaciones.</p>
                                        </div>
                                    @endforelse
                                </div>

                                @if ($notificaciones->count() > 0)
                                    <div class="px-4 py-3 border-t border-slate-800/80 bg-[#0b1026]">
                                        <p class="text-[10px] text-center text-slate-500">Mostrando tus notificaciones
                                            recientes</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="relative z-[100]" x-data="{ perfilAbierto: false }">
                            <button id="profile-button" type="button" @click="perfilAbierto = !perfilAbierto"
                                class="relative flex items-center gap-3 bg-slate-900/80 border border-slate-800 rounded-full p-1.5 pr-4 hover:bg-slate-800 transition-all duration-200 focus:outline-none">
                                <img src="{{ auth()->user()->picture ? asset('storage/' . auth()->user()->picture) : asset('images/default-avatar.png') }}"
                                    alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full object-cover">

                                <div class="text-left leading-tight hidden sm:block">
                                    <p class="text-xs font-semibold text-white">
                                        {{ auth()->user()->name ?? 'Desconocido' }}</p>
                                    <p class="text-[10px] text-blue-400 font-medium">
                                        {{ auth()->user()->role ?? 'Desconocido' }}
                                </div>

                                <svg id="profile-arrow"
                                    class="w-4 h-4 text-slate-400 ml-1 transition-transform duration-200"
                                    :class="{ 'rotate-180': perfilAbierto }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div id="profile-dropdown" x-show="perfilAbierto" @click.outside="perfilAbierto = false"
                                x-transition
                                class="absolute right-0 top-full mt-3 w-56 bg-[#0f1535] border border-[#1e295d] rounded-xl shadow-2xl overflow-hidden z-[99999]"
                                style="display: none;">
                                <a href="{{ route('perfiltecnologias') }}"
                                    class="flex items-center gap-3 px-4 py-3 text-sm text-slate-300 hover:bg-[#151b3b] hover:text-white transition-colors">
                                    <i data-lucide="circle-user-round" class="w-5 h-5 text-slate-400"></i>
                                    <span>Perfil</span>
                                </a>

                                <div class="border-t border-[#1e295d]"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-3 px-4 py-3 text-sm text-slate-300 hover:bg-red-500/10 hover:text-red-400 transition-colors text-left">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
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
                </div>
            </header>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
                @if (session('success'))
                    <div id="successMessage"
                        class="fixed right-5 top-5 z-[9999] w-full max-w-sm rounded-2xl border border-green-500/30 bg-[#0f1535] p-4 shadow-[0_0_30px_rgba(34,197,94,0.20)]">
                        <div class="flex items-start gap-3">
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-green-500/15 text-green-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-white">¡Éxito!</p>
                                <p class="mt-1 text-sm text-slate-400">{{ session('success') }}</p>
                            </div>
                            <button onclick="document.getElementById('successMessage').remove()"
                                class="text-slate-500 hover:text-white">✕</button>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-white">¡Error!</p>
                                <p class="mt-1 text-sm text-slate-400">{{ session('error') }}</p>
                            </div>
                            <button type="button" onclick="document.getElementById('errorMessage')?.remove()"
                                class="text-slate-500 hover:text-white transition">✕</button>
                        </div>
                    </div>
                @endif

                <div
                    class="bg-[#0b1026]/80 border border-blue-900/40 rounded-2xl p-4 relative overflow-hidden backdrop-blur-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2.5 rounded-xl bg-blue-600/20 text-blue-400">
                            <i data-lucide="ticket-check" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-medium">Total de tickets</p>
                            <h3 class="text-2xl font-bold text-white">{{ $totalTickets }}</h3>
                        </div>
                    </div>
                    <p class="text-[11px] {{ $colorTotal }} font-medium mt-1">{{ $porcentajeTotalTexto }}</p>
                </div>

                <div class="bg-[#0b1026]/80 border border-slate-800/80 rounded-2xl p-4 backdrop-blur-sm">
                    <div class="flex items-center gap-2 mb-1">
                        <i data-lucide="clock-3" class="w-4 h-4 text-amber-400"></i>
                        <p class="text-xs text-slate-400 font-medium">Pendientes</p>
                    </div>
                    <h3 class="text-2xl font-bold text-white">{{ $pendientes }}</h3>
                    <p class="text-[11px] {{ $colorPendientes }} font-medium mt-1">{{ $porcentajePendientesTexto }}
                    </p>
                </div>

                <div class="bg-[#0b1026]/80 border border-slate-800/80 rounded-2xl p-4 backdrop-blur-sm">
                    <div class="flex items-center gap-2 mb-1">
                        <i data-lucide="loader-circle" class="w-4 h-4 text-blue-400"></i>
                        <p class="text-xs text-slate-400 font-medium">En proceso</p>
                    </div>
                    <h3 class="text-2xl font-bold text-white">{{ $enProceso }}</h3>
                    <p class="text-[11px] {{ $colorEnProceso }} font-medium mt-1">{{ $porcentajeEnProcesoTexto }}</p>
                </div>

                <div class="bg-[#0b1026]/80 border border-slate-800/80 rounded-2xl p-4 backdrop-blur-sm">
                    <div class="flex items-center gap-2 mb-1">
                        <i data-lucide="circle-check" class="w-4 h-4 text-emerald-400"></i>
                        <p class="text-xs text-slate-400 font-medium">Solucionados</p>
                    </div>
                    <h3 class="text-2xl font-bold text-white">{{ $solucionados }}</h3>
                    <p class="text-[11px] {{ $colorSolucionados }} font-medium mt-1">
                        {{ $porcentajeSolucionadosTexto }}</p>
                </div>

                <div class="bg-[#0b1026]/80 border border-slate-800/80 rounded-2xl p-4 backdrop-blur-sm">
                    <div class="flex items-center gap-2 mb-1">
                        <i data-lucide="circle-x" class="w-4 h-4 text-rose-400"></i>
                        <p class="text-xs text-slate-400 font-medium">Cancelados</p>
                    </div>
                    <h3 class="text-2xl font-bold text-white">{{ $cancelados }}</h3>
                    <p class="text-[11px] {{ $colorCancelados }} font-medium mt-1">{{ $porcentajeCanceladosTexto }}
                    </p>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
                <div
                    class="flex items-center gap-1 bg-[#0b1026] border border-slate-800 p-1.5 rounded-2xl overflow-x-auto">
                    <button type="button" @click="cambiarFiltro('todos')"
                        :class="filtro === 'todos' ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' :
                            'text-slate-400 hover:text-white'"
                        class="px-5 py-2 rounded-xl text-xs font-semibold transition whitespace-nowrap">
                        Todos
                    </button>

                    <button type="button" @click="cambiarFiltro('mis tickets')"
                        :class="filtro === 'mis tickets' ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' :
                            'text-slate-400 hover:text-white'"
                        class="px-5 py-2 rounded-xl text-xs font-semibold transition whitespace-nowrap">
                        Mis tickets
                    </button>

                    <button type="button" @click="cambiarFiltro('pendiente')"
                        :class="filtro === 'pendiente' ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' :
                            'text-slate-400 hover:text-white'"
                        class="px-5 py-2 rounded-xl text-xs font-semibold transition whitespace-nowrap">
                        Pendientes
                    </button>

                    <button type="button" @click="cambiarFiltro('en proceso')"
                        :class="filtro === 'en proceso' ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' :
                            'text-slate-400 hover:text-white'"
                        class="px-5 py-2 rounded-xl text-xs font-semibold transition whitespace-nowrap">
                        En proceso
                    </button>

                    <button type="button" @click="cambiarFiltro('solucionado')"
                        :class="filtro === 'solucionado' ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' :
                            'text-slate-400 hover:text-white'"
                        class="px-5 py-2 rounded-xl text-xs font-semibold transition whitespace-nowrap">
                        Solucionados
                    </button>

                    <button type="button" @click="cambiarFiltro('cancelado')"
                        :class="filtro === 'cancelado' ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' :
                            'text-slate-400 hover:text-white'"
                        class="px-5 py-2 rounded-xl text-xs font-semibold transition whitespace-nowrap">
                        Cancelados
                    </button>
                </div>

                <div class="flex items-center gap-3">
                    <div class="relative flex-1 sm:w-64">
                        <i data-lucide="search" class="absolute left-3.5 top-3 w-4 h-4 text-slate-500"></i>
                        <input type="text" x-model="busqueda" @keydown.enter.prevent="buscarTickets()"
                            placeholder="Buscar..." autocomplete="off"
                            class="w-full bg-[#0b1026] border border-slate-800 text-xs rounded-xl pl-10 pr-10 py-2.5 text-slate-200 placeholder-slate-500 focus:outline-none focus:border-blue-500 transition">
                        <button type="button" x-show="busqueda.trim() !== ''" x-cloak @click="limpiarBusqueda()"
                            class="absolute right-3 top-2.5 text-slate-500 hover:text-white transition"
                            title="Limpiar búsqueda">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>

                    <div class="relative" x-data="{ abierto: false }">
                        <button type="button" @click="abierto = !abierto"
                            class="flex items-center gap-2 bg-[#0b1026] border border-slate-800 text-xs font-medium text-slate-300 px-4 py-2.5 rounded-xl hover:bg-slate-800/50 transition">
                            <i data-lucide="calendar-days" class="w-4 h-4 text-slate-400"></i>
                            <span>Este mes</span>
                            <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform"
                                :class="abierto ? 'rotate-180' : ''"></i>
                        </button>

                        <div x-show="abierto" x-cloak @click.outside="abierto = false" x-transition
                            class="absolute right-0 mt-2 w-48 bg-[#0b1026] border border-slate-800 rounded-xl shadow-2xl z-50 overflow-hidden">
                            <button type="button" @click="abierto = false"
                                class="w-full flex items-center gap-2 px-4 py-3 text-xs text-white bg-blue-600/20 hover:bg-blue-600/30 transition">
                                <i data-lucide="calendar-days" class="w-4 h-4 text-blue-400"></i>
                                <span>Este mes</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="bg-[#0b1026]/90 border border-slate-800/80 rounded-2xl overflow-hidden shadow-2xl backdrop-blur-md">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="border-b border-slate-800 text-[11px] uppercase tracking-wider text-slate-400 font-semibold bg-slate-900/30">
                                <th class="py-4 px-6">Folio</th>
                                <th class="py-4 px-6">Título</th>
                                <th class="py-4 px-6">Tipo de falla</th>
                                <th class="py-4 px-6">Prioridad</th>
                                <th class="py-4 px-6">Estado</th>
                                <th class="py-4 px-6">Tomado por</th>
                                <th class="py-4 px-6">Fecha</th>
                                <th class="py-4 px-6 text-right">Acción</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-800/60 text-xs">
                            @forelse ($tickets as $ticket)
                                @php
                                    $ticket->load([
                                        'user',
                                        'user.departamento',
                                        'user.departamento.oficina',
                                        'user.departamento.oficina.empresa',
                                        'historialComentarios.usuario',
                                        'tomadoPor',
                                    ]);

                                    $ticketData = $ticket->toArray();

                                    $comentariosData = $ticket->historialComentarios
                                        ->map(function ($comentario) {
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
                                                    ? strtoupper(pathinfo($comentario->archivo, PATHINFO_EXTENSION))
                                                    : null,
                                                'usuario' => [
                                                    'login' => $comentario->usuario?->login,
                                                    'name' => $comentario->usuario?->name ?? 'Usuario',
                                                    'role' => $comentario->usuario?->role ?? 'Usuario',
                                                    'picture' => $comentario->usuario?->picture
                                                        ? Storage::url($comentario->usuario->picture)
                                                        : null,
                                                ],
                                                'fecha' => $comentario->created_at
                                                    ? $comentario->created_at->format('d M Y h:i A')
                                                    : '',
                                            ];
                                        })
                                        ->values();

                                    $iconoFalla = match (strtolower($ticket->tipo_falla ?? '')) {
                                        'hardware' => 'cpu',
                                        'software' => 'code-2',
                                        'redes' => 'network',
                                        'impresora', 'impresión' => 'printer',
                                        'correo' => 'mail',
                                        'internet' => 'globe',
                                        'telefonía', 'telefonia' => 'phone',
                                        'sistema' => 'monitor-cog',
                                        default => 'ticket',
                                    };

                                    $prioridad = strtolower($ticket->prioridad ?? 'normal');

                                    $configPrioridad = match ($prioridad) {
                                        'critica', 'crítica' => [
                                            'icono' => 'alert-octagon',
                                            'texto' => 'text-red-400',
                                            'fondo' => 'bg-red-500/10',
                                            'borde' => 'border-red-500/30',
                                        ],
                                        'alta' => [
                                            'icono' => 'chevrons-up',
                                            'texto' => 'text-orange-400',
                                            'fondo' => 'bg-orange-500/10',
                                            'borde' => 'border-orange-500/30',
                                        ],
                                        'media' => [
                                            'icono' => 'chevron-up',
                                            'texto' => 'text-yellow-400',
                                            'fondo' => 'bg-yellow-500/10',
                                            'borde' => 'border-yellow-500/30',
                                        ],
                                        'normal' => [
                                            'icono' => 'minus',
                                            'texto' => 'text-green-400',
                                            'fondo' => 'bg-green-500/10',
                                            'borde' => 'border-green-500/30',
                                        ],
                                        default => [
                                            'icono' => 'circle-help',
                                            'texto' => 'text-slate-400',
                                            'fondo' => 'bg-slate-500/10',
                                            'borde' => 'border-slate-500/30',
                                        ],
                                    };

                                    $tomadoPorData = $ticket->tomadoPor
                                        ? [
                                            'login' => $ticket->tomadoPor->login,
                                            'name' => $ticket->tomadoPor->name,
                                            'picture' => $ticket->tomadoPor->picture,
                                            'departamento' => $ticket->tomadoPor->departamento?->nombre,
                                        ]
                                        : null;
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
                                                'login' => $ticket->tomadoPor->login,
                                                'name' => $ticket->tomadoPor->name,
                                                'picture' => $ticket->tomadoPor->picture,
                                            ]
                                            : null,
                                        'user' => $ticket->user
                                            ? [
                                                'login' => $ticket->user->login,
                                                'name' => $ticket->user->name,
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
                                }" x-show="mostrarTicket(ticket.estado, ticket)" x-transition
                                    class="hover:bg-slate-800/20 transition">

                                    <td class="py-4 px-6 font-bold text-white whitespace-nowrap">{{ $ticket->folio }}
                                    </td>

                                    <td class="py-4 px-6 font-medium text-slate-200 min-w-[200px]">
                                        {{ $ticket->titulo }}</td>

                                    <td class="py-4 px-6 text-slate-300 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="{{ $iconoFalla }}" class="w-4 h-4 text-slate-400"></i>
                                            <span>{{ $ticket->tipo_falla ?? 'Sin especificar' }}</span>
                                        </div>
                                    </td>

                                    <td class="py-4 px-6 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-semibold border {{ $configPrioridad['texto'] }} {{ $configPrioridad['fondo'] }} {{ $configPrioridad['borde'] }}">
                                            <i data-lucide="{{ $configPrioridad['icono'] }}" class="w-3 h-3"></i>
                                            {{ ucfirst($ticket->prioridad ?? 'Normal') }}
                                        </span>
                                    </td>

                                    <td class="py-4 px-6 whitespace-nowrap">
                                        <template
                                            x-if="(ticketsActualizados[{{ $ticket->id }}]?.estado ?? '{{ strtolower($ticket->estado ?? '') }}') === 'solucionado'">
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#133d28] text-green-300 border border-green-500/40">
                                                ✓ Solucionado
                                            </span>
                                        </template>

                                        <template
                                            x-if="(ticketsActualizados[{{ $ticket->id }}]?.estado ?? '{{ strtolower($ticket->estado ?? '') }}') === 'pendiente'">
                                            <span
                                                class="inline-flex items-center justify-center min-w-[100px] px-3 py-1 rounded-full text-xs font-semibold bg-[#4a4213] text-yellow-300 border border-yellow-500/40">
                                                <span class="w-2 h-2 rounded-full bg-orange-400 mr-2"></span>
                                                Pendiente
                                            </span>
                                        </template>

                                        <template
                                            x-if="(ticketsActualizados[{{ $ticket->id }}]?.estado ?? '{{ strtolower($ticket->estado ?? '') }}') === 'en proceso'">
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#1d2757] text-blue-300 border border-blue-500/40">
                                                <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                                                En proceso
                                            </span>
                                        </template>

                                        <template
                                            x-if="(ticketsActualizados[{{ $ticket->id }}]?.estado ?? '{{ strtolower($ticket->estado ?? '') }}') === 'cancelado'">
                                            <span
                                                class="inline-flex items-center justify-center min-w-[100px] px-3 py-1 rounded-full text-xs font-semibold bg-[#4d1616] text-red-300 border border-red-500/40">
                                                Cancelado
                                            </span>
                                        </template>
                                    </td>

                                    <td class="py-4 px-6 text-slate-400 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-full border border-blue-500/20 overflow-hidden shrink-0">
                                                <img :src="(() => {
                                                    const datos = obtenerDatosTicket({
                                                        id: {{ $ticket->id }},
                                                        tomado_por: @js($tomadoPorData)
                                                    });
                                                
                                                    return datos.tomado_por?.picture ?
                                                        '{{ asset('storage') }}/' + datos.tomado_por.picture :
                                                        '{{ asset('storage/profile-photos/user.png') }}';
                                                })()"
                                                    class="w-full h-full object-cover" alt="Usuario">
                                            </div>

                                            <div class="flex flex-col min-w-0">
                                                <span class="text-slate-300 font-medium truncate"
                                                    x-text="(() => {
                                                            const datos = obtenerDatosTicket({
                                                                id: {{ $ticket->id }},
                                                                tomado_por: @js($tomadoPorData)
                                                            });
                                                            return nombreTomadoPor(datos);
                                                        })()">
                                                    {{ $ticket->tomadoPor?->name ?? '—————' }}
                                                </span>

                                                <span class="text-[10px] text-slate-500"
                                                    x-text="(() => {
                                                            const datos = obtenerDatosTicket({
                                                                id: {{ $ticket->id }},
                                                                tomado_por: @js($tomadoPorData)
                                                            });
                                                            const tecnico = obtenerTecnico(datos);
                                                            return tecnico
                                                                ? (tecnico.departamento?.nombre || tecnico.departamento || 'Tecnologías')
                                                                : '';
                                                        })()">
                                                    @if ($ticket->tomadoPor)
                                                        {{ $ticket->tomadoPor->departamento?->nombre ?? 'Tecnologías' }}
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="py-4 px-6 text-slate-400 whitespace-nowrap">
                                        <div class="font-medium text-slate-300">
                                            {{ $ticket->created_at->format('d M Y') }}</div>
                                        <div class="text-[10px] text-slate-500">
                                            {{ $ticket->created_at->format('h:i A') }}</div>
                                    </td>

                                    <td class="py-4 px-6 text-right whitespace-nowrap">
                                        <button type="button" @click="abrirTicket({{ Js::from($ticketData) }})"
                                            class="text-slate-400 hover:text-blue-400 p-2 rounded-lg hover:bg-blue-500/10 transition"
                                            title="Ver ticket">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </button>

                                        <template x-if="esMiTicket(ticket)">
                                            <button type="button"
                                                @click="abrirModalSolucion(
                                                        obtenerDatosTicket(ticket),
                                                        ['solucionado', 'cancelado'].includes(String(ticket.estado).toLowerCase())
                                                    )"
                                                :disabled="!(
                                                    ['mis tickets', 'solucionado', 'cancelado'].includes(filtro) ||
                                                    (filtro === 'todos' && ['solucionado', 'cancelado'].includes(
                                                        String(ticket.estado).toLowerCase()))
                                                )"
                                                :class="!(
                                                    ['mis tickets', 'solucionado', 'cancelado'].includes(filtro) ||
                                                    (
                                                        filtro === 'todos' && ['solucionado', 'cancelado'].includes(
                                                            String(ticket.estado).toLowerCase())
                                                    )
                                                ) ?
                                                'opacity-40 cursor-not-allowed' :
                                                'text-emerald-400 hover:text-emerald-300 hover:bg-emerald-500/10'"
                                                class="p-2 rounded-lg transition"
                                                :title="filtro === 'mis tickets'
                                                    ?
                                                    'Resolver ticket' :
                                                    'Ver solución'">
                                                <i data-lucide="hand" class="w-4 h-4"></i>
                                            </button>
                                        </template>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-12 text-center text-gray-400">No tienes tickets
                                        registrados.</td>
                                </tr>
                            @endforelse

                            <tr x-show="hayBusquedaYNoHayResultados()" x-cloak>
                                <td colspan="8" class="py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div
                                            class="w-14 h-14 rounded-full bg-slate-800/60 flex items-center justify-center mb-4">
                                            <i data-lucide="search-x" class="w-6 h-6 text-slate-500"></i>
                                        </div>
                                        <p class="text-sm font-semibold text-slate-300">No se encontraron tickets</p>
                                        <p class="text-xs text-slate-500 mt-1">Intenta cambiar el filtro o realizar
                                            otra búsqueda.</p>
                                        <button type="button" @click="limpiarFiltros()"
                                            class="mt-4 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-xs font-semibold text-white transition">
                                            Limpiar filtros
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-8 px-2 sm:px-4 pb-2">
                    <div
                        class="pt-5 border-t border-slate-800/80 flex flex-col sm:flex-row justify-between items-center gap-5">
                        <span class="text-xs text-slate-400 text-center sm:text-left">
                            Mostrando
                            <span class="text-slate-200 font-medium">{{ $tickets->firstItem() ?? 0 }}</span>
                            a
                            <span class="text-slate-200 font-medium">{{ $tickets->lastItem() ?? 0 }}</span>
                            de
                            <span class="text-slate-200 font-medium">{{ $tickets->total() }}</span>
                            tickets
                        </span>

                        <div class="flex items-center gap-2">
                            @if ($tickets->onFirstPage())
                                <span
                                    class="w-9 h-9 bg-slate-900 text-slate-600 rounded-xl flex items-center justify-center">
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                </span>
                            @else
                                <a href="{{ $tickets->previousPageUrl() }}"
                                    class="w-9 h-9 bg-slate-800 text-slate-400 rounded-xl hover:bg-slate-700 hover:text-white flex items-center justify-center transition-all duration-200">
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                </a>
                            @endif

                            @foreach ($tickets->getUrlRange(max(1, $tickets->currentPage() - 2), min($tickets->lastPage(), $tickets->currentPage() + 2)) as $page => $url)
                                <a href="{{ $url }}"
                                    class="w-9 h-9 rounded-xl flex items-center justify-center text-xs transition-all duration-200 {{ $page == $tickets->currentPage() ? 'bg-blue-600 text-white font-bold shadow-lg shadow-blue-600/20' : 'bg-slate-800 text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                                    {{ $page }}
                                </a>
                            @endforeach

                            @if ($tickets->hasMorePages())
                                <a href="{{ $tickets->nextPageUrl() }}"
                                    class="w-9 h-9 bg-slate-800 text-slate-400 rounded-xl hover:bg-slate-700 hover:text-white flex items-center justify-center transition-all duration-200">
                                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                </a>
                            @else
                                <span
                                    class="w-9 h-9 bg-slate-900 text-slate-600 rounded-xl flex items-center justify-center">
                                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <template x-teleport="body">
                    <div x-show="openModalSolucion" x-cloak x-transition.opacity
                        class="fixed inset-0 z-[100000] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4"
                        @keydown.escape.window="cerrarModalSolucion()" @click.self="cerrarModalSolucion()">
                        <div x-show="openModalSolucion" x-transition @click.stop
                            class="relative flex flex-col w-full max-w-3xl max-h-[90vh] bg-[#030712] border border-emerald-500/30 rounded-3xl shadow-2xl overflow-hidden">
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
                                        <p class="text-xs text-slate-400 mt-1"
                                            x-text="modalSolucionSoloLectura ? 'Consulta la información registrada de este ticket.' : 'Registra la solución y solicita la firma de conformidad.'">
                                        </p>
                                    </div>
                                    <button type="button" @click="cerrarModalSolucion()"
                                        class="p-2 text-slate-400 hover:text-white rounded-xl hover:bg-slate-800 transition shrink-0">
                                        <i data-lucide="x" class="w-5 h-5"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="flex-1 min-h-0 overflow-y-auto custom-scrollbar">
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
                                            :class="ticketSolucion?.estado === 'solucionado' ? 'text-emerald-400' :
                                                ticketSolucion?.estado === 'cancelado' ? 'text-red-400' :
                                                ticketSolucion?.estado === 'en proceso' ? 'text-amber-400' :
                                                'text-slate-400'"
                                            x-text="ticketSolucion?.estado === 'solucionado' ? 'Solucionado' : ticketSolucion?.estado === 'cancelado' ? 'Cancelado' : ticketSolucion?.estado === 'en proceso' ? 'En proceso' : 'Pendiente'">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-300 mb-2">
                                            Solución aplicada
                                        </label>
                                        <textarea x-model="solucionForm.solucion" rows="5" :readonly="modalSolucionSoloLectura"
                                            placeholder="Describe la solución aplicada al problema..."
                                            class="w-full bg-[#060c21] border border-slate-800 rounded-xl px-4 py-3 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 transition resize-none"
                                            :class="modalSolucionSoloLectura ? 'opacity-70 cursor-default' : ''"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-300 mb-2">
                                            Evidencias de la solución
                                        </label>
                                        <div x-show="!modalSolucionSoloLectura">
                                            <div
                                                class="relative border-2 border-dashed border-slate-700 hover:border-emerald-500/50 rounded-xl p-5 transition bg-[#060c21]">
                                                <input type="file" x-ref="evidenciaInput"
                                                    @change="seleccionarEvidencias($event)" multiple
                                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                                    accept="image/*,.pdf,.mp4,.webm,application/pdf,video/mp4,video/webm">
                                                <div
                                                    class="flex flex-col items-center justify-center text-center pointer-events-none">
                                                    <div
                                                        class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center mb-3">
                                                        <i data-lucide="upload-cloud"
                                                            class="w-6 h-6 text-emerald-400"></i>
                                                    </div>
                                                    <p class="text-sm font-semibold text-white">
                                                        Seleccionar evidencias
                                                    </p>
                                                    <p class="text-[11px] text-slate-500 mt-1">
                                                        Puedes seleccionar varios archivos
                                                    </p>
                                                    <p class="text-[10px] text-slate-600 mt-2">
                                                        Imágenes, PDF, MP4 o WebM
                                                    </p>
                                                </div>
                                            </div>
                                            <div x-show="Array.isArray(evidenciasSolucion) && evidenciasSolucion.length > 0"
                                                x-cloak class="mt-4 space-y-2">
                                                <div class="flex items-center justify-between">
                                                    <span
                                                        class="text-[10px] uppercase tracking-wider font-bold text-slate-500">
                                                        Archivos seleccionados
                                                    </span>
                                                    <span class="text-[10px] font-bold text-emerald-400"
                                                        x-text="evidenciasSolucion.length + (evidenciasSolucion.length === 1 ? ' archivo' : ' archivos')">
                                                    </span>
                                                </div>
                                                <template x-for="(archivo, index) in evidenciasSolucion"
                                                    :key="`${archivo.name}-${archivo.size}-${archivo.lastModified}-${index}`">
                                                    <div
                                                        class="rounded-xl bg-[#060c21] border border-slate-800 overflow-hidden">
                                                        <div x-show="esImagen(archivo)"
                                                            class="p-3 border-b border-slate-800">
                                                            <div
                                                                class="rounded-lg overflow-hidden bg-black/30 flex items-center justify-center">
                                                                <img :src="archivoUrl(archivo)" :alt="archivo.name"
                                                                    class="max-h-64 max-w-full object-contain rounded-lg">
                                                            </div>
                                                        </div>
                                                        <div x-show="esPDF(archivo)"
                                                            class="border-b border-slate-800">
                                                            <iframe :src="archivoUrl(archivo)"
                                                                class="w-full h-64 bg-white"
                                                                title="Vista previa PDF"></iframe>
                                                        </div>
                                                        <div x-show="esVideo(archivo)"
                                                            class="p-3 border-b border-slate-800">
                                                            <div
                                                                class="rounded-lg overflow-hidden bg-black/30 flex items-center justify-center">
                                                                <video :src="archivoUrl(archivo)" controls
                                                                    class="w-full max-h-64 rounded-lg"></video>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center justify-between gap-3 p-3">
                                                            <div class="flex items-center gap-3 min-w-0">
                                                                <div
                                                                    class="w-10 h-10 rounded-lg bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center shrink-0">
                                                                    <i :data-lucide="esVideo(archivo) ? 'video' : esPDF(archivo) ?
                                                                        'file-text' : esImagen(archivo) ? 'image' :
                                                                        'file-check-2'"
                                                                        class="w-5 h-5 text-emerald-400"></i>
                                                                </div>
                                                                <div class="min-w-0">
                                                                    <p class="text-xs text-white font-medium truncate"
                                                                        x-text="archivo.name || nombreArchivo(archivo)">
                                                                    </p>
                                                                    <div class="flex items-center gap-2 mt-0.5">
                                                                        <p class="text-[10px] text-slate-500"
                                                                            x-text="formatearTamano(archivo.size)">
                                                                        </p>
                                                                        <span class="text-slate-700">•</span>
                                                                        <p class="text-[10px] text-slate-500 truncate"
                                                                            x-text="archivo.type || tipoArchivo(archivo)">
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <button type="button"
                                                                @click.stop="eliminarEvidencia(index)"
                                                                class="p-2 rounded-lg text-slate-500 hover:text-red-400 hover:bg-red-500/10 transition shrink-0"
                                                                title="Eliminar evidencia">
                                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                        <div x-show="modalSolucionSoloLectura">
                                            <div x-show="Array.isArray(evidenciasSolucion) && evidenciasSolucion.length > 0"
                                                class="space-y-2">
                                                <div class="flex items-center justify-between mb-2">
                                                    <span
                                                        class="text-[10px] uppercase tracking-wider font-bold text-slate-500">
                                                        Evidencias registradas
                                                    </span>
                                                    <span class="text-[10px] font-bold text-emerald-400"
                                                        x-text="evidenciasSolucion.length + (evidenciasSolucion.length === 1 ? ' archivo' : ' archivos')">
                                                    </span>
                                                </div>
                                                <template x-for="(archivo, index) in evidenciasSolucion"
                                                    :key="index">
                                                    <a :href="archivo.url || archivo.url_archivo || archivoUrl(archivo)"
                                                        target="_blank" rel="noopener noreferrer"
                                                        class="flex items-center justify-between gap-3 px-3 py-3 rounded-xl bg-[#060c21] border border-slate-800 hover:border-emerald-500/40 transition">
                                                        <div class="flex items-center gap-3 min-w-0">
                                                            <div
                                                                class="w-9 h-9 rounded-lg bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center shrink-0">
                                                                <i :data-lucide="esVideo(archivo) ? 'video' : esPDF(archivo) ?
                                                                    'file-text' : esImagen(archivo) ? 'image' : 'file'"
                                                                    class="w-4 h-4 text-emerald-400"></i>
                                                            </div>
                                                            <div class="min-w-0">
                                                                <p class="text-xs text-white truncate"
                                                                    x-text="archivo.nombre || archivo.name || nombreArchivo(archivo)">
                                                                </p>
                                                                <p class="text-[10px] text-slate-500"
                                                                    x-text="tipoArchivo(archivo)">
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <i data-lucide="external-link"
                                                            class="w-4 h-4 text-slate-500 shrink-0"></i>
                                                    </a>
                                                </template>
                                            </div>
                                            <div x-show="!Array.isArray(evidenciasSolucion) || evidenciasSolucion.length === 0"
                                                class="px-3 py-3 rounded-xl bg-[#060c21] border border-slate-800">
                                                <p class="text-xs text-slate-500">
                                                    No hay evidencias registradas.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-300 mb-3">
                                            ¿El problema fue solucionado?
                                        </label>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            <button type="button"
                                                @click="!modalSolucionSoloLectura && (solucionForm.solucionado = true)"
                                                :disabled="modalSolucionSoloLectura"
                                                :class="solucionForm.solucionado === true ?
                                                    'bg-emerald-500/15 border-emerald-500/50 text-emerald-300' :
                                                    'bg-[#060c21] border-slate-800 text-slate-400'"
                                                class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border transition disabled:opacity-70 disabled:cursor-default">
                                                <i data-lucide="circle-check" class="w-4 h-4"></i>
                                                Sí, fue solucionado
                                            </button>
                                            <button type="button"
                                                @click="!modalSolucionSoloLectura && (solucionForm.solucionado = false)"
                                                :disabled="modalSolucionSoloLectura"
                                                :class="solucionForm.solucionado === false ?
                                                    'bg-red-500/15 border-red-500/50 text-red-300' :
                                                    'bg-[#060c21] border-slate-800 text-slate-400'"
                                                class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border transition disabled:opacity-70 disabled:cursor-default">
                                                <i data-lucide="circle-x" class="w-4 h-4"></i>
                                                No fue solucionado
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-300 mb-2">
                                            Fecha de solución
                                        </label>
                                        <input type="datetime-local" x-model="solucionForm.fecha_solucion"
                                            :disabled="modalSolucionSoloLectura"
                                            class="w-full bg-[#060c21] border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-emerald-500 transition disabled:opacity-70">
                                    </div>
                                    <div class="border-t border-slate-800 pt-6">
                                        <h3 class="text-sm font-bold text-white mb-1">
                                            Conformidad del usuario
                                        </h3>
                                        <p class="text-[11px] text-slate-500 mb-5"
                                            x-text="modalSolucionSoloLectura ? 'Información registrada al momento de cerrar el ticket.' : 'La persona que levantó el ticket deberá confirmar que recibió atención.'">
                                        </p>
                                        <div class="mb-5">
                                            <label class="block text-xs font-semibold text-slate-300 mb-2">
                                                Persona que levantó el ticket
                                            </label>
                                            <div
                                                class="w-full bg-[#060c21] border border-slate-800 rounded-xl px-4 py-3 text-xs text-white">
                                                <span
                                                    x-text="ticketSolucion?.user?.name ?? solucionForm.nombre_firmante ?? 'Sin nombre'"
                                                    class="font-medium">
                                                </span>
                                            </div>
                                        </div>
                                        <div>
                                            <div x-show="!modalSolucionSoloLectura">
                                                <div class="flex items-center justify-between mb-2">
                                                    <label class="block text-xs font-semibold text-slate-300">
                                                        Firma
                                                    </label>
                                                    <button type="button" @click="limpiarFirma()"
                                                        class="text-[10px] text-slate-500 hover:text-red-400 transition">
                                                        Limpiar firma
                                                    </button>
                                                </div>
                                                <div
                                                    class="bg-white rounded-xl overflow-hidden border border-slate-700">
                                                    <canvas x-ref="canvasFirma" width="900" height="220"
                                                        class="w-full h-40 sm:h-48 cursor-crosshair touch-none"></canvas>
                                                </div>
                                                <p class="text-[10px] text-slate-500 mt-2">
                                                    Firma dentro del recuadro utilizando el mouse o pantalla táctil.
                                                </p>
                                            </div>
                                            <div x-show="modalSolucionSoloLectura">
                                                <label class="block text-xs font-semibold text-slate-300 mb-2">
                                                    Firma
                                                </label>
                                                <div
                                                    class="bg-white rounded-xl overflow-hidden border border-slate-700 p-3 min-h-[160px] flex items-center justify-center">
                                                    <img x-show="ticketSolucion?.solucion?.firma || ticketSolucion?.solucion?.url_firma"
                                                        :src="ticketSolucion?.solucion?.url_firma ?? archivoUrl(ticketSolucion
                                                            ?.solucion?.firma)"
                                                        alt="Firma registrada"
                                                        class="max-w-full max-h-40 object-contain"
                                                        x-on:error="console.error('No se pudo cargar la firma:', $event.target.src)">
                                                    <span
                                                        x-show="!ticketSolucion?.solucion?.firma && !ticketSolucion?.solucion?.url_firma"
                                                        class="text-slate-500 text-xs">
                                                        No hay una firma registrada.
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-5">
                                            <label class="block text-xs font-semibold text-slate-300 mb-2">
                                                Fecha de firma
                                            </label>
                                            <input type="datetime-local" x-model="solucionForm.fecha_firma"
                                                :disabled="modalSolucionSoloLectura"
                                                class="w-full bg-[#060c21] border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-emerald-500 transition disabled:opacity-70">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="shrink-0 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3 px-6 py-4 border-t border-slate-800 bg-[#030712]">
                                <button type="button" @click="cerrarModalSolucion()"
                                    class="w-full sm:w-auto px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-400 hover:text-white hover:bg-slate-800 transition text-center">
                                    Cerrar
                                </button>
                                <button x-show="!modalSolucionSoloLectura" type="button" @click="guardarSolucion()"
                                    :disabled="guardandoSolucion"
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold transition disabled:opacity-50 disabled:cursor-not-allowed">
                                    <i data-lucide="save" class="w-4 h-4"></i>
                                    <span x-text="guardandoSolucion ? 'Guardando...' : 'Guardar solución'">
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
                <template x-teleport="body">
                    <div x-show="openModal" x-cloak x-transition.opacity
                        class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4 sm:p-6 overflow-y-auto"
                        @keydown.escape.window="cerrarModal()" @click.self="cerrarModal()">

                        <div x-show="openModal" x-transition @click.stop
                            class="relative w-full max-w-7xl bg-[#030712] border border-blue-600/40 rounded-3xl shadow-2xl flex flex-col max-h-[92vh] text-slate-200 overflow-hidden">

                            <div
                                class="flex items-center justify-between p-6 pb-4 border-b border-slate-800/80 shrink-0">
                                <div>
                                    <h2 class="text-2xl font-bold text-white tracking-wide">Detalle del ticket</h2>
                                    <p class="text-xs text-slate-400 mt-0.5">Consulta toda la información y el
                                        seguimiento de este ticket.</p>
                                </div>

                                <button type="button" @click="cerrarModal()"
                                    class="p-2 text-slate-400 hover:text-white rounded-xl hover:bg-slate-800/60 transition">
                                    <i data-lucide="x" class="w-6 h-6"></i>
                                </button>
                            </div>

                            <div class="p-6 overflow-y-auto custom-scrollbar flex-1 space-y-6">
                                <div
                                    class="grid grid-cols-2 md:grid-cols-5 gap-4 p-4 rounded-2xl bg-[#060c21] border border-blue-500/40">
                                    <div class="border-r border-slate-800/60 pr-2">
                                        <span class="text-[11px] font-semibold text-blue-400 block mb-1">Folio</span>
                                        <span class="text-sm font-bold text-white"
                                            x-text="selectedTicket?.folio ?? '—'"></span>
                                    </div>

                                    <div class="border-r border-slate-800/60 pr-2">
                                        <span
                                            class="text-[11px] font-semibold text-blue-400 block mb-1">Prioridad</span>
                                        <span class="text-sm font-bold text-white"
                                            x-text="capitalizar(selectedTicket?.prioridad)"></span>
                                    </div>

                                    <div class="border-r border-slate-800/60 pr-2">
                                        <span class="text-[11px] font-semibold text-blue-400 block mb-1">Estado</span>
                                        <span class="text-sm font-bold text-white"
                                            x-text="capitalizar(selectedTicket?.estado)"></span>
                                    </div>

                                    <div class="border-r border-slate-800/60 pr-2">
                                        <span class="text-[11px] font-semibold text-blue-400 block mb-1">Tomado
                                            por</span>
                                        <span class="text-sm font-bold text-white"
                                            x-text="nombreTomadoPor(selectedTicket)"></span>
                                    </div>

                                    <div>
                                        <span class="text-[11px] font-semibold text-blue-400 block mb-1">Fecha</span>
                                        <span class="text-xs font-bold text-slate-200"
                                            x-text="formatearFecha(selectedTicket?.created_at)"></span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                                    <div class="lg:col-span-6 space-y-5">
                                        <div class="p-5 rounded-2xl bg-[#060c21] border border-blue-500/30 space-y-4">
                                            <div class="flex items-center gap-2 border-b border-slate-800 pb-3">
                                                <i data-lucide="receipt" class="w-4 h-4 text-slate-300"></i>
                                                <h3 class="text-sm font-bold text-white">Resumen del ticket</h3>
                                            </div>

                                            <div class="space-y-3 text-xs">
                                                <div class="flex justify-between items-start">
                                                    <span class="text-slate-400 font-semibold">Título</span>
                                                    <span class="text-white font-medium text-right max-w-[220px]"
                                                        x-text="selectedTicket?.titulo ?? '—'"></span>
                                                </div>

                                                <div class="flex justify-between items-center">
                                                    <span class="text-slate-400 font-semibold">Tipo de falla:</span>
                                                    <div class="flex items-center gap-1.5 text-slate-200 font-medium">
                                                        <i data-lucide="ticket"
                                                            class="w-3.5 h-3.5 text-slate-400"></i>
                                                        <span
                                                            x-text="selectedTicket?.tipo_falla ?? 'Sin especificar'"></span>
                                                    </div>
                                                </div>

                                                <div x-show="selectedTicket?.tipo_falla === 'Equipo'" x-cloak
                                                    class="flex justify-between items-center">
                                                    <span class="text-slate-400 font-semibold">Equipo:</span>
                                                    <div class="flex items-center gap-1.5 text-slate-200 font-medium">
                                                        <i data-lucide="laptop"
                                                            class="w-3.5 h-3.5 text-slate-400"></i>
                                                        <span
                                                            x-text="selectedTicket?.equipo ?? 'No especificado'"></span>
                                                    </div>
                                                </div>

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
                                                            selectedTicket.user.picture :
                                                            '{{ asset('storage/profile-photos/user.png') }}'"
                                                            :alt="selectedTicket?.user?.name ?? selectedTicket?.usuario
                                                                ?.name ?? 'Usuario'"
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

                                                <div class="flex justify-between items-center">
                                                    <span class="text-slate-400 font-semibold">Departamento:</span>
                                                    <span class="text-slate-300"
                                                        x-text="selectedTicket?.user?.departamento?.nombre ?? 'Sin especificar'"></span>
                                                </div>

                                                <div class="flex justify-between items-center">
                                                    <span class="text-slate-400 font-semibold">Empresa:</span>
                                                    <span class="text-slate-300"
                                                        x-text="selectedTicket?.user?.departamento?.oficina?.empresa?.empresa ?? 'Sin especificar'"></span>
                                                </div>

                                                <div class="flex justify-between items-center">
                                                    <span class="text-slate-400 font-semibold">Oficina:</span>
                                                    <span class="text-slate-300"
                                                        x-text="selectedTicket?.user?.departamento?.oficina?.nombre ?? 'Sin especificar'"></span>
                                                </div>

                                                <div class="flex justify-between items-center">
                                                    <span class="text-slate-400 font-semibold">Ubicación:</span>
                                                    <span class="text-slate-300"
                                                        x-text="selectedTicket?.ubicacion ?? 'Sin especificar'"></span>
                                                </div>

                                                <div class="pt-2 space-y-2">
                                                    <div
                                                        class="p-2.5 rounded-xl bg-[#030712] border border-slate-800/80">
                                                        <div class="text-[10px] text-slate-400 font-semibold mb-0.5">
                                                            Fecha en que fue levantado</div>
                                                        <div class="text-xs text-slate-200 font-medium"
                                                            x-text="formatearFecha(selectedTicket?.created_at)"></div>
                                                    </div>

                                                    <div
                                                        class="p-2.5 rounded-xl bg-[#030712] border border-slate-800/80">
                                                        <div
                                                            class="flex items-center gap-1.5 text-[10px] text-slate-400 font-semibold mb-0.5">
                                                            <i data-lucide="alarm-clock"
                                                                class="w-3 h-3 text-slate-400"></i>
                                                            <span>Fecha en que fue tomado</span>
                                                        </div>
                                                        <div class="text-xs text-slate-400"
                                                            x-text="selectedTicket?.fecha_tomado ? formatearFecha(selectedTicket.fecha_tomado) : 'Aún sin tomar'">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-5 rounded-2xl bg-[#060c21] border border-blue-500/30">
                                            <h3 class="text-xs font-bold text-white mb-2">Descripción del problema</h3>
                                            <p class="text-xs text-slate-300 leading-relaxed whitespace-pre-line"
                                                x-text="selectedTicket?.descripcion ?? 'Sin descripción'"></p>
                                        </div>

                                        <div class="p-5 rounded-2xl bg-[#060c21] border border-blue-500/30">
                                            <h3 class="text-xs font-bold text-white mb-2">Información adicional</h3>
                                            <p class="text-xs text-slate-300 leading-relaxed whitespace-pre-line"
                                                x-text="selectedTicket?.informacion_adicional ?? selectedTicket?.comentarios ?? 'Sin información adicional'">
                                            </p>
                                        </div>

                                        <div class="p-5 rounded-2xl bg-[#060c21] border border-blue-500/30">
                                            <h3 class="text-xs font-bold text-white mb-3">Evidencia proporcionada</h3>

                                            <template x-if="evidencias.length > 0">
                                                <div class="flex items-center gap-3 overflow-x-auto pb-1">
                                                    <template x-for="(archivo, index) in evidencias"
                                                        :key="index">
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
                                                                            class="w-7 h-7 text-slate-500"></i>
                                                                    </div>
                                                                </template>
                                                            </div>
                                                            <div
                                                                class="flex items-center justify-between gap-1 text-[9px] text-slate-300 pt-2 border-t border-slate-800">
                                                                <span class="truncate"
                                                                    x-text="nombreArchivo(archivo)"></span>
                                                                <span
                                                                    class="px-1 py-0.5 rounded bg-blue-600 text-white font-bold text-[8px] shrink-0"
                                                                    x-text="extensionArchivo(archivo)"></span>
                                                            </div>
                                                        </a>
                                                    </template>
                                                </div>
                                            </template>

                                            <template x-if="evidencias.length === 0">
                                                <div
                                                    class="flex items-center justify-center py-6 text-slate-500 text-xs">
                                                    <div class="flex items-center gap-2">
                                                        <i data-lucide="file-x" class="w-4 h-4"></i>
                                                        <span>No se proporcionó evidencia.</span>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <div
                                        class="lg:col-span-6 p-5 rounded-2xl bg-[#060c21] border border-blue-500/30 flex flex-col min-h-[600px]">
                                        <div class="flex items-center gap-2 pb-4 border-b border-slate-800/80">
                                            <i data-lucide="message-square" class="w-4 h-4 text-slate-300"></i>
                                            <h3 class="text-sm font-bold text-white">Comentarios y seguimiento</h3>
                                        </div>

                                        <form id="formComentario" method="POST" enctype="multipart/form-data"
                                            class="flex items-end gap-3">
                                            @csrf

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

                                            <div class="relative flex-1">
                                                <input type="file" name="archivo" x-ref="fileInputModal"
                                                    @change="seleccionarArchivo($event)" class="hidden">

                                                <input type="text" name="mensaje"
                                                    placeholder="Escribe un comentario..." autocomplete="off"
                                                    class="w-full h-11 pl-4 pr-24 text-xs bg-[#030712] border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition">

                                                <div
                                                    class="absolute right-2 top-1/2 -translate-y-1/2 flex items-center gap-1">
                                                    <button type="button" @click="$refs.fileInputModal.click()"
                                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-blue-400 hover:bg-blue-500/10 transition"
                                                        title="Adjuntar archivo">
                                                        <i data-lucide="paperclip" class="w-4 h-4"></i>
                                                    </button>

                                                    <button type="submit" id="btnEnviarComentario"
                                                        class="h-8 px-3 flex items-center justify-center rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-[11px] font-semibold transition shadow-lg shadow-blue-900/20">
                                                        <i data-lucide="send" class="w-3.5 h-3.5 mr-1.5"></i>
                                                        Enviar
                                                    </button>
                                                </div>
                                            </div>
                                        </form>

                                        <template x-if="archivoAdjunto">
                                            <div
                                                class="mt-3 flex items-center gap-2 px-3 py-2 rounded-xl bg-blue-500/10 border border-blue-500/20">
                                                <i data-lucide="paperclip" class="w-4 h-4 text-blue-400"></i>
                                                <span class="text-[10px] text-slate-300 truncate"
                                                    x-text="archivoAdjunto?.name"></span>
                                                <button type="button" @click="quitarArchivo()"
                                                    class="ml-auto text-slate-500 hover:text-red-400">
                                                    <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </div>
                                        </template>

                                        <div id="listaComentarios"
                                            class="flex-1 space-y-5 mt-4 overflow-y-auto max-h-[460px] pr-2 custom-scrollbar">
                                            <template x-if="comentarios.length === 0">
                                                <div
                                                    class="flex flex-col items-center justify-center py-16 text-slate-500">
                                                    <div
                                                        class="w-12 h-12 rounded-full bg-slate-800/60 flex items-center justify-center mb-3">
                                                        <i data-lucide="message-square-off" class="w-5 h-5"></i>
                                                    </div>
                                                    <p class="text-xs">Aún no hay comentarios.</p>
                                                    <span class="text-[10px] text-slate-600 mt-1">Sé el primero en
                                                        agregar un comentario.</span>
                                                </div>
                                            </template>

                                            <template x-for="comentario in comentarios" :key="comentario.id">
                                                <div class="flex items-start gap-3">
                                                    <img :src="comentario.usuario?.picture ?
                                                        comentario.usuario.picture :
                                                        '{{ asset('storage/profile-photos/user.png') }}'"
                                                        class="w-8 h-8 rounded-full object-cover shrink-0 border border-blue-400/30"
                                                        :alt="comentario.usuario?.name || 'Usuario'"
                                                        x-on:error="
                                                            if ($event.target.dataset.fallback === 'user') {
                                                                $event.target.dataset.fallback = 'avatar';
                                                                $event.target.src = avatarUsuario(
                                                                    comentario.usuario?.name || 'Usuario'
                                                                );
                                                            } else {
                                                                $event.target.dataset.fallback = 'user';
                                                                $event.target.src = '{{ asset('storage/profile-photos/user.png') }}';
                                                            }
                                                        ">

                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                                                            <span class="text-xs font-bold text-white"
                                                                x-text="comentario.usuario?.name ?? 'Usuario'"></span>
                                                            <span
                                                                class="px-2 py-0.5 rounded-full text-[9px] font-semibold bg-indigo-600/30 text-indigo-300 border border-indigo-500/40"
                                                                x-text="comentario.usuario?.role ?? 'Usuario'"></span>
                                                            <span class="text-[10px] text-slate-500 ml-auto"
                                                                x-text="comentario.fecha ?? ''"></span>
                                                        </div>

                                                        <template x-if="comentario.mensaje">
                                                            <p class="text-xs text-slate-300 mb-2 whitespace-pre-line"
                                                                x-text="comentario.mensaje"></p>
                                                        </template>

                                                        <template
                                                            x-if="comentario.archivo && esImagen(comentario.archivo)">
                                                            <a :href="comentario.url_archivo" target="_blank"
                                                                class="block w-36 rounded-xl overflow-hidden bg-slate-900 border border-slate-700/80 hover:border-blue-500/60 transition">
                                                                <img :src="comentario.url_archivo"
                                                                    :alt="comentario.nombre_archivo"
                                                                    class="w-36 h-20 object-cover">
                                                                <div
                                                                    class="px-2 py-1.5 flex items-center justify-between gap-2 border-t border-slate-800">
                                                                    <span class="text-[9px] text-slate-300 truncate"
                                                                        x-text="comentario.nombre_archivo"></span>
                                                                    <span
                                                                        class="px-1 py-0.5 rounded bg-blue-600 text-white font-bold text-[8px] shrink-0"
                                                                        x-text="comentario.extension"></span>
                                                                </div>
                                                            </a>
                                                        </template>

                                                        <template
                                                            x-if="comentario.archivo && !esImagen(comentario.archivo)">
                                                            <a :href="comentario.url_archivo" target="_blank"
                                                                class="group block w-40 h-20 rounded-xl bg-slate-900 border border-slate-700/80 p-2 hover:border-blue-500/60 hover:bg-slate-800 transition">
                                                                <div class="flex items-center gap-2 mb-2">
                                                                    <div
                                                                        class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center">
                                                                        <i data-lucide="file"
                                                                            class="w-4 h-4 text-blue-400"></i>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="flex items-center justify-between gap-2 text-[9px] text-slate-300 pt-1 border-t border-slate-800">
                                                                    <span class="truncate"
                                                                        x-text="comentario.nombre_archivo"></span>
                                                                    <span
                                                                        class="px-1 py-0.5 rounded bg-blue-600 text-white font-bold text-[8px] shrink-0"
                                                                        x-text="comentario.extension"></span>
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

                            <div
                                class="p-5 border-t border-slate-800/80 bg-[#030712] flex items-center justify-between shrink-0">
                                <button type="button" @click="cerrarModal()"
                                    class="px-5 py-2.5 text-xs font-semibold text-slate-400 hover:text-white hover:bg-slate-800/60 rounded-xl transition-all duration-200">
                                    Cerrar
                                </button>

                                <button type="button" x-show="!tieneTomado(selectedTicket)" @click="tomarTicket()"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold border border-blue-500/40 shadow-lg shadow-blue-600/20 hover:shadow-blue-500/30 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
                                    <i data-lucide="hand" class="w-4 h-4"></i>
                                    <span>Tomar ticket</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <div>
                <!-- NOTIFICACIÓN -->
                <div x-cloak x-show="notificacionVisible" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-x-5"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 translate-x-5"
                    class="fixed right-5 top-5 z-[999999] w-full max-w-sm rounded-2xl border bg-[#0f1535] p-4"
                    :class="notificacionTipo === 'success'
                        ?
                        'border-green-500/30 shadow-[0_0_30px_rgba(34,197,94,0.20)]' :
                        'border-red-500/30 shadow-[0_0_30px_rgba(239,68,68,0.20)]'">
                    <div class="flex items-start gap-3">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl"
                            :class="notificacionTipo === 'success'
                                ?
                                'bg-green-500/10 text-green-400' :
                                'bg-red-500/10 text-red-400'">
                            <i :data-lucide="notificacionTipo === 'success'
                                ?
                                'check-circle' :
                                'circle-alert'"
                                class="h-5 w-5"></i>
                        </div>

                        <div class="min-w-0 flex-1">

                            <p class="text-sm font-semibold text-white"
                                x-text="
                    notificacionTipo === 'success'
                        ? 'Éxito'
                        : 'Error'
                ">
                            </p>

                            <p class="mt-1 text-xs text-slate-400 break-words" x-text="notificacionMensaje"></p>

                        </div>

                        <button type="button" @click="cerrarNotificacion()" class="text-slate-500 hover:text-white">
                            <i data-lucide="x" class="h-4 w-4"></i>
                        </button>

                    </div>
                </div>

            </div>
    </main>
</body>

</html>
