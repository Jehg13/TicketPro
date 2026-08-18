<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TicketPro - Nuevo Aviso</title>

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
                    class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold shadow-lg shadow-blue-600/30 transition">
                    <i data-lucide="megaphone" class="w-5 h-5"></i>
                    <span class="text-sm">
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
            <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-white">
                        Nuevo aviso
                    </h1>
                    <p class="text-sm text-slate-400 mt-1">
                        Crea y publica un aviso para mantener informado a todos los usuarios
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
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="{
                tipo: '{{ old('tipo', 'mantenimiento') }}',
                importancia: '{{ old('importancia', 'media') }}',
                aplicaA: '{{ old('aplica_a', 'todos') }}',
                titulo: @js(old('titulo', '')),
                descripcion: @js(old('descripcion', '')),
                fechaInicio: @js(old('fecha_inicio', now()->format('Y-m-d'))),
                horaInicio: @js(old('hora_inicio', now()->format('H:i'))),
                mostrarNotificaciones: {{ old('mostrar_notificaciones', 1) ? 'true' : 'false' }},
                fijado: {{ old('fijado', 0) ? 'true' : 'false' }},
                seleccionados: @js(old('afecta_a', []))
            }">
                <div
                    class="lg:col-span-2 bg-[#0b1026]/90 border border-blue-900/40 rounded-2xl p-6 shadow-2xl backdrop-blur-md space-y-6">
                    <form action="{{ route('avisos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <h2 class="text-lg font-bold text-white mb-4">
                                1. Información del aviso
                            </h2>
                            <div class="mb-5">
                                <label class="block text-xs font-semibold text-slate-300 mb-2">
                                    Título del aviso
                                </label>
                                <input type="text" name="titulo" x-model="titulo"
                                    placeholder="Ingresa un título claro y descriptivo" required maxlength="255"
                                    class="w-full bg-[#070b19] border border-slate-800 rounded-xl px-4 py-3 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 transition">
                                @error('titulo')
                                    <p class="text-[10px] text-rose-400 mt-1">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="mb-5">
                                <label class="block text-xs font-semibold text-slate-300 mb-2">
                                    Tipo de aviso
                                </label>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    <label
                                        class="cursor-pointer rounded-xl p-4 flex flex-col items-center justify-center gap-3 relative transition"
                                        :class="tipo === 'mantenimiento'
                                            ?
                                            'border-2 border-amber-500/80 bg-amber-500/10' :
                                            'border border-slate-800 bg-slate-900/20 hover:border-slate-700'">
                                        <input type="radio" name="tipo" value="mantenimiento" x-model="tipo"
                                            class="sr-only">
                                        <span
                                            class="absolute top-2.5 right-2.5 w-3.5 h-3.5 rounded-full flex items-center justify-center"
                                            :class="tipo === 'mantenimiento'
                                                ?
                                                'border-2 border-amber-500' :
                                                'border border-slate-600'">
                                            <span x-show="tipo === 'mantenimiento'"
                                                class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                                        </span>
                                        <i data-lucide="wrench" class="w-5 h-5 text-amber-400"></i>
                                        <span class="text-xs font-semibold"
                                            :class="tipo === 'mantenimiento'
                                                ?
                                                'text-white' :
                                                'text-slate-300'">
                                            Mantenimiento
                                        </span>
                                    </label>
                                    <label
                                        class="cursor-pointer rounded-xl p-4 flex flex-col items-center justify-center gap-3 relative transition"
                                        :class="tipo === 'incidente'
                                            ?
                                            'border-2 border-rose-500/80 bg-rose-500/10' :
                                            'border border-slate-800 bg-slate-900/20 hover:border-slate-700'">
                                        <input type="radio" name="tipo" value="incidente" x-model="tipo"
                                            class="sr-only">
                                        <span
                                            class="absolute top-2.5 right-2.5 w-3.5 h-3.5 rounded-full flex items-center justify-center"
                                            :class="tipo === 'incidente'
                                                ?
                                                'border-2 border-rose-500' :
                                                'border border-slate-600'">
                                            <span x-show="tipo === 'incidente'"
                                                class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
                                        </span>
                                        <i data-lucide="triangle-alert" class="w-5 h-5 text-rose-400"></i>
                                        <span class="text-xs font-semibold"
                                            :class="tipo === 'incidente'
                                                ?
                                                'text-white' :
                                                'text-slate-300'">
                                            Falla / Incidente
                                        </span>
                                    </label>
                                    <label
                                        class="cursor-pointer rounded-xl p-4 flex flex-col items-center justify-center gap-3 relative transition"
                                        :class="tipo === 'informativo'
                                            ?
                                            'border-2 border-blue-500/80 bg-blue-500/10' :
                                            'border border-slate-800 bg-slate-900/20 hover:border-slate-700'">
                                        <input type="radio" name="tipo" value="informativo" x-model="tipo"
                                            class="sr-only">
                                        <span
                                            class="absolute top-2.5 right-2.5 w-3.5 h-3.5 rounded-full flex items-center justify-center"
                                            :class="tipo === 'informativo'
                                                ?
                                                'border-2 border-blue-500' :
                                                'border border-slate-600'">
                                            <span x-show="tipo === 'informativo'"
                                                class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                                        </span>
                                        <i data-lucide="info" class="w-5 h-5 text-blue-400"></i>
                                        <span class="text-xs font-semibold"
                                            :class="tipo === 'informativo'
                                                ?
                                                'text-white' :
                                                'text-slate-300'">
                                            Informativo
                                        </span>
                                    </label>
                                    <label
                                        class="cursor-pointer rounded-xl p-4 flex flex-col items-center justify-center gap-3 relative transition"
                                        :class="tipo === 'general'
                                            ?
                                            'border-2 border-slate-500/80 bg-slate-500/10' :
                                            'border border-slate-800 bg-slate-900/20 hover:border-slate-700'">
                                        <input type="radio" name="tipo" value="general" x-model="tipo"
                                            class="sr-only">
                                        <span
                                            class="absolute top-2.5 right-2.5 w-3.5 h-3.5 rounded-full flex items-center justify-center"
                                            :class="tipo === 'general'
                                                ?
                                                'border-2 border-slate-400' :
                                                'border border-slate-600'">
                                            <span x-show="tipo === 'general'"
                                                class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>
                                        </span>
                                        <i data-lucide="megaphone" class="w-5 h-5 text-slate-400"></i>
                                        <span class="text-xs font-semibold"
                                            :class="tipo === 'general'
                                                ?
                                                'text-white' :
                                                'text-slate-300'">
                                            General
                                        </span>
                                    </label>
                                </div>
                                @error('tipo')
                                    <p class="text-[10px] text-rose-400 mt-1">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="mb-5">
                                <label class="block text-xs font-semibold text-slate-300 mb-2">
                                    Nivel de importancia
                                </label>
                                <div class="flex flex-wrap gap-2">
                                    <label
                                        class="cursor-pointer flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer"
                                        :class="importancia === 'critica'
                                            ?
                                            'bg-rose-500/20 text-rose-400 border border-rose-500/50' :
                                            'bg-rose-500/10 text-rose-500 border border-rose-500/30 hover:bg-rose-500/20'">
                                        <input type="radio" name="importancia" value="critica"
                                            x-model="importancia" class="sr-only">
                                        <i data-lucide="octagon-alert" class="w-3.5 h-3.5"></i>
                                        Crítica
                                    </label>
                                    <label
                                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer"
                                        :class="importancia === 'alta'
                                            ?
                                            'bg-amber-500/20 text-amber-400 border border-amber-500/50' :
                                            'bg-amber-500/10 text-amber-500 border border-amber-500/30 hover:bg-amber-500/20'">
                                        <input type="radio" name="importancia" value="alta"
                                            x-model="importancia" class="sr-only">
                                        <i data-lucide="trending-up" class="w-3.5 h-3.5"></i>
                                        Alta
                                    </label>
                                    <label
                                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer"
                                        :class="importancia === 'media'
                                            ?
                                            'bg-yellow-500/20 text-yellow-400 border border-yellow-500/50' :
                                            'bg-yellow-500/10 text-yellow-400 border border-yellow-500/30 hover:bg-yellow-500/20'">
                                        <input type="radio" name="importancia" value="media"
                                            x-model="importancia" class="sr-only">
                                        <i data-lucide="circle-minus" class="w-3.5 h-3.5"></i>
                                        Media
                                    </label>
                                    <label
                                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer"
                                        :class="importancia === 'normal'
                                            ?
                                            'bg-emerald-500/20 text-emerald-400 border border-emerald-500/50' :
                                            'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 hover:bg-emerald-500/20'">
                                        <input type="radio" name="importancia" value="normal"
                                            x-model="importancia" class="sr-only">
                                        <i data-lucide="circle-check" class="w-3.5 h-3.5"></i>
                                        Normal
                                    </label>
                                </div>
                                @error('importancia')
                                    <p class="text-[10px] text-rose-400 mt-1">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-300 mb-2">
                                        Fecha del aviso
                                    </label>
                                    <div class="relative">
                                        <input type="date" name="fecha_inicio" x-model="fechaInicio" required
                                            class="w-full bg-[#070b19] border border-slate-800 rounded-xl pl-10 pr-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500">
                                        <i data-lucide="calendar-days"
                                            class="w-4 h-4 text-slate-400 absolute left-3.5 top-3 pointer-events-none"></i>
                                    </div>
                                    @error('fecha_inicio')
                                        <p class="text-[10px] text-rose-400 mt-1">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-300 mb-2">
                                        Hora del aviso
                                    </label>
                                    <div class="relative">
                                        <input type="time" name="hora_inicio" x-model="horaInicio" required
                                            class="w-full bg-[#070b19] border border-slate-800 rounded-xl pl-10 pr-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500">
                                        <i data-lucide="clock-3"
                                            class="w-4 h-4 text-slate-400 absolute left-3.5 top-3 pointer-events-none"></i>
                                    </div>
                                    @error('hora_inicio')
                                        <p class="text-[10px] text-rose-400 mt-1">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-6">
                                <label class="block text-xs font-semibold text-slate-300 mb-2">
                                    Aplicar a
                                </label>
                                <div class="relative">
                                    <select name="aplica_a" x-model="aplicaA" required
                                        class="w-full appearance-none bg-[#070b19] border border-slate-800 rounded-xl pl-10 pr-10 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500">
                                        <option value="todos">
                                            Todos los usuarios
                                        </option>
                                        <option value="departamento">
                                            Departamentos
                                        </option>
                                        <option value="usuarios">
                                            Usuarios específicos
                                        </option>
                                    </select>
                                    <i data-lucide="users-round"
                                        class="w-4 h-4 text-slate-400 absolute left-3.5 top-3 pointer-events-none"></i>
                                    <i data-lucide="chevron-down"
                                        class="w-4 h-4 text-slate-500 absolute right-3.5 top-3 pointer-events-none"></i>
                                </div>
                                <div x-show="aplicaA === 'todos'" x-cloak class="mt-3">
                                    <div
                                        class="flex items-start gap-3 p-4 rounded-xl bg-blue-500/5 border border-blue-500/20">
                                        <i data-lucide="users" class="w-5 h-5 text-blue-400 mt-0.5 shrink-0"></i>
                                        <div>
                                            <p class="text-xs font-semibold text-white">
                                                Todos los usuarios
                                            </p>
                                            <p class="text-[10px] text-slate-500 mt-1">
                                                El aviso será visible para todos los usuarios
                                                pertenecientes a tu empresa.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div x-show="aplicaA === 'departamento'" x-cloak class="mt-3">
                                    <label class="block text-[11px] font-semibold text-slate-400 mb-2">
                                        Seleccionar departamentos
                                    </label>
                                    <div
                                        class="bg-[#070b19] border border-slate-800 rounded-xl p-3 max-h-60 overflow-y-auto space-y-1">
                                        @forelse($departamentos as $departamento)
                                            <label
                                                class="flex items-center gap-3 p-3 rounded-lg cursor-pointer hover:bg-slate-900/70 transition">
                                                <input type="checkbox" name="afecta_a[]"
                                                    value="{{ $departamento->id }}"
                                                    :disabled="aplicaA !== 'departamento'"
                                                    {{ in_array((string) $departamento->id, array_map('strval', old('afecta_a', []))) ? 'checked' : '' }}
                                                    class="w-4 h-4 rounded border-slate-700 bg-slate-900 text-blue-600 focus:ring-blue-500">
                                                <div
                                                    class="w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center shrink-0">
                                                    <i data-lucide="building-2" class="w-4 h-4 text-blue-400"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-xs font-medium text-white">
                                                        {{ $departamento->nombre }}
                                                    </p>
                                                    @if ($departamento->oficina)
                                                        <p class="text-[9px] text-slate-500">
                                                            {{ $departamento->oficina->nombre }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </label>
                                        @empty
                                            <div class="p-5 text-center">
                                                <i data-lucide="building-2"
                                                    class="w-6 h-6 text-slate-600 mx-auto mb-2"></i>
                                                <p class="text-xs text-slate-500">
                                                    No hay departamentos disponibles.
                                                </p>
                                            </div>
                                        @endforelse
                                    </div>
                                    <div class="flex items-start gap-2 mt-2">
                                        <i data-lucide="info" class="w-3.5 h-3.5 text-slate-500 mt-0.5"></i>
                                        <p class="text-[10px] text-slate-500">
                                            Puedes seleccionar uno o varios departamentos.
                                            Solo aparecen departamentos de tu empresa.
                                        </p>
                                    </div>
                                    @error('afecta_a')
                                        <p class="text-[10px] text-rose-400 mt-1">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                <div x-show="aplicaA === 'usuarios'" x-cloak class="mt-3">
                                    <label class="block text-[11px] font-semibold text-slate-400 mb-2">
                                        Seleccionar usuarios
                                    </label>
                                    <div
                                        class="bg-[#070b19] border border-slate-800 rounded-xl p-3 max-h-72 overflow-y-auto space-y-1">
                                        @forelse($usuarios as $usuario)
                                            <label
                                                class="flex items-center gap-3 p-3 rounded-lg cursor-pointer hover:bg-slate-900/70 transition">
                                                <input type="checkbox" name="afecta_a[]" value="{{ $usuario->id }}"
                                                    :disabled="aplicaA !== 'usuarios'"
                                                    {{ in_array((string) $usuario->id, array_map('strval', old('afecta_a', []))) ? 'checked' : '' }}
                                                    class="w-4 h-4 rounded border-slate-700 bg-slate-900 text-blue-600 focus:ring-blue-500">
                                                <div
                                                    class="w-8 h-8 rounded-full bg-indigo-500/10 flex items-center justify-center shrink-0">
                                                    <i data-lucide="user-round" class="w-4 h-4 text-indigo-400"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-xs font-medium text-white truncate">
                                                        {{ $usuario->name }}
                                                    </p>
                                                    @if ($usuario->departamento)
                                                        <p class="text-[9px] text-slate-500 truncate">
                                                            {{ $usuario->departamento->nombre }}
                                                            @if ($usuario->departamento->oficina)
                                                                · {{ $usuario->departamento->oficina->nombre }}
                                                            @endif
                                                        </p>
                                                    @endif
                                                </div>
                                            </label>
                                        @empty
                                            <div class="p-5 text-center">
                                                <i data-lucide="users"
                                                    class="w-6 h-6 text-slate-600 mx-auto mb-2"></i>
                                                <p class="text-xs text-slate-500">
                                                    No hay usuarios disponibles.
                                                </p>
                                            </div>
                                        @endforelse
                                    </div>
                                    <div class="flex items-start gap-2 mt-2">
                                        <i data-lucide="info" class="w-3.5 h-3.5 text-slate-500 mt-0.5"></i>
                                        <p class="text-[10px] text-slate-500">
                                            Puedes seleccionar uno o varios usuarios.
                                            Solo aparecen usuarios de tu empresa.
                                        </p>
                                    </div>
                                    @error('afecta_a')
                                        <p class="text-[10px] text-rose-400 mt-1">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                <p class="text-[11px] text-slate-500 mt-2">
                                    Selecciona a qué usuarios o departamentos se mostrará este aviso.
                                </p>
                                @error('aplica_a')
                                    <p class="text-[10px] text-rose-400 mt-1">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                        <hr class="border-slate-800/80 my-6">
                    <div>
                            <h2 class="text-lg font-bold text-white mb-4">
                                2. Contenido del aviso
                            </h2>
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 mb-2">
                                    Descripción
                                </label>
                                <textarea name="descripcion" x-model="descripcion" rows="5" maxlength="1000" required
                                    placeholder="Escribe la información que deseas comunicar..."
                                    class="w-full bg-[#070b19] border border-slate-800 rounded-xl p-3 text-xs text-slate-200 placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 resize-none transition"></textarea>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                                <div class="flex items-center justify-between gap-4 bg-[#070b19] border border-slate-800 rounded-xl p-4 cursor-pointer hover:border-blue-500/40 transition"
                                    @click="mostrarNotificaciones = !mostrarNotificaciones">
                                    <div>
                                        <p class="text-xs font-semibold text-white">
                                            Mostrar notificaciones
                                        </p>
                                        <p class="text-[10px] text-slate-500 mt-1">
                                            Los usuarios recibirán una notificación
                                        </p>
                                    </div>
                                    <div class="w-10 h-6 rounded-full relative transition-colors duration-200 shrink-0"
                                        :class="mostrarNotificaciones
                                            ?
                                            'bg-blue-600' :
                                            'bg-slate-700'">

                                        <div class="w-4 h-4 bg-white rounded-full absolute top-1 transition-all duration-200 shadow"
                                            :class="mostrarNotificaciones
                                                ?
                                                'right-1' :
                                                'left-1'">
                                        </div>
                                    </div>
                                    <input type="hidden" name="mostrar_notificaciones"
                                        :value="mostrarNotificaciones ? 1 : 0">
                                </div>
                                <div class="flex items-center justify-between gap-4 bg-[#070b19] border border-slate-800 rounded-xl p-4 cursor-pointer hover:border-blue-500/40 transition"
                                    @click="fijado = !fijado">
                                    <div>
                                        <p class="text-xs font-semibold text-white">
                                            Fijar aviso
                                        </p>
                                        <p class="text-[10px] text-slate-500 mt-1">
                                            Mantener el aviso arriba de la lista
                                        </p>
                                    </div>
                                    <div class="w-10 h-6 rounded-full relative transition-colors duration-200 shrink-0"
                                        :class="fijado
                                            ?
                                            'bg-blue-600' :
                                            'bg-slate-700'">
                                        <div class="w-4 h-4 bg-white rounded-full absolute top-1 transition-all duration-200 shadow"
                                            :class="fijado
                                                ?
                                                'right-1' :
                                                'left-1'">
                                        </div>
                                    </div>
                                    <input type="hidden" name="fijado" :value="fijado ? 1 : 0">
                                </div>
                            </div>
                            <div class="mb-6">
                                <label class="block text-xs font-semibold text-slate-300 mb-2">
                                    Adjuntar archivo
                                </label>
                                <p class="text-[11px] text-slate-400 mb-3">
                                    Puedes adjuntar un documento relacionado con el aviso.
                                </p>
                                <label
                                    class="block border-2 border-dashed border-blue-900/60 hover:border-blue-500/50 bg-[#070b19]/60 rounded-xl p-6 text-center cursor-pointer transition">
                                    <i data-lucide="cloud-upload" class="w-6 h-6 text-blue-400 mx-auto mb-2"></i>
                                    <p class="text-[11px] font-medium text-slate-300">
                                        Arrastra archivos aquí o haz click para seleccionar
                                    </p>
                                    <p class="text-[9px] text-slate-500 mt-1">
                                        JPG, JPEG, PNG, PDF o MP4
                                    </p>
                                    <input type="file" name="archivo" accept=".jpg,.jpeg,.png,.pdf,.mp4"
                                        class="hidden">
                                </label>
                                @error('archivo')
                                    <p class="text-[10px] text-rose-400 mt-1">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="flex items-center justify-end gap-3 pt-2">
                                <a href="{{ route('avisostecnologias') }}"
                                    class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:text-white bg-slate-900 border border-slate-800 hover:bg-slate-800 transition">
                                    Cancelar
                                </a>
                                <button type="submit"
                                    class="px-6 py-2.5 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 shadow-lg shadow-blue-600/30 hover:opacity-90 transition">
                                    <span class="flex items-center gap-2">
                                        <i data-lucide="send" class="w-4 h-4"></i>
                                        Publicar aviso
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="space-y-6">
                    <div class="bg-[#0b1026]/90 border border-blue-900/40 rounded-2xl p-5 shadow-xl backdrop-blur-md">
                        <h3 class="text-base font-bold text-white mb-1">
                            Vista previa del aviso
                        </h3>
                        <p class="text-xs text-slate-400 mb-4">
                            Así verán los usuarios este aviso
                        </p>
                        <div class="bg-[#070b19] border border-slate-800 rounded-xl p-4 space-y-3">
                            <div class="flex items-start gap-3">
                                <div class="w-12 h-12 rounded-lg flex items-center justify-center shrink-0"
                                    :class="{
                                        'bg-amber-700/60': tipo === 'mantenimiento',
                                        'bg-rose-700/60': tipo === 'incidente',
                                        'bg-blue-700/60': tipo === 'informativo',
                                        'bg-slate-700/60': tipo === 'general'
                                    }">
                                    <i x-show="tipo === 'mantenimiento'" data-lucide="wrench"
                                        class="w-6 h-6 text-amber-300">
                                    </i>
                                    <i x-show="tipo === 'incidente'" data-lucide="triangle-alert"
                                        class="w-6 h-6 text-rose-300">
                                    </i>
                                    <i x-show="tipo === 'informativo'" data-lucide="info"
                                        class="w-6 h-6 text-blue-300">
                                    </i>
                                    <i x-show="tipo === 'general'" data-lucide="megaphone"
                                        class="w-6 h-6 text-slate-300">
                                    </i>
                                </div>
                                <div class="space-y-1.5 min-w-0">
                                    <span
                                        class="inline-block px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider"
                                        :class="{
                                            'bg-amber-500/20 text-amber-400 border border-amber-500/30': tipo === 'mantenimiento',
                                            'bg-rose-500/20 text-rose-400 border border-rose-500/30': tipo === 'incidente',
                                            'bg-blue-500/20 text-blue-400 border border-blue-500/30': tipo === 'informativo',
                                            'bg-slate-500/20 text-slate-400 border border-slate-500/30': tipo === 'general'
                                        }"
                                        x-text="
                            tipo === 'mantenimiento'
                                ? 'Mantenimiento'
                                : tipo === 'incidente'
                                    ? 'Falla / Incidente'
                                    : tipo === 'informativo'
                                        ? 'Informativo'
                                        : 'General'
                        ">
                                    </span>
                                    <br>
                                    <span
                                        class="inline-block px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider"
                                        :class="{
                                            'bg-rose-500/20 text-rose-400 border border-rose-500/30': importancia === 'critica',
                                            'bg-amber-500/20 text-amber-400 border border-amber-500/30': importancia === 'alta',
                                            'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30': importancia === 'media',
                                            'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30': importancia === 'normal'
                                        }"
                                        x-text="
                            importancia === 'critica'
                                ? 'Crítica'
                                : importancia === 'alta'
                                    ? 'Alta'
                                    : importancia === 'media'
                                        ? 'Media'
                                        : 'Normal'
                        ">
                                    </span>
                                    <h4 class="text-xs font-bold text-white leading-snug break-words"
                                        x-text="titulo || 'Título del aviso'">
                                    </h4>
                                </div>
                            </div>
                            <div
                                class="text-[11px] text-slate-400 space-y-1 border-t border-b border-slate-800/80 py-2 my-2">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="calendar-days" class="w-3.5 h-3.5 text-slate-500">
                                    </i>
                                    <span
                                        x-text="
                            fechaInicio
                                ? new Date(fechaInicio + 'T00:00:00')
                                    .toLocaleDateString('es-MX', {
                                        day: '2-digit',
                                        month: 'short',
                                        year: 'numeric'
                                    })
                                : 'Fecha no especificada'
                        ">
                                    </span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="clock-3" class="w-3.5 h-3.5 text-slate-500">
                                    </i>
                                    <span x-text="horaInicio || 'Hora no especificada'">
                                    </span>
                                </div>
                            </div>
                            <div class="text-[11px] text-slate-300 leading-relaxed whitespace-pre-line"
                                x-text="descripcion || 'Aquí aparecerá la descripción del aviso...'">
                            </div>
                            <div class="pt-2">
                                <p class="text-[10px] text-slate-400 mb-1">
                                    Afecta a:
                                </p>
                                <span
                                    class="inline-block px-2.5 py-1 rounded-full text-[10px] font-medium bg-blue-500/10 text-blue-300 border border-blue-500/30"
                                    x-text="
                        aplicaA === 'todos'
                            ? 'Todos los usuarios'
                            : aplicaA === 'departamento'
                                ? 'Departamentos seleccionados'
                                : 'Usuarios específicos'
                    ">
                                </span>
                            </div>
                            <div
                                class="pt-2 text-[10px] text-slate-400 flex items-center justify-between border-t border-slate-800/60">
                                <span>
                                    Publicado por:
                                    <strong class="text-slate-200 font-medium">
                                        {{ Auth::user()->name ?? 'Usuario' }}
                                    </strong>
                                </span>
                                <span class="px-2 py-0.5 rounded bg-slate-800 text-slate-300 text-[9px]">
                                    <span
                                        x-text="
                            aplicaA === 'todos'
                                ? 'Todos'
                                : aplicaA === 'departamento'
                                    ? 'Departamentos'
                                    : 'Usuarios'
                        ">
                                    </span>
                                </span>
                            </div>
                            <div class="flex items-center gap-2 pt-1">
                                <template x-if="mostrarNotificaciones">
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-blue-500/10 border border-blue-500/20 text-[9px] text-blue-400">
                                        <i data-lucide="bell" class="w-3 h-3">
                                        </i>
                                        Notificaciones
                                    </span>
                                </template>
                                <template x-if="fijado">
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-amber-500/10 border border-amber-500/20 text-[9px] text-amber-400">
                                        <i data-lucide="pin" class="w-3 h-3">
                                        </i>
                                        Fijado
                                    </span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div x-data="avisosApp()" class="mt-8">
                <div
                    class="bg-[#0b1026]/90 border border-blue-900/40 rounded-2xl shadow-2xl backdrop-blur-md overflow-hidden">
                    <div class="p-6 border-b border-slate-800/80">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <h2 class="text-lg font-bold text-white">
                                    Avisos publicados
                                </h2>
                                <p class="text-xs text-slate-400 mt-1">
                                    Consulta, modifica o elimina los avisos registrados.
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span
                                    class="px-3 py-1.5 rounded-lg bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-semibold">
                                    {{ $avisos->count() }}
                                    {{ $avisos->count() == 1 ? 'aviso' : 'avisos' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr
                                    class="border-b border-slate-800 text-[10px] uppercase tracking-wider text-slate-400 font-semibold bg-slate-900/30">
                                    <th class="px-6 py-4">
                                        Aviso
                                    </th>
                                    <th class="px-6 py-4">
                                        Tipo
                                    </th>
                                    <th class="px-6 py-4">
                                        Importancia
                                    </th>
                                    <th class="px-6 py-4">
                                        Fecha
                                    </th>
                                    <th class="px-6 py-4">
                                        Aplicación
                                    </th>
                                    <th class="px-6 py-4 text-center">
                                        Estado
                                    </th>
                                    <th class="px-6 py-4 text-right">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60">
                                @forelse($avisos as $aviso)
                                    <tr class="hover:bg-slate-900/40 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3 min-w-[260px]">
                                                <div
                                                    class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0
                                                    @if ($aviso->tipo === 'mantenimiento') bg-amber-500/10 text-amber-400 border border-amber-500/20
                                                    @elseif($aviso->tipo === 'incidente')
                                                        bg-rose-500/10 text-rose-400 border border-rose-500/20
                                                    @elseif($aviso->tipo === 'informativo')
                                                        bg-blue-500/10 text-blue-400 border border-blue-500/20
                                                    @else
                                                        bg-slate-500/10 text-slate-400 border border-slate-500/20 @endif
                                                    ">
                                                    @if ($aviso->tipo === 'mantenimiento')
                                                        <i data-lucide="wrench" class="w-5 h-5"></i>
                                                    @elseif($aviso->tipo === 'incidente')
                                                        <i data-lucide="triangle-alert" class="w-5 h-5"></i>
                                                    @elseif($aviso->tipo === 'informativo')
                                                        <i data-lucide="info" class="w-5 h-5"></i>
                                                    @else
                                                        <i data-lucide="megaphone" class="w-5 h-5"></i>
                                                    @endif
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="flex items-center gap-2">
                                                        <h3
                                                            class="text-sm font-semibold text-white truncate max-w-[260px]">
                                                            {{ $aviso->titulo }}
                                                        </h3>
                                                        @if ($aviso->fijado)
                                                            <span
                                                                class="text-[9px] px-1.5 py-0.5 rounded bg-blue-500/10 text-blue-400 border border-blue-500/20"
                                                                title="Aviso fijado">
                                                                <i data-lucide="pin" class="w-3 h-3 inline"></i>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <p class="text-[10px] text-slate-500 mt-1">
                                                        Publicado por:
                                                        {{ $aviso->publicadoPor->name ?? 'Desconocido' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($aviso->tipo === 'mantenimiento')
                                                <span
                                                    class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                                    Mantenimiento
                                                </span>
                                            @elseif($aviso->tipo === 'incidente')
                                                <span
                                                    class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                                    Incidente
                                                </span>
                                            @elseif($aviso->tipo === 'informativo')
                                                <span
                                                    class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                                    Informativo
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-semibold bg-slate-500/10 text-slate-400 border border-slate-500/20">
                                                    General
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($aviso->importancia === 'critica')
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                                    Crítica
                                                </span>
                                            @elseif($aviso->importancia === 'alta')
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                                    Alta
                                                </span>
                                            @elseif($aviso->importancia === 'media')
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-semibold bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-400"></span>
                                                    Media
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                                    Normal
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <i data-lucide="calendar-days" class="w-4 h-4 text-slate-500"></i>
                                                <div>
                                                    <p class="text-xs text-slate-300">
                                                        {{ \Carbon\Carbon::parse($aviso->fecha_inicio)->format('d/m/Y') }}
                                                    </p>
                                                    <p class="text-[10px] text-slate-500">
                                                        {{ \Carbon\Carbon::parse($aviso->fecha_inicio)->format('h:i A') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($aviso->aplica_a === 'todos')
                                                <span class="text-xs text-slate-300">
                                                    Todos los usuarios
                                                </span>
                                            @elseif($aviso->aplica_a === 'departamento')
                                                <span class="text-xs text-slate-300">
                                                    Departamento
                                                </span>
                                            @else
                                                <span class="text-xs text-slate-300">
                                                    Usuarios específicos
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @php
                                                $inicio = \Carbon\Carbon::parse($aviso->fecha_inicio);
                                                $fin = $aviso->fecha_fin
                                                    ? \Carbon\Carbon::parse($aviso->fecha_fin)
                                                    : null;
                                            @endphp
                                            @if ($inicio->isFuture())
                                                <span
                                                    class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                                    Programado
                                                </span>
                                            @elseif($fin && $fin->isPast())
                                                <span
                                                    class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-semibold bg-slate-500/10 text-slate-400 border border-slate-500/20">
                                                    Finalizado
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                    <span
                                                        class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                                    Activo
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-end gap-2">
                                                <button type="button" @click="abrirVer(@js($aviso))"
                                                    class="p-2 rounded-lg text-slate-400 hover:text-blue-400 hover:bg-blue-500/10 border border-transparent hover:border-blue-500/20 transition"
                                                    title="Ver aviso">
                                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                                </button>
                                                <button type="button"
                                                    @click="abrirEditar(@js($aviso))"
                                                    class="p-2 rounded-lg text-slate-400 hover:text-amber-400 hover:bg-amber-500/10 border border-transparent hover:border-amber-500/20 transition"
                                                    title="Editar aviso">
                                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                                </button>
                                                <button type="button"
                                                    @click="abrirEliminar(@js($aviso))"
                                                    class="p-2 rounded-lg text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 border border-transparent hover:border-rose-500/20 transition"
                                                    title="Eliminar aviso">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-16 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div
                                                    class="w-14 h-14 rounded-2xl bg-slate-800/50 border border-slate-700 flex items-center justify-center mb-4">
                                                    <i data-lucide="megaphone-off" class="w-7 h-7 text-slate-500"></i>
                                                </div>
                                                <h3 class="text-sm font-semibold text-white">
                                                    No hay avisos registrados
                                                </h3>
                                                <p class="text-xs text-slate-500 mt-1">
                                                    Los avisos que publiques aparecerán aquí.
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div x-show="modalVer" x-cloak x-transition.opacity @keydown.escape.window="cerrarModales()"
                    class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">
                    <div @click.outside="cerrarModales()" x-show="modalVer" x-transition
                        class="w-full max-w-3xl max-h-[90vh] overflow-y-auto bg-[#0b1026] border border-blue-900/50 rounded-2xl shadow-2xl">
                        <template x-if="avisoSeleccionado">
                            <div>
                                <div class="p-6 border-b border-slate-800 flex items-center justify-between">
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span
                                                class="text-[10px] uppercase tracking-wider text-blue-400 font-semibold">
                                                Detalle del aviso
                                            </span>
                                            <template x-if="avisoSeleccionado.fijado">
                                                <span
                                                    class="px-2 py-0.5 rounded bg-blue-500/10 text-blue-400 border border-blue-500/20 text-[9px]">
                                                    Fijado
                                                </span>
                                            </template>
                                        </div>
                                        <h2 class="text-xl font-bold text-white" x-text="avisoSeleccionado.titulo">
                                        </h2>
                                    </div>
                                    <button type="button" @click="cerrarModales()"
                                        class="p-2 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition">
                                        <i data-lucide="x" class="w-5 h-5"></i>
                                    </button>
                                </div>
                                <div class="p-6 space-y-6">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div class="bg-[#070b19] border border-slate-800 rounded-xl p-4">
                                            <p class="text-[10px] uppercase tracking-wider text-slate-500 mb-2">
                                                Tipo
                                            </p>
                                            <p class="text-sm font-semibold text-white capitalize"
                                                x-text="avisoSeleccionado.tipo"></p>
                                        </div>
                                        <div class="bg-[#070b19] border border-slate-800 rounded-xl p-4">
                                            <p class="text-[10px] uppercase tracking-wider text-slate-500 mb-2">
                                                Importancia
                                            </p>
                                            <p class="text-sm font-semibold text-white capitalize"
                                                x-text="avisoSeleccionado.importancia"></p>
                                        </div>
                                        <div class="bg-[#070b19] border border-slate-800 rounded-xl p-4">
                                            <p class="text-[10px] uppercase tracking-wider text-slate-500 mb-2">
                                                Importancia
                                            </p>
                                            <p class="text-sm font-semibold text-white capitalize"
                                                x-text="avisoSeleccionado.importancia"></p>
                                        </div>
                                        <div class="bg-[#070b19] border border-slate-800 rounded-xl p-4">
                                            <p class="text-[10px] uppercase tracking-wider text-slate-500 mb-2">
                                                Fecha de inicio
                                            </p>
                                            <p class="text-sm text-slate-200"
                                                x-text="formatearFecha(avisoSeleccionado.fecha_inicio)"></p>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-slate-300 mb-2">
                                            Descripción
                                        </p>
                                        <div class="bg-[#070b19] border border-slate-800 rounded-xl p-4">
                                            <p class="text-sm text-slate-300 leading-relaxed whitespace-pre-line"
                                                x-text="avisoSeleccionado.descripcion || 'Sin descripción'"></p>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-slate-300 mb-2">
                                            Afecta a
                                        </p>
                                        <div class="bg-[#070b19] border border-slate-800 rounded-xl p-4">
                                            <template x-if="avisoSeleccionado?.afecta_a?.tipo === 'todos'">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="w-9 h-9 rounded-lg bg-blue-500/10 border border-blue-500/20 flex items-center justify-center">
                                                        <i data-lucide="users" class="w-4 h-4 text-blue-400"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-semibold text-white">
                                                            Todos los usuarios
                                                        </p>
                                                        <p class="text-[10px] text-slate-500 mt-1">
                                                            Aplica a toda la empresa
                                                        </p>
                                                    </div>
                                                </div>
                                            </template>
                                            <template x-if="avisoSeleccionado?.afecta_a?.tipo === 'departamentos'">
                                                <div>
                                                    <p
                                                        class="text-[10px] uppercase tracking-wider text-slate-500 mb-3">
                                                        Departamentos seleccionados
                                                    </p>
                                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                        <template x-for="id in (avisoSeleccionado.afecta_a.ids || [])"
                                                            :key="'departamento-' + id">
                                                            <div
                                                                class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-slate-900/50 border border-slate-800">
                                                                <div class="min-w-0">
                                                                    <p class="text-xs font-semibold text-white truncate"
                                                                        x-text="obtenerDepartamento(id)?.nombre || 'Departamento no encontrado'">
                                                                    </p>
                                                                    <p class="text-[10px] text-slate-500">
                                                                        Destinatario
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </template>
                                            <template x-if="avisoSeleccionado?.afecta_a?.tipo === 'usuarios'">
                                                <div>
                                                    <p
                                                        class="text-[10px] uppercase tracking-wider text-slate-500 mb-3">
                                                        Usuarios específicos
                                                    </p>
                                                    <div class="space-y-2">
                                                        <template x-for="id in (avisoSeleccionado.afecta_a.ids || [])"
                                                            :key="id">
                                                            <div
                                                                class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-slate-900/50 border border-slate-800">
                                                                <div
                                                                    class="w-8 h-8 rounded-lg bg-blue-500/10 border border-blue-500/20 flex items-center justify-center shrink-0">
                                                                    <i data-lucide="user"
                                                                        class="w-4 h-4 text-blue-400"></i>
                                                                </div>
                                                                <div class="min-w-0">
                                                                    <p class="text-xs font-semibold text-white truncate"
                                                                        x-text="obtenerUsuario(id)?.name || 'Usuario no encontrado'">
                                                                    </p>
                                                                    <p class="text-[10px] text-slate-500">
                                                                        Usuario
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </template>
                                            <template x-if="!avisoSeleccionado?.afecta_a">
                                                <p class="text-sm text-slate-500">
                                                    No especificado
                                                </p>
                                            </template>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                        <div class="bg-[#070b19] border border-slate-800 rounded-xl p-4">
                                            <p class="text-[10px] text-slate-500 mb-1">
                                                Aplica a
                                            </p>
                                            <p class="text-xs font-semibold text-white"
                                                x-text="textoAplicaA(avisoSeleccionado.aplica_a)">
                                            </p>
                                        </div>
                                        <div class="bg-[#070b19] border border-slate-800 rounded-xl p-4">
                                            <p class="text-[10px] text-slate-500 mb-1">
                                                Notificaciones
                                            </p>
                                            <p class="text-xs font-semibold text-white"
                                                x-text="avisoSeleccionado.mostrar_notificaciones
                                                ? 'Activadas'
                                                : 'Desactivadas'">
                                            </p>
                                        </div>
                                        <div class="bg-[#070b19] border border-slate-800 rounded-xl p-4">
                                            <p class="text-[10px] text-slate-500 mb-1">
                                                Aviso fijado
                                            </p>
                                            <p class="text-xs font-semibold text-white"
                                                x-text="avisoSeleccionado.fijado
                                                ? 'Sí'
                                                : 'No'">
                                            </p>
                                        </div>
                                    </div>
                                    <div class="bg-[#070b19] border border-slate-800 rounded-xl p-4">
                                        <template x-if="!avisoSeleccionado.archivo">
                                            <div
                                                class="flex flex-col items-center justify-center py-6 border border-dashed border-slate-800 rounded-lg">
                                                <i data-lucide="file-x" class="w-6 h-6 text-slate-600 mb-2">
                                                </i>
                                                <p class="text-xs text-slate-500">
                                                    Este aviso no tiene archivo adjunto.
                                                </p>
                                            </div>
                                        </template>
                                        <template x-if="avisoSeleccionado.archivo">
                                            <div>
                                                <p class="text-[10px] uppercase tracking-wider text-slate-500 mb-3">
                                                    Archivo adjunto
                                                </p>
                                                <template x-if="esImagen(avisoSeleccionado.archivo)">
                                                    <a :href="urlArchivo(avisoSeleccionado.archivo)" target="_blank"
                                                        rel="noopener noreferrer"
                                                        class="block rounded-xl overflow-hidden border border-slate-800 bg-black/30 hover:border-blue-500/40 transition cursor-pointer">
                                                        <img :src="urlArchivo(avisoSeleccionado.archivo)"
                                                            alt="Archivo adjunto"
                                                            class="w-full max-h-[350px] object-contain">
                                                    </a>
                                                </template>
                                                <template x-if="esPdf(avisoSeleccionado.archivo)">
                                                    <a :href="urlArchivo(avisoSeleccionado.archivo)" target="_blank"
                                                        rel="noopener noreferrer"
                                                        class="block rounded-xl overflow-hidden border border-slate-800 bg-[#111827] hover:border-blue-500/40 transition cursor-pointer">
                                                        <iframe :src="urlArchivo(avisoSeleccionado.archivo)"
                                                            class="w-full h-[400px] pointer-events-none"
                                                            frameborder="0">
                                                        </iframe>
                                                    </a>
                                                </template>
                                                <template x-if="esVideo(avisoSeleccionado.archivo)">
                                                    <a :href="urlArchivo(avisoSeleccionado.archivo)" target="_blank"
                                                        rel="noopener noreferrer"
                                                        class="block rounded-xl overflow-hidden border border-slate-800 bg-black hover:border-blue-500/40 transition cursor-pointer">
                                                        <video :src="urlArchivo(avisoSeleccionado.archivo)"
                                                            class="w-full max-h-[400px] pointer-events-none" muted>
                                                        </video>
                                                    </a>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6 border-t border-slate-800 flex justify-end gap-3">
                                <button type="button" @click="cerrarModales()"
                                    class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 bg-slate-900 border border-slate-800 hover:bg-slate-800 hover:text-white transition">
                                    Cerrar
                                </button>
                                <button type="button" @click="modalVer = false; modalEditar = true"
                                    class="px-5 py-2.5 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:opacity-90 transition">
                                    Editar aviso
                                </button>
                            </div>
                    </div>
                    </template>
                </div>
            </div>
            <div x-show="modalEditar" x-cloak x-transition.opacity @keydown.escape.window="cerrarModales()"
                class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">
                <div @click.outside="cerrarModales()" x-show="modalEditar" x-transition
                    class="w-full max-w-3xl max-h-[90vh] overflow-y-auto bg-[#0b1026] border border-blue-900/50 rounded-2xl shadow-2xl">
                    <template x-if="avisoSeleccionado">
                        <form :action="'/tecnologias/avisos/' + avisoSeleccionado.id" method="POST"
                            enctype="multipart/form-data" class="p-6">
                            @csrf
                            @method('PUT')
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <span class="text-[10px] uppercase tracking-wider text-blue-400 font-semibold">
                                        Modificar aviso
                                    </span>
                                    <h2 class="text-xl font-bold text-white mt-1">
                                        Editar información
                                    </h2>
                                </div>
                                <button type="button" @click="cerrarModales()"
                                    class="p-2 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition">
                                    ×
                                </button>
                            </div>
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-300 mb-2">
                                        Título
                                    </label>
                                    <input type="text" name="titulo" x-model="avisoSeleccionado.titulo" required
                                        class="w-full bg-[#070b19] border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-blue-500">
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-300 mb-2">
                                            Tipo
                                        </label>
                                        <select name="tipo" x-model="avisoSeleccionado.tipo" required
                                            class="w-full bg-[#070b19] border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-blue-500">
                                            <option value="mantenimiento">Mantenimiento</option>
                                            <option value="incidente">Incidente</option>
                                            <option value="informativo">Informativo</option>
                                            <option value="general">General</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-300 mb-2">
                                            Importancia
                                        </label>
                                        <select name="importancia" x-model="avisoSeleccionado.importancia" required
                                            class="w-full bg-[#070b19] border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-blue-500">
                                            <option value="critica">Crítica</option>
                                            <option value="alta">Alta</option>
                                            <option value="media">Media</option>
                                            <option value="normal">Normal</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-300 mb-2">
                                            Fecha de inicio
                                        </label>
                                        <input type="date" name="fecha_inicio"
                                            :value="fechaSolo(avisoSeleccionado.fecha_inicio)" required
                                            class="w-full bg-[#070b19] border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-300 mb-2">
                                            Hora de inicio
                                        </label>
                                        <input type="time" name="hora_inicio"
                                            :value="horaSolo(avisoSeleccionado.fecha_inicio)" required
                                            class="w-full bg-[#070b19] border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-blue-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-300 mb-2">
                                        Aplicar a
                                    </label>
                                    <select name="aplica_a" x-model="avisoSeleccionado.aplica_a"
                                        @change="cambiarDestino()" required
                                        class="w-full bg-[#070b19] border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-blue-500">
                                        <option value="todos">
                                            Todos los usuarios
                                        </option>
                                        <option value="departamento">
                                            Departamentos
                                        </option>
                                        <option value="usuarios">
                                            Usuarios específicos
                                        </option>
                                    </select>
                                </div>
                                <div x-show="avisoSeleccionado.aplica_a === 'departamento'" x-transition
                                    class="bg-[#070b19] border border-slate-800 rounded-xl p-4">
                                    <div class="mb-3">
                                        <p class="text-xs font-semibold text-white">
                                            Departamentos
                                        </p>
                                        <p class="text-[10px] text-slate-500 mt-1">
                                            Selecciona los departamentos que recibirán el aviso.
                                        </p>
                                    </div>
                                    <div class="space-y-2 max-h-48 overflow-y-auto">
                                        @foreach ($departamentos as $departamento)
                                            <label
                                                class="flex items-center gap-3 p-3 rounded-lg hover:bg-slate-900/60 cursor-pointer">
                                                <input type="checkbox" name="afecta_a[]"
                                                    value="{{ $departamento->id }}"
                                                    :checked="afectaSeleccionado('departamentos', {{ $departamento->id }})"
                                                    class="w-4 h-4 accent-blue-600">
                                                <span class="text-xs text-slate-200">
                                                    {{ $departamento->nombre }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                <div x-show="avisoSeleccionado.aplica_a === 'usuarios'" x-transition
                                    class="bg-[#070b19] border border-slate-800 rounded-xl p-4">
                                    <div class="mb-3">
                                        <p class="text-xs font-semibold text-white">
                                            Usuarios
                                        </p>
                                        <p class="text-[10px] text-slate-500 mt-1">
                                            Selecciona los usuarios que recibirán el aviso.
                                        </p>
                                    </div>
                                    <div class="space-y-2 max-h-48 overflow-y-auto">
                                        @foreach ($usuarios as $usuario)
                                            <label
                                                class="flex items-center gap-3 p-3 rounded-lg hover:bg-slate-900/60 cursor-pointer">
                                                <input type="checkbox" name="afecta_a[]" value="{{ $usuario->id }}"
                                                    :checked="afectaSeleccionado('usuarios', {{ $usuario->id }})"
                                                    class="w-4 h-4 accent-blue-600">
                                                <div>
                                                    <p class="text-xs text-slate-200">
                                                        {{ $usuario->name }}
                                                    </p>
                                                    @if ($usuario->departamento)
                                                        <p class="text-[10px] text-slate-500">
                                                            {{ $usuario->departamento->nombre }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                <div x-show="avisoSeleccionado.aplica_a === 'todos'" x-transition
                                    class="bg-blue-500/5 border border-blue-500/20 rounded-xl p-4">
                                    <p class="text-xs font-semibold text-blue-300">
                                        Todos los usuarios
                                    </p>
                                    <p class="text-[10px] text-slate-500 mt-1">
                                        El aviso será visible para todos los usuarios de la empresa.
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-300 mb-2">
                                        Descripción
                                    </label>
                                    <textarea name="descripcion" rows="5" x-model="avisoSeleccionado.descripcion" required
                                        class="w-full bg-[#070b19] border border-slate-800 rounded-xl p-4 text-xs text-slate-200 focus:outline-none focus:border-blue-500 resize-none"></textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-300 mb-2">
                                        Archivo adjunto
                                    </label>
                                    <template x-if="avisoSeleccionado.archivo">
                                        <div class="mb-4">
                                            <p class="text-[10px] uppercase tracking-wider text-slate-500 mb-2">
                                                Archivo actual
                                            </p>
                                            <template x-if="esImagen(avisoSeleccionado.archivo)">
                                                <a :href="urlArchivo(avisoSeleccionado.archivo)" target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="block rounded-xl overflow-hidden border border-slate-800 bg-black/30 hover:border-blue-500/40 transition cursor-pointer">
                                                    <img :src="urlArchivo(avisoSeleccionado.archivo)"
                                                        alt="Archivo actual"
                                                        class="w-full max-h-[250px] object-contain">
                                                </a>
                                            </template>
                                            <template x-if="esPdf(avisoSeleccionado.archivo)">
                                                <a :href="urlArchivo(avisoSeleccionado.archivo)" target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="block rounded-xl overflow-hidden border border-slate-800 bg-[#111827] hover:border-blue-500/40 transition cursor-pointer">
                                                    <iframe :src="urlArchivo(avisoSeleccionado.archivo)"
                                                        class="w-full h-[250px] pointer-events-none" frameborder="0">
                                                    </iframe>
                                                </a>
                                            </template>
                                            <template x-if="esVideo(avisoSeleccionado.archivo)">
                                                <a :href="urlArchivo(avisoSeleccionado.archivo)" target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="block rounded-xl overflow-hidden border border-slate-800 bg-black hover:border-blue-500/40 transition cursor-pointer">
                                                    <video :src="urlArchivo(avisoSeleccionado.archivo)"
                                                        class="w-full max-h-[250px] pointer-events-none" muted>
                                                    </video>
                                                </a>
                                            </template>
                                            <div
                                                class="flex items-center gap-2 mt-2 px-3 py-2 rounded-lg bg-slate-900/50 border border-slate-800">
                                                <i data-lucide="paperclip" class="w-4 h-4 text-blue-400 shrink-0">
                                                </i>
                                                <p class="text-[10px] text-slate-400 truncate"
                                                    x-text="nombreArchivo(avisoSeleccionado.archivo)">
                                                </p>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="!avisoSeleccionado.archivo">
                                        <div class="mb-4">
                                            <div
                                                class="flex items-center gap-3 p-4 rounded-xl border border-dashed border-slate-800 bg-[#070b19]/50">
                                                <i data-lucide="file-x" class="w-5 h-5 text-slate-600">
                                                </i>
                                                <p class="text-[10px] text-slate-500">
                                                    Actualmente no hay archivo adjunto.
                                                </p>
                                            </div>
                                        </div>
                                    </template>
                                    <div>
                                        <p class="text-[10px] uppercase tracking-wider text-slate-500 mb-2">
                                            Reemplazar archivo
                                        </p>
                                        <input type="file" name="archivo" accept=".jpg,.jpeg,.png,.pdf,.mp4"
                                            class="w-full bg-[#070b19] border border-slate-800 rounded-xl px-4 py-3 text-xs text-slate-300">
                                        <p class="text-[10px] text-slate-500 mt-2">
                                            Selecciona un archivo únicamente si deseas reemplazar el actual.
                                        </p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <label
                                        class="flex items-center justify-between bg-[#070b19] border border-slate-800 rounded-xl p-4 cursor-pointer hover:border-blue-900/60 transition">
                                        <div>
                                            <p class="text-xs font-semibold text-white">
                                                Mostrar notificaciones
                                            </p>
                                            <p class="text-[10px] text-slate-500 mt-1">
                                                Notificar a los usuarios.
                                            </p>
                                        </div>
                                        <div class="relative shrink-0 ml-4">
                                            <input type="checkbox" name="mostrar_notificaciones" value="1"
                                                x-model="avisoSeleccionado.mostrar_notificaciones"
                                                class="sr-only peer">
                                            <div
                                                class="w-11 h-6 bg-slate-800 border border-slate-700 rounded-full transition-colors peer-checked:bg-blue-600 peer-checked:border-blue-500">
                                            </div>
                                            <div
                                                class="absolute top-0.5 left-0.5 w-5 h-5 bg-slate-300 rounded-full shadow transition-transform peer-checked:translate-x-5 peer-checked:bg-white">
                                            </div>
                                        </div>
                                    </label>
                                    <label
                                        class="flex items-center justify-between bg-[#070b19] border border-slate-800 rounded-xl p-4 cursor-pointer hover:border-blue-900/60 transition">
                                        <div>
                                            <p class="text-xs font-semibold text-white">
                                                Fijar aviso
                                            </p>
                                            <p class="text-[10px] text-slate-500 mt-1">
                                                Mantener el aviso arriba.
                                            </p>
                                        </div>
                                        <div class="relative shrink-0 ml-4">
                                            <input type="checkbox" name="fijado" value="1"
                                                x-model="avisoSeleccionado.fijado" class="sr-only peer">
                                            <div
                                                class="w-11 h-6 bg-slate-800 border border-slate-700 rounded-full transition-colors peer-checked:bg-blue-600 peer-checked:border-blue-500">
                                            </div>
                                            <div
                                                class="absolute top-0.5 left-0.5 w-5 h-5 bg-slate-300 rounded-full shadow transition-transform peer-checked:translate-x-5 peer-checked:bg-white">
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-slate-800">
                                <button type="button" @click="cerrarModales()"
                                    class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 bg-slate-900 border border-slate-800 hover:bg-slate-800 hover:text-white transition">
                                    Cancelar
                                </button>
                                <button type="submit"
                                    class="px-6 py-2.5 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 shadow-lg shadow-blue-600/30 hover:opacity-90 transition">
                                    Guardar cambios
                                </button>
                            </div>
                        </form>
                    </template>
                </div>
            </div>
            <div x-show="modalEliminar" x-cloak x-transition.opacity @keydown.escape.window="cerrarModales()"
                class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">
                <div @click.outside="cerrarModales()" x-show="modalEliminar" x-transition
                    class="w-full max-w-md bg-[#0b1026] border border-rose-900/50 rounded-2xl shadow-2xl">
                    <template x-if="avisoSeleccionado">
                        <div class="p-6">
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-12 h-12 rounded-xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center shrink-0">
                                    <i data-lucide="trash-2" class="w-6 h-6 text-rose-400"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg font-bold text-white">
                                        Eliminar aviso
                                    </h2>
                                    <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                                        ¿Estás seguro de que deseas eliminar este aviso?
                                    </p>
                                    <p class="text-sm font-semibold text-white mt-3"
                                        x-text="avisoSeleccionado.titulo"></p>
                                    <p class="text-[10px] text-rose-400 mt-2">
                                        Esta acción no se puede deshacer.
                                    </p>
                                </div>
                            </div>
                            <form :action="'/tecnologias/avisos/' + avisoSeleccionado.id" method="POST"
                                class="flex justify-end gap-3 mt-6">
                                @csrf
                                @method('DELETE')
                                <button type="button" @click="cerrarModales()"
                                    class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 bg-slate-900 border border-slate-800 hover:bg-slate-800 hover:text-white transition">
                                    Cancelar
                                </button>
                                <button type="submit"
                                    class="px-5 py-2.5 rounded-xl text-xs font-semibold text-white bg-rose-600 hover:bg-rose-500 transition shadow-lg shadow-rose-600/20">
                                    Sí, eliminar
                                </button>
                            </form>
                        </div>
                    </template>
                </div>
            </div>
        </div>
        </div>
    </main>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('avisosApp', () => ({
                modalVer: false,
                modalEditar: false,
                modalEliminar: false,
                avisoSeleccionado: null,
                abrirVer(aviso) {
                    this.cerrarModales();
                    this.avisoSeleccionado = JSON.parse(
                        JSON.stringify(aviso)
                    );
                    this.normalizarAviso();
                    this.modalVer = true;
                },

                abrirEditar(aviso) {
                    this.cerrarModales();
                    this.avisoSeleccionado = JSON.parse(
                        JSON.stringify(aviso)
                    );
                    this.normalizarAviso();
                    this.modalEditar = true;
                },

                abrirEliminar(aviso) {
                    this.cerrarModales();
                    this.avisoSeleccionado = JSON.parse(
                        JSON.stringify(aviso)
                    );
                    this.normalizarAviso();
                    this.modalEliminar = true;
                },

                normalizarAviso() {
                    if (!this.avisoSeleccionado) {
                        return;
                    }
                    if (!this.avisoSeleccionado.afecta_a) {

                        this.avisoSeleccionado.afecta_a = {};
                    }
                    if (
                        typeof this.avisoSeleccionado.afecta_a === 'string'
                    ) {
                        try {
                            this.avisoSeleccionado.afecta_a =
                                JSON.parse(
                                    this.avisoSeleccionado.afecta_a
                                );
                        } catch (error) {
                            this.avisoSeleccionado.afecta_a = {};
                        }
                    }
                    this.avisoSeleccionado.mostrar_notificaciones =
                        Boolean(
                            Number(
                                this.avisoSeleccionado.mostrar_notificaciones
                            )
                        );
                    this.avisoSeleccionado.fijado =
                        Boolean(
                            Number(
                                this.avisoSeleccionado.fijado
                            )
                        );
                },

                obtenerDepartamento(id) {
                    const departamentos = @js($departamentos);
                    return departamentos.find(
                        departamento =>
                        Number(departamento.id) === Number(id)
                    ) || null;
                },
                
                obtenerUsuario(id) {
                    const usuarios = @js($usuarios);
                    return usuarios.find(
                        usuario =>
                        Number(usuario.id) === Number(id)
                    ) || null;
                },

                cerrarModales() {
                    this.modalVer = false;
                    this.modalEditar = false;
                    this.modalEliminar = false;
                    this.avisoSeleccionado = null;
                },
                
                formatearFecha(fecha) {
                    if (!fecha) {
                        return 'No especificada';
                    }
                    try {
                        const fechaNormalizada = fecha.replace(
                            ' ',
                            'T'
                        );
                        const date = new Date(
                            fechaNormalizada
                        );
                        if (isNaN(date.getTime())) {
                            return fecha;
                        }
                        return date.toLocaleString(
                            'es-MX', {
                                day: '2-digit',
                                month: '2-digit',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            }
                        );
                    } catch (error) {
                        return fecha;
                    }
                },

                fechaSolo(fecha) {
                    if (!fecha) {
                        return '';
                    }
                    return fecha
                        .replace(' ', 'T')
                        .substring(0, 10);
                },

                horaSolo(fecha) {
                    if (!fecha) {
                        return '';
                    }
                    return fecha
                        .replace(' ', 'T')
                        .substring(11, 16);
                },
               
                afectaSeleccionado(tipo, id) {
                    if (
                        !this.avisoSeleccionado ||
                        !this.avisoSeleccionado.afecta_a
                    ) {
                        return false;
                    }
                    const afecta =
                        this.avisoSeleccionado.afecta_a;
                    if (afecta.tipo !== tipo) {
                        return false;
                    }
                    if (!Array.isArray(afecta.ids)) {
                        return false;
                    }
                    return afecta.ids
                        .map(Number)
                        .includes(Number(id));
                },

                cambiarDestino() {
                    if (!this.avisoSeleccionado) {
                        return;
                    }
                    if (
                        this.avisoSeleccionado.aplica_a === 'todos'
                    ) {
                        this.avisoSeleccionado.afecta_a = {
                            tipo: 'todos'
                        };
                        return;
                    }
                    if (
                        this.avisoSeleccionado.aplica_a === 'departamento'
                    ) {
                        this.avisoSeleccionado.afecta_a = {
                            tipo: 'departamentos',
                            ids: []
                        };
                        return;
                    }
                    if (
                        this.avisoSeleccionado.aplica_a === 'usuarios'
                    ) {
                        this.avisoSeleccionado.afecta_a = {
                            tipo: 'usuarios',
                            ids: []
                        };
                    }
                },

                textoAplicaA(valor) {
                    if (valor === 'todos') {
                        return 'Todos los usuarios';
                    }
                    if (valor === 'departamento') {
                        return 'Departamentos';
                    }
                    if (valor === 'usuarios') {
                        return 'Usuarios específicos';
                    }
                    return valor || 'No especificado';
                },

                nombreArchivo(archivo) {
                    if (!archivo) {
                        return 'Sin archivo';
                    }
                    return archivo
                        .split('/')
                        .pop();
                },
                
                esImagen(archivo) {
                    if (!archivo) {
                        return false;
                    }
                    return /\.(jpg|jpeg|png)$/i.test(
                        archivo
                    );
                },

                esPdf(archivo) {
                    if (!archivo) {
                        return false;
                    }
                    return /\.pdf$/i.test(
                        archivo
                    );
                },
            
                esVideo(archivo) {
                    if (!archivo) {
                        return false;
                    }
                    return /\.mp4$/i.test(
                        archivo
                    );
                },

                urlArchivo(archivo) {
                    if (!archivo) {
                        return '';
                    }
                    return '/storage/' + archivo;
                }
            }));
        });
    </script>

</body>

</html>
