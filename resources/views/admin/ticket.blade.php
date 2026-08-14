<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TicketPro - Dashboard</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(71, 85, 105, .6);
            border-radius: 999px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(59, 130, 246, .7);
        }
    </style>

</head>


<body class="bg-[#070b19] text-white font-sans h-screen flex antialiased overflow-hidden">

    {{-- ========================================================= --}}
    {{-- SIDEBAR --}}
    {{-- ========================================================= --}}

    <aside
        class="w-64 bg-[#0a0f24] border-r border-slate-800/60 p-6 flex flex-col justify-between hidden md:flex h-screen shrink-0 overflow-hidden">

        <div class="min-h-0">

            {{-- LOGO --}}

            <div class="flex items-center gap-2 mb-10">

                <span class="text-3xl font-extrabold tracking-wide text-white">

                    Ticket<span class="text-blue-500">Pro</span>

                </span>

            </div>


            {{-- USUARIO --}}

            <div class="flex items-center gap-3 mb-8 p-2 rounded-xl bg-slate-900/40 border border-slate-800/50">

                <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="{{ auth()->user()->name }}"
                    class="w-10 h-10 rounded-full object-cover ring-2 ring-blue-500/30">

                <div class="overflow-hidden">

                    <h4 class="text-sm font-semibold text-slate-200 truncate">

                        {{ Auth::user()->name ?? 'Desconocido' }}

                    </h4>

                    <p class="text-xs text-slate-400 truncate">

                        {{ Auth::user()->departamento->nombre ?? 'Desconocido' }}

                    </p>

                </div>

            </div>


            {{-- MENU --}}

            <nav class="space-y-2">

                <a href="{{ route('tecnologias') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">

                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>

                    <span class="font-medium text-sm">
                        Inicio
                    </span>

                </a>


                <a href="{{ route('tickettecnologias') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold shadow-lg shadow-blue-600/30 transition">

                    <i data-lucide="ticket-check" class="w-5 h-5"></i>

                    <span class="text-sm">
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


                <a href="{{ route('perfiltecnologias') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">

                    <i data-lucide="circle-user-round" class="w-5 h-5"></i>

                    <span class="font-medium text-sm">
                        Mi perfil
                    </span>

                </a>

            </nav>

        </div>


        {{-- LOGOUT --}}

        <div class="shrink-0 pt-4">

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


    {{-- ========================================================= --}}
    {{-- MAIN --}}
    {{-- ========================================================= --}}

    <main class="flex-1 h-screen min-w-0 overflow-y-auto overflow-x-hidden p-6 md:p-8">

        <div class="max-w-7xl mx-auto" x-data="ticketModal">

            {{-- ================================================= --}}
            {{-- HEADER --}}
            {{-- ================================================= --}}

            <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">

                <div>

                    <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-white">
                        Tickets
                    </h1>

                    <p class="text-sm text-slate-400 mt-1">

                        Consulta y da seguimiento a todos los tickets que se han creado

                    </p>

                </div>


                <div class="flex items-center gap-4 self-end md:self-auto">

                    <div class="flex items-center gap-6">


                        {{-- NOTIFICACIONES --}}

                        <div class="relative inline-block text-left">

                            <button id="notif-button" type="button"
                                class="relative p-2 text-gray-300 hover:text-white bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700/50 rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 group shadow-lg"
                                aria-label="Ver notificaciones">

                                <svg class="w-6 h-6 transition-transform group-hover:scale-110 duration-200"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">

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
                                class="hidden absolute right-0 mt-3 w-80 sm:w-96 rounded-2xl bg-slate-900/95 backdrop-blur-md border border-slate-800 shadow-2xl z-50 overflow-hidden divide-y divide-slate-800">

                                <div class="p-4 flex items-center justify-between">

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


                                <div class="max-h-80 overflow-y-auto divide-y divide-slate-800/50">

                                    <a href="#"
                                        class="flex gap-3 p-4 bg-slate-800/40 hover:bg-slate-800/80 transition-colors group">

                                        <div class="relative shrink-0">

                                            <img class="w-10 h-10 rounded-full object-cover ring-2 ring-indigo-500/30"
                                                src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80"
                                                alt="Avatar">

                                            <span
                                                class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-slate-900 rounded-full"></span>

                                        </div>

                                        <div class="flex-1 min-w-0">

                                            <p class="text-xs text-slate-300 leading-relaxed">

                                                <strong
                                                    class="font-semibold text-white group-hover:text-indigo-400 transition-colors">
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
                                        class="flex gap-3 p-4 hover:bg-slate-800/50 transition-colors group">

                                        <div
                                            class="w-10 h-10 rounded-full bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 shrink-0">

                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">

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


                        {{-- USUARIO HEADER --}}

                        <div
                            class="flex items-center gap-3 bg-slate-900/80 border border-slate-800 rounded-full p-1.5 pr-4">

                            <img src="{{ asset('storage/' . auth()->user()->foto) }}"
                                alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full object-cover">

                            <div class="text-left leading-tight hidden sm:block">

                                <p class="text-xs font-semibold text-white">

                                    {{ Auth::user()->name ?? 'Desconocido' }}

                                </p>

                                <p class="text-[10px] text-slate-400">

                                    {{ Auth::user()->departamento->nombre ?? 'Desconocido' }}

                                </p>

                            </div>

                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 ml-1"></i>

                        </div>

                    </div>

                </div>

            </header>


            {{-- ================================================= --}}
            {{-- ESTADISTICAS --}}
            {{-- ================================================= --}}

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">

                {{-- TOTAL --}}

                <div
                    class="bg-[#0b1026]/80 border border-blue-900/40 rounded-2xl p-4 relative overflow-hidden backdrop-blur-sm">

                    <div class="flex items-center gap-3 mb-2">

                        <div class="p-2.5 rounded-xl bg-blue-600/20 text-blue-400">

                            <i data-lucide="ticket-check" class="w-5 h-5"></i>

                        </div>

                        <div>

                            <p class="text-xs text-slate-400 font-medium">
                                Total de tickets
                            </p>

                            <h3 class="text-2xl font-bold text-white">
                                {{ $totalTickets }}
                            </h3>

                        </div>

                    </div>

                    <p class="text-[11px] {{ $colorTotal }} font-medium mt-1">
                        {{ $porcentajeTotalTexto }}
                    </p>

                </div>


                {{-- PENDIENTES --}}

                <div class="bg-[#0b1026]/80 border border-slate-800/80 rounded-2xl p-4 backdrop-blur-sm">

                    <div class="flex items-center gap-2 mb-1">

                        <i data-lucide="clock-3" class="w-4 h-4 text-amber-400"></i>

                        <p class="text-xs text-slate-400 font-medium">
                            Pendientes
                        </p>

                    </div>

                    <h3 class="text-2xl font-bold text-white">
                        {{ $pendientes }}
                    </h3>

                    <p class="text-[11px] {{ $colorPendientes }} font-medium mt-1">
                        {{ $porcentajePendientesTexto }}
                    </p>

                </div>


                {{-- EN PROCESO --}}

                <div class="bg-[#0b1026]/80 border border-slate-800/80 rounded-2xl p-4 backdrop-blur-sm">

                    <div class="flex items-center gap-2 mb-1">

                        <i data-lucide="loader-circle" class="w-4 h-4 text-blue-400"></i>

                        <p class="text-xs text-slate-400 font-medium">
                            En proceso
                        </p>

                    </div>

                    <h3 class="text-2xl font-bold text-white">
                        {{ $enProceso }}
                    </h3>

                    <p class="text-[11px] {{ $colorEnProceso }} font-medium mt-1">
                        {{ $porcentajeEnProcesoTexto }}
                    </p>

                </div>


                {{-- SOLUCIONADOS --}}

                <div class="bg-[#0b1026]/80 border border-slate-800/80 rounded-2xl p-4 backdrop-blur-sm">

                    <div class="flex items-center gap-2 mb-1">

                        <i data-lucide="circle-check" class="w-4 h-4 text-emerald-400"></i>

                        <p class="text-xs text-slate-400 font-medium">
                            Solucionados
                        </p>

                    </div>

                    <h3 class="text-2xl font-bold text-white">
                        {{ $solucionados }}
                    </h3>

                    <p class="text-[11px] {{ $colorSolucionados }} font-medium mt-1">
                        {{ $porcentajeSolucionadosTexto }}
                    </p>

                </div>


                {{-- CANCELADOS --}}

                <div class="bg-[#0b1026]/80 border border-slate-800/80 rounded-2xl p-4 backdrop-blur-sm">

                    <div class="flex items-center gap-2 mb-1">

                        <i data-lucide="circle-x" class="w-4 h-4 text-rose-400"></i>

                        <p class="text-xs text-slate-400 font-medium">
                            Cancelados
                        </p>

                    </div>

                    <h3 class="text-2xl font-bold text-white">
                        {{ $cancelados }}
                    </h3>

                    <p class="text-[11px] {{ $colorCancelados }} font-medium mt-1">
                        {{ $porcentajeCanceladosTexto }}
                    </p>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- FILTROS --}}
            {{-- ================================================= --}}

            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">

                {{-- FILTRO ESTADOS --}}

                <div
                    class="flex items-center gap-1 bg-[#0b1026] border border-slate-800 p-1.5 rounded-2xl overflow-x-auto">

                    <button type="button" @click="filtro = 'todos'"
                        :class="filtro === 'todos'
                            ?
                            'bg-blue-600 text-white shadow-md shadow-blue-600/30' :
                            'text-slate-400 hover:text-white'"
                        class="px-5 py-2 rounded-xl text-xs font-semibold transition whitespace-nowrap">
                        Todos
                    </button>


                    <button type="button" @click="filtro = 'pendiente'"
                        :class="filtro === 'pendiente'
                            ?
                            'bg-blue-600 text-white shadow-md shadow-blue-600/30' :
                            'text-slate-400 hover:text-white'"
                        class="px-5 py-2 rounded-xl text-xs font-semibold transition whitespace-nowrap">
                        Pendientes
                    </button>


                    <button type="button" @click="filtro = 'en proceso'"
                        :class="filtro === 'en proceso'
                            ?
                            'bg-blue-600 text-white shadow-md shadow-blue-600/30' :
                            'text-slate-400 hover:text-white'"
                        class="px-5 py-2 rounded-xl text-xs font-semibold transition whitespace-nowrap">
                        En proceso
                    </button>


                    <button type="button" @click="filtro = 'solucionado'"
                        :class="filtro === 'solucionado'
                            ?
                            'bg-blue-600 text-white shadow-md shadow-blue-600/30' :
                            'text-slate-400 hover:text-white'"
                        class="px-5 py-2 rounded-xl text-xs font-semibold transition whitespace-nowrap">
                        Solucionados
                    </button>


                    <button type="button" @click="filtro = 'cancelado'"
                        :class="filtro === 'cancelado'
                            ?
                            'bg-blue-600 text-white shadow-md shadow-blue-600/30' :
                            'text-slate-400 hover:text-white'"
                        class="px-5 py-2 rounded-xl text-xs font-semibold transition whitespace-nowrap">

                        Cancelados

                        <span class="inline-block w-2 h-2 bg-rose-500 rounded-full ml-1"></span>

                    </button>

                </div>


                {{-- BUSQUEDA --}}

                <div class="flex items-center gap-3">

                    <div class="relative flex-1 sm:w-64">

                        <i data-lucide="search" class="absolute left-3.5 top-3 w-4 h-4 text-slate-500"></i>

                        <input type="text" x-model="busqueda" placeholder="Buscar..." autocomplete="off"
                            class="w-full bg-[#0b1026] border border-slate-800 text-xs rounded-xl pl-10 pr-10 py-2.5 text-slate-200 placeholder-slate-500 focus:outline-none focus:border-blue-500 transition">

                        <button type="button" x-show="busqueda.length > 0" x-cloak @click="busqueda = ''"
                            class="absolute right-3 top-2.5 text-slate-500 hover:text-white transition">

                            <i data-lucide="x" class="w-4 h-4"></i>

                        </button>

                    </div>

                    <div class="relative" x-data="{ abierto: false }">

                        <button type="button" @click="abierto = !abierto"
                            class="flex items-center gap-2 bg-[#0b1026] border border-slate-800 text-xs font-medium text-slate-300 px-4 py-2.5 rounded-xl hover:bg-slate-800/50 transition">

                            <i data-lucide="calendar-days" class="w-4 h-4 text-slate-400"></i>

                            <span>
                                Este mes
                            </span>

                            <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform"
                                :class="abierto ? 'rotate-180' : ''"></i>

                        </button>


                        <div x-show="abierto" x-cloak @click.outside="abierto = false" x-transition
                            class="absolute right-0 mt-2 w-48 bg-[#0b1026] border border-slate-800 rounded-xl shadow-2xl z-50 overflow-hidden">

                            <button type="button" @click="abierto = false"
                                class="w-full flex items-center gap-2 px-4 py-3 text-xs text-white bg-blue-600/20 hover:bg-blue-600/30 transition">

                                <i data-lucide="calendar-days" class="w-4 h-4 text-blue-400"></i>

                                <span>
                                    Este mes
                                </span>

                            </button>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- TABLA --}}
            {{-- ================================================= --}}

            <div
                class="bg-[#0b1026]/90 border border-slate-800/80 rounded-2xl overflow-hidden shadow-2xl backdrop-blur-md">

                <div class="overflow-x-auto">

                    <table class="w-full text-left border-collapse">

                        {{-- ENCABEZADO --}}

                        <thead>

                            <tr
                                class="border-b border-slate-800 text-[11px] uppercase tracking-wider text-slate-400 font-semibold bg-slate-900/30">

                                <th class="py-4 px-6">
                                    Folio
                                </th>

                                <th class="py-4 px-6">
                                    Título
                                </th>

                                <th class="py-4 px-6">
                                    Tipo de falla
                                </th>

                                <th class="py-4 px-6">
                                    Prioridad
                                </th>

                                <th class="py-4 px-6">
                                    Estado
                                </th>

                                <th class="py-4 px-6">
                                    Tomado por
                                </th>

                                <th class="py-4 px-6">
                                    Fecha
                                </th>

                                <th class="py-4 px-6 text-right">
                                    Acción
                                </th>

                            </tr>

                        </thead>


                        {{-- CUERPO --}}

                        <tbody class="divide-y divide-slate-800/60 text-xs">

                            @forelse ($tickets as $ticket)
                                @php

                                    $ticket->load([
                                        'user',
                                        'user.departamento',
                                        'user.departamento.oficina',
                                        'user.departamento.oficina.empresa',
                                        'historialComentarios.usuario',
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
                                                    'id' => $comentario->usuario->id ?? null,
                                                    'name' => $comentario->usuario->name ?? 'Usuario',
                                                    'rol' => $comentario->usuario->rol ?? 'Usuario',
                                                ],

                                                'fecha' => $comentario->created_at
                                                    ? $comentario->created_at->format('d M Y h:i A')
                                                    : '',
                                            ];
                                        })
                                        ->values();

                                @endphp


                                @php

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

                                @endphp


                                {{-- FILA --}}

                                <tr x-data="{
                                    ticket: {{ Js::from([
                                        'id' => $ticket->id,
                                        'estado' => $ticket->estado,
                                        'tomado_por' => $ticket->tomado_por,
                                    ]) }}
                                }"
                                    x-show="mostrarTicket(
        ticket.estado,
        {{ Js::from([
            'folio' => $ticket->folio,
            'titulo' => $ticket->titulo,
            'tipo_falla' => $ticket->tipo_falla,
            'prioridad' => $ticket->prioridad,
            'estado' => $ticket->estado,
            'tomado_por' => $ticket->tomado_por,
        ]) }}
    )"
                                    x-transition class="hover:bg-slate-800/20 transition">

                                    {{-- FOLIO --}}

                                    <td class="py-4 px-6 font-bold text-white whitespace-nowrap">

                                        {{ $ticket->folio }}

                                    </td>


                                    {{-- TITULO --}}

                                    <td class="py-4 px-6 font-medium text-slate-200 min-w-[200px]">

                                        {{ $ticket->titulo }}

                                    </td>


                                    {{-- TIPO FALLA --}}

                                    <td class="py-4 px-6 text-slate-300 whitespace-nowrap">

                                        <div class="flex items-center gap-2">

                                            <i data-lucide="{{ $iconoFalla }}" class="w-4 h-4 text-slate-400"></i>

                                            <span>
                                                {{ $ticket->tipo_falla ?? 'Sin especificar' }}
                                            </span>

                                        </div>

                                    </td>


                                    {{-- PRIORIDAD --}}

                                    <td class="py-4 px-6 whitespace-nowrap">

                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-semibold border {{ $configPrioridad['texto'] }} {{ $configPrioridad['fondo'] }} {{ $configPrioridad['borde'] }}">

                                            <i data-lucide="{{ $configPrioridad['icono'] }}" class="w-3 h-3"></i>

                                            {{ ucfirst($ticket->prioridad ?? 'Normal') }}

                                        </span>

                                    </td>


                                    {{-- ESTADO --}}

                                    @php
                                        $estadoInicial = strtolower($ticket->estado ?? '');
                                    @endphp

                                    <td class="py-4 px-6 whitespace-nowrap">

                                        {{-- SOLUCIONADO --}}
                                        <template
                                            x-if="
        (ticketsActualizados[{{ $ticket->id }}]?.estado ?? {{ Js::from($ticket->estado) }})
