<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="view-transition" content="same-origin">
    <title>Avisos - TicketPro</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/images/logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js' ])
    <script>
    window.avisosUsuarioConfig = {
        filtroTipo: @json(request('tipo', 'todos')),
        busqueda: @json(request('buscar', '')),
        avisosTotales: @json($avisosTodos),
        departamentos: @json($departamentos ?? []),
        usuarios: @json($usuarios ?? [])
    };
</script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="bg-[#060818] text-white font-sans antialiased flex h-screen overflow-hidden" x-data="avisosUsuario()">

    <aside class="hidden md:flex flex-col w-64 bg-[#0a0e27] border-r border-[#1e295d] px-4 py-6 overflow-y-auto">

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
                    {{ auth()->user()->name ?? 'Desconocido' }}
                </h3>

                <p class="text-xs text-gray-400">
                    {{ optional(auth()->user()->departamento)->nombre ?? 'Desconocido' }}
                </p>
            </div>
        </div>

        <nav class="flex-1 space-y-2">

            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-[#151b3b] hover:text-white rounded-lg transition">

                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>

                <span class="text-sm font-medium">
                    Inicio
                </span>
            </a>

            <a href="{{ route('misticketusuario') }}"
                class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-[#151b3b] hover:text-white rounded-lg transition">

                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                </svg>

                <span class="text-sm font-medium">
                    Mis tickets
                </span>
            </a>

            <a href="{{ route('ticketusuario') }}"
                class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-[#151b3b] hover:text-white rounded-lg transition">

                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                </svg>

                <span class="text-sm font-medium">
                    Crear ticket
                </span>
            </a>

            <a href="{{ route('avisosusuario') }}"
                class="flex items-center gap-3 px-4 py-3 bg-blue-600 text-white rounded-lg font-medium shadow-[0_0_15px_rgba(37,99,235,0.4)]">

                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>

                <span class="text-sm font-medium">
                    Avisos
                </span>
            </a>

            <a href="{{ route('perfilusuario') }}"
                class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-[#151b3b] hover:text-white rounded-lg transition">

                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>

                <span class="text-sm font-medium">
                    Mi perfil
                </span>
            </a>

        </nav>

        <div class="mt-auto pt-6">

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit"
                    class="w-full flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-[#151b3b] hover:text-white rounded-lg transition text-left">

                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>

                    <span class="text-sm font-medium">
                        Cerrar sesión
                    </span>

                </button>
            </form>

        </div>

    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-y-auto">

        <header
            class="flex flex-wrap justify-between items-center px-6 py-6 gap-4 border-b border-[#1e295d]/50 bg-[#060818]/80 backdrop-blur sticky top-0 z-10">

            <div>
                <h1 class="text-2xl font-bold tracking-tight text-white">
                    Avisos
                </h1>

                <p class="text-xs sm:text-sm text-gray-400 mt-1">
                    Mantente informado sobre mantenimientos, fallas y actualizaciones
                </p>
            </div>

            <div class="flex items-center gap-6 self-end md:self-auto">

                <div class="relative">

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

        <div class="px-6 my-6">

            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">

                <div class="flex items-center gap-2 overflow-x-auto pb-2 lg:pb-0">

                    <button type="button" @click="cambiarFiltro('todos')"
                        :class="filtroTipo === 'todos'
                            ?
                            'bg-blue-600 text-white shadow-md shadow-blue-600/30 border-blue-600' :
                            'bg-[#0f1535] border-[#1e295d] text-gray-300 hover:bg-[#151d45] hover:border-gray-500'"
                        class="px-5 py-2 rounded-lg border font-medium text-xs sm:text-sm whitespace-nowrap transition">
                        Todos
                    </button>

                    <button type="button" @click="cambiarFiltro('mantenimiento')"
                        :class="filtroTipo === 'mantenimiento'
                            ?
                            'bg-amber-600 text-white border-amber-500 shadow-md shadow-amber-600/20' :
                            'bg-[#3b2900] border-amber-600/60 text-amber-500 hover:bg-[#4d3600]'"
                        class="px-4 py-2 rounded-lg border transition font-medium text-xs sm:text-sm flex items-center gap-1.5 whitespace-nowrap">
                        Mantenimiento
                    </button>

                    <button type="button" @click="cambiarFiltro('incidente')"
                        :class="filtroTipo === 'incidente'
                            ?
                            'bg-red-600 text-white border-red-500 shadow-md shadow-red-600/20' :
                            'bg-[#3f0e0e] border-red-600/60 text-red-500 hover:bg-[#541313]'"
                        class="px-4 py-2 rounded-lg border transition font-medium text-xs sm:text-sm flex items-center gap-1.5 whitespace-nowrap">
                        Falla / Incidente
                    </button>

                    <button type="button" @click="cambiarFiltro('informativo')"
                        :class="filtroTipo === 'informativo'
                            ?
                            'bg-cyan-600 text-white border-cyan-500 shadow-md shadow-cyan-600/20' :
                            'bg-[#092c42] border-cyan-500/60 text-cyan-400 hover:bg-[#0c3956]'"
                        class="px-4 py-2 rounded-lg border transition font-medium text-xs sm:text-sm flex items-center gap-1.5 whitespace-nowrap">
                        Informativo
                    </button>

                    <button type="button" @click="cambiarFiltro('general')"
                        :class="filtroTipo === 'general'
                            ?
                            'bg-gray-600 text-white border-gray-500 shadow-md shadow-gray-600/20' :
                            'bg-[#0f1535] border-[#1e295d] text-gray-300 hover:bg-[#151d45] hover:border-gray-500'"
                        class="px-4 py-2 rounded-lg border transition font-medium text-xs sm:text-sm flex items-center gap-1.5 whitespace-nowrap">
                        General
                    </button>

                </div>

                <div class="relative w-full lg:w-64">

                    <input type="text" x-model="busqueda" @keydown.enter.prevent="buscarAvisos()"
                        placeholder="Buscar aviso..."
                        class="w-full bg-[#060818] border border-[#1e295d] rounded-full py-2 pl-4 pr-10 text-xs sm:text-sm text-gray-200 placeholder-gray-500 focus:outline-none focus:border-blue-500 transition">

                    <button type="button" x-show="busqueda.trim() !== ''" @click="limpiarBusqueda()" x-transition
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-white transition"
                        title="Limpiar búsqueda">

                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />

                        </svg>

                    </button>

                    <div x-show="busqueda.trim() === ''"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">

                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />

                        </svg>

                    </div>

                </div>

            </div>

        </div>

        <div class="px-6 pb-10">
            <div class="bg-[#0f1535] rounded-2xl border border-[#1e295d] p-5 sm:p-6 space-y-4">

                <template x-if="avisosPagina.length > 0">
                    <template x-for="aviso in avisosPagina" :key="aviso.id">

                        <div x-show="mostrarAviso(
                        aviso.tipo,
                        aviso.titulo,
                        aviso.descripcion
                    )"
                            x-transition.opacity.duration.200ms
                            class="bg-[#0b102b] border border-[#1e295d] rounded-xl p-5 flex flex-col lg:flex-row lg:items-center justify-between gap-5 hover:border-blue-500/40 transition">

                            <div class="flex flex-col sm:flex-row items-start gap-4 flex-1">

                                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl flex items-center justify-center shrink-0"
                                    :style="`background-color: ${
                                                                                                                                                                                                                    {
                                                                                                                                                                                                                        mantenimiento: '#3b2900',
                                                                                                                                                                                                                        incidente: '#3f0e0e',
                                                                                                                                                                                                                        informativo: '#092c42',
                                                                                                                                                                                                                        general: '#111827'
                                                                                                                                                                                                                    }[aviso.tipo] || '#111827'
                                                                                                                                                                                                                }`">

                                    <template x-if="aviso.tipo === 'mantenimiento'">
                                        <svg class="w-9 h-9 text-amber-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10.325 4.317a1.724 1.724 0 013.35 0 1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543-.94-3.31-.826-2.37-2.37a1.724 1.724 0 001.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 012.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </template>

                                    <template x-if="aviso.tipo === 'incidente'">
                                        <svg class="w-9 h-9 text-red-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </template>

                                    <template x-if="aviso.tipo === 'informativo'">
                                        <svg class="w-9 h-9 text-cyan-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </template>

                                    <template
                                        x-if="aviso.tipo !== 'mantenimiento' && aviso.tipo !== 'incidente' && aviso.tipo !== 'informativo'">
                                        <svg class="w-9 h-9 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                        </svg>
                                    </template>

                                </div>

                                <div class="space-y-1.5">

                                    <span
                                        class="inline-block px-2.5 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider"
                                        :style="`background-color: ${
                                                                                                                                                                                                                                            {
                                                                                                                                                                                                                                                mantenimiento: '#3b2900',
                                                                                                                                                                                                                                                incidente: '#3f0e0e',
                                                                                                                                                                                                                                                informativo: '#092c42',
                                                                                                                                                                                                                                                general: '#111827'
                                                                                                                                                                                                                                            }[aviso.tipo] || '#111827'
                                                                                                                                                                                                                                        }`"
                                        x-text="{
                                    mantenimiento: 'MANTENIMIENTO',
                                    incidente: 'FALLA / INCIDENTE',
                                    informativo: 'INFORMATIVO',
                                    general: 'GENERAL'
                                }[aviso.tipo] || 'GENERAL'">
                                    </span>

                                    <h3 class="text-sm sm:text-base font-bold text-white uppercase tracking-wide"
                                        x-text="aviso.titulo">
                                    </h3>

                                    <p class="text-xs sm:text-sm text-gray-300 leading-relaxed max-w-2xl line-clamp-2"
                                        x-text="aviso.descripcion">
                                    </p>

                                    <p class="text-xs text-gray-400 pt-1">
                                        <span class="font-semibold text-gray-300">
                                            Afecta a:
                                        </span>

                                        <span x-text="aviso.afecta_texto || 'Todos los usuarios'"></span>
                                    </p>

                                </div>
                            </div>

                            <div
                                class="flex lg:flex-col justify-between items-end shrink-0 border-t lg:border-t-0 border-[#1e295d] pt-3 lg:pt-0 gap-2">

                                <span class="px-3 py-1 rounded-md text-xs font-semibold"
                                    :class="{
                                        'bg-red-950 border border-red-500 text-red-400': aviso
                                            .importancia === 'critica',
                                    
                                        'bg-orange-950 border border-orange-500 text-orange-400': aviso
                                            .importancia === 'alta',
                                    
                                        'bg-yellow-950 border border-yellow-500 text-yellow-400': aviso
                                            .importancia === 'media',
                                    
                                        'bg-slate-800 border border-slate-600 text-slate-300':
                                            !['critica', 'alta', 'media'].includes(aviso.importancia)
                                    }"
                                    x-text="aviso.importancia
                                ? aviso.importancia.charAt(0).toUpperCase() + aviso.importancia.slice(1)
                                : ''">
                                </span>

                                <div class="text-right text-[11px] text-gray-400 space-y-0.5">

                                    <p class="flex items-center justify-end gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>

                                        <span x-text="formatearFecha(aviso.fecha_inicio)">
                                        </span>
                                    </p>

                                    <p class="flex items-center justify-end gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>

                                        <span x-text="formatearHora(aviso.fecha_inicio)">
                                        </span>
                                    </p>

                                </div>

                                <button type="button" @click="abrirAviso(aviso)"
                                    class="text-xs font-semibold text-blue-400 hover:text-blue-300 flex items-center gap-1 transition">

                                    Ver más

                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>

                                </button>

                            </div>
                        </div>

                    </template>
                </template>

                <template x-if="avisosPagina.length === 0">
                    <div class="text-center py-16">

                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-slate-800 flex items-center justify-center">

                            <svg class="w-8 h-8 text-slate-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01M12 5a7 7 0 100 14 7 7 0 000-14z" />
                            </svg>

                        </div>

                        <h3 class="text-lg font-semibold text-white">
                            No hay avisos
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Actualmente no existen avisos disponibles.
                        </p>

                    </div>
                </template>

                <div x-show="avisosTotales.length > 0"
                    class="mt-6 pt-4 border-t border-[#1e295d] flex flex-col sm:flex-row justify-between items-center gap-4">

                    <p class="text-sm font-semibold text-gray-200">

                        Mostrando

                        <span
                            x-text="avisosTotales.length === 0
                        ? 0
                        : ((paginaActual - 1) * porPagina) + 1">
                        </span>

                        a

                        <span
                            x-text="Math.min(
                        paginaActual * porPagina,
                        avisosTotales.length
                    )">
                        </span>

                        de

                        <span x-text="avisosTotales.length"></span>

                        avisos

                    </p>

                    <div x-show="totalPaginas > 1" class="flex items-center gap-2">

                        <button type="button" @click="paginaAnterior()" :disabled="paginaActual === 1"
                            class="w-9 h-9 rounded-lg bg-[#060818] border border-[#1e295d] flex items-center justify-center transition"
                            :class="paginaActual === 1 ?
                                'text-gray-600 cursor-not-allowed' :
                                'text-gray-400 hover:text-white hover:border-blue-500'">

                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>

                        </button>

                        <template x-for="pagina in totalPaginas" :key="pagina">

                            <button type="button" @click="irPagina(pagina)"
                                class="w-9 h-9 rounded-lg font-semibold text-sm flex items-center justify-center transition"
                                :class="pagina === paginaActual ?
                                    'bg-blue-600 text-white shadow-[0_0_10px_rgba(37,99,235,0.4)]' :
                                    'bg-[#060818] border border-[#1e295d] text-gray-300 hover:border-blue-500'">

                                <span x-text="pagina"></span>

                            </button>

                        </template>

                        <button type="button" @click="paginaSiguiente()" :disabled="paginaActual === totalPaginas"
                            class="w-9 h-9 rounded-lg bg-[#060818] border border-[#1e295d] flex items-center justify-center transition"
                            :class="paginaActual === totalPaginas ?
                                'text-gray-600 cursor-not-allowed' :
                                'text-gray-400 hover:text-white hover:border-blue-500'">

                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7-7" />
                            </svg>

                        </button>

                    </div>
                </div>

                <div x-cloak x-show="avisosTotales.length > 0 && avisosPagina.length === 0"
                    x-transition.opacity.duration.200ms class="text-center py-16">

                    <div
                        class="w-16 h-16 mx-auto mb-4 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center">

                        <svg class="w-8 h-8 text-slate-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>

                    </div>

                    <h3 class="text-lg font-semibold text-white">
                        No se encontraron avisos
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        No hay
                        <span class="text-gray-400" x-text="nombreFiltro()">
                        </span>
                        que coincidan con tu búsqueda.
                    </p>

                    <button type="button" @click="limpiarFiltros()"
                        class="mt-5 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold transition">

                        Limpiar filtros

                    </button>

                </div>

            </div>
        </div>

    </main>

    <div x-cloak x-show="modalAbierto" @keydown.escape.window.prevent="cerrarAviso()" @click.self="cerrarAviso()"
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">

        <div x-show="modalAbierto" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative w-full max-w-3xl max-h-[90vh] overflow-y-auto bg-[#0b102b] border border-[#26345f] rounded-2xl shadow-2xl">

            <div class="sticky top-0 z-10 bg-[#0b102b]/95 backdrop-blur border-b border-[#1e295d] px-6 py-5">

                <div class="flex items-start justify-between gap-4">

                    <div class="flex items-start gap-4">

                        <div class="w-14 h-14 rounded-xl flex items-center justify-center shrink-0"
                            :class="{
                                'bg-[#3b2900]': avisoSeleccionado.tipo === 'mantenimiento',
                                'bg-[#3f0e0e]': avisoSeleccionado.tipo === 'incidente',
                                'bg-[#092c42]': avisoSeleccionado.tipo === 'informativo',
                                'bg-[#111827]': avisoSeleccionado.tipo === 'general'
                            }">

                            <template x-if="avisoSeleccionado.tipo === 'mantenimiento'">

                                <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317a1.724 1.724 0 013.35 0 1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543-.94-3.31.826-2.37-2.37a1.724 1.724 0 001.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 012.572-1.065z" />

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />

                                </svg>

                            </template>

                            <template x-if="avisoSeleccionado.tipo === 'incidente'">

                                <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />

                                </svg>

                            </template>

                            <template x-if="avisoSeleccionado.tipo === 'informativo'">

                                <svg class="w-7 h-7 text-cyan-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />

                                </svg>

                            </template>

                            <template x-if="avisoSeleccionado.tipo === 'general'">

                                <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 10h16M4 14h16M4 18h16" />

                                </svg>

                            </template>

                        </div>

                        <div>

                            <span class="inline-block px-2.5 py-1 rounded-md text-[10px] font-bold tracking-wider"
                                :class="{
                                    'bg-[#3b2900] text-amber-400': avisoSeleccionado.tipo === 'mantenimiento',
                                    'bg-[#3f0e0e] text-red-400': avisoSeleccionado.tipo === 'incidente',
                                    'bg-[#092c42] text-cyan-400': avisoSeleccionado.tipo === 'informativo',
                                    'bg-[#111827] text-gray-400': avisoSeleccionado.tipo === 'general'
                                }"
                                x-text="tipoLabel(avisoSeleccionado.tipo)">
                            </span>

                            <h2 class="text-xl font-bold text-white mt-2" x-text="avisoSeleccionado.titulo"></h2>

                        </div>

                    </div>

                    <button type="button" @click="cerrarAviso()">
                        <i data-lucide="x"></i>
                    </button>

                </div>

            </div>

            <div class="p-6 space-y-6">

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

                    <div class="bg-[#080d22] border border-[#1e295d] rounded-xl p-4">

                        <p class="text-[10px] uppercase tracking-wider text-gray-500 font-semibold">
                            Importancia
                        </p>

                        <span class="inline-flex mt-2 px-3 py-1 rounded-md text-xs font-semibold"
                            :class="{
                                'bg-red-950 border border-red-500 text-red-400': avisoSeleccionado
                                    .importancia === 'critica',
                                'bg-orange-950 border border-orange-500 text-orange-400': avisoSeleccionado
                                    .importancia === 'alta',
                                'bg-yellow-950 border border-yellow-500 text-yellow-400': avisoSeleccionado
                                    .importancia === 'media',
                                'bg-slate-800 border border-slate-600 text-slate-300': avisoSeleccionado
                                    .importancia === 'normal'
                            }"
                            x-text="capitalizar(avisoSeleccionado.importancia)">
                        </span>

                    </div>

                    <div class="bg-[#080d22] border border-[#1e295d] rounded-xl p-4">

                        <p class="text-[10px] uppercase tracking-wider text-gray-500 font-semibold">
                            Fecha
                        </p>

                        <p class="text-sm text-white mt-2">
                            <span x-text="formatearFecha(avisoSeleccionado.fecha_inicio)"></span>
                        </p>

                    </div>

                    {{-- <div class="bg-[#080d22] border border-[#1e295d] rounded-xl p-4">

                        <p class="text-[10px] uppercase tracking-wider text-gray-500 font-semibold">
                            Finaliza
                        </p>

                        <p class="text-sm text-white mt-2"
                            x-text="avisoSeleccionado.fecha_fin ? formatearFecha(avisoSeleccionado.fecha_fin) : 'Sin fecha de finalización'">
                        </p>

                    </div> --}}

                </div>

                <div>

                    <div class="flex items-center gap-2 mb-3">

                        <div
                            class="w-8 h-8 rounded-lg bg-blue-500/10 border border-blue-500/20 flex items-center justify-center">

                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h10" />

                            </svg>

                        </div>

                        <h3 class="text-sm font-semibold text-white">
                            Detalles del aviso
                        </h3>

                    </div>

                    <div class="bg-[#080d22] border border-[#1e295d] rounded-xl p-5">

                        <p class="text-sm text-gray-300 leading-7 whitespace-pre-line"
                            x-text="avisoSeleccionado.descripcion">
                        </p>

                    </div>

                </div>

                <div>

                    <div class="flex items-center gap-2 mb-3">

                        <div
                            class="w-8 h-8 rounded-lg bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center">

                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />

                            </svg>

                        </div>

                        <h3 class="text-sm font-semibold text-white">
                            Afecta a
                        </h3>

                    </div>

                    <div class="bg-[#080d22] border border-[#1e295d] rounded-xl p-5">

                        <template x-if="avisoSeleccionado.afecta_a?.tipo === 'todos'">

                            <div class="flex items-center gap-3">

                                <div
                                    class="w-10 h-10 rounded-lg bg-blue-500/10 border border-blue-500/20 flex items-center justify-center">

                                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 21h18M5 21V5l7-3 7 3v16M9 9h6M9 13h6M9 17h6" />

                                    </svg>

                                </div>

                                <div>

                                    <p class="text-sm font-semibold text-white">
                                        Todos los usuarios
                                    </p>

                                    <p class="text-xs text-gray-500 mt-0.5">
                                        Aplica a toda la empresa
                                    </p>

                                </div>

                            </div>

                        </template>

                        <template x-if="avisoSeleccionado.afecta_a?.tipo === 'departamentos'">

                            <div>

                                <p class="text-xs text-gray-500 mb-3">
                                    Este aviso aplica a los siguientes departamentos:
                                </p>

                                <div class="flex flex-wrap gap-2">

                                    <template x-for="nombre in obtenerDepartamentos(avisoSeleccionado.afecta_a.ids)"
                                        :key="nombre">

                                        <span
                                            class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-xs font-medium">

                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">

                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 21h18M5 21V5l7-3 7 3v16M9 9h6M9 13h6M9 17h6" />

                                            </svg>

                                            <span x-text="nombre"></span>

                                        </span>

                                    </template>

                                </div>

                            </div>

                        </template>

                        <template x-if="avisoSeleccionado.afecta_a?.tipo === 'usuarios'">

                            <div>

                                <p class="text-xs text-gray-500 mb-3">
                                    Este aviso está dirigido a:
                                </p>

                                <div class="space-y-2">

                                    <template x-for="usuario in obtenerUsuarios(avisoSeleccionado.afecta_a.ids)"
                                        :key="usuario.id">

                                        <div
                                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-slate-900 border border-slate-800">

                                            <div
                                                class="w-8 h-8 rounded-full bg-blue-500/10 border border-blue-500/20 flex items-center justify-center">

                                                <svg class="w-4 h-4 text-blue-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">

                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />

                                                </svg>

                                            </div>

                                            <div>

                                                <p class="text-xs font-semibold text-white" x-text="usuario.name">
                                                </p>

                                                <p class="text-[11px] text-gray-500" x-text="usuario.departamento">
                                                </p>

                                            </div>

                                        </div>

                                    </template>

                                </div>

                            </div>

                        </template>

                    </div>

                </div>

                <template x-if="avisoSeleccionado.archivo">

                    <div>

                        <div class="flex items-center gap-2 mb-3">

                            <div
                                class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center">

                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.586-6.586a4 4 0 10-5.656-5.656l-6.586 6.586a6 6 0 108.485 8.485L18 13" />

                                </svg>

                            </div>

                            <h3 class="text-sm font-semibold text-white">
                                Archivo adjunto
                            </h3>

                        </div>

                        <div class="bg-[#080d22] border border-[#1e295d] rounded-xl overflow-hidden">

                            <template x-if="esImagen(avisoSeleccionado.archivo)">

                                <a :href="urlArchivo(avisoSeleccionado.archivo)" target="_blank" class="block group">

                                    <img :src="urlArchivo(avisoSeleccionado.archivo)"
                                        class="w-full max-h-[450px] object-contain bg-black/30" alt="Archivo adjunto">

                                    <div
                                        class="px-4 py-3 border-t border-[#1e295d] flex items-center justify-between group-hover:bg-slate-800/50 transition">

                                        <span class="text-xs text-gray-400">
                                            Ver imagen en tamaño completo
                                        </span>

                                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />

                                        </svg>

                                    </div>

                                </a>

                            </template>

                            <template x-if="esPdf(avisoSeleccionado.archivo)">

                                <div>

                                    <iframe :src="urlArchivo(avisoSeleccionado.archivo)" class="w-full h-[450px]"
                                        frameborder="0">
                                    </iframe>

                                    <a :href="urlArchivo(avisoSeleccionado.archivo)" target="_blank"
                                        class="flex items-center justify-center gap-2 px-4 py-3 border-t border-[#1e295d] text-xs text-blue-400 hover:bg-slate-800/50 transition">

                                        Abrir PDF en otra pestaña

                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />

                                        </svg>

                                    </a>

                                </div>

                            </template>

                            <template x-if="esVideo(avisoSeleccionado.archivo)">

                                <video :src="urlArchivo(avisoSeleccionado.archivo)" controls
                                    class="w-full max-h-[450px] bg-black">
                                </video>

                            </template>

                            <template
                                x-if="!esImagen(avisoSeleccionado.archivo) && !esPdf(avisoSeleccionado.archivo) && !esVideo(avisoSeleccionado.archivo)">

                                <a :href="urlArchivo(avisoSeleccionado.archivo)" target="_blank"
                                    class="flex items-center gap-4 p-5 hover:bg-slate-800/50 transition">

                                    <div
                                        class="w-12 h-12 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center">

                                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />

                                        </svg>

                                    </div>

                                    <div class="flex-1">

                                        <p class="text-sm font-semibold text-white">
                                            Archivo adjunto
                                        </p>

                                        <p class="text-xs text-gray-500 mt-1">
                                            Haz clic para abrir el archivo
                                        </p>

                                    </div>

                                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />

                                    </svg>

                                </a>

                            </template>

                        </div>

                    </div>

                </template>

                <template x-if="!avisoSeleccionado.archivo">

                    <div class="rounded-xl border border-dashed border-[#1e295d] bg-[#080d22] p-5 text-center">

                        <svg class="w-7 h-7 text-gray-600 mx-auto mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.586-6.586a4 4 0 10-5.656-5.656l-6.586 6.586a6 6 0 108.485 8.485L18 13" />

                        </svg>

                        <p class="text-xs text-gray-500">
                            Este aviso no tiene archivos adjuntos.
                        </p>

                    </div>

                </template>

            </div>

            <div class="px-6 py-4 border-t border-[#1e295d] bg-[#080d22] flex justify-end">

                <button type="button" @click="cerrarAviso()">
                    Cerrar
                </button>

            </div>

        </div>

    </div>

  

</body>

</html>
