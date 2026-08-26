<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TicketPro - Solicitudes de cambio</title>

    <link rel="icon" type="image/png" href="{{ asset('storage/images/logo.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body x-data="{ menuMovil: false }" class="min-h-screen bg-[#070b19] text-white font-sans antialiased">

    <aside :class="menuMovil
        ?
        'translate-x-0' :
        '-translate-x-full md:translate-x-0'"
        class="fixed inset-y-0 left-0 z-[99999]
               w-[280px] max-w-[85vw]
               border-r border-slate-800/60
               bg-[#0a0f24]
               p-5 sm:p-6
               flex flex-col justify-between
               transition-transform duration-300">

        <div>

            {{-- LOGO --}}
            <div class="flex items-center justify-between mb-8 sm:mb-10">

                <span class="text-2xl sm:text-3xl font-extrabold tracking-wide text-white">
                    Ticket<span class="text-blue-500">Pro</span>
                </span>

                <button type="button" @click="menuMovil = false"
                    class="flex md:hidden h-9 w-9 items-center justify-center
                           rounded-lg text-slate-400
                           hover:bg-slate-800 hover:text-white transition">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>

            </div>

            {{-- USUARIO --}}
            <div
                class="flex items-center gap-3 mb-8 p-2
                       rounded-xl bg-slate-900/40
                       border border-slate-800/50">

                <img src="{{ auth()->user()->picture
                    ? asset('storage/' . auth()->user()->picture)
                    : asset('storage/profile-photos/user.png') }}"
                    alt="{{ auth()->user()->name }}"
                    class="h-11 w-11 sm:h-12 sm:w-12
                           shrink-0 rounded-full
                           border-2 border-gray-500
                           object-cover">

                <div class="overflow-hidden min-w-0">

                    <h4 class="text-sm font-semibold text-slate-200 truncate">
                        {{ auth()->user()->name ?? 'Desconocido' }}
                    </h4>

                    <p class="text-xs text-slate-400 truncate">
                        {{ auth()->user()->role ?? 'Desconocido' }}
                    </p>

                </div>

            </div>

            {{-- NAVEGACIÓN --}}
            <nav class="space-y-2">

                <a href="{{ route('tecnologias') }}"
                    class="flex items-center gap-3 px-4 py-3
                           rounded-xl text-slate-400
                           hover:bg-slate-800/50 hover:text-white transition">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 shrink-0"></i>
                    <span class="font-medium text-sm">Inicio</span>
                </a>

                <a href="{{ route('tickettecnologias') }}"
                    class="flex items-center gap-3 px-4 py-3
                           rounded-xl text-slate-400
                           hover:bg-slate-800/50 hover:text-white transition">
                    <i data-lucide="ticket-check" class="w-5 h-5 shrink-0"></i>
                    <span class="font-medium text-sm">Tickets</span>
                </a>

                @if (auth()->check() && auth()->user()->role === 'Gerente Ti' && auth()->user()->priv_admin === 'Y')
                    <a href="{{ route('cambiostecnologias') }}"
                        class="flex items-center gap-3 px-4 py-3
                               rounded-xl
                               bg-gradient-to-r from-blue-600 to-indigo-600
                               text-white font-semibold
                               shadow-lg shadow-blue-600/30">
                        <i data-lucide="git-compare-arrows" class="w-5 h-5 shrink-0"></i>
                        <span class="text-sm">Cambios</span>
                    </a>
                @endif
                @if (auth()->check() && auth()->user()->role === 'Gerente Ti' && auth()->user()->priv_admin === 'Y')
                    <a href="{{ route('usuarios.tecnologias') }}"
                        class="flex items-center gap-3 px-4 py-3
                               rounded-xl text-slate-400
                               hover:bg-slate-800/50 hover:text-white transition">
                        <i data-lucide="users" class="w-5 h-5 shrink-0"></i>
                        <span class="text-sm">Usuarios</span>
                    </a>
                @endif
                <a href="{{ route('avisostecnologias') }}"
                    class="flex items-center gap-3 px-4 py-3
                           rounded-xl text-slate-400
                           hover:bg-slate-800/50 hover:text-white transition">
                    <i data-lucide="megaphone" class="w-5 h-5 shrink-0"></i>
                    <span class="font-medium text-sm">Avisos</span>
                </a>

                <a href="{{ route('perfiltecnologias') }}"
                    class="flex items-center gap-3 px-4 py-3
                           rounded-xl text-slate-400
                           hover:bg-slate-800/50 hover:text-white transition">
                    <i data-lucide="circle-user-round" class="w-5 h-5 shrink-0"></i>
                    <span class="font-medium text-sm">Mi perfil</span>
                </a>

            </nav>

        </div>

        {{-- LOGOUT --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit"
                class="w-full flex items-center gap-3
                       px-4 py-3 rounded-xl
                       text-slate-400
                       hover:bg-red-500/10
                       hover:text-red-400 transition">
                <i data-lucide="log-out" class="w-5 h-5 shrink-0"></i>
                <span class="font-medium text-sm">
                    Cerrar sesión
                </span>
            </button>
        </form>

    </aside>


    {{-- OVERLAY MOBILE --}}
    <div x-show="menuMovil" x-cloak x-transition.opacity @click="menuMovil = false"
        class="fixed inset-0 z-[99998]
               bg-black/70 backdrop-blur-sm md:hidden"></div>


    {{-- =========================================================
        CONTENIDO PRINCIPAL
    ========================================================== --}}

    <main
        class="md:ml-[280px]
               min-h-screen
               px-4 py-5
               sm:px-6 sm:py-6
               lg:px-8 lg:py-8
               pt-20 md:pt-8">

        <div class="max-w-[1500px] mx-auto">

            {{-- =================================================
                BOTÓN MENÚ MOBILE
            ================================================== --}}

            <button type="button" @click="menuMovil = true"
                class="md:hidden fixed
                       top-4 left-4
                       z-[99997]
                       flex h-10 w-10
                       items-center justify-center
                       rounded-xl
                       bg-[#0f1535]
                       border border-slate-800
                       text-slate-300
                       hover:bg-slate-800
                       hover:text-white transition">
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>

            <header
                class="flex flex-col
                       gap-5
                       mb-6 sm:mb-8
                       md:flex-row
                       md:items-center
                       md:justify-between">

                <div class="min-w-0">

                    <h1
                        class="text-2xl
                               sm:text-3xl
                               font-bold
                               tracking-tight
                               text-white">
                        Solicitudes de cambio
                    </h1>

                    <p
                        class="text-xs
                               sm:text-sm
                               text-slate-400
                               mt-1
                               max-w-2xl">
                        Consulta y da seguimiento a las solicitudes de cambio
                        de información de cuentas
                    </p>

                </div>


                {{-- HEADER DERECHA --}}
                <div class="flex items-center gap-3 sm:gap-4
                           self-end md:self-auto">

                    {{-- NOTIFICACIONES --}}
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


                    {{-- PERFIL --}}
                    <div class="relative z-[100]" x-data="{ perfilAbierto: false, configuracionAbierta: false }">
                        <button id="profile-button" type="button" @click="perfilAbierto = !perfilAbierto"
                            class="relative flex items-center gap-2 sm:gap-3 bg-slate-900/80 border border-slate-800 rounded-full p-1.5 pr-2 sm:pr-4 hover:bg-slate-800 transition">
                            <img src="{{ auth()->user()->picture ? asset('storage/' . auth()->user()->picture) : asset('storage/profile-photos/user.png') }}"
                                alt="{{ auth()->user()->name }}"
                                class="w-9 h-9 sm:w-12 sm:h-12 rounded-full border-2 border-gray-500 object-cover">
                            <div class="text-left leading-tight hidden sm:block">
                                <p class="text-xs font-semibold text-white max-w-[130px] truncate">
                                    {{ auth()->user()->name ?? 'Desconocido' }}</p>
                                <p class="text-[10px] text-blue-400 font-medium">
                                    {{ auth()->user()->role ?? 'Desconocido' }}</p>
                            </div>
                            <i data-lucide="chevron-down"
                                class="hidden sm:block w-4 h-4 text-slate-400 ml-1 transition-transform duration-200"
                                :class="{ 'rotate-180': perfilAbierto }"></i>
                        </button>
                        <div x-show="perfilAbierto" @click.outside="perfilAbierto = false" x-transition
                            class="absolute right-0 top-full mt-3 w-60 bg-[#0f1535] border border-[#1e295d] rounded-xl shadow-2xl overflow-hidden z-[99999]"
                            style="display:none;">
                            <button type="button" @click="configuracionAbierta = !configuracionAbierta"
                                class="w-full flex items-center gap-3 px-4 py-3 text-sm text-slate-300 hover:bg-[#151b3b] hover:text-white transition text-left">
                                <i data-lucide="settings" class="w-5 h-5 text-slate-400"></i>
                                <span class="flex-1">Configuración</span>
                                <i data-lucide="chevron-down"
                                    class="w-4 h-4 text-slate-500 transition-transform duration-200"
                                    :class="{ 'rotate-180': configuracionAbierta }"></i>
                            </button>
                            <div x-show="configuracionAbierta" x-transition
                                class="border-t border-[#1e295d] bg-[#0b1026]/50">
                                <a href="{{ route('perfiltecnologias') }}"
                                    class="flex items-center gap-3 px-5 py-3 text-sm text-slate-400 hover:bg-[#151b3b] hover:text-white transition">
                                    <i data-lucide="circle-user-round" class="w-4 h-4 text-blue-400"></i>
                                    <span>Mi perfil</span>
                                </a>
                                @if (auth()->check() &&
                                        trim((string) auth()->user()->role) === 'Gerente Ti' &&
                                        strtoupper(trim((string) auth()->user()->priv_admin)) === 'Y')
                                    <a href="{{ route('backups') }}"
                                        class="flex items-center gap-3 px-5 py-3 text-sm text-slate-400 hover:bg-[#151b3b] hover:text-white transition">
                                        <i data-lucide="database-backup" class="w-4 h-4 text-emerald-400"></i>
                                        <span>Backups</span>
                                    </a>
                                @endif
                            </div>
                            <div class="border-t border-[#1e295d]"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-3 text-sm text-slate-300 hover:bg-red-500/10 hover:text-red-400 transition text-left">
                                    <i data-lucide="log-out" class="w-5 h-5"></i>
                                    <span>Cerrar sesión</span>
                                </button>
                            </form>
                        </div>
                    </div>

                </div>

            </header>


            {{-- =================================================
                MENSAJES
            ================================================== --}}

            @if (session('success'))
                <div id="successMessage"
                    class="fixed
                           left-4 right-4
                           sm:left-auto
                           sm:right-5
                           top-5
                           z-[9999]
                           w-auto
                           sm:w-full
                           sm:max-w-sm
                           rounded-2xl
                           border border-green-500/30
                           bg-[#0f1535]
                           p-4
                           shadow-[0_0_30px_rgba(34,197,94,0.20)]">

                    <div class="flex items-start gap-3">

                        <div
                            class="flex h-10 w-10 shrink-0
                                   items-center justify-center
                                   rounded-full
                                   bg-green-500/15
                                   text-green-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>

                        <div class="flex-1 min-w-0">

                            <p class="font-bold text-white">
                                ¡Éxito!
                            </p>

                            <p class="mt-1 text-sm text-slate-400 break-words">
                                {{ session('success') }}
                            </p>

                        </div>

                        <button type="button" onclick="document.getElementById('successMessage')?.remove()"
                            class="text-slate-500 hover:text-white shrink-0">
                            ✕
                        </button>

                    </div>

                </div>
            @endif


            @if (session('error'))
                <div id="errorMessage"
                    class="fixed
                           left-4 right-4
                           sm:left-auto
                           sm:right-5
                           top-5
                           z-[9999]
                           w-auto
                           sm:w-full
                           sm:max-w-sm
                           rounded-2xl
                           border border-red-500/30
                           bg-[#0f1535]
                           p-4
                           shadow-[0_0_30px_rgba(239,68,68,0.20)]">

                    <div class="flex items-start gap-3">

                        <div
                            class="flex h-10 w-10 shrink-0
                                   items-center justify-center
                                   rounded-full
                                   bg-red-500/15
                                   text-red-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>

                        <div class="flex-1 min-w-0">

                            <p class="font-bold text-white">
                                ¡Error!
                            </p>

                            <p class="mt-1 text-sm text-slate-400 break-words">
                                {{ session('error') }}
                            </p>

                        </div>

                        <button type="button" onclick="document.getElementById('errorMessage')?.remove()"
                            class="text-slate-500 hover:text-white transition shrink-0">
                            ✕
                        </button>

                    </div>

                </div>
            @endif


            {{-- =================================================
                ESTADÍSTICAS
            ================================================== --}}

            <div
                class="grid
                       grid-cols-1
                       min-[480px]:grid-cols-2
                       xl:grid-cols-4
                       gap-3 sm:gap-4
                       mb-6">

                {{-- TOTAL --}}
                <div
                    class="bg-[#0b1026]/90
                           border border-blue-900/40
                           rounded-2xl
                           p-4 sm:p-5
                           flex items-center justify-between
                           shadow-xl">

                    <div class="min-w-0">

                        <p
                            class="text-[11px] sm:text-xs
                                   text-slate-400
                                   font-medium
                                   mb-1">
                            Total de solicitudes
                        </p>

                        <h3 class="text-2xl font-bold text-white">
                            {{ $total }}
                        </h3>

                    </div>

                    <div
                        class="w-10 h-10 sm:w-11 sm:h-11
                               shrink-0
                               bg-blue-500/10
                               text-blue-400
                               rounded-xl
                               flex items-center justify-center
                               border border-blue-500/20">
                        <i data-lucide="folder-open" class="w-5 h-5"></i>
                    </div>

                </div>


                {{-- PENDIENTES --}}
                <div
                    class="bg-[#0b1026]/90
                           border border-blue-900/40
                           rounded-2xl
                           p-4 sm:p-5
                           flex items-center justify-between
                           shadow-xl">

                    <div class="min-w-0">

                        <p
                            class="text-[11px] sm:text-xs
                                   text-slate-400
                                   font-medium
                                   mb-1">
                            En revisión
                        </p>

                        <h3 class="text-2xl font-bold text-white">
                            {{ $pendientes }}
                        </h3>

                    </div>

                    <div
                        class="w-10 h-10 sm:w-11 sm:h-11
                               shrink-0
                               bg-amber-500/10
                               text-amber-400
                               rounded-xl
                               flex items-center justify-center
                               border border-amber-500/20">
                        <i data-lucide="clock-3" class="w-5 h-5"></i>
                    </div>

                </div>


                {{-- APROBADAS --}}
                <div
                    class="bg-[#0b1026]/90
                           border border-blue-900/40
                           rounded-2xl
                           p-4 sm:p-5
                           flex items-center justify-between
                           shadow-xl">

                    <div class="min-w-0">

                        <p
                            class="text-[11px] sm:text-xs
                                   text-slate-400
                                   font-medium
                                   mb-1">
                            Aprobadas
                        </p>

                        <h3 class="text-2xl font-bold text-white">
                            {{ $aprobadas }}
                        </h3>

                    </div>

                    <div
                        class="w-10 h-10 sm:w-11 sm:h-11
                               shrink-0
                               bg-emerald-500/10
                               text-emerald-400
                               rounded-xl
                               flex items-center justify-center
                               border border-emerald-500/20">
                        <i data-lucide="circle-check" class="w-5 h-5"></i>
                    </div>

                </div>


                {{-- RECHAZADAS --}}
                <div
                    class="bg-[#0b1026]/90
                           border border-blue-900/40
                           rounded-2xl
                           p-4 sm:p-5
                           flex items-center justify-between
                           shadow-xl">

                    <div class="min-w-0">

                        <p
                            class="text-[11px] sm:text-xs
                                   text-slate-400
                                   font-medium
                                   mb-1">
                            Rechazadas
                        </p>

                        <h3 class="text-2xl font-bold text-white">
                            {{ $rechazadas }}
                        </h3>

                    </div>

                    <div
                        class="w-10 h-10 sm:w-11 sm:h-11
                               shrink-0
                               bg-rose-500/10
                               text-rose-400
                               rounded-xl
                               flex items-center justify-center
                               border border-rose-500/20">
                        <i data-lucide="circle-x" class="w-5 h-5"></i>
                    </div>

                </div>

            </div>


            {{-- =================================================
                FILTROS + BUSCADOR
            ================================================== --}}

            <div
                class="flex flex-col
                       xl:flex-row
                       xl:items-center
                       xl:justify-between
                       gap-4
                       mb-6">

                {{-- TABS --}}
                <div
                    class="flex
                           flex-wrap
                           items-center
                           gap-1
                           bg-[#0b1026]
                           p-1.5
                           rounded-xl
                           border border-slate-800
                           w-full
                           xl:w-auto">

                    <a href="{{ route('cambiostecnologias') }}"
                        class="flex-1 sm:flex-none
                               text-center
                               px-3 sm:px-4
                               py-2
                               rounded-lg
                               text-xs
                               font-semibold
                               {{ !request('estado') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-white' }}">
                        Todos
                    </a>

                    <a href="{{ route('cambiostecnologias', ['estado' => 'pendiente']) }}"
                        class="flex-1 sm:flex-none
                               text-center
                               px-3 sm:px-4
                               py-2
                               rounded-lg
                               text-xs
                               font-semibold
                               flex items-center
                               justify-center
                               gap-2
                               {{ request('estado') === 'pendiente' ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-white' }}">
                        En revisión

                        <span
                            class="w-2 h-2
                                   shrink-0
                                   rounded-full
                                   bg-amber-400"></span>
                    </a>

                    <a href="{{ route('cambiostecnologias', ['estado' => 'aprobada']) }}"
                        class="flex-1 sm:flex-none
                               text-center
                               px-3 sm:px-4
                               py-2
                               rounded-lg
                               text-xs
                               font-semibold
                               flex items-center
                               justify-center
                               gap-2
                               {{ request('estado') === 'aprobada' ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-white' }}">
                        Aprobadas

                        <span
                            class="w-2 h-2
                                   shrink-0
                                   rounded-full
                                   bg-emerald-400"></span>
                    </a>

                </div>


                {{-- BUSCADOR --}}
                <form method="GET" action="{{ route('cambiostecnologias') }}" class="relative w-full xl:w-80">

                    @if (request('estado'))
                        <input type="hidden" name="estado" value="{{ request('estado') }}">
                    @endif

                    <i data-lucide="search"
                        class="absolute
                               left-3.5
                               top-1/2
                               -translate-y-1/2
                               w-4 h-4
                               text-slate-500"></i>

                    <input type="text" name="buscar" value="{{ request('buscar') }}"
                        placeholder="Buscar solicitud..."
                        class="w-full
                               bg-[#0b1026]
                               border border-slate-800
                               text-slate-200
                               text-xs
                               rounded-xl
                               pl-10 pr-4
                               py-3
                               focus:outline-none
                               focus:border-blue-500
                               transition">

                </form>

            </div>


            {{-- =================================================
                CONTENIDO
            ================================================== --}}

            <div
                class="grid
                       grid-cols-1
                       lg:grid-cols-3
                       gap-5 lg:gap-6">

                {{-- =================================================
                    TABLA
                ================================================== --}}

                <div
                    class="lg:col-span-2
                           min-w-0
                           bg-[#070e27]
                           border border-slate-800/80
                           rounded-2xl
                           p-4 sm:p-5
                           flex flex-col">

                    {{-- IMPORTANTE:
                         overflow-x-auto para móvil --}}
                    <div class="w-full overflow-x-auto">

                        <table
                            class="w-full
                                   min-w-[760px]
                                   text-left
                                   text-xs">

                            <thead>

                                <tr
                                    class="text-slate-400
                                           border-b border-slate-800/80">

                                    <th class="pb-3 font-semibold">
                                        Folio
                                    </th>

                                    <th class="pb-3 font-semibold">
                                        Solicitante
                                    </th>

                                    <th class="pb-3 font-semibold">
                                        Campo
                                    </th>

                                    <th class="pb-3 font-semibold text-center">
                                        Estado
                                    </th>

                                    <th class="pb-3 font-semibold">
                                        Fecha
                                    </th>

                                    <th class="pb-3 text-center">
                                        Acción
                                    </th>

                                </tr>

                            </thead>


                            <tbody
                                class="divide-y
                                       divide-slate-800/50
                                       text-slate-300">

                                @forelse($solicitudes as $solicitud)
                                    <tr
                                        class="hover:bg-slate-800/20
                                               transition
                                               {{ $seleccionada?->id === $solicitud->id ? 'bg-blue-500/5' : '' }}">

                                        <td
                                            class="py-4
                                                   font-semibold
                                                   text-white">
                                            {{ $solicitud->id }}
                                        </td>


                                        <td class="py-4">

                                            <div class="flex items-center gap-2">

                                                <img src="{{ $solicitud->usuario?->picture
                                                    ? asset('storage/' . $solicitud->usuario->picture)
                                                    : asset('storage/profile-photos/user.png') }}"
                                                    class="w-7 h-7
                                                           shrink-0
                                                           rounded-full
                                                           object-cover">

                                                <div class="min-w-0">

                                                    <p
                                                        class="text-white
                                                               truncate
                                                               max-w-[180px]">
                                                        {{ $solicitud->usuario?->name ?? 'Usuario eliminado' }}
                                                    </p>

                                                    <p
                                                        class="text-[10px]
                                                               text-slate-500
                                                               truncate
                                                               max-w-[180px]">
                                                        {{ $solicitud->usuario?->email ?? '' }}
                                                    </p>

                                                </div>

                                            </div>

                                        </td>


                                        <td class="py-4">
                                            {{ ucfirst(str_replace('_', ' ', $solicitud->campo)) }}
                                        </td>


                                        <td class="py-4 text-center">

                                            @if ($solicitud->estado === 'pendiente')
                                                <span
                                                    class="inline-flex
                                                           px-2.5 py-1
                                                           rounded-full
                                                           text-[10px]
                                                           font-medium
                                                           bg-amber-500/10
                                                           text-amber-400
                                                           border border-amber-500/20">
                                                    En revisión
                                                </span>
                                            @elseif($solicitud->estado === 'aprobada')
                                                <span
                                                    class="inline-flex
                                                           px-2.5 py-1
                                                           rounded-full
                                                           text-[10px]
                                                           font-medium
                                                           bg-emerald-500/10
                                                           text-emerald-400
                                                           border border-emerald-500/20">
                                                    Aprobada
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex
                                                           px-2.5 py-1
                                                           rounded-full
                                                           text-[10px]
                                                           font-medium
                                                           bg-rose-500/10
                                                           text-rose-400
                                                           border border-rose-500/20">
                                                    Rechazada
                                                </span>
                                            @endif

                                        </td>


                                        <td class="py-4">

                                            {{ $solicitud->created_at->format('d M Y') }}

                                            <br>

                                            <span
                                                class="text-[10px]
                                                       text-slate-500">
                                                {{ $solicitud->created_at->format('h:i A') }}
                                            </span>

                                        </td>


                                        <td class="py-4 text-center">

                                            <a href="{{ request()->fullUrlWithQuery(['solicitud' => $solicitud->id]) }}"
                                                class="inline-flex
                                                       items-center
                                                       justify-center
                                                       w-8 h-8
                                                       rounded-lg
                                                       text-slate-400
                                                       hover:text-blue-400
                                                       hover:bg-blue-500/10
                                                       transition">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                            </a>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="6"
                                            class="py-12
                                                   text-center
                                                   text-slate-500">

                                            <i data-lucide="inbox"
                                                class="w-10 h-10
                                                       mx-auto
                                                       mb-3
                                                       opacity-40"></i>

                                            No se encontraron solicitudes.

                                        </td>

                                    </tr>
                                @endforelse

                            </tbody>

                        </table>

                    </div>


                    {{-- PAGINACIÓN --}}
                    <div
                        class="mt-6
                               pt-5
                               border-t border-slate-800/80
                               flex flex-col
                               sm:flex-row
                               justify-between
                               items-center
                               gap-4">

                        <span
                            class="text-xs
                                   text-slate-400
                                   text-center sm:text-left">
                            Mostrando
                            {{ $solicitudes->firstItem() ?? 0 }}
                            a
                            {{ $solicitudes->lastItem() ?? 0 }}
                            de
                            {{ $solicitudes->total() }}
                            solicitudes
                        </span>


                        <div
                            class="flex
                                   items-center
                                   gap-1
                                   max-w-full
                                   overflow-x-auto">

                            @if ($solicitudes->onFirstPage())
                                <span
                                    class="w-8 h-8 shrink-0
                                           bg-slate-900
                                           text-slate-600
                                           rounded-lg
                                           flex items-center justify-center">
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                </span>
                            @else
                                <a href="{{ $solicitudes->previousPageUrl() }}"
                                    class="w-8 h-8 shrink-0
                                           bg-slate-800
                                           text-slate-400
                                           rounded-lg
                                           hover:bg-slate-700
                                           flex items-center justify-center">
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                </a>
                            @endif


                            @foreach ($solicitudes->getUrlRange(max(1, $solicitudes->currentPage() - 2), min($solicitudes->lastPage(), $solicitudes->currentPage() + 2)) as $page => $url)
                                <a href="{{ $url }}"
                                    class="w-8 h-8 shrink-0
                                           rounded-lg
                                           flex items-center justify-center
                                           text-xs
                                           {{ $page == $solicitudes->currentPage()
                                               ? 'bg-blue-600 text-white font-bold'
                                               : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                                    {{ $page }}
                                </a>
                            @endforeach


                            @if ($solicitudes->hasMorePages())
                                <a href="{{ $solicitudes->nextPageUrl() }}"
                                    class="w-8 h-8 shrink-0
                                           bg-slate-800
                                           text-slate-400
                                           rounded-lg
                                           hover:bg-slate-700
                                           flex items-center justify-center">
                                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                </a>
                            @else
                                <span
                                    class="w-8 h-8 shrink-0
                                           bg-slate-900
                                           text-slate-600
                                           rounded-lg
                                           flex items-center justify-center">
                                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                </span>
                            @endif

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    DETALLE
                ================================================== --}}

                <div
                    class="min-w-0
                           bg-[#0b1026]/90
                           border border-blue-900/40
                           rounded-2xl
                           p-4 sm:p-5
                           shadow-xl
                           backdrop-blur-md">

                    @if ($seleccionada)

                        <div class="space-y-5">

                            {{-- CABECERA --}}
                            <div
                                class="flex flex-col
                                       sm:flex-row
                                       sm:justify-between
                                       sm:items-center
                                       gap-3
                                       pb-3
                                       border-b border-slate-800">

                                <div class="min-w-0">

                                    <h2
                                        class="text-sm
                                               font-semibold
                                               text-white">
                                        Detalle de la solicitud
                                    </h2>

                                    <p
                                        class="text-[10px]
                                               text-slate-500
                                               truncate">
                                        {{ $seleccionada->folio }}
                                    </p>

                                </div>


                                @if ($seleccionada->estado === 'pendiente')
                                    <span
                                        class="self-start sm:self-auto
                                               px-2.5 py-0.5
                                               rounded-full
                                               text-[10px]
                                               font-semibold
                                               bg-amber-500/10
                                               text-amber-400
                                               border border-amber-500/20">
                                        En revisión
                                    </span>
                                @elseif($seleccionada->estado === 'aprobada')
                                    <span
                                        class="self-start sm:self-auto
                                               px-2.5 py-0.5
                                               rounded-full
                                               text-[10px]
                                               font-semibold
                                               bg-emerald-500/10
                                               text-emerald-400
                                               border border-emerald-500/20">
                                        Aprobada
                                    </span>
                                @else
                                    <span
                                        class="self-start sm:self-auto
                                               px-2.5 py-0.5
                                               rounded-full
                                               text-[10px]
                                               font-semibold
                                               bg-rose-500/10
                                               text-rose-400
                                               border border-rose-500/20">
                                        Rechazada
                                    </span>
                                @endif

                            </div>


                            {{-- SOLICITANTE --}}
                            <div>

                                <p
                                    class="text-slate-400
                                           text-[10px]
                                           mb-2">
                                    Solicitado por
                                </p>

                                <div
                                    class="flex items-center gap-3
                                           bg-slate-800/40
                                           p-3
                                           rounded-xl">

                                    <img src="{{ $seleccionada->usuario?->picture
                                        ? asset('storage/' . $seleccionada->usuario->picture)
                                        : asset('storage/profile-photos/user.png') }}"
                                        class="w-9 h-9
                                               shrink-0
                                               rounded-full
                                               object-cover">

                                    <div class="min-w-0">

                                        <p
                                            class="text-xs
                                                   font-medium
                                                   text-white
                                                   truncate">
                                            {{ $seleccionada->usuario?->name ?? 'Usuario eliminado' }}
                                        </p>

                                        <p
                                            class="text-[10px]
                                                   text-slate-400
                                                   truncate">
                                            {{ $seleccionada->usuario?->email ?? 'Sin correo' }}
                                        </p>

                                        <p
                                            class="text-[10px]
                                                   text-blue-400
                                                   truncate">
                                            {{ auth()->user()->role ?? 'Desconocido' }}
                                        </p>

                                    </div>

                                </div>

                            </div>


                            {{-- DATOS --}}
                            <div
                                class="grid
                                       grid-cols-1
                                       sm:grid-cols-2
                                       gap-4
                                       text-xs">

                                <div class="min-w-0">

                                    <p
                                        class="text-slate-400
                                               text-[10px]">
                                        Campo solicitado
                                    </p>

                                    <p
                                        class="text-white
                                               font-medium
                                               break-words">
                                        {{ ucfirst(str_replace('_', ' ', $seleccionada->campo)) }}
                                    </p>

                                </div>


                                <div class="min-w-0">

                                    <p
                                        class="text-slate-400
                                               text-[10px]">
                                        Fecha solicitud
                                    </p>

                                    <p
                                        class="text-white
                                               font-medium
                                               break-words">
                                        {{ $seleccionada->created_at->format('d/m/Y h:i A') }}
                                    </p>

                                </div>

                            </div>


                            {{-- INFORMACIÓN ACTUAL --}}
                            <div>

                                <p
                                    class="text-slate-400
                                           text-[10px]
                                           mb-1">
                                    Información actual
                                </p>

                                <div
                                    class="bg-slate-900/70
                                           border border-slate-800
                                           rounded-xl
                                           p-3
                                           text-xs
                                           text-slate-300
                                           break-words
                                           overflow-hidden">
                                    {{ $seleccionada->valor_actual ?? 'Sin información' }}
                                </div>

                            </div>


                            {{-- NUEVO VALOR --}}
                            <div>

                                <p
                                    class="text-slate-400
                                           text-[10px]
                                           mb-1">
                                    Información solicitada
                                </p>

                                <div
                                    class="bg-blue-500/5
                                           border border-blue-500/20
                                           rounded-xl
                                           p-3
                                           text-xs
                                           text-blue-300
                                           break-words
                                           overflow-hidden">
                                    {{ $seleccionada->nuevo_valor }}
                                </div>

                            </div>


                            {{-- MOTIVO --}}
                            <div>

                                <p
                                    class="text-slate-400
                                           text-[10px]
                                           mb-1">
                                    Motivo de solicitud
                                </p>

                                <p
                                    class="text-slate-300
                                           text-[11px]
                                           leading-relaxed
                                           break-words">
                                    {{ $seleccionada->motivo }}
                                </p>

                            </div>


                            {{-- REVISOR --}}
                            @if ($seleccionada->revisor)
                                <div class="pt-2
                                           border-t border-slate-800">

                                    <p
                                        class="text-slate-400
                                               text-[10px]
                                               mb-2">
                                        Revisada por
                                    </p>

                                    <div
                                        class="flex items-center gap-3
                                               bg-slate-800/40
                                               p-3
                                               rounded-xl">

                                        <img src="{{ $seleccionada->revisor?->picture
                                            ? asset('storage/' . $seleccionada->revisor->picture)
                                            : asset('storage/profile-photos/user.png') }}"
                                            class="w-9 h-9
                                                   shrink-0
                                                   rounded-full
                                                   object-cover">

                                        <div class="min-w-0">

                                            <p
                                                class="text-xs
                                                       font-medium
                                                       text-white
                                                       truncate">
                                                {{ $seleccionada->revisor->name }}
                                            </p>

                                            <p
                                                class="text-[9px]
                                                       text-slate-400">
                                                {{ optional($seleccionada->revisado_at)->format('d M Y - h:i A') }}
                                            </p>

                                        </div>

                                    </div>

                                </div>
                            @endif


                            {{-- OBSERVACIONES --}}
                            @if ($seleccionada->comentario_admin)
                                <div>

                                    <p
                                        class="text-slate-400
                                               text-[10px]">
                                        Observaciones
                                    </p>

                                    <p
                                        class="text-slate-300
                                               text-[11px]
                                               mt-1
                                               leading-relaxed
                                               break-words">
                                        {{ $seleccionada->comentario_admin }}
                                    </p>

                                </div>
                            @endif


                            {{-- =================================================
                                RESOLVER
                            ================================================== --}}

                            @if ($seleccionada->estado === 'pendiente')
                                <div
                                    class="pt-3
                                           border-t border-slate-800
                                           space-y-4">

                                    <p
                                        class="text-slate-400
                                               text-[10px]">
                                        Resolver solicitud
                                    </p>


                                    {{-- APROBAR --}}
                                    <form method="POST" action="{{ route('cambios.aprobar', $seleccionada) }}">

                                        @csrf

                                        <textarea name="comentario_admin" placeholder="Comentario opcional al aprobar..." rows="3"
                                            class="w-full
                                                   bg-slate-900
                                                   border border-slate-800
                                                   rounded-xl
                                                   p-3
                                                   text-xs
                                                   text-white
                                                   placeholder:text-slate-600
                                                   focus:outline-none
                                                   focus:border-emerald-500
                                                   resize-none
                                                   transition"></textarea>

                                        <button type="submit"
                                            class="w-full
                                                   mt-2
                                                   py-2.5
                                                   rounded-xl
                                                   bg-emerald-600
                                                   hover:bg-emerald-500
                                                   text-white
                                                   text-xs
                                                   font-semibold
                                                   transition
                                                   flex items-center
                                                   justify-center
                                                   gap-2">

                                            <i data-lucide="check" class="w-4 h-4"></i>

                                            Aprobar solicitud

                                        </button>

                                    </form>


                                    {{-- RECHAZAR --}}
                                    <form method="POST" action="{{ route('cambios.rechazar', $seleccionada) }}">

                                        @csrf

                                        <textarea name="comentario_admin" required placeholder="Motivo del rechazo..." rows="3"
                                            class="w-full
                                                   bg-slate-900
                                                   border border-slate-800
                                                   rounded-xl
                                                   p-3
                                                   text-xs
                                                   text-white
                                                   placeholder:text-slate-600
                                                   focus:outline-none
                                                   focus:border-rose-500
                                                   resize-none
                                                   transition"></textarea>

                                        <button type="submit"
                                            class="w-full
                                                   mt-2
                                                   py-2.5
                                                   rounded-xl
                                                   bg-rose-600
                                                   hover:bg-rose-500
                                                   text-white
                                                   text-xs
                                                   font-semibold
                                                   transition
                                                   flex items-center
                                                   justify-center
                                                   gap-2">

                                            <i data-lucide="x" class="w-4 h-4"></i>

                                            Rechazar solicitud

                                        </button>

                                    </form>

                                </div>
                            @endif

                        </div>
                    @else
                        {{-- SIN SELECCIÓN --}}
                        <div
                            class="min-h-[350px]
                                   lg:min-h-[500px]
                                   flex flex-col
                                   items-center
                                   justify-center
                                   text-center
                                   px-5">

                            <i data-lucide="file-question"
                                class="w-12 h-12
                                       text-slate-700
                                       mb-3"></i>

                            <p class="text-sm
                                       text-slate-500">
                                No hay ninguna solicitud seleccionada.
                            </p>

                            <p
                                class="text-[10px]
                                       text-slate-700
                                       mt-1">
                                Selecciona una solicitud de la tabla.
                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>

</body>

</html>