=== 'solucionado'
    ">

                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#133d28] text-green-300 border border-green-500/40">

                                                ✓ Solucionado

                                            </span>

                                        </template>


                                        {{-- PENDIENTE --}}
                                        <template
                                            x-if="
        (ticketsActualizados[{{ $ticket->id }}]?.estado ?? {{ Js::from($ticket->estado) }})
        === 'pendiente'
    ">

                                            <span
                                                class="inline-flex items-center justify-center min-w-[100px] px-3 py-1 rounded-full text-xs font-semibold bg-[#4a4213] text-yellow-300 border border-yellow-500/40">

                                                <span class="w-2 h-2 rounded-full bg-orange-400 mr-2"></span>

                                                Pendiente

                                            </span>

                                        </template>


                                        {{-- EN PROCESO --}}
                                        <template
                                            x-if="
        (ticketsActualizados[{{ $ticket->id }}]?.estado ?? {{ Js::from($ticket->estado) }})
        === 'en proceso'
    ">

                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#1d2757] text-blue-300 border border-blue-500/40">

                                                <span class="w-2 h-2 rounded-full bg-blue-400"></span>

                                                En proceso

                                            </span>

                                        </template>


                                        {{-- CANCELADO --}}
                                        <template
                                            x-if="
        (ticketsActualizados[{{ $ticket->id }}]?.estado ?? {{ Js::from($ticket->estado) }})
        === 'cancelado'
    ">

                                            <span
                                                class="inline-flex items-center justify-center min-w-[100px] px-3 py-1 rounded-full text-xs font-semibold bg-[#4d1616] text-red-300 border border-red-500/40">

                                                Cancelado

                                            </span>

                                        </template>

                                    </td>


                                    {{-- TOMADO POR --}}

                                    <td class="py-4 px-6 text-slate-400 whitespace-nowrap">

    <div class="flex items-center gap-3">

        {{-- FOTO DEL USUARIO DE TECNOLOGÍAS --}}
        <div
            class="w-8 h-8 rounded-full
            border border-blue-500/20
            overflow-hidden shrink-0"
        >

            <img
    :src="
        ticketsActualizados[{{ $ticket->id }}]
            ?.tomado_por
            ?.foto
        ??
        {{ Js::from(
            asset('storage/' . $ticket->tomadoPor->foto)
        ) }}
    "
    class="w-full h-full object-cover"
    alt="Usuario"
