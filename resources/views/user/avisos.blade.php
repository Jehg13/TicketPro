<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Avisos - TicketPro</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#060818] text-white font-sans antialiased flex h-screen overflow-hidden" x-data="avisosUsuario()">
    <!-- SIDEBAR -->
    <aside class="hidden md:flex flex-col w-64 bg-[#0a0e27] border-r border-[#1e295d] px-4 py-6 overflow-y-auto">

        <div class="text-3xl font-bold mb-8 px-2 tracking-wide">
            Ticket<span class="text-blue-500">Pro</span>
        </div>

        <div class="flex items-center gap-3 mb-10 px-2">

            <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="{{ auth()->user()->name }}"
                class="w-12 h-12 rounded-full border-2 border-gray-500 object-cover">

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


    <!-- CONTENIDO -->
    <main class="flex-1 flex flex-col h-screen overflow-y-auto">

        <!-- HEADER -->
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

                <!-- NOTIFICACIONES -->
                <div class="relative">

                    <button id="notif-button" type="button"
                        class="relative p-2 text-gray-300 hover:text-white bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700/50 rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 group shadow-lg">

                        <svg class="w-6 h-6 transition-transform group-hover:scale-110 duration-200" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />

                        </svg>

                        <span class="absolute top-1.5 right-1.5 flex h-3 w-3">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                            <span
                                class="relative inline-flex rounded-full h-3 w-3 bg-rose-500 border-2 border-slate-900"></span>
                        </span>

                    </button>


                    <div id="notif-dropdown"
                        class="hidden absolute right-0 mt-3 w-80 sm:w-96 rounded-2xl bg-slate-900/95 backdrop-blur-md border border-slate-800 shadow-2xl z-50 overflow-hidden">

                        <div class="p-4 flex items-center justify-between border-b border-slate-800">

                            <div class="flex items-center gap-2">

                                <h3 class="text-sm font-semibold text-white">
                                    Notificaciones
                                </h3>

                                <span
                                    class="px-2 py-0.5 text-xs font-medium bg-indigo-500/10 text-indigo-400 rounded-full border border-indigo-500/20">
                                    3 nuevas
                                </span>

                            </div>

                            <button type="button"
                                class="text-xs text-indigo-400 hover:text-indigo-300 font-medium transition-colors">
                                Marcar leídas
                            </button>

                        </div>

                        <div class="max-h-80 overflow-y-auto">

                            <a href="#"
                                class="flex gap-3 p-4 bg-slate-800/40 hover:bg-slate-800/80 transition-colors group border-b border-slate-800/50">

                                <div class="relative shrink-0">

                                    <img class="w-10 h-10 rounded-full object-cover ring-2 ring-indigo-500/30"
                                        src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80"
                                        alt="Avatar">

                                    <span
                                        class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-slate-900 rounded-full"></span>

                                </div>

                                <div class="flex-1 min-w-0">

                                    <p class="text-xs text-slate-300 leading-relaxed">

                                        <strong class="font-semibold text-white">
                                            Elena Rostova
                                        </strong>

                                        comentó en tu proyecto

                                        <span class="text-slate-400">
                                            Dashboard UI
                                        </span>.

                                    </p>

                                    <span class="text-[10px] text-slate-500 mt-1 block">
                                        Hace 2 minutos
                                    </span>

                                </div>

                                <span class="w-2 h-2 rounded-full bg-indigo-500 shrink-0 self-center"></span>

                            </a>

                            <a href="#"
                                class="flex gap-3 p-4 hover:bg-slate-800/50 transition-colors group border-b border-slate-800/50">

                                <div
                                    class="w-10 h-10 rounded-full bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 shrink-0">

                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />

                                    </svg>

                                </div>

                                <div class="flex-1 min-w-0">

                                    <p class="text-xs text-slate-300 leading-relaxed">

                                        Tu despliegue en

                                        <strong class="font-semibold text-white">
                                            Vite/Production
                                        </strong>

                                        se completó con éxito.

                                    </p>

                                    <span class="text-[10px] text-slate-500 mt-1 block">
                                        Hace 1 hora
                                    </span>

                                </div>

                            </a>

                        </div>

                        <a href="#"
                            class="block p-3 text-center text-xs font-medium text-slate-400 hover:text-white hover:bg-slate-800/50 transition-colors">

                            Ver todas las notificaciones

                        </a>

                    </div>

                </div>


                <!-- USUARIO -->
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

        </header>


        <!-- FILTROS -->
        <div class="px-6 my-6">

            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">

                <!-- TIPOS -->
                <div class="flex items-center gap-2 overflow-x-auto pb-2 lg:pb-0">

                    <!-- TODOS -->
                    <button type="button" @click="filtroTipo = 'todos'"
                        :class="filtroTipo === 'todos'
                            ?
                            'bg-blue-600 text-white shadow-md shadow-blue-600/30 border-blue-600' :
                            'bg-[#060818] border-[#1e295d] text-gray-300 hover:border-gray-500'"
                        class="px-5 py-2 rounded-lg border font-medium text-xs sm:text-sm whitespace-nowrap transition">

                        Todos

                    </button>


                    <!-- MANTENIMIENTO -->
                    <button type="button" @click="filtroTipo = 'mantenimiento'"
                        :class="filtroTipo === 'mantenimiento'
                            ?
                            'bg-amber-600 text-white border-amber-500 shadow-md shadow-amber-600/20' :
                            'bg-[#3b2900] border-amber-600/60 text-amber-500 hover:bg-[#4d3600]'"
                        class="px-4 py-2 rounded-lg border transition font-medium text-xs sm:text-sm flex items-center gap-1.5 whitespace-nowrap">

                        Mantenimiento

                    </button>


                    <!-- INCIDENTE -->
                    <button type="button" @click="filtroTipo = 'incidente'"
                        :class="filtroTipo === 'incidente'
                            ?
                            'bg-red-600 text-white border-red-500 shadow-md shadow-red-600/20' :
                            'bg-[#3f0e0e] border-red-600/60 text-red-500 hover:bg-[#541313]'"
                        class="px-4 py-2 rounded-lg border transition font-medium text-xs sm:text-sm flex items-center gap-1.5 whitespace-nowrap">

                        Falla / Incidente

                    </button>


                    <!-- INFORMATIVO -->
                    <button type="button" @click="filtroTipo = 'informativo'"
                        :class="filtroTipo === 'informativo'
                            ?
                            'bg-cyan-600 text-white border-cyan-500 shadow-md shadow-cyan-600/20' :
                            'bg-[#092c42] border-cyan-500/60 text-cyan-400 hover:bg-[#0c3956]'"
                        class="px-4 py-2 rounded-lg border transition font-medium text-xs sm:text-sm flex items-center gap-1.5 whitespace-nowrap">

                        Informativo

                    </button>


                    <!-- GENERAL -->
                    <button type="button" @click="filtroTipo = 'general'"
                        :class="filtroTipo === 'general'
                            ?
                            'bg-gray-600 text-white border-gray-500 shadow-md shadow-gray-600/20' :
                            'bg-[#060818] border-[#1e295d] text-gray-300 hover:border-gray-500'"
                        class="px-4 py-2 rounded-lg border transition font-medium text-xs sm:text-sm flex items-center gap-1.5 whitespace-nowrap">

                        General

                    </button>

                </div>


                <!-- BUSCADOR -->
                <div class="relative w-full lg:w-64">

                    <input type="text" x-model="busqueda" placeholder="Buscar aviso..."
                        class="w-full bg-[#060818] border border-[#1e295d] rounded-full py-2 pl-4 pr-10 text-xs sm:text-sm text-gray-200 placeholder-gray-500 focus:outline-none focus:border-blue-500 transition">

                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">

                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />

                        </svg>

                    </div>

                </div>

            </div>

        </div>


        <!-- LISTADO -->
        <div class="px-6 pb-10">

            <div class="bg-[#0f1535] rounded-2xl border border-[#1e295d] p-5 sm:p-6 space-y-4">

                @forelse($avisos ?? [] as $aviso)
                    @php

                        $tipo = $aviso->tipo;

                        $tipoConfig = [
                            'mantenimiento' => [
                                'label' => 'MANTENIMIENTO',
                                'bg' => '#3b2900',
                            ],

                            'incidente' => [
                                'label' => 'FALLA / INCIDENTE',
                                'bg' => '#3f0e0e',
                            ],

                            'informativo' => [
                                'label' => 'INFORMATIVO',
                                'bg' => '#092c42',
                            ],

                            'general' => [
                                'label' => 'GENERAL',
                                'bg' => '#111827',
                            ],
                        ];

                        $config = $tipoConfig[$tipo] ?? $tipoConfig['general'];

                        $afecta = $aviso->afecta_a;

                        if (is_string($afecta)) {
                            $decoded = json_decode($afecta, true);

                            if (json_last_error() === JSON_ERROR_NONE) {
                                $afecta = $decoded;
                            }
                        }

                        $afectaTexto = 'Todos los usuarios';

                        if (is_array($afecta)) {
                            if (($afecta['tipo'] ?? null) === 'todos') {
                                $afectaTexto = 'Todos los usuarios — Aplica a toda la empresa';
                            } elseif (($afecta['tipo'] ?? null) === 'departamentos') {
                                $ids = $afecta['ids'] ?? [];

                                $nombres = \App\Models\Departamento::whereIn('id', $ids)
                                    ->orderBy('nombre')
                                    ->pluck('nombre')
                                    ->toArray();

                                $afectaTexto = !empty($nombres)
                                    ? implode(', ', $nombres)
                                    : 'Departamentos seleccionados';
                            } elseif (($afecta['tipo'] ?? null) === 'usuarios') {
                                $ids = $afecta['ids'] ?? [];

                                $nombres = \App\Models\User::whereIn('id', $ids)
                                    ->orderBy('name')
                                    ->pluck('name')
                                    ->toArray();

                                $afectaTexto = !empty($nombres) ? implode(', ', $nombres) : 'Usuarios seleccionados';
                            }
                        }

                    @endphp


                    <!-- ================================================= -->
                    <!-- TARJETA DEL AVISO -->
                    <!-- ================================================= -->

                    <div x-show="mostrarAviso(
                    @js($aviso->tipo),
                    @js($aviso->titulo),
                    @js($aviso->descripcion)
                )"
                        x-transition.opacity.duration.200ms
                        class="bg-[#0b102b] border border-[#1e295d] rounded-xl p-5 flex flex-col lg:flex-row lg:items-center justify-between gap-5 hover:border-blue-500/40 transition">


                        <!-- INFORMACIÓN PRINCIPAL -->
                        <div class="flex flex-col sm:flex-row items-start gap-4 flex-1">


                            <!-- ICONO -->
                            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl flex items-center justify-center shrink-0"
                                style="background-color: {{ $config['bg'] }};">

                                @if ($tipo === 'mantenimiento')
                                    <svg class="w-9 h-9 text-amber-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10.325 4.317a1.724 1.724 0 013.35 0 1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543-.94-3.31.826-2.37-2.37a1.724 1.724 0 001.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 012.572-1.065z" />

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />

                                    </svg>
                                @elseif($tipo === 'incidente')
                                    <svg class="w-9 h-9 text-red-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />

                                    </svg>
                                @elseif($tipo === 'informativo')
                                    <svg class="w-9 h-9 text-cyan-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />

                                    </svg>
                                @else
                                    <svg class="w-9 h-9 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 10h16M4 14h16M4 18h16" />

                                    </svg>
                                @endif

                            </div>


                            <!-- INFORMACIÓN -->
                            <div class="space-y-1.5">

                                <span
                                    class="inline-block px-2.5 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider"
                                    style="background-color: {{ $config['bg'] }};">

                                    {{ $config['label'] }}

                                </span>


                                <h3 class="text-sm sm:text-base font-bold text-white uppercase tracking-wide">

                                    {{ $aviso->titulo }}

                                </h3>


                                <p class="text-xs sm:text-sm text-gray-300 leading-relaxed max-w-2xl line-clamp-2">

                                    {{ $aviso->descripcion }}

                                </p>


                                <p class="text-xs text-gray-400 pt-1">

                                    <span class="font-semibold text-gray-300">
                                        Afecta a:
                                    </span>

                                    {{ $afectaTexto }}

                                </p>

                            </div>

                        </div>


                        <!-- INFORMACIÓN LATERAL -->
                        <div
                            class="flex lg:flex-col justify-between items-end shrink-0 border-t lg:border-t-0 border-[#1e295d] pt-3 lg:pt-0 gap-2">


                            <!-- IMPORTANCIA -->
                            <span
                                class="px-3 py-1 rounded-md text-xs font-semibold

                        @if ($aviso->importancia === 'critica') bg-red-950 border border-red-500 text-red-400

                        @elseif($aviso->importancia === 'alta')
                            bg-orange-950 border border-orange-500 text-orange-400

                        @elseif($aviso->importancia === 'media')
                            bg-yellow-950 border border-yellow-500 text-yellow-400

                        @else
                            bg-slate-800 border border-slate-600 text-slate-300 @endif">

                                {{ ucfirst($aviso->importancia) }}

                            </span>


                            <!-- FECHA Y HORA -->
                            <div class="text-right text-[11px] text-gray-400 space-y-0.5">

                                <p class="flex items-center justify-end gap-1">

                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />

                                    </svg>

                                    {{ optional($aviso->fecha_inicio)->format('d M Y') }}

                                </p>


                                <p class="flex items-center justify-end gap-1">

                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />

                                    </svg>

                                    {{ optional($aviso->fecha_inicio)->format('H:i') }}

                                </p>

                            </div>


                            <!-- VER MÁS -->
                            <button type="button" @click='abrirAviso(@json($aviso))'
                                class="text-xs font-semibold text-blue-400 hover:text-blue-300 flex items-center gap-1 transition">

                                Ver más

                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />

                                </svg>

                            </button>

                        </div>

                    </div>


                @empty

                    <!-- ================================================= -->
                    <!-- NO EXISTEN AVISOS -->
                    <!-- ================================================= -->

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
                @endforelse


                <!-- ===================================================== -->
                <!-- NO HAY RESULTADOS DEL FILTRO -->
                <!-- ===================================================== -->

                <div x-cloak x-show="hayAvisos() && !hayAvisosVisibles()" x-transition.opacity.duration.200ms
                    class="text-center py-16">

                    <!-- ICONO -->
                    <div
                        class="w-16 h-16 mx-auto mb-4 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center">

                        <svg class="w-8 h-8 text-slate-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />

                        </svg>

                    </div>


                    <!-- TITULO -->
                    <h3 class="text-lg font-semibold text-white">

                        No se encontraron avisos

                    </h3>


                    <!-- DESCRIPCIÓN -->
                    <p class="text-sm text-gray-500 mt-1">

                        No hay

                        <span class="text-gray-400" x-text="nombreFiltro()">
                        </span>

                        que coincidan con tu búsqueda.

                    </p>


                    <!-- LIMPIAR FILTROS -->
                    <button type="button" @click="filtroTipo = 'todos'; busqueda = ''"
                        class="mt-5 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold transition">

                        Limpiar filtros

                    </button>

                </div>

            </div>

        </div>

    </main>


    <!-- ========================================================= -->
    <!-- MODAL VISTA PREVIA DEL AVISO -->
    <!-- ========================================================= -->

    <div x-cloak x-show="modalAbierto" @keydown.escape.window.prevent="cerrarAviso()" @click.self="cerrarAviso()"
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">

        <div x-show="modalAbierto" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative w-full max-w-3xl max-h-[90vh] overflow-y-auto bg-[#0b102b] border border-[#26345f] rounded-2xl shadow-2xl">

            <!-- HEADER DEL MODAL -->
            <div class="sticky top-0 z-10 bg-[#0b102b]/95 backdrop-blur border-b border-[#1e295d] px-6 py-5">

                <div class="flex items-start justify-between gap-4">

                    <div class="flex items-start gap-4">

                        <!-- ICONO -->
                        <div class="w-14 h-14 rounded-xl flex items-center justify-center shrink-0"
                            :class="{
                                'bg-[#3b2900]': avisoSeleccionado.tipo === 'mantenimiento',
                                'bg-[#3f0e0e]': avisoSeleccionado.tipo === 'incidente',
                                'bg-[#092c42]': avisoSeleccionado.tipo === 'informativo',
                                'bg-[#111827]': avisoSeleccionado.tipo === 'general'
                            }">

                            <!-- Mantenimiento -->
                            <template x-if="avisoSeleccionado.tipo === 'mantenimiento'">

                                <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317a1.724 1.724 0 013.35 0 1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924-1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31.826-2.37-2.37a1.724 1.724 0 001.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543-3.31-.826-2.37-2.37a1.724 1.724 0 012.572-1.065z" />

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />

                                </svg>

                            </template>

                            <!-- Incidente -->
                            <template x-if="avisoSeleccionado.tipo === 'incidente'">

                                <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />

                                </svg>

                            </template>

                            <!-- Informativo -->
                            <template x-if="avisoSeleccionado.tipo === 'informativo'">

                                <svg class="w-7 h-7 text-cyan-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />

                                </svg>

                            </template>

                            <!-- General -->
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

                            <h2 class="text-xl font-bold text-white mt-2" x-text="avisoSeleccionado.titulo">
                            </h2>

                        </div>

                    </div>


                    <button type="button" @click="cerrarAviso()">
                        <i data-lucide="x"></i>
                    </button>

                </div>

            </div>


            <!-- CONTENIDO -->
            <div class="p-6 space-y-6">

                <!-- IMPORTANCIA + FECHAS -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

                    <!-- Importancia -->
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


                    <!-- Inicio -->
                    <div class="bg-[#080d22] border border-[#1e295d] rounded-xl p-4">

                        <p class="text-[10px] uppercase tracking-wider text-gray-500 font-semibold">
                            Inicio
                        </p>

                        <p class="text-sm text-white mt-2">
                            <span x-text="formatearFecha(avisoSeleccionado.fecha_inicio)"></span>
                        </p>

                    </div>


                    <!-- Fin -->
                    <div class="bg-[#080d22] border border-[#1e295d] rounded-xl p-4">

                        <p class="text-[10px] uppercase tracking-wider text-gray-500 font-semibold">
                            Finaliza
                        </p>

                        <p class="text-sm text-white mt-2"
                            x-text="avisoSeleccionado.fecha_fin ? formatearFecha(avisoSeleccionado.fecha_fin) : 'Sin fecha de finalización'">
                        </p>

                    </div>

                </div>


                <!-- DESCRIPCIÓN -->
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


                <!-- AFECTA A -->
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

                        <!-- TODOS -->
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


                        <!-- DEPARTAMENTOS -->
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


                        <!-- USUARIOS -->
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


                <!-- ARCHIVO -->
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

                            <!-- IMAGEN -->
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


                            <!-- PDF -->
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


                            <!-- VIDEO -->
                            <template x-if="esVideo(avisoSeleccionado.archivo)">

                                <video :src="urlArchivo(avisoSeleccionado.archivo)" controls
                                    class="w-full max-h-[450px] bg-black">
                                </video>

                            </template>


                            <!-- OTRO -->
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


                <!-- SIN ARCHIVO -->
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


            <!-- FOOTER -->
            <div class="px-6 py-4 border-t border-[#1e295d] bg-[#080d22] flex justify-end">

                <button type="button" @click="cerrarAviso()">
                    Cerrar
                </button>

            </div>

        </div>

    </div>


    <script>
        function avisosUsuario() {

            return {

                // =====================================================
                // MODAL
                // =====================================================

                modalAbierto: false,

                avisoSeleccionado: {},


                // =====================================================
                // FILTROS
                // =====================================================

                filtroTipo: 'todos',

                busqueda: '',

                avisosTotales: @js($avisos ?? []),


                // =====================================================
                // DATOS
                // =====================================================

                departamentos: @js($departamentos ?? []),

                usuarios: @js($usuarios ?? []),


                // =====================================================
                // ABRIR AVISO
                // =====================================================

                abrirAviso(aviso) {

                    // ---------------------------------------------
                    // Convertir afecta_a si viene como JSON string
                    // ---------------------------------------------

                    if (typeof aviso.afecta_a === 'string') {

                        try {

                            aviso.afecta_a = JSON.parse(aviso.afecta_a);

                        } catch (error) {

                            console.error(
                                'Error al convertir afecta_a:',
                                error
                            );

                            aviso.afecta_a = {};

                        }

                    }


                    // ---------------------------------------------
                    // Si afecta_a viene vacío
                    // ---------------------------------------------

                    if (!aviso.afecta_a || typeof aviso.afecta_a !== 'object') {

                        aviso.afecta_a = {};

                    }


                    // ---------------------------------------------
                    // Abrir modal
                    // ---------------------------------------------

                    this.avisoSeleccionado = aviso;

                    this.modalAbierto = true;

                    document.body.classList.add('overflow-hidden');

                },


                // =====================================================
                // CERRAR AVISO
                // =====================================================

                cerrarAviso() {

                    this.modalAbierto = false;

                    document.body.classList.remove('overflow-hidden');

                    setTimeout(() => {
                        this.avisoSeleccionado = {};
                    }, 200);

                },


                // =====================================================
                // MOSTRAR AVISO SEGÚN FILTROS
                // =====================================================

                mostrarAviso(tipo, titulo, descripcion) {

                    // ---------------------------------------------
                    // FILTRO POR TIPO
                    // ---------------------------------------------

                    if (
                        this.filtroTipo !== 'todos' &&
                        tipo !== this.filtroTipo
                    ) {

                        return false;

                    }


                    // ---------------------------------------------
                    // FILTRO POR TEXTO
                    // ---------------------------------------------

                    const texto = this.busqueda
                        .toLowerCase()
                        .trim();


                    // Si no hay búsqueda,
                    // solamente se aplica el filtro por tipo.

                    if (!texto) {

                        return true;

                    }


                    // ---------------------------------------------
                    // CONTENIDO A BUSCAR
                    // ---------------------------------------------

                    const contenido = [

                            titulo ?? '',

                            descripcion ?? '',

                            tipo ?? ''

                        ]
                        .join(' ')
                        .toLowerCase();


                    return contenido.includes(texto);

                },


                // =====================================================
                // VERIFICAR SI EXISTEN AVISOS VISIBLES
                // =====================================================

                hayAvisosVisibles() {

                    return this.avisosTotales.some(aviso => {

                        return this.mostrarAviso(

                            aviso.tipo,

                            aviso.titulo,

                            aviso.descripcion

                        );

                    });

                },


                // =====================================================
                // VERIFICAR SI EXISTEN AVISOS EN GENERAL
                // =====================================================

                hayAvisos() {

                    return this.avisosTotales.length > 0;

                },


                // =====================================================
                // NOMBRE DEL FILTRO ACTUAL
                // =====================================================

                nombreFiltro() {

                    const nombres = {

                        todos: 'todos los avisos',

                        mantenimiento: 'avisos de mantenimiento',

                        incidente: 'avisos de falla o incidente',

                        informativo: 'avisos informativos',

                        general: 'avisos generales'

                    };


                    return nombres[this.filtroTipo] ?? 'avisos';

                },


                // =====================================================
                // ETIQUETA DEL TIPO
                // =====================================================

                tipoLabel(tipo) {

                    const tipos = {

                        mantenimiento: 'MANTENIMIENTO',

                        incidente: 'FALLA / INCIDENTE',

                        informativo: 'INFORMATIVO',

                        general: 'GENERAL'

                    };


                    return tipos[tipo] ?? 'GENERAL';

                },


                // =====================================================
                // CAPITALIZAR
                // =====================================================

                capitalizar(texto) {

                    if (!texto) {

                        return '';

                    }


                    return texto.charAt(0).toUpperCase() +
                        texto.slice(1);

                },


                // =====================================================
                // FORMATEAR FECHA
                // =====================================================

                formatearFecha(fecha) {

                    if (!fecha) {

                        return 'No especificada';

                    }


                    const date = new Date(fecha);


                    if (isNaN(date.getTime())) {

                        return fecha;

                    }


                    return new Intl.DateTimeFormat('es-MX', {

                        day: '2-digit',

                        month: 'short',

                        year: 'numeric',

                        hour: '2-digit',

                        minute: '2-digit'

                    }).format(date);

                },


                // =====================================================
                // URL DEL ARCHIVO
                // =====================================================

                urlArchivo(archivo) {

                    if (!archivo) {

                        return '';

                    }


                    return '/storage/' + archivo;

                },


                // =====================================================
                // OBTENER EXTENSIÓN
                // =====================================================

                extensionArchivo(archivo) {

                    if (!archivo) {

                        return '';

                    }


                    return archivo

                        .split('?')[0]

                        .split('.')

                        .pop()

                        .toLowerCase();

                },


                // =====================================================
                // VERIFICAR SI ES IMAGEN
                // =====================================================

                esImagen(archivo) {

                    return [

                        'jpg',

                        'jpeg',

                        'png',

                        'gif',

                        'webp',

                        'svg'

                    ].includes(

                        this.extensionArchivo(archivo)

                    );

                },


                // =====================================================
                // VERIFICAR SI ES PDF
                // =====================================================

                esPdf(archivo) {

                    return this.extensionArchivo(archivo) === 'pdf';

                },


                // =====================================================
                // VERIFICAR SI ES VIDEO
                // =====================================================

                esVideo(archivo) {

                    return [

                        'mp4',

                        'webm',

                        'ogg',

                        'mov'

                    ].includes(

                        this.extensionArchivo(archivo)

                    );

                },


                // =====================================================
                // OBTENER DEPARTAMENTOS
                // =====================================================

                obtenerDepartamentos(ids) {

                    // ---------------------------------------------
                    // Validar IDs
                    // ---------------------------------------------

                    if (!Array.isArray(ids)) {

                        return [];

                    }


                    // ---------------------------------------------
                    // Convertir todos los IDs a números
                    // ---------------------------------------------

                    const idsNumeros = ids.map(

                        id => Number(id)

                    );


                    // ---------------------------------------------
                    // CASO 1:
                    // departamentos como objeto:
                    //
                    // {
                    //     1: "Sistemas",
                    //     2: "Recursos Humanos"
                    // }
                    // ---------------------------------------------

                    if (
                        this.departamentos &&
                        !Array.isArray(this.departamentos) &&
                        typeof this.departamentos === 'object'
                    ) {

                        return idsNumeros

                            .map(id => {

                                return this.departamentos[id];

                            })

                            .filter(nombre => {

                                return nombre;

                            });

                    }


                    // ---------------------------------------------
                    // CASO 2:
                    // departamentos como array:
                    //
                    // [
                    //     {
                    //         id: 1,
                    //         nombre: "Sistemas"
                    //     }
                    // ]
                    // ---------------------------------------------

                    if (Array.isArray(this.departamentos)) {

                        return this.departamentos

                            .filter(departamento => {

                                return idsNumeros.includes(

                                    Number(departamento.id)

                                );

                            })

                            .map(departamento => {

                                return departamento.nombre;

                            })

                            .filter(nombre => {

                                return nombre;

                            });

                    }


                    // ---------------------------------------------
                    // Si no coincide ningún formato
                    // ---------------------------------------------

                    return [];

                },


                // =====================================================
                // OBTENER USUARIOS
                // =====================================================

                obtenerUsuarios(ids) {

                    if (!Array.isArray(ids)) {

                        return [];

                    }


                    const idsNumeros = ids.map(

                        id => Number(id)

                    );


                    return this.usuarios.filter(

                        usuario => {

                            return idsNumeros.includes(

                                Number(usuario.id)

                            );

                        }

                    );

                }

            };

        }
    </script>
</body>

</html>
