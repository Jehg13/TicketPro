<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TicketPro - Mi Perfil</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/images/logo.png') }}">

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

                <img src="{{ auth()->user()->foto ? asset('storage/' . auth()->user()->foto) : asset('images/default-avatar.png') }}"
                    alt="{{ auth()->user()->name }}"
                    class="w-10 h-10 rounded-full object-cover ring-2 ring-blue-500/30">

                <div class="overflow-hidden">

                    <h4 class="text-sm font-semibold text-slate-200 truncate">
                        {{ auth()->user()->name ?? 'Desconocido' }}
                    </h4>

                    <p class="text-xs text-slate-400 truncate">
                        {{ auth()->user()->departamento->nombre ?? 'Sin departamento' }}
                    </p>

                </div>

            </div>

            <nav class="space-y-2">

                <a href="{{ route('tecnologias') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">

                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>

                    <span class="font-medium text-sm">
                        Inicio
                    </span>

                </a>

                <a href="{{ route('tickettecnologias') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">

                    <i data-lucide="ticket-check" class="w-5 h-5"></i>

                    <span class="font-medium text-sm">
                        Tickets
                    </span>

                </a>

                <a href="{{ route('cambiostecnologias') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">

                    <i data-lucide="git-compare-arrows" class="w-5 h-5"></i>

                    <span class="font-medium text-sm">
                        Cambios
                    </span>

                </a>

                <a href="{{ route('avisostecnologias') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">

                    <i data-lucide="megaphone" class="w-5 h-5"></i>

                    <span class="font-medium text-sm">
                        Avisos
                    </span>

                </a>

                <!-- PERFIL -->
                <a href="{{ route('perfiltecnologias') }}"
                    class="flex items-center gap-3
                           px-4 py-3
                           text-sm text-slate-300
                           hover:bg-[#151b3b]
                           hover:text-white
                           transition-colors">

                    <i data-lucide="circle-user-round" class="w-5 h-5 text-slate-400">
                    </i>

                    <span>
                        Perfil
                    </span>

                </a>

            </nav>

        </div>

        <div>

            <form method="POST" action="{{ route('logout') }}" class="mt-6">

                @csrf

                <button type="submit"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-red-500/10 hover:text-red-400 transition">

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

            <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">

                <div>

                    <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-white">
                        Mi perfil
                    </h1>

                    <p class="text-sm text-slate-400 mt-1">
                        Gestión y actualización directa de tu información administrativa
                    </p>

                </div>

                <div class="flex items-center gap-4 self-end md:self-auto">
                    @if (session('success'))
                        <div id="successMessage"
                            class="fixed right-5 top-5 z-[9999] w-full max-w-sm
                            rounded-2xl border border-green-500/30
                            bg-[#0f1535] p-4
                            shadow-[0_0_30px_rgba(34,197,94,0.20)]">
                            <div class="flex items-start gap-3">
                                <div
                                    class="flex h-10 w-10 shrink-0 items-center justify-center
                                    rounded-full bg-green-500/15 text-green-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
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
                                <button onclick="document.getElementById('successMessage').remove()"
                                    class="text-slate-500 hover:text-white">
                                    ✕
                                </button>
                            </div>
                        </div>
                    @endif
                    @if (session('error'))
                        <div id="errorMessage"
                            class="fixed right-5 top-5 z-[9999] w-full max-w-sm
                            rounded-2xl border border-red-500/30
                            bg-[#0f1535] p-4
                            shadow-[0_0_30px_rgba(239,68,68,0.20)]">
                            <div class="flex items-start gap-3">
                                <div
                                    class="flex h-10 w-10 shrink-0 items-center justify-center
                                    rounded-full bg-red-500/15 text-red-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
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
                    <div class="flex items-center gap-6 self-end md:self-auto">
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
                        <div class="relative z-[100]" x-data="{ perfilAbierto: false }">

                            <button id="profile-button" type="button" @click="perfilAbierto = !perfilAbierto"
                                class="relative flex items-center gap-3 bg-slate-900/80 border border-slate-800 rounded-full p-1.5 pr-4 hover:bg-slate-800 transition-all duration-200 focus:outline-none">

                                <img src="{{ auth()->user()->foto ? asset('storage/' . auth()->user()->foto) : asset('images/default-avatar.png') }}"
                                    alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full object-cover">

                                <div class="text-left leading-tight hidden sm:block">
                                    <p class="text-xs font-semibold text-white">
                                        {{ auth()->user()->name ?? 'Desconocido' }}
                                    </p>

                                    <p class="text-[10px] text-blue-400 font-medium">
                                        {{ optional(auth()->user()->departamento)->nombre ?? 'Sin departamento' }}
                                    </p>
                                </div>

                                <svg id="profile-arrow"
                                    class="w-4 h-4 text-slate-400 ml-1 transition-transform duration-200"
                                    :class="{ 'rotate-180': perfilAbierto }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7">
                                    </path>

                                </svg>

                            </button>


                            <div id="profile-dropdown" x-show="perfilAbierto" @click.outside="perfilAbierto = false"
                                x-transition
                                class="absolute right-0 top-full mt-3 w-56 bg-[#0f1535] border border-[#1e295d] rounded-xl shadow-2xl overflow-hidden z-[99999]"
                                style="display: none;">

                                <a href="{{ route('perfiltecnologias') }}"
                                    class="flex items-center gap-3 px-4 py-3 text-sm text-slate-300 hover:bg-[#151b3b] hover:text-white transition-colors">

                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7-7z">
                                        </path>

                                    </svg>

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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

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

                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />

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

                <div class="lg:col-span-2 space-y-6">

                    <div class="bg-[#0b1026]/90 border border-blue-900/40 rounded-2xl p-6 shadow-xl backdrop-blur-md">

                        <div
                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 pb-4 border-b border-slate-800/80">

                            <div class="flex items-center gap-3">

                                <div class="p-2.5 rounded-xl bg-blue-600/20 text-blue-400 shrink-0">

                                    <i data-lucide="user-check" class="w-5 h-5"></i>

                                </div>

                                <div>

                                    <h2 class="text-base font-bold text-white">
                                        Información personal y laboral
                                    </h2>

                                    <p class="text-xs text-slate-400 mt-0.5">
                                        Como Administrador de TI puedes modificar tus datos de contacto directamente.
                                    </p>

                                </div>

                            </div>

                            <span
                                class="self-start sm:self-auto px-3 py-1 rounded-full text-[10px] font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/30 whitespace-nowrap">

                                Modo Administrador

                            </span>

                        </div>

                        <form action="{{ route('tecnologias.perfil.update') }}" method="POST" class="space-y-5"
                            x-data="{ confirmar: false }" @submit.prevent="confirmar = true">

                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                <div class="space-y-1.5">

                                    <label
                                        class="flex items-center justify-between text-xs font-semibold text-slate-300">

                                        <span class="flex items-center gap-1.5">

                                            <i data-lucide="user" class="w-3.5 h-3.5 text-blue-400"></i>

                                            Nombre completo

                                        </span>

                                        <span class="text-[10px] text-blue-400 font-normal flex items-center gap-1">

                                            <i data-lucide="pen" class="w-3 h-3"></i>

                                            Editable

                                        </span>

                                    </label>

                                    <input type="text" name="name" value="{{ auth()->user()->name }}"
                                        class="w-full bg-[#030712] border border-slate-700/80 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">

                                </div>

                                <div class="space-y-1.5">

                                    <label
                                        class="flex items-center justify-between text-xs font-semibold text-slate-400">

                                        <span class="flex items-center gap-1.5">

                                            <i data-lucide="building-2" class="w-3.5 h-3.5 text-slate-500"></i>

                                            Empresa

                                        </span>

                                        <i data-lucide="lock" class="w-3.5 h-3.5 text-slate-500"></i>

                                    </label>

                                    <div
                                        class="w-full bg-[#030712]/50 border border-slate-800/80 rounded-xl px-4 py-2.5 text-xs text-slate-400 flex items-center justify-between gap-3">

                                        <span class="truncate">
                                            Cymez
                                        </span>

                                        <span
                                            class="text-[9px] uppercase tracking-wider text-slate-500 bg-slate-900 px-2 py-0.5 rounded border border-slate-800 whitespace-nowrap">

                                            Fijo

                                        </span>

                                    </div>

                                </div>

                                <div class="space-y-1.5">

                                    <label
                                        class="flex items-center justify-between text-xs font-semibold text-slate-300">

                                        <span class="flex items-center gap-1.5">

                                            <i data-lucide="mail" class="w-3.5 h-3.5 text-blue-400"></i>

                                            Correo electrónico

                                        </span>

                                        <span class="text-[10px] text-blue-400 font-normal flex items-center gap-1">

                                            <i data-lucide="pen" class="w-3 h-3"></i>

                                            Editable

                                        </span>

                                    </label>

                                    <input type="email" name="email" value="{{ auth()->user()->email }}"
                                        class="w-full bg-[#030712] border border-slate-700/80 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">

                                </div>

                                <div class="space-y-1.5">

                                    <label
                                        class="flex items-center justify-between text-xs font-semibold text-slate-400">

                                        <span class="flex items-center gap-1.5">

                                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-500"></i>

                                            Oficina / Sucursal

                                        </span>

                                        <i data-lucide="lock" class="w-3.5 h-3.5 text-slate-500"></i>

                                    </label>

                                    <div
                                        class="w-full bg-[#030712]/50 border border-slate-800/80 rounded-xl px-4 py-2.5 text-xs text-slate-400 flex items-center justify-between gap-3">

                                        <span class="truncate">
                                            Reynosa, Centro
                                        </span>

                                        <span
                                            class="text-[9px] uppercase tracking-wider text-slate-500 bg-slate-900 px-2 py-0.5 rounded border border-slate-800 whitespace-nowrap">

                                            Fijo

                                        </span>

                                    </div>

                                </div>

                                <div class="space-y-1.5">

                                    <label
                                        class="flex items-center justify-between text-xs font-semibold text-slate-300">

                                        <span class="flex items-center gap-1.5">

                                            <i data-lucide="briefcase-business" class="w-3.5 h-3.5 text-blue-400"></i>

                                            Departamento

                                        </span>

                                        <span class="text-[10px] text-blue-400 font-normal flex items-center gap-1">

                                            <i data-lucide="pen" class="w-3 h-3"></i>

                                            Editable

                                        </span>

                                    </label>

                                    <input type="text" name="departamento"
                                        value="{{ auth()->user()->departamento->nombre ?? '' }}"
                                        class="w-full bg-[#030712] border border-slate-700/80 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">

                                </div>

                                <div class="space-y-1.5">

                                    <label
                                        class="flex items-center justify-between text-xs font-semibold text-slate-400">

                                        <span class="flex items-center gap-1.5">

                                            <i data-lucide="building" class="w-3.5 h-3.5 text-slate-500"></i>

                                            Ubicación física

                                        </span>

                                        <i data-lucide="lock" class="w-3.5 h-3.5 text-slate-500"></i>

                                    </label>

                                    <div
                                        class="w-full bg-[#030712]/50 border border-slate-800/80 rounded-xl px-4 py-2.5 text-xs text-slate-400 flex items-center justify-between gap-3">

                                        <span class="truncate">
                                            Edificio A, piso 2
                                        </span>

                                        <span
                                            class="text-[9px] uppercase tracking-wider text-slate-500 bg-slate-900 px-2 py-0.5 rounded border border-slate-800 whitespace-nowrap">

                                            Fijo

                                        </span>

                                    </div>

                                </div>

                            </div>

                            <div class="pt-4 flex justify-end">

                                <button type="submit"
                                    class="flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 shadow-lg shadow-blue-600/30 hover:opacity-90 transition">

                                    <i data-lucide="save" class="w-4 h-4"></i>

                                    Guardar cambios

                                </button>

                            </div>

                            <div x-cloak x-show="confirmar" x-transition.opacity
                                class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
                                @keydown.escape.window="confirmar = false">

                                <div x-show="confirmar" x-transition @click.outside="confirmar = false"
                                    class="w-full max-w-md bg-[#0b1026] border border-blue-900/50 rounded-2xl shadow-2xl shadow-black/50 p-6">

                                    <div class="flex items-start gap-4">

                                        <div
                                            class="flex items-center justify-center w-11 h-11 rounded-xl bg-blue-500/10 text-blue-400 shrink-0">

                                            <i data-lucide="triangle-alert" class="w-5 h-5"></i>

                                        </div>

                                        <div>

                                            <h3 class="text-base font-bold text-white">
                                                Confirmar cambios
                                            </h3>

                                            <p class="text-sm text-slate-400 mt-1.5 leading-relaxed">
                                                ¿Estás seguro de que quieres cambiar esta información?
                                            </p>

                                        </div>

                                    </div>

                                    <div class="flex justify-end gap-3 mt-6">

                                        <button type="button" @click="confirmar = false"
                                            class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-300 bg-slate-900 border border-slate-700 hover:bg-slate-800 transition">

                                            Cancelar

                                        </button>

                                        <button type="button" @click="$el.closest('form').submit()"
                                            class="px-4 py-2.5 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:opacity-90 transition shadow-lg shadow-blue-600/20">

                                            Sí, guardar cambios

                                        </button>

                                    </div>

                                </div>

                            </div>

                        </form>

                    </div>

                    <div class="bg-[#0b1026]/90 border border-blue-900/40 rounded-2xl p-6 shadow-xl backdrop-blur-md">

                        <div class="flex items-center gap-3 mb-4">

                            <div class="p-2.5 rounded-xl bg-blue-600/20 text-blue-400 shrink-0">

                                <i data-lucide="shield-check" class="w-5 h-5"></i>

                            </div>

                            <div>

                                <h2 class="text-base font-bold text-white">
                                    Seguridad de tu cuenta
                                </h2>

                                <p class="text-xs text-slate-400">
                                    Administra las credenciales de acceso a tu perfil administrativo
                                </p>

                            </div>

                        </div>

                        <div
                            class="bg-[#030712] border border-slate-800 rounded-xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">

                            <div class="flex items-center gap-3">

                                <div class="p-2.5 rounded-lg bg-slate-900 border border-slate-800 text-slate-400">

                                    <i data-lucide="key-round" class="w-5 h-5"></i>

                                </div>

                                <div>

                                    <h4 class="text-xs font-bold text-white">
                                        Contraseña de acceso
                                    </h4>

                                    <p class="text-xs text-gray-400 mt-0.5">

                                        Última actualización:

                                        {{ Auth::user()->password_updated_at
                                            ? Auth::user()->password_updated_at->locale('es')->translatedFormat('d M Y')
                                            : 'No registrada' }}

                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="space-y-6" x-data="{
                    confirmarActualizar: false,
                    confirmarEliminar: false
                }">

                    <form action="{{ route('perfil.update') }}" method="POST" enctype="multipart/form-data"
                        @submit.prevent="confirmarActualizar = true">

                        @csrf
                        @method('PUT')

                        <div
                            class="bg-[#0b1026]/90 border border-blue-900/40 rounded-2xl p-6 shadow-xl backdrop-blur-md text-center">

                            <div class="flex items-center justify-center gap-2 mb-5">

                                <i data-lucide="camera" class="w-4 h-4 text-blue-400"></i>

                                <h3 class="text-sm font-bold text-white">
                                    Foto de perfil
                                </h3>

                            </div>

                            <div class="relative w-36 h-36 mx-auto mb-4 group">

                                <img id="profileImage"
                                    src="{{ auth()->user()->foto ? asset('storage/' . auth()->user()->foto) : asset('images/default-avatar.png') }}"
                                    alt="{{ auth()->user()->name }}"
                                    class="w-full h-full rounded-full object-cover ring-4 ring-blue-500/30 group-hover:ring-blue-500/60 transition duration-300">

                                <input type="file" name="foto" id="photoInput" accept="image/jpeg,image/png"
                                    class="hidden">

                                <button type="button" id="cameraButton"
                                    class="absolute bottom-2 right-2 bg-white text-[#060818] p-2.5 rounded-full shadow-lg hover:bg-gray-200 transition cursor-pointer">

                                    <i data-lucide="camera" class="w-4 h-4"></i>

                                </button>

                            </div>

                            <p class="text-[11px] text-slate-400">
                                Formatos permitidos: JPG, PNG
                            </p>

                            <p class="text-[10px] text-slate-500 mb-6">
                                Tamaño máximo: 2 MB
                            </p>

                            <div class="grid grid-cols-2 gap-3">

                                <button type="submit"
                                    class="px-3 py-2 rounded-xl text-xs font-semibold bg-blue-600 hover:bg-blue-500 text-white shadow-md shadow-blue-600/30 transition">

                                    <i data-lucide="upload" class="w-3.5 h-3.5 inline-block mr-1"></i>

                                    Actualizar foto

                                </button>

                                @if (auth()->user()->foto)
                                    <button type="button" id="deletePhotoButton" @click="confirmarEliminar = true"
                                        class="px-3 py-2 rounded-xl text-xs font-semibold bg-slate-900 border border-slate-800 text-rose-400 hover:bg-rose-500/10 transition">

                                        <i data-lucide="trash-2" class="w-3.5 h-3.5 inline-block mr-1"></i>

                                        Eliminar foto

                                    </button>
                                @endif

                            </div>

                            @error('foto')
                                <p class="text-[10px] text-rose-400 mt-3">
                                    {{ $message }}
                                </p>
                            @enderror

                            @if (session('success'))
                                <p class="text-[10px] text-emerald-400 mt-3">
                                    {{ session('success') }}
                                </p>
                            @endif

                        </div>

                    </form>

                    @if (auth()->user()->foto)
                        <form id="deletePhotoForm" action="{{ route('perfil.delete') }}" method="POST">

                            @csrf
                            @method('DELETE')

                        </form>
                    @endif

                    <div x-cloak x-show="confirmarActualizar" x-transition.opacity
                        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">

                        <div x-show="confirmarActualizar" x-transition @click.outside="confirmarActualizar = false"
                            @keydown.escape.window="confirmarActualizar = false"
                            class="w-full max-w-md bg-[#0b1026] border border-blue-900/50 rounded-2xl shadow-2xl shadow-black/50 p-6">

                            <div class="flex items-start gap-4">

                                <div
                                    class="flex items-center justify-center w-11 h-11 rounded-xl bg-blue-500/10 text-blue-400 shrink-0">

                                    <i data-lucide="camera" class="w-5 h-5"></i>

                                </div>

                                <div>

                                    <h3 class="text-base font-bold text-white">
                                        Confirmar actualización
                                    </h3>

                                    <p class="text-sm text-slate-400 mt-1.5 leading-relaxed">
                                        ¿Estás seguro de que quieres actualizar tu foto de perfil?
                                    </p>

                                </div>

                            </div>

                            <div class="flex justify-end gap-3 mt-6">

                                <button type="button" @click="confirmarActualizar = false"
                                    class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-300 bg-slate-900 border border-slate-700 hover:bg-slate-800 transition">

                                    Cancelar

                                </button>

                                <button type="button"
                                    @click="$el.closest('.space-y-6').querySelector('form[enctype]').submit()"
                                    class="px-4 py-2.5 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:opacity-90 transition shadow-lg shadow-blue-600/20">

                                    Sí, actualizar

                                </button>

                            </div>

                        </div>

                    </div>

                    <div x-cloak x-show="confirmarEliminar" x-transition.opacity
                        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">

                        <div x-show="confirmarEliminar" x-transition @click.outside="confirmarEliminar = false"
                            @keydown.escape.window="confirmarEliminar = false"
                            class="w-full max-w-md bg-[#0b1026] border border-rose-900/50 rounded-2xl shadow-2xl shadow-black/50 p-6">

                            <div class="flex items-start gap-4">

                                <div
                                    class="flex items-center justify-center w-11 h-11 rounded-xl bg-rose-500/10 text-rose-400 shrink-0">

                                    <i data-lucide="trash-2" class="w-5 h-5"></i>

                                </div>

                                <div>

                                    <h3 class="text-base font-bold text-white">
                                        Eliminar foto
                                    </h3>

                                    <p class="text-sm text-slate-400 mt-1.5 leading-relaxed">
                                        ¿Estás seguro de que quieres eliminar tu foto de perfil?
                                        Esta acción reemplazará tu foto actual por la imagen predeterminada.
                                    </p>

                                </div>

                            </div>

                            <div class="flex justify-end gap-3 mt-6">

                                <button type="button" @click="confirmarEliminar = false"
                                    class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-300 bg-slate-900 border border-slate-700 hover:bg-slate-800 transition">

                                    Cancelar

                                </button>

                                <button type="button" @click="document.getElementById('deletePhotoForm').submit()"
                                    class="px-4 py-2.5 rounded-xl text-xs font-semibold text-white bg-rose-600 hover:bg-rose-500 transition shadow-lg shadow-rose-600/20">

                                    Sí, eliminar

                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </main>
</body>

</html>