>

        </div>


        {{-- INFORMACIÓN DEL USUARIO --}}
        <div class="flex flex-col min-w-0">

            <span
                class="text-slate-300 font-medium truncate"
                x-text="
                    ticketsActualizados[{{ $ticket->id }}]
                        ?.tomado_por
                        ?.name
                    ??
                    {{ Js::from($ticket->tomadoPor?->name) }}
                    ??
                    '—————'
                "
            >
            </span>

            <span
                class="text-[10px] text-slate-500"
                x-show="
                    ticketsActualizados[{{ $ticket->id }}]
                        ?.tomado_por
                        ?.name
                    ||
                    {{ Js::from($ticket->tomadoPor?->name) }}
                "
            >
                Tecnologías
            </span>

        </div>

    </div>

</td>


                                    {{-- FECHA --}}

                                    <td class="py-4 px-6 text-slate-400 whitespace-nowrap">

                                        <div class="font-medium text-slate-300">
                                            {{ $ticket->created_at->format('d M Y') }}
                                        </div>

                                        <div class="text-[10px] text-slate-500">
                                            {{ $ticket->created_at->format('h:i A') }}
                                        </div>

                                    </td>


                                    {{-- ACCION --}}

                                    <td class="py-4 px-6 text-right whitespace-nowrap">

                                        <button type="button"
                                            @click="abrirTicket(
                                                {{ Js::from($ticketData) }},
                                                {{ Js::from($comentariosData) }}
                                            )"
                                            class="text-slate-400 hover:text-blue-400 p-2 rounded-lg hover:bg-blue-500/10 transition"
                                            title="Ver ticket">

                                            <i data-lucide="eye" class="w-4 h-4"></i>

                                        </button>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="8" class="py-12 text-center text-gray-400">

                                        No tienes tickets registrados.

                                    </td>

                                </tr>
                            @endforelse


                            {{-- SIN RESULTADOS --}}

                            <tr x-show="hayBusquedaYNoHayResultados()" x-cloak>

                                <td colspan="8" class="py-16 text-center">

                                    <div class="flex flex-col items-center justify-center">

                                        <div
                                            class="w-14 h-14 rounded-full bg-slate-800/60 flex items-center justify-center mb-4">

                                            <i data-lucide="search-x" class="w-6 h-6 text-slate-500"></i>

                                        </div>

                                        <p class="text-sm font-semibold text-slate-300">
                                            No se encontraron tickets
                                        </p>

                                        <p class="text-xs text-slate-500 mt-1">
                                            Intenta cambiar el filtro o realizar otra búsqueda.
                                        </p>

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


                {{-- ================================================= --}}
                {{-- MODAL --}}
                {{-- ================================================= --}}

                <template x-teleport="body">

                    <div x-show="openModal" x-cloak x-transition.opacity
                        class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4 sm:p-6 overflow-y-auto"
                        @keydown.escape.window="cerrarModal()" @click.self="cerrarModal()">

                        <div x-show="openModal" x-transition @click.stop
                            calass="relative w-full max-w-7xl bg-[#030712] border border-blue-600/40 rounded-3xl shadow-2xl flex flex-col max-h-[92vh] text-slate-200 overflow-hidden">

                            {{-- HEADER MODAL --}}

                            <div
                                class="flex items-center justify-between p-6 pb-4 border-b border-slate-800/80 shrink-0">

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

                                    <i data-lucide="x" class="w-6 h-6"></i>

                                </button>

                            </div>


                            {{-- CONTENIDO MODAL --}}

                            <div class="p-6 overflow-y-auto custom-scrollbar flex-1 space-y-6">

                                {{-- RESUMEN SUPERIOR --}}

                                <div
                                    class="grid grid-cols-2 md:grid-cols-5 gap-4 p-4 rounded-2xl bg-[#060c21] border border-blue-500/40">

                                    <div class="border-r border-slate-800/60 pr-2">

                                        <span class="text-[11px] font-semibold text-blue-400 block mb-1">
                                            Folio
                                        </span>

                                        <span class="text-sm font-bold text-white"
                                            x-text="selectedTicket?.folio ?? '—'"></span>

                                    </div>


                                    <div class="border-r border-slate-800/60 pr-2">

                                        <span class="text-[11px] font-semibold text-blue-400 block mb-1">
                                            Prioridad
                                        </span>

                                        <span class="text-sm font-bold text-white"
                                            x-text="capitalizar(selectedTicket?.prioridad)"></span>

                                    </div>


                                    <div class="border-r border-slate-800/60 pr-2">

                                        <span class="text-[11px] font-semibold text-blue-400 block mb-1">
                                            Estado
                                        </span>

                                        <span class="text-sm font-bold text-white"
                                            x-text="capitalizar(selectedTicket?.estado)"></span>

                                    </div>


                                    <div class="border-r border-slate-800/60 pr-2">

                                        <span class="text-[11px] font-semibold text-blue-400 block mb-1">
                                            Tomado por
                                        </span>

                                        <span class="text-sm font-bold text-white"
                                            x-text="selectedTicket?.tomado_por ?? 'Sin asignar'"></span>

                                    </div>


                                    <div>

                                        <span class="text-[11px] font-semibold text-blue-400 block mb-1">
                                            Fecha
                                        </span>

                                        <span class="text-xs font-bold text-slate-200"
                                            x-text="formatearFecha(selectedTicket?.created_at)"></span>

                                    </div>

                                </div>


                                {{-- COLUMNAS --}}

                                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">


                                    {{-- ================================================= --}}
                                    {{-- COLUMNA IZQUIERDA --}}
                                    {{-- ================================================= --}}

                                    <div class="lg:col-span-6 space-y-5">


                                        {{-- RESUMEN --}}

                                        <div class="p-5 rounded-2xl bg-[#060c21] border border-blue-500/30 space-y-4">

                                            <div class="flex items-center gap-2 border-b border-slate-800 pb-3">

                                                <i data-lucide="receipt" class="w-4 h-4 text-slate-300"></i>

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
                                                        x-text="selectedTicket?.titulo ?? '—'"></span>

                                                </div>


                                                {{-- TIPO --}}

                                                <div class="flex justify-between items-center">

                                                    <span class="text-slate-400 font-semibold">
                                                        Tipo de falla:
                                                    </span>

                                                    <div class="flex items-center gap-1.5 text-slate-200 font-medium">

                                                        <i data-lucide="ticket"
                                                            class="w-3.5 h-3.5 text-slate-400"></i>

                                                        <span
                                                            x-text="selectedTicket?.tipo_falla ?? 'Sin especificar'"></span>

                                                    </div>

                                                </div>


                                                {{-- LEVANTADO POR --}}

                                                <div class="flex justify-between items-start pt-1">

                                                    <span class="text-slate-400 font-semibold">
                                                        Levantado por:
                                                    </span>

                                                    <div class="flex items-center gap-2 text-right">

                                                        <div>

                                                            <div class="text-white font-medium"
                                                                x-text="selectedTicket?.user?.name ?? selectedTicket?.usuario?.name ?? 'Usuario'">
                                                            </div>

                                                            <div class="text-[10px] text-slate-400"
                                                                x-text="selectedTicket?.user?.email ?? selectedTicket?.usuario?.email ?? ''">
                                                            </div>

                                                        </div>

                                                        <img :src="avatarUsuario(
                                                            selectedTicket?.user?.name ??
                                                            selectedTicket?.usuario?.name ??
                                                            'Usuario'
                                                        )"
                                                            class="w-8 h-8 rounded-full object-cover border border-blue-400/40">

                                                    </div>

                                                </div>


                                                {{-- DEPARTAMENTO --}}

                                                <div class="flex justify-between items-center">

                                                    <span class="text-slate-400 font-semibold">
                                                        Departamento:
                                                    </span>

                                                    <span class="text-slate-300"
                                                        x-text="selectedTicket?.user?.departamento?.nombre ?? 'Sin especificar'"></span>

                                                </div>


                                                {{-- EMPRESA --}}

                                                <div class="flex justify-between items-center">

                                                    <span class="text-slate-400 font-semibold">
                                                        Empresa:
                                                    </span>

                                                    <span class="text-slate-300"
                                                        x-text="selectedTicket?.user?.departamento?.oficina?.empresa?.empresa ?? 'Sin especificar'"></span>

                                                </div>


                                                {{-- OFICINA --}}

                                                <div class="flex justify-between items-center">

                                                    <span class="text-slate-400 font-semibold">
                                                        Oficina:
                                                    </span>

                                                    <span class="text-slate-300"
                                                        x-text="selectedTicket?.user?.departamento?.oficina?.nombre ?? 'Sin especificar'"></span>

                                                </div>


                                                {{-- UBICACION --}}

                                                <div class="flex justify-between items-center">

                                                    <span class="text-slate-400 font-semibold">
                                                        Ubicación:
                                                    </span>

                                                    <span class="text-slate-300"
                                                        x-text="selectedTicket?.ubicacion ?? 'Sin especificar'"></span>

                                                </div>


                                                {{-- FECHAS --}}

                                                <div class="pt-2 space-y-2">

                                                    <div
                                                        class="p-2.5 rounded-xl bg-[#030712] border border-slate-800/80">

                                                        <div class="text-[10px] text-slate-400 font-semibold mb-0.5">
                                                            Fecha en que fue levantado
                                                        </div>

                                                        <div class="text-xs text-slate-200 font-medium"
                                                            x-text="formatearFecha(selectedTicket?.created_at)"></div>

                                                    </div>


                                                    <div
                                                        class="p-2.5 rounded-xl bg-[#030712] border border-slate-800/80">

                                                        <div
                                                            class="flex items-center gap-1.5 text-[10px] text-slate-400 font-semibold mb-0.5">

                                                            <i data-lucide="alarm-clock"
                                                                class="w-3 h-3 text-slate-400"></i>

                                                            <span>
                                                                Fecha en que fue tomado
                                                            </span>

                                                        </div>

                                                        <div class="text-xs text-slate-400"
                                                            x-text="selectedTicket?.fecha_tomado? formatearFecha(selectedTicket.fecha_tomado): 'Aún sin tomar'">
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
                                                x-text="selectedTicket?.descripcion ?? 'Sin descripción'"></p>

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

                                                        <span>
                                                            No se proporcionó evidencia.
                                                        </span>

                                                    </div>

                                                </div>

                                            </template>

                                        </div>

                                    </div>


                                    {{-- ================================================= --}}
                                    {{-- COLUMNA DERECHA --}}
                                    {{-- ================================================= --}}

                                    <div
                                        class="lg:col-span-6 p-5 rounded-2xl bg-[#060c21] border border-blue-500/30 flex flex-col min-h-[600px]">

                                        <div class="flex items-center gap-2 pb-4 border-b border-slate-800/80">

                                            <i data-lucide="message-square" class="w-4 h-4 text-slate-300"></i>

                                            <h3 class="text-sm font-bold text-white">
                                                Comentarios y seguimiento
                                            </h3>

                                        </div>


                                        {{-- FORMULARIO COMENTARIO --}}

                                        <form id="formComentario" method="POST" enctype="multipart/form-data"
                                            class="py-4 border-b border-slate-800/80 flex items-center gap-3"
                                            :action="rutaComentario">

                                            @csrf

                                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Usuario') }}&background=0D8ABC&color=fff"
                                                class="w-10 h-10 rounded-full object-cover border border-blue-400/40 shrink-0">


                                            <div class="relative flex-1">

                                                <input type="file" name="archivo" x-ref="fileInputModal"
                                                    @change="archivoAdjunto = $event.target.files[0]" class="hidden">


                                                <input type="text" name="mensaje"
                                                    placeholder="Escribe un comentario.." autocomplete="off"
                                                    class="w-full pl-4 pr-16 py-3 text-xs bg-[#030712] border border-blue-900/60 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-blue-500">


                                                <div
                                                    class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center gap-2">

                                                    <button type="button" @click="$refs.fileInputModal.click()"
                                                        class="text-slate-400 hover:text-blue-400 transition"
                                                        title="Adjuntar archivo">

                                                        <i data-lucide="paperclip" class="w-4 h-4"></i>

                                                    </button>


                                                    <button type="submit"
                                                        class="text-blue-400 hover:text-blue-300 transition"
                                                        title="Enviar comentario">

                                                        <i data-lucide="send" class="w-4 h-4"></i>

                                                    </button>

                                                </div>

                                            </div>

                                        </form>


                                        {{-- COMENTARIOS --}}

                                        <div id="listaComentarios"
                                            class="flex-1 space-y-5 mt-4 overflow-y-auto max-h-[460px] pr-2 custom-scrollbar">

                                            <template x-if="comentarios.length === 0">

                                                <div
                                                    class="flex flex-col items-center justify-center py-16 text-slate-500">

                                                    <div
                                                        class="w-12 h-12 rounded-full bg-slate-800/60 flex items-center justify-center mb-3">

                                                        <i data-lucide="message-square-off" class="w-5 h-5"></i>

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

                                                    <img :src="avatarUsuario(comentario.usuario?.name ?? 'Usuario')"
                                                        class="w-8 h-8 rounded-full object-cover shrink-0 border border-blue-400/30"
                                                        :alt="comentario.usuario?.name ?? 'Usuario'">


                                                    <div class="flex-1 min-w-0">

                                                        <div class="flex items-center gap-2 mb-1 flex-wrap">

                                                            <span class="text-xs font-bold text-white"
                                                                x-text="comentario.usuario?.name ?? 'Usuario'"></span>


                                                            <span
                                                                class="px-2 py-0.5 rounded-full text-[9px] font-semibold bg-indigo-600/30 text-indigo-300 border border-indigo-500/40"
                                                                x-text="comentario.usuario?.rol ?? 'Usuario'"></span>


                                                            <span class="text-[10px] text-slate-500 ml-auto"
                                                                x-text="comentario.fecha ?? ''"></span>

                                                        </div>


                                                        <template x-if="comentario.mensaje">

                                                            <p class="text-xs text-slate-300 mb-2 whitespace-pre-line"
                                                                x-text="comentario.mensaje"></p>

                                                        </template>


                                                        <template x-if="comentario.archivo">

                                                            <template x-if="esImagen(comentario.archivo)">

                                                                <a :href="comentario.url_archivo" target="_blank"
                                                                    class="block w-36 rounded-xl overflow-hidden bg-slate-900 border border-slate-700/80 hover:border-blue-500/60 transition">

                                                                    <img :src="comentario.url_archivo"
                                                                        :alt="comentario.nombre_archivo"
                                                                        class="w-36 h-20 object-cover">

                                                                    <div
                                                                        class="px-2 py-1.5 flex items-center justify-between gap-2 border-t border-slate-800">

                                                                        <span
                                                                            class="text-[9px] text-slate-300 truncate"
                                                                            x-text="comentario.nombre_archivo"></span>

                                                                        <span
                                                                            class="px-1 py-0.5 rounded bg-blue-600 text-white font-bold text-[8px] shrink-0"
                                                                            x-text="comentario.extension"></span>

                                                                    </div>

                                                                </a>

                                                            </template>

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


                            {{-- FOOTER MODAL --}}

                            <div
                                class="p-5 border-t border-slate-800/80 bg-[#030712] flex items-center justify-between shrink-0">

                                <button type="button" @click="cerrarModal()"
                                    class="px-5 py-2.5 text-xs font-semibold text-slate-400 hover:text-white transition">
                                    Cerrar
                                </button>


                                <button type="button" x-show="!selectedTicket?.tomado_por" @click="tomarTicket()"
                                    class="...">
                                    <i data-lucide="hand" class="w-4 h-4"></i>
                                    Tomar ticket
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </main>
</body>

</html>
