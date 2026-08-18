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

<body class="bg-[#070b19] text-white font-sans min-h-screen antialiased">


    {{-- ========================================================= --}}
    {{-- SIDEBAR --}}
    {{-- ========================================================= --}}

    <aside
        class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0a0f24] border-r border-slate-800/60 p-6 hidden md:flex flex-col justify-between">

        <div>

            <div class="flex items-center gap-2 mb-10">

                <span class="text-3xl font-extrabold tracking-wide text-white">
                    Ticket<span class="text-blue-500">Pro</span>
                </span>

            </div>


            {{-- USUARIO --}}

            <div class="flex items-center gap-3 mb-8 p-2 rounded-xl bg-slate-900/40 border border-slate-800/50">

                <img src="{{ auth()->user()->foto ? asset('storage/' . auth()->user()->foto) : asset('images/default-avatar.png') }}"
                    alt="{{ auth()->user()->name }}"
                    class="w-10 h-10 rounded-full object-cover ring-2 ring-blue-500/30">

                <div class="overflow-hidden">

                    <h4 class="text-sm font-semibold text-slate-200 truncate">

                        {{ auth()->user()->name ?? 'Desconocido' }}

                    </h4>

                    <p class="text-xs text-slate-400 truncate">

                        {{ optional(auth()->user()->departamento)->nombre ?? 'Sin departamento' }}

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
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">

                    <i data-lucide="ticket-check" class="w-5 h-5"></i>

                    <span class="font-medium text-sm">
                        Tickets
                    </span>

                </a>


                <a href="{{ route('cambiostecnologias') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold shadow-lg shadow-blue-600/30">

                    <i data-lucide="git-compare-arrows" class="w-5 h-5"></i>

                    <span class="text-sm">
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

        <form method="POST" action="{{ route('logout') }}">

            @csrf

            <button type="submit"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-red-500/10 hover:text-red-400 transition">

                <i data-lucide="log-out" class="w-5 h-5"></i>

                <span class="font-medium text-sm">
                    Cerrar sesión
                </span>

            </button>

        </form>

    </aside>



    {{-- ========================================================= --}}
    {{-- CONTENIDO --}}
    {{-- ========================================================= --}}

    <main class="md:ml-64 min-h-screen p-6 md:p-8">

        <div class="max-w-[1400px] mx-auto">


            {{-- ========================================================= --}}
            {{-- HEADER --}}
            {{-- ========================================================= --}}

            <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">

                <div>

                    <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-white">

                        Solicitudes de cambio

                    </h1>

                    <p class="text-sm text-slate-400 mt-1">

                        Consulta y da seguimiento a las solicitudes de cambio de información de cuentas

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
                        <div class="relative inline-block text-left">
                            <button id="notif-button" type="button"
                                class="relative p-2 text-gray-300 hover:text-white bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700/50 rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 group shadow-lg"
                                aria-label="Ver notificaciones">
                                <svg class="w-6 h-6 transition-transform group-hover:scale-110 duration-200"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                    </path>
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
                                                <strong class="font-semibold text-white">
                                                    Elena Rostova
                                                </strong>
                                                comentó en tu proyecto
                                                <span class="text-slate-400">
                                                    Dashboard UI
                                                </span>
                                            </p>
                                            <span class="text-[10px] text-slate-500 mt-1 block">
                                                Hace 2 minutos
                                            </span>
                                        </div>
                                        <span class="w-2 h-2 rounded-full bg-indigo-500 shrink-0 self-center"></span>
                                    </a>
                                    <a href="#" class="flex gap-3 p-4 hover:bg-slate-800/50 transition-colors group">
                                        <div class="w-10 h-10 rounded-full bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
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
                        <div class="relative z-[100]">
                            <button id="profile-button" type="button"
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
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7">
                                    </path>
                                </svg>
                            </button>
                            <div id="profile-dropdown"
                                class="hidden absolute right-0 top-full mt-3 w-56 bg-[#0f1535] border border-[#1e295d] rounded-xl shadow-2xl overflow-hidden z-[99999]">
                                <a href="{{ route('perfiltecnologias') }}"
                                    class="flex items-center gap-3 px-4 py-3 text-sm text-slate-300 hover:bg-[#151b3b] hover:text-white transition-colors">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
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



            {{-- ========================================================= --}}
            {{-- MENSAJES --}}
            {{-- ========================================================= --}}

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

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
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


            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

                <div
                    class="bg-[#0b1026]/90 border border-blue-900/40 rounded-2xl p-5 flex items-center justify-between shadow-xl">

                    <div>

                        <p class="text-xs text-slate-400 font-medium mb-1">
                            Total de solicitudes
                        </p>

                        <h3 class="text-2xl font-bold text-white">
                            {{ $total }}
                        </h3>

                    </div>

                    <div
                        class="w-11 h-11 bg-blue-500/10 text-blue-400 rounded-xl flex items-center justify-center border border-blue-500/20">

                        <i data-lucide="folder-open" class="w-5 h-5"></i>

                    </div>

                </div>


                {{-- PENDIENTES --}}

                <div
                    class="bg-[#0b1026]/90 border border-blue-900/40 rounded-2xl p-5 flex items-center justify-between shadow-xl">

                    <div>

                        <p class="text-xs text-slate-400 font-medium mb-1">
                            En revisión
                        </p>

                        <h3 class="text-2xl font-bold text-white">
                            {{ $pendientes }}
                        </h3>

                    </div>

                    <div
                        class="w-11 h-11 bg-amber-500/10 text-amber-400 rounded-xl flex items-center justify-center border border-amber-500/20">

                        <i data-lucide="clock-3" class="w-5 h-5"></i>

                    </div>

                </div>


                {{-- APROBADAS --}}

                <div
                    class="bg-[#0b1026]/90 border border-blue-900/40 rounded-2xl p-5 flex items-center justify-between shadow-xl">

                    <div>

                        <p class="text-xs text-slate-400 font-medium mb-1">
                            Aprobadas
                        </p>

                        <h3 class="text-2xl font-bold text-white">
                            {{ $aprobadas }}
                        </h3>

                    </div>

                    <div
                        class="w-11 h-11 bg-emerald-500/10 text-emerald-400 rounded-xl flex items-center justify-center border border-emerald-500/20">

                        <i data-lucide="circle-check" class="w-5 h-5"></i>

                    </div>

                </div>


                {{-- RECHAZADAS --}}

                <div
                    class="bg-[#0b1026]/90 border border-blue-900/40 rounded-2xl p-5 flex items-center justify-between shadow-xl">

                    <div>

                        <p class="text-xs text-slate-400 font-medium mb-1">
                            Rechazadas
                        </p>

                        <h3 class="text-2xl font-bold text-white">
                            {{ $rechazadas }}
                        </h3>

                    </div>

                    <div
                        class="w-11 h-11 bg-rose-500/10 text-rose-400 rounded-xl flex items-center justify-center border border-rose-500/20">

                        <i data-lucide="circle-x" class="w-5 h-5"></i>

                    </div>

                </div>

            </div>



            {{-- ========================================================= --}}
            {{-- FILTROS --}}
            {{-- ========================================================= --}}

            <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4 mb-6">


                <div class="flex flex-wrap items-center gap-1 bg-[#0b1026] p-1.5 rounded-xl border border-slate-800">


                    <a href="{{ route('cambiostecnologias') }}"
                        class="px-4 py-2 rounded-lg text-xs font-semibold
    {{ !request('estado') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-white' }}">

                        Todos

                    </a>


                    <a href="{{ route('cambiostecnologias', ['estado' => 'pendiente']) }}"
                        class="px-4 py-2 rounded-lg text-xs font-semibold flex items-center gap-2
    {{ request('estado') === 'pendiente' ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-white' }}">

                        En revisión

                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>

                    </a>


                    <a href="{{ route('cambiostecnologias', ['estado' => 'aprobada']) }}"
                        class="px-4 py-2 rounded-lg text-xs font-semibold flex items-center gap-2
    {{ request('estado') === 'aprobada' ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-white' }}">

                        Aprobadas

                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>

                    </a>


                    <a href="{{ route('cambiostecnologias', ['estado' => 'rechazada']) }}"
                        class="px-4 py-2 rounded-lg text-xs font-semibold flex items-center gap-2
    {{ request('estado') === 'rechazada' ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-white' }}">

                        Rechazadas

                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>

                    </a>

                </div>



                {{-- BUSCADOR --}}

                <form method="GET" action="{{ route('cambiostecnologias') }}" class="relative w-full xl:w-72">

                    @if (request('estado'))
                        <input type="hidden" name="estado" value="{{ request('estado') }}">
                    @endif

                    <i data-lucide="search" class="absolute left-3.5 top-3 w-4 h-4 text-slate-500">
                    </i>

                    <input type="text" name="buscar" value="{{ request('buscar') }}"
                        placeholder="Buscar solicitud..."
                        class="w-full bg-[#0b1026] border border-slate-800 text-slate-200 text-xs rounded-xl pl-10 pr-4 py-2.5 focus:outline-none focus:border-blue-500">

                </form>

            </div>



            {{-- ========================================================= --}}
            {{-- TABLA + DETALLE --}}
            {{-- ========================================================= --}}

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">


                {{-- ========================================================= --}}
                {{-- TABLA --}}
                {{-- ========================================================= --}}

                <div class="lg:col-span-2 bg-[#070e27] border border-slate-800/80 rounded-2xl p-5 flex flex-col">


                    <div class="overflow-x-auto">

                        <table class="w-full text-left text-xs">

                            <thead>

                                <tr class="text-slate-400 border-b border-slate-800/80">

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


                            <tbody class="divide-y divide-slate-800/50 text-slate-300">


                                @forelse($solicitudes as $solicitud)
                                    <tr
                                        class="hover:bg-slate-800/20 transition
    {{ $seleccionada?->id === $solicitud->id ? 'bg-blue-500/5' : '' }}">


                                        <td class="py-4 font-semibold text-white">

                                            {{ $solicitud->id }}

                                        </td>


                                        <td class="py-4">

                                            <div class="flex items-center gap-2">

                                                <img src="{{ $solicitud->usuario?->foto
                                                    ? asset('storage/' . $solicitud->usuario->foto)
                                                    : asset('images/default-avatar.png') }}"
                                                    class="w-7 h-7 rounded-full object-cover">

                                                <div>

                                                    <p class="text-white">
                                                        {{ $solicitud->usuario?->name ?? 'Usuario eliminado' }}
                                                    </p>

                                                    <p class="text-[10px] text-slate-500">
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
                                                    class="px-2.5 py-1 rounded-full text-[10px] font-medium bg-amber-500/10 text-amber-400 border border-amber-500/20">

                                                    En revisión

                                                </span>
                                            @elseif($solicitud->estado === 'aprobada')
                                                <span
                                                    class="px-2.5 py-1 rounded-full text-[10px] font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">

                                                    Aprobada

                                                </span>
                                            @else
                                                <span
                                                    class="px-2.5 py-1 rounded-full text-[10px] font-medium bg-rose-500/10 text-rose-400 border border-rose-500/20">

                                                    Rechazada

                                                </span>
                                            @endif

                                        </td>


                                        <td class="py-4">

                                            {{ $solicitud->created_at->format('d M Y') }}

                                            <br>

                                            <span class="text-[10px] text-slate-500">

                                                {{ $solicitud->created_at->format('h:i A') }}

                                            </span>

                                        </td>


                                        <td class="py-4 text-center">

                                            <a href="{{ request()->fullUrlWithQuery(['solicitud' => $solicitud->id]) }}"
                                                class="text-slate-400 hover:text-blue-400 transition">

                                                <i data-lucide="eye" class="w-4 h-4"></i>

                                            </a>

                                        </td>

                                    </tr>


                                @empty

                                    <tr>

                                        <td colspan="6" class="py-12 text-center text-slate-500">

                                            <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-3 opacity-40">
                                            </i>

                                            No se encontraron solicitudes.

                                        </td>

                                    </tr>
                                @endforelse


                            </tbody>

                        </table>

                    </div>



                    {{-- PAGINACIÓN --}}

                    <div
                        class="mt-6 pt-5 border-t border-slate-800/80 flex flex-col sm:flex-row justify-between items-center gap-4">

                        <span class="text-xs text-slate-400">

                            Mostrando
                            {{ $solicitudes->firstItem() ?? 0 }}
                            a
                            {{ $solicitudes->lastItem() ?? 0 }}
                            de
                            {{ $solicitudes->total() }}
                            solicitudes

                        </span>


                        <div class="flex items-center gap-1">

                            @if ($solicitudes->onFirstPage())
                                <span
                                    class="w-8 h-8 bg-slate-900 text-slate-600 rounded-lg flex items-center justify-center">

                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>

                                </span>
                            @else
                                <a href="{{ $solicitudes->previousPageUrl() }}"
                                    class="w-8 h-8 bg-slate-800 text-slate-400 rounded-lg hover:bg-slate-700 flex items-center justify-center">

                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>

                                </a>
                            @endif


                            @foreach ($solicitudes->getUrlRange(max(1, $solicitudes->currentPage() - 2), min($solicitudes->lastPage(), $solicitudes->currentPage() + 2)) as $page => $url)
                                <a href="{{ $url }}"
                                    class="w-8 h-8 rounded-lg flex items-center justify-center text-xs
                {{ $page == $solicitudes->currentPage()
                    ? 'bg-blue-600 text-white font-bold'
                    : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">

                                    {{ $page }}

                                </a>
                            @endforeach


                            @if ($solicitudes->hasMorePages())
                                <a href="{{ $solicitudes->nextPageUrl() }}"
                                    class="w-8 h-8 bg-slate-800 text-slate-400 rounded-lg hover:bg-slate-700 flex items-center justify-center">

                                    <i data-lucide="chevron-right" class="w-4 h-4"></i>

                                </a>
                            @else
                                <span
                                    class="w-8 h-8 bg-slate-900 text-slate-600 rounded-lg flex items-center justify-center">

                                    <i data-lucide="chevron-right" class="w-4 h-4"></i>

                                </span>
                            @endif

                        </div>

                    </div>

                </div>



                {{-- ========================================================= --}}
                {{-- DETALLE --}}
                {{-- ========================================================= --}}

                <div class="bg-[#0b1026]/90 border border-blue-900/40 rounded-2xl p-5 shadow-xl backdrop-blur-md">

                    @if ($seleccionada)

                        <div class="space-y-5">


                            {{-- HEADER DETALLE --}}

                            <div class="flex justify-between items-center pb-3 border-b border-slate-800">

                                <div>

                                    <h2 class="text-sm font-semibold text-white">
                                        Detalle de la solicitud
                                    </h2>

                                    <p class="text-[10px] text-slate-500">
                                        {{ $seleccionada->folio }}
                                    </p>

                                </div>


                                @if ($seleccionada->estado === 'pendiente')
                                    <span
                                        class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">

                                        En revisión

                                    </span>
                                @elseif($seleccionada->estado === 'aprobada')
                                    <span
                                        class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">

                                        Aprobada

                                    </span>
                                @else
                                    <span
                                        class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20">

                                        Rechazada

                                    </span>
                                @endif

                            </div>



                            {{-- SOLICITANTE --}}

                            <div>

                                <p class="text-slate-400 text-[10px] mb-2">
                                    Solicitado por
                                </p>

                                <div class="flex items-center gap-3 bg-slate-800/40 p-3 rounded-xl">

                                    <img src="{{ $seleccionada->usuario?->foto
                                        ? asset('storage/' . $seleccionada->usuario->foto)
                                        : asset('images/default-avatar.png') }}"
                                        class="w-9 h-9 rounded-full object-cover">

                                    <div>

                                        <p class="text-xs font-medium text-white">

                                            {{ $seleccionada->usuario?->name ?? 'Usuario eliminado' }}

                                        </p>

                                        <p class="text-[10px] text-slate-400">

                                            {{ $seleccionada->usuario?->email ?? 'Sin correo' }}

                                        </p>

                                        <p class="text-[10px] text-blue-400">

                                            {{ optional($seleccionada->usuario?->departamento)->nombre ?? 'Sin departamento' }}

                                        </p>

                                    </div>

                                </div>

                            </div>



                            {{-- INFORMACIÓN --}}

                            <div class="grid grid-cols-2 gap-3 text-xs">

                                <div>

                                    <p class="text-slate-400 text-[10px]">
                                        Campo solicitado
                                    </p>

                                    <p class="text-white font-medium">

                                        {{ ucfirst(str_replace('_', ' ', $seleccionada->campo)) }}

                                    </p>

                                </div>


                                <div>

                                    <p class="text-slate-400 text-[10px]">
                                        Fecha solicitud
                                    </p>

                                    <p class="text-white font-medium">

                                        {{ $seleccionada->created_at->format('d/m/Y h:i A') }}

                                    </p>

                                </div>

                            </div>



                            {{-- VALOR ACTUAL --}}

                            <div>

                                <p class="text-slate-400 text-[10px] mb-1">
                                    Información actual
                                </p>

                                <div
                                    class="bg-slate-900/70 border border-slate-800 rounded-xl p-3 text-xs text-slate-300 break-words">

                                    {{ $seleccionada->valor_actual ?? 'Sin información' }}

                                </div>

                            </div>



                            {{-- NUEVO VALOR --}}

                            <div>

                                <p class="text-slate-400 text-[10px] mb-1">
                                    Información solicitada
                                </p>

                                <div
                                    class="bg-blue-500/5 border border-blue-500/20 rounded-xl p-3 text-xs text-blue-300 break-words">

                                    {{ $seleccionada->nuevo_valor }}

                                </div>

                            </div>



                            {{-- MOTIVO --}}

                            <div>

                                <p class="text-slate-400 text-[10px] mb-1">
                                    Motivo de solicitud
                                </p>

                                <p class="text-slate-300 text-[11px] leading-relaxed">

                                    {{ $seleccionada->motivo }}

                                </p>

                            </div>



                            {{-- REVISOR --}}

                            @if ($seleccionada->revisor)
                                <div class="pt-2 border-t border-slate-800">

                                    <p class="text-slate-400 text-[10px] mb-2">
                                        Revisada por
                                    </p>

                                    <div class="flex items-center gap-3 bg-slate-800/40 p-3 rounded-xl">

                                        <img src="{{ $seleccionada->revisor->foto
                                            ? asset('storage/' . $seleccionada->revisor->foto)
                                            : asset('images/default-avatar.png') }}"
                                            class="w-8 h-8 rounded-full object-cover">

                                        <div>

                                            <p class="text-xs font-medium text-white">

                                                {{ $seleccionada->revisor->name }}

                                            </p>

                                            <p class="text-[9px] text-slate-400">

                                                {{ optional($seleccionada->revisado_at)->format('d M Y - h:i A') }}

                                            </p>

                                        </div>

                                    </div>

                                </div>
                            @endif



                            {{-- COMENTARIO ADMIN --}}

                            @if ($seleccionada->comentario_admin)
                                <div>

                                    <p class="text-slate-400 text-[10px]">
                                        Observaciones
                                    </p>

                                    <p class="text-slate-300 text-[11px] mt-1 leading-relaxed">

                                        {{ $seleccionada->comentario_admin }}

                                    </p>

                                </div>
                            @endif



                            {{-- ACCIONES --}}

                            @if ($seleccionada->estado === 'pendiente')
                                <div class="pt-3 border-t border-slate-800 space-y-3">

                                    <p class="text-slate-400 text-[10px]">
                                        Resolver solicitud
                                    </p>


                                    {{-- APROBAR --}}

                                    <form method="POST" action="{{ route('cambios.aprobar', $seleccionada) }}">

                                        @csrf

                                        <textarea name="comentario_admin" placeholder="Comentario opcional al aprobar..." rows="2"
                                            class="w-full bg-slate-900 border border-slate-800 rounded-xl p-3 text-xs text-white focus:outline-none focus:border-emerald-500 resize-none"></textarea>

                                        <button type="submit"
                                            class="w-full mt-2 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold transition flex items-center justify-center gap-2">

                                            <i data-lucide="check" class="w-4 h-4"></i>

                                            Aprobar solicitud

                                        </button>

                                    </form>


                                    {{-- RECHAZAR --}}

                                    <form method="POST" action="{{ route('cambios.rechazar', $seleccionada) }}">

                                        @csrf

                                        <textarea name="comentario_admin" required placeholder="Motivo del rechazo..." rows="2"
                                            class="w-full bg-slate-900 border border-slate-800 rounded-xl p-3 text-xs text-white focus:outline-none focus:border-rose-500 resize-none"></textarea>

                                        <button type="submit"
                                            class="w-full mt-2 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-semibold transition flex items-center justify-center gap-2">

                                            <i data-lucide="x" class="w-4 h-4"></i>

                                            Rechazar solicitud

                                        </button>

                                    </form>

                                </div>
                            @endif


                        </div>
                    @else
                        <div class="h-full min-h-[400px] flex flex-col items-center justify-center text-center">

                            <i data-lucide="file-question" class="w-12 h-12 text-slate-700 mb-3">
                            </i>

                            <p class="text-sm text-slate-500">
                                No hay ninguna solicitud seleccionada.
                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </main>





</body>

</html>
