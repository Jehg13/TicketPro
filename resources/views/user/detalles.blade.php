<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" href="{{ asset('storage/images/logo.png') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>{{ $ticket->folio }} | TicketPro</title>

</head>


<body class="bg-[#060818] text-white font-sans antialiased flex h-screen overflow-hidden">


    <div class="flex min-h-screen w-full">


        {{-- ========================================================= --}}
        {{-- OVERLAY MOBILE --}}
        {{-- ========================================================= --}}

        <div id="sidebarOverlay" onclick="toggleSidebar()" class="fixed inset-0 z-40 hidden bg-black/60 lg:hidden">
        </div>


        {{-- ========================================================= --}}
        {{-- SIDEBAR --}}
        {{-- ========================================================= --}}

        <aside id="sidebar"
            class="hidden md:flex flex-col w-64 bg-[#0a0e27] border-r border-[#1e295d] px-4 py-6 overflow-y-auto [::-webkit-scrollbar]:w-2 [::-webkit-scrollbar-track]:bg-[#060818] [::-webkit-scrollbar-thumb]:bg-[#1e295d] [::-webkit-scrollbar-thumb]:rounded-full">


            {{-- LOGO --}}

            <div class="mb-8 px-2 text-3xl font-bold tracking-wide">

                Ticket<span class="text-blue-500">Pro</span>

            </div>


            {{-- USUARIO --}}

            <div class="mb-10 flex items-center gap-3 px-2">

                @if (auth()->user()->foto)
                    <img src="{{ Storage::url(auth()->user()->foto) }}" alt="{{ auth()->user()->name }}"
                        class="h-12 w-12 rounded-full border-2 border-gray-500 object-cover">
                @else
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-full border-2 border-gray-500 bg-blue-600">

                        <span class="font-bold text-white">

                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}

                        </span>

                    </div>
                @endif


                <div class="min-w-0">

                    <h3 class="truncate text-sm font-semibold text-white">

                        {{ Auth::user()->name ?? 'Desconocido' }}

                    </h3>

                    <p class="truncate text-xs text-gray-400">

                        {{ Auth::user()->departamento->nombre ?? 'Desconocido' }}

                    </p>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- MENU --}}
            {{-- ========================================================= --}}

            <nav class="flex-1 space-y-2">


                {{-- DASHBOARD --}}

                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-3 text-gray-300 transition hover:bg-[#151b3b] hover:text-white">

                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>

                    </svg>

                    <span class="text-sm font-medium">
                        Inicio
                    </span>

                </a>


                {{-- MIS TICKETS --}}

                <a href="{{ route('misticketusuario') }}"
                    class="flex items-center gap-3 rounded-lg bg-blue-600 px-4 py-3 font-medium text-white shadow-[0_0_15px_rgba(37,99,235,0.4)]">

                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                        </path>

                    </svg>

                    <span class="text-sm font-medium">
                        Mis tickets
                    </span>

                </a>


                {{-- CREAR TICKET --}}

                <a href="{{ route('ticketusuario') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-3 text-gray-300 transition hover:bg-[#151b3b] hover:text-white">

                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z">
                        </path>

                    </svg>

                    <span class="text-sm font-medium">
                        Crear ticket
                    </span>

                </a>


                {{-- AVISOS --}}

                <a href="{{ route('avisosusuario') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-3 text-gray-300 transition hover:bg-[#151b3b] hover:text-white">

                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>

                    </svg>

                    <span class="text-sm font-medium">
                        Avisos
                    </span>

                </a>


                {{-- PERFIL --}}

                <a href="{{ route('perfilusuario') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-3 text-gray-300 transition hover:bg-[#151b3b] hover:text-white">

                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                        </path>

                    </svg>

                    <span class="text-sm font-medium">
                        Mi perfil
                    </span>

                </a>

            </nav>


            {{-- LOGOUT --}}

            <div class="mt-auto pt-6">

                <form method="POST" action="{{ route('logout') }}">

                    @csrf

                    <button type="submit"
                        class="flex w-full items-center gap-3 rounded-lg px-4 py-3 text-left text-gray-300 transition hover:bg-red-500/10 hover:text-red-400">

                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">

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


        {{-- ========================================================= --}}
        {{-- CONTENIDO --}}
        {{-- ========================================================= --}}

        <main
            class="flex-1 flex flex-col h-screen overflow-y-auto px-6 py-6 md:py-8 [::-webkit-scrollbar]:w-2 [::-webkit-scrollbar-track]:bg-[#060818] [::-webkit-scrollbar-thumb]:bg-[#1e295d] [::-webkit-scrollbar-thumb]:rounded-full">


            {{-- ========================================================= --}}
            {{-- HEADER --}}
            {{-- ========================================================= --}}

            <header class="mb-8">

                <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">


                    {{-- IZQUIERDA --}}

                    <div class="flex items-center gap-3">


                        {{-- BOTÓN MOBILE --}}

                        <button type="button" onclick="toggleSidebar()"
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#151b3b] text-white transition hover:bg-[#1c244d] lg:hidden">

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">

                                <path d="M4 7h16M4 12h16M4 17h16" />

                            </svg>

                        </button>


                        {{-- TITULO --}}

                        <div>

                            <div class="flex items-center gap-2">

                                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z">
                                    </path>

                                </svg>

                                <h1 class="text-2xl font-extrabold text-white sm:text-3xl">
                                    Detalles del ticket
                                </h1>

                            </div>


                            <p class="mt-1 text-sm text-gray-400">

                                Consulta toda la información relacionada con el ticket

                                <span class="font-semibold text-blue-400">
                                    {{ $ticket->folio }}
                                </span>

                            </p>

                        </div>

                    </div>


                    {{-- DERECHA --}}

                    <div class="flex items-center gap-3 sm:gap-4">


                        {{-- NOTIFICACIONES --}}

                        <div class="relative inline-block text-left">


                            <button id="notif-button" type="button"
                                class="group relative rounded-xl border border-slate-700/50 bg-slate-800/80 p-2 text-gray-300 shadow-lg transition-all duration-200 hover:bg-slate-700/80 hover:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/50"
                                aria-label="Ver notificaciones">

                                <svg class="h-6 w-6 transition-transform duration-200 group-hover:scale-110"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                    </path>

                                </svg>


                                <span class="absolute right-1.5 top-1.5 flex h-3 w-3">

                                    <span
                                        class="absolute inline-flex h-full w-full animate-ping rounded-full bg-rose-400 opacity-75">
                                    </span>

                                    <span
                                        class="relative inline-flex h-3 w-3 rounded-full border-2 border-slate-900 bg-rose-500">
                                    </span>

                                </span>

                            </button>


                            {{-- DROPDOWN --}}

                            <div id="notif-dropdown"
                                class="absolute right-0 z-50 mt-3 hidden w-80 overflow-hidden divide-y divide-slate-800 rounded-2xl border border-slate-800 bg-slate-900/95 shadow-2xl backdrop-blur-md sm:w-96">


                                <div class="flex items-center justify-between p-4">

                                    <div class="flex items-center gap-2">

                                        <h3 class="text-sm font-semibold text-white">
                                            Notificaciones
                                        </h3>

                                        <span
                                            class="rounded-full border border-indigo-500/20 bg-indigo-500/10 px-2 py-0.5 text-xs font-medium text-indigo-400">

                                            3 nuevas

                                        </span>

                                    </div>


                                    <button type="button" id="markNotificationsRead"
                                        class="text-xs font-medium text-indigo-400 transition-colors hover:text-indigo-300">

                                        Marcar leídas

                                    </button>

                                </div>


                                <div class="max-h-80 divide-y divide-slate-800/50 overflow-y-auto">


                                    {{-- NOTIFICACIÓN 1 --}}

                                    <a href="#"
                                        class="group flex gap-3 bg-slate-800/40 p-4 transition-colors hover:bg-slate-800/80">

                                        <div class="relative shrink-0">

                                            <div
                                                class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-500/10 text-blue-400">

                                                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">

                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5">
                                                    </path>

                                                </svg>

                                            </div>

                                        </div>


                                        <div class="min-w-0 flex-1">

                                            <p class="text-xs leading-relaxed text-slate-300">

                                                Tienes una nueva actualización relacionada con tus tickets.

                                            </p>

                                            <span class="mt-1 block text-[10px] text-slate-500">

                                                Hace 2 minutos

                                            </span>

                                        </div>

                                    </a>


                                    {{-- NOTIFICACIÓN 2 --}}

                                    <a href="#"
                                        class="group flex gap-3 p-4 transition-colors hover:bg-slate-800/50">

                                        <div
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-indigo-500/20 bg-indigo-500/10 text-indigo-400">

                                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">

                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 10V3L4 14h7v7l9-11h-7z">
                                                </path>

                                            </svg>

                                        </div>


                                        <div class="min-w-0 flex-1">

                                            <p class="text-xs leading-relaxed text-slate-300">

                                                Tu sistema está funcionando correctamente.

                                            </p>

                                            <span class="mt-1 block text-[10px] text-slate-500">

                                                Hace 1 hora

                                            </span>

                                        </div>

                                    </a>

                                </div>


                                <a href="#"
                                    class="block p-3 text-center text-xs font-medium text-slate-400 transition-colors hover:bg-slate-800/50 hover:text-white">

                                    Ver todas las notificaciones

                                </a>

                            </div>

                        </div>


                        {{-- VOLVER --}}

                        <a href="{{ route('misticketusuario') }}"
                            class="hidden items-center gap-2 rounded-xl border border-[#1e295d] bg-[#0f1535] px-4 py-3 text-sm font-bold text-gray-300 transition hover:bg-[#151b3b] hover:text-white sm:flex">

                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7">
                                </path>

                            </svg>

                            Mis tickets

                        </a>


                        {{-- NUEVO TICKET --}}

                        <a href="{{ route('ticketusuario') }}"
                            class="flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-bold text-white shadow-[0_0_15px_rgba(37,99,235,0.4)] transition hover:bg-blue-700 sm:text-base">

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"
                                stroke-linejoin="round">

                                <path d="M12 5v14M5 12h14" />

                            </svg>

                            Nuevo ticket

                        </a>

                    </div>

                </div>

            </header>



            {{-- ========================================================= --}}
            {{-- DETALLES DEL TICKET --}}
            {{-- ========================================================= --}}

            <div class="mx-auto w-full max-w-7xl space-y-6">


                {{-- ========================================================= --}}
                {{-- ENCABEZADO DEL TICKET --}}
                {{-- ========================================================= --}}

                <div class="overflow-hidden rounded-2xl border border-[#1e295d] bg-[#0f1535] shadow-xl">

                    <div class="p-6">

                        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">


                            <div class="flex items-start gap-4">


                                {{-- ICONO --}}

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


                            {{-- ESTADO --}}

                            @php

                                $estadoClases = [
                                    'pendiente' => 'border-yellow-500/30 bg-yellow-500/10 text-yellow-400',

                                    'en proceso' => 'border-blue-500/30 bg-blue-500/10 text-blue-400',

                                    'solucionado' => 'border-green-500/30 bg-green-500/10 text-green-400',

                                    'cancelado' => 'border-red-500/30 bg-red-500/10 text-red-400',
                                ];

                                $estadoClase =
                                    $estadoClases[strtolower($ticket->estado)] ??
                                    'border-gray-500/30 bg-gray-500/10 text-gray-400';

                            @endphp


                            <div
                                class="inline-flex w-fit items-center gap-2 rounded-xl border px-4 py-2 text-sm font-bold {{ $estadoClase }}">

                                <span class="h-2 w-2 rounded-full bg-current"></span>

                                {{ ucfirst($ticket->estado) }}

                            </div>

                        </div>

                    </div>

                </div>



                {{-- ========================================================= --}}
                {{-- GRID PRINCIPAL --}}
                {{-- ========================================================= --}}

                <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">


                    {{-- ================================================= --}}
                    {{-- INFORMACIÓN PRINCIPAL --}}
                    {{-- ================================================= --}}

                    <div class="space-y-6 xl:col-span-2">


                        {{-- INFORMACIÓN DEL TICKET --}}

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


                            {{-- DATOS --}}

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">


                                {{-- TIPO --}}

                                <div class="rounded-xl border border-[#1e295d] bg-[#0b1026] p-4">

                                    <p class="text-xs font-medium text-gray-500">
                                        Tipo de falla
                                    </p>

                                    <p class="mt-2 font-semibold text-white">
                                        {{ $ticket->tipo_falla ?? 'No especificado' }}
                                    </p>

                                </div>


                                {{-- PRIORIDAD --}}

                                <div class="rounded-xl border border-[#1e295d] bg-[#0b1026] p-4">

                                    <p class="text-xs font-medium text-gray-500">
                                        Prioridad
                                    </p>

                                    <p class="mt-2 font-semibold text-white">
                                        {{ ucfirst($ticket->prioridad ?? 'No especificada') }}
                                    </p>

                                </div>


                                {{-- FECHA --}}

                                <div class="rounded-xl border border-[#1e295d] bg-[#0b1026] p-4">

                                    <p class="text-xs font-medium text-gray-500">
                                        Fecha de reporte
                                    </p>

                                    <p class="mt-2 font-semibold text-white">

                                        {{ $ticket->created_at?->timezone('America/Matamoros')->format('d M Y, H:i') }}

                                    </p>

                                </div>


                                {{-- RECURRENTE --}}

                                <div class="rounded-xl border border-[#1e295d] bg-[#0b1026] p-4">

                                    <p class="text-xs font-medium text-gray-500">
                                        ¿Es recurrente?
                                    </p>

                                    <p class="mt-2 font-semibold text-white">
                                        {{ $ticket->es_recurrente ? 'Sí' : 'No' }}
                                    </p>

                                </div>


                                {{-- DEPARTAMENTO --}}

                                <div class="rounded-xl border border-[#1e295d] bg-[#0b1026] p-4">

                                    <p class="text-xs font-medium text-gray-500">
                                        Departamento
                                    </p>

                                    <p class="mt-2 font-semibold text-white">
                                        {{ $ticket->user?->departamento?->nombre ?? 'Sin departamento' }}
                                    </p>

                                </div>


                                {{-- OFICINA --}}

                                <div class="rounded-xl border border-[#1e295d] bg-[#0b1026] p-4">

                                    <p class="text-xs font-medium text-gray-500">
                                        Sucursal / Oficina
                                    </p>

                                    <p class="mt-2 font-semibold text-white">
                                        {{ $ticket->user?->departamento?->oficina?->nombre ?? 'Sin oficina' }}
                                    </p>

                                </div>


                                {{-- TOMADO POR --}}

                                <div class="rounded-xl border border-[#1e295d] bg-[#0b1026] p-4">

                                    <p class="text-xs font-medium text-gray-500">
                                        Tomado por
                                    </p>

                                    <p class="mt-2 font-semibold text-white">
                                        {{ $tomadoPor?->name ?? 'Sin asignar' }}
                                    </p>

                                </div>


                                {{-- FECHA TOMADO --}}

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


                            {{-- DESCRIPCIÓN --}}

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


                            {{-- INFORMACIÓN ADICIONAL --}}

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


                            {{-- COMENTARIOS --}}

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



                    {{-- ================================================= --}}
                    {{-- PANEL DERECHO --}}
                    {{-- ================================================= --}}

                    <div class="space-y-6">


                        {{-- SOLICITANTE --}}

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

                                    @if ($ticket->user?->foto)
                                        <img src="{{ Storage::url($ticket->user->foto) }}"
                                            class="h-full w-full object-cover" alt="Foto del usuario">
                                    @else
                                        <span class="text-lg font-bold text-white">

                                            {{ strtoupper(substr($ticket->user?->name ?? 'U', 0, 1)) }}

                                        </span>
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



                        {{-- SOLUCIÓN --}}

                        <div class="rounded-2xl border border-[#1e295d] bg-[#0f1535] p-6 shadow-xl">

                            {{-- ENCABEZADO --}}

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

                                {{-- ESTADO --}}

                                <div
                                    class="mb-5 flex items-center gap-2 rounded-xl border border-green-500/20 bg-green-500/5 px-4 py-3">

                                    <span class="h-2.5 w-2.5 rounded-full bg-green-400"></span>

                                    <span class="text-xs font-bold uppercase tracking-wider text-green-400">
                                        Ticket solucionado
                                    </span>

                                </div>


                                {{-- INFORMACIÓN DE LA SOLUCIÓN --}}

                                <div class="space-y-4">


                                    {{-- PROBLEMA SOLUCIONADO --}}

                                    <div class="rounded-xl border border-[#1e295d] bg-[#0b1026] p-5">

                                        <div class="mb-3 flex items-center gap-2">

                                            <svg class="h-4 w-4 text-orange-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">

                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.8"
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


                                    {{-- SOLUCIÓN --}}

                                    @if (!empty($ticket->solucion->solucion))
                                        <div class="rounded-xl border border-green-500/20 bg-green-500/5 p-5">

                                            <div class="mb-3 flex items-center gap-2">

                                                <svg class="h-4 w-4 text-green-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">

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


                                    {{-- DATOS DE LA SOLUCIÓN --}}

                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">


                                        {{-- SOLUCIONADO POR --}}

                                        <div class="rounded-xl border border-[#1e295d] bg-[#0b1026] p-4">

                                            <p class="text-xs font-medium text-gray-500">
                                                Solucionado por
                                            </p>

                                            <p class="mt-2 font-semibold text-white">
                                                {{ $ticket->solucion->solucionadoPor?->name ?? ($ticket->solucion->solucionado_por ?? 'Tecnologías') }}
                                            </p>

                                        </div>


                                        {{-- FECHA DE SOLUCIÓN --}}

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


                                        {{-- NOMBRE DEL FIRMANTE --}}

                                        <div class="rounded-xl border border-[#1e295d] bg-[#0b1026] p-4">

                                            <p class="text-xs font-medium text-gray-500">
                                                Firmado por
                                            </p>

                                            <p class="mt-2 font-semibold text-white">
                                                {{ $ticket->solucion->nombre_firmante ?? 'Sin información' }}
                                            </p>

                                        </div>


                                        {{-- FECHA DE FIRMA --}}

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


                                    {{-- FIRMA --}}

                                    @if (!empty($ticket->solucion->firma))
                                        <div class="rounded-xl border border-[#1e295d] bg-[#0b1026] p-5">

                                            <div class="mb-4 flex items-center gap-2">

                                                <svg class="h-4 w-4 text-purple-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">

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

                                                <img src="{{ Storage::url($ticket->solucion->firma) }}"
                                                    alt="Firma de {{ $ticket->solucion->nombre_firmante ?? 'usuario' }}"
                                                    class="max-h-40 max-w-full object-contain">

                                            </div>

                                        </div>
                                    @endif


                                </div>
                            @else
                                {{-- SIN SOLUCIÓN --}}

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
                                                La información de la solución aparecerá aquí cuando Tecnologías finalice
                                                el ticket.
                                            </p>

                                        </div>

                                    </div>

                                </div>

                            @endif

                        </div>


                        {{-- ACCIONES --}}

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


        </main>

    </div>

</body>

</html>
