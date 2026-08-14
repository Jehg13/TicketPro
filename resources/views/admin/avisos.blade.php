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
    <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0a0f24] border-r border-slate-800/60 p-6 hidden md:flex flex-col justify-between">
        <div>
            <div class="flex items-center gap-2 mb-10">
                <span class="text-3xl font-extrabold tracking-wide text-white">
                    Ticket<span class="text-blue-500">Pro</span>
                </span>
            </div>
            <div class="flex items-center gap-3 mb-8 p-2 rounded-xl bg-slate-900/40 border border-slate-800/50">
                <img
                   src="{{ asset('storage/' . auth()->user()->foto) }}" alt="{{ auth()->user()->name }}"
                    class="w-10 h-10 rounded-full object-cover ring-2 ring-blue-500/30"
                >
                <div class="overflow-hidden">
                    <h4 class="text-sm font-semibold text-slate-200 truncate">
                        {{Auth::User()->name ?? 'Desconocido'}}
                    </h4>
                    <p class="text-xs text-slate-400 truncate">
                        {{Auth::User()->departamento->nombre ?? 'Desconocido'}}
                    </p>
                </div>
            </div>
            <nav class="space-y-2">
                <a
                    href="{{ route('tecnologias')}}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition"
                >
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span class="font-medium text-sm">
                        Inicio
                    </span>
                </a>
                <a
                    href="{{ route('tickettecnologias')}}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition"
                >
                    <i data-lucide="ticket-check" class="w-5 h-5"></i>
                    <span class="text-sm">
                        Tickets
                    </span>
                </a>
                <a
                    href="{{ route('cambiostecnologias')}}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition"
                >
                    <i data-lucide="git-compare-arrows" class="w-5 h-5"></i>
                    <span class="font-medium text-sm">
                        Cambios
                    </span>
                </a>
                <a
                    href="{{ route('avisostecnologias')}}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold shadow-lg shadow-blue-600/30 transition"
                >
                    <i data-lucide="megaphone" class="w-5 h-5"></i>
                    <span class="text-sm">
                        Avisos
                    </span>
                </a>
                <a
                    href="{{ route('perfiltecnologias')}}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition"
                >
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
                class="
                    flex
                    items-center
                    gap-3
                    px-4
                    py-3
                    rounded-xl
                    text-slate-400
                    hover:bg-red-500/10
                    hover:text-red-400
                    transition
                ">
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
     <div class="flex items-center gap-6 self-end md:self-auto">
                        <div class="relative inline-block text-left">
                            <!-- Botón de Notificaciones -->
                            <button id="notif-button" type="button"
                                class="relative p-2 text-gray-300 hover:text-white bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700/50 rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 group shadow-lg"
                                aria-label="Ver notificaciones">
                                <!-- Icono Campana -->
                                <svg class="w-6 h-6 transition-transform group-hover:scale-110 duration-200"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                    </path>
                                </svg>

                                <!-- Punto Rojo Animado (Ping) -->
                                <span class="absolute top-1.5 right-1.5 flex h-3 w-3">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                    <span
                                        class="relative inline-flex rounded-full h-3 w-3 bg-rose-500 border-2 border-slate-900"></span>
                                </span>
                            </button>

                            <!-- Panel Desplegable (Dropdown) con la clase 'hidden' por defecto -->
                            <div id="notif-dropdown"
                                class="hidden absolute right-0 mt-3 w-80 sm:w-96 rounded-2xl bg-slate-900/95 backdrop-blur-md border border-slate-800 shadow-2xl z-50 overflow-hidden divide-y divide-slate-800">
                                <!-- Encabezado -->
                                <div class="p-4 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-sm font-semibold text-white">Notificaciones</h3>
                                        <span
                                            class="px-2 py-0.5 text-xs font-medium bg-indigo-500/10 text-indigo-400 rounded-full border border-indigo-500/20">3
                                            nuevas</span>
                                    </div>
                                    <button
                                        class="text-xs text-indigo-400 hover:text-indigo-300 font-medium transition-colors">
                                        Marcar leídas
                                    </button>
                                </div>

                                <!-- Lista de Notificaciones -->
                                <div class="max-h-80 overflow-y-auto divide-y divide-slate-800/50">
                                    <a href="#"
                                        class="flex gap-3 p-4 bg-slate-800/40 hover:bg-slate-800/80 transition-colors group">
                                        <div class="relative shrink-0">
                                            <img class="w-10 h-10 rounded-full object-cover ring-2 ring-indigo-500/30"
                                                src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80"
                                                alt="Avatar" />
                                            <span
                                                class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-slate-900 rounded-full"></span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs text-slate-300 leading-relaxed">
                                                <strong
                                                    class="font-semibold text-white group-hover:text-indigo-400 transition-colors">Elena
                                                    Rostova</strong> comentó en tu proyecto <span
                                                    class="text-slate-400">Dashboard UI</span>.
                                            </p>
                                            <span class="text-[10px] text-slate-500 mt-1 block">Hace 2 minutos</span>
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
                                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs text-slate-300 leading-relaxed">
                                                Tu despliegue en <strong
                                                    class="font-semibold text-white">Vite/Production</strong> se
                                                completó con
                                                éxito.
                                            </p>
                                            <span class="text-[10px] text-slate-500 mt-1 block">Hace 1 hora</span>
                                        </div>
                                    </a>
                                </div>

                                <!-- Pie de panel -->
                                <a href="#"
                                    class="block p-3 text-center text-xs font-medium text-slate-400 hover:text-white hover:bg-slate-800/50 transition-colors">
                                    Ver todas las notificaciones
                                </a>
                            </div>
                        </div>

                        <div
                            class="
                            flex
                            items-center
                            gap-3
                            bg-slate-900/80
                            border
                            border-slate-800
                            rounded-full
                            p-1.5
                            pr-4
                        ">
                            <img src="{{ asset('storage/' . auth()->user()->foto) }}"
                                alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full object-cover">
                            <div class="text-left leading-tight hidden sm:block">
                                <p class="text-xs font-semibold text-white">
                                    {{ Auth::User()->name ?? 'Desconocido' }}
                                </p>
                                <p class="text-[10px] text-slate-400">
                                    {{ Auth::User()->departamento->nombre ?? 'Desconocido' }}
                                </p>
                            </div>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 ml-1"></i>
                        </div>
                    </div>
                </div>
            </header>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-[#0b1026]/90 border border-blue-900/40 rounded-2xl p-6 shadow-2xl backdrop-blur-md space-y-6">
                    <div>
                        <h2 class="text-lg font-bold text-white mb-4">
                            1. Información del aviso
                        </h2>
                        <div class="mb-5">
                            <label class="block text-xs font-semibold text-slate-300 mb-2">
                                Título del aviso
                            </label>
                            <input
                                type="text"
                                placeholder="Ingresa un título claro y descriptivo"
                                class="w-full bg-[#070b19] border border-slate-800 rounded-xl px-4 py-3 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 transition"
                            >
                        </div>
                        <div class="mb-5">
                            <label class="block text-xs font-semibold text-slate-300 mb-2">
                                Tipo de aviso
                            </label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                <label class="cursor-pointer border-2 border-amber-500/80 bg-slate-900/50 rounded-xl p-4 flex flex-col items-center justify-center gap-3 relative transition">
                                    <span class="absolute top-2.5 right-2.5 w-3.5 h-3.5 rounded-full border-2 border-amber-500 flex items-center justify-center">
                                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                                    </span>
                                    <i data-lucide="wrench" class="w-5 h-5 text-amber-400"></i>
                                    <span class="text-xs font-semibold text-white">
                                        Mantenimiento
                                    </span>
                                </label>
                                <label class="cursor-pointer border border-slate-800 hover:border-slate-700 bg-slate-900/20 rounded-xl p-4 flex flex-col items-center justify-center gap-3 relative transition">
                                    <span class="absolute top-2.5 right-2.5 w-3.5 h-3.5 rounded-full border border-slate-600"></span>
                                    <i data-lucide="triangle-alert" class="w-5 h-5 text-rose-400"></i>
                                    <span class="text-xs font-semibold text-slate-300">
                                        Falla / Incidente
                                    </span>
                                </label>
                                <label class="cursor-pointer border border-slate-800 hover:border-slate-700 bg-slate-900/20 rounded-xl p-4 flex flex-col items-center justify-center gap-3 relative transition">
                                    <span class="absolute top-2.5 right-2.5 w-3.5 h-3.5 rounded-full border border-slate-600"></span>
                                    <i data-lucide="info" class="w-5 h-5 text-blue-400"></i>
                                    <span class="text-xs font-semibold text-slate-300">
                                        Informativo
                                    </span>
                                </label>
                                <label class="cursor-pointer border border-slate-800 hover:border-slate-700 bg-slate-900/20 rounded-xl p-4 flex flex-col items-center justify-center gap-3 relative transition">
                                    <span class="absolute top-2.5 right-2.5 w-3.5 h-3.5 rounded-full border border-slate-600"></span>
                                    <i data-lucide="megaphone" class="w-5 h-5 text-slate-400"></i>
                                    <span class="text-xs font-semibold text-slate-300">
                                        General
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="mb-5">
                            <label class="block text-xs font-semibold text-slate-300 mb-2">
                                Nivel de importancia
                            </label>
                            <div class="flex flex-wrap gap-2">
                                <button
                                    type="button"
                                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-rose-500/10 text-rose-500 border border-rose-500/30 hover:bg-rose-500/20 transition"
                                >
                                    <i data-lucide="octagon-alert" class="w-3.5 h-3.5"></i>
                                    Crítica
                                </button>
                                <button
                                    type="button"
                                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-amber-500/10 text-amber-500 border border-amber-500/30 hover:bg-amber-500/20 transition"
                                >
                                    <i data-lucide="trending-up" class="w-3.5 h-3.5"></i>
                                    Alta
                                </button>
                                <button
                                    type="button"
                                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-yellow-500/20 text-yellow-400 border border-yellow-500/50 transition"
                                >
                                    <i data-lucide="circle-minus" class="w-3.5 h-3.5"></i>
                                    Media
                                </button>
                                <button
                                    type="button"
                                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 hover:bg-emerald-500/20 transition"
                                >
                                    <i data-lucide="circle-check" class="w-3.5 h-3.5"></i>
                                    Normal
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 mb-2">
                                    Fecha del aviso
                                </label>
                                <div class="relative">
                                    <input
                                        type="text"
                                        value="05 Ago 2026"
                                        class="w-full bg-[#070b19] border border-slate-800 rounded-xl pl-10 pr-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500"
                                    >
                                    <i
                                        data-lucide="calendar-days"
                                        class="w-4 h-4 text-slate-400 absolute left-3.5 top-3"
                                    ></i>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 mb-2">
                                    Hora del aviso
                                </label>
                                <div class="relative">
                                    <input
                                        type="text"
                                        value="11:30 AM"
                                        class="w-full bg-[#070b19] border border-slate-800 rounded-xl pl-10 pr-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500"
                                    >
                                    <i
                                        data-lucide="clock-3"
                                        class="w-4 h-4 text-slate-400 absolute left-3.5 top-3"
                                    ></i>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-2">
                                Aplicar a
                            </label>
                            <div class="relative">
                                <input
                                    type="text"
                                    value="Todos los usuarios"
                                    class="w-full bg-[#070b19] border border-slate-800 rounded-xl pl-10 pr-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500"
                                >
                                <i
                                    data-lucide="users-round"
                                    class="w-4 h-4 text-slate-400 absolute left-3.5 top-3"
                                ></i>
                            </div>
                            <p class="text-[11px] text-slate-500 mt-1">
                                Selecciona a qué usuarios o áreas se mostrará este aviso
                            </p>
                        </div>
                    </div>
                    <hr class="border-slate-800/80">
                    <div>
                        <h2 class="text-lg font-bold text-white mb-4">
                            2. Contenido del aviso
                        </h2>
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-slate-300 mb-2">
                                Descripción del aviso
                            </label>
                            <textarea
                                rows="4"
                                class="w-full bg-[#070b19] border border-slate-800 rounded-xl p-3 text-xs text-slate-200 placeholder-slate-500 focus:outline-none focus:border-blue-500 resize-none"
                            >Se realizará mantenimiento preventivo en los servidores aplicaciones el día lunes 10 de agosto a partir de las 20:00 PM hasta las 23:00 PM.</textarea>
                            <div class="text-right text-[10px] text-slate-500 mt-1">
                                120 / 1000 caracteres
                            </div>
                        </div>
                        <div class="mb-6">
                            <label class="block text-xs font-semibold text-slate-300 mb-2">
                                Afecta a (Opcional)
                            </label>
                            <input
                                type="text"
                                placeholder="Ej. Sistemas internos, Portal de empleados"
                                class="w-full bg-[#070b19] border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-blue-500"
                            >
                        </div>
                        <div class="flex items-center justify-end gap-3 pt-2">
                            <button
                                type="button"
                                class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:text-white bg-slate-900 border border-slate-800 hover:bg-slate-800 transition"
                            >
                                Cancelar
                            </button>
                            <button
                                type="button"
                                class="px-6 py-2.5 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 shadow-lg shadow-blue-600/30 hover:opacity-90 transition"
                            >
                                Publicar aviso
                            </button>
                        </div>
                    </div>
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
                                <div class="w-12 h-12 rounded-lg bg-amber-700/60 flex items-center justify-center shrink-0">
                                    <i
                                        data-lucide="wrench"
                                        class="w-6 h-6 text-amber-300"
                                    ></i>
                                </div>
                                <div class="space-y-1.5">
                                    <span class="inline-block px-2 py-0.5 rounded text-[9px] font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30 uppercase tracking-wider">
                                        Mantenimiento Programado
                                    </span>
                                    <br>
                                    <span class="inline-block px-2 py-0.5 rounded text-[9px] font-bold bg-rose-500/20 text-rose-400 border border-rose-500/30 uppercase tracking-wider">
                                        Importante
                                    </span>
                                    <h4 class="text-xs font-bold text-white leading-snug">
                                        Mantenimiento en servidores de aplicaciones
                                    </h4>
                                </div>
                            </div>
                            <div class="text-[11px] text-slate-400 space-y-1 border-t border-b border-slate-800/80 py-2 my-2">
                                <div class="flex items-center gap-1.5">
                                    <i
                                        data-lucide="calendar-days"
                                        class="w-3.5 h-3.5 text-slate-500"
                                    ></i>
                                    <span>
                                        10 Ago 2026 - 20:00 PM
                                    </span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <i
                                        data-lucide="clock-3"
                                        class="w-3.5 h-3.5 text-slate-500"
                                    ></i>
                                    <span>
                                        10 Ago 2026 - 23:00 PM
                                    </span>
                                </div>
                            </div>
                            <p class="text-[11px] text-slate-300 leading-relaxed">
                                Se realizará mantenimiento preventivo en los servidores de aplicaciones el día lunes 10 de agosto a partir de las 20:00 PM hasta las 23:00 PM
                            </p>
                            <p class="text-[11px] text-slate-300 leading-relaxed">
                                Durante este periodo, algunos servicios podrían presentar intermitencias
                            </p>
                            <div class="pt-2">
                                <p class="text-[10px] text-slate-400 mb-1">
                                    Afecta a:
                                </p>
                                <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-medium bg-blue-500/10 text-blue-300 border border-blue-500/30">
                                    Sistemas internos, Portal de empleados
                                </span>
                            </div>
                            <div class="pt-2 text-[10px] text-slate-400 flex items-center justify-between border-t border-slate-800/60">
                                <span>
                                    Publicado por:
                                    <strong class="text-slate-200 font-medium">
                                        Carlos Martinez
                                    </strong>
                                </span>
                                <span class="px-2 py-0.5 rounded bg-slate-800 text-slate-300 text-[9px]">
                                    Tecnologías
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-[#0b1026]/90 border border-blue-900/40 rounded-2xl p-5 shadow-xl backdrop-blur-md space-y-4">
                        <h3 class="text-base font-bold text-white mb-2">
                            3. Configuración adicional
                        </h3>
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h4 class="text-xs font-semibold text-white">
                                    Mostrar notificaciones
                                </h4>
                                <p class="text-[11px] text-slate-400 leading-snug">
                                    Los usuarios recibirán una notificación sobre este aviso
                                </p>
                            </div>
                            <div class="w-9 h-5 bg-blue-600 rounded-full relative cursor-pointer shrink-0">
                                <div class="w-3.5 h-3.5 bg-white rounded-full absolute top-0.75 right-0.75"></div>
                            </div>
                        </div>
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h4 class="text-xs font-semibold text-white">
                                    Fijar aviso
                                </h4>
                                <p class="text-[11px] text-slate-400 leading-snug">
                                    El aviso permanecerá en la parte superior de la lista
                                </p>
                            </div>
                            <div class="w-9 h-5 bg-slate-800 rounded-full relative cursor-pointer shrink-0 border border-slate-700">
                                <div class="w-3.5 h-3.5 bg-slate-400 rounded-full absolute top-0.75 left-0.75"></div>
                            </div>
                        </div>
                        <div class="pt-2">
                            <h4 class="text-xs font-semibold text-white mb-1">
                                Adjuntar archivo
                            </h4>
                            <p class="text-[11px] text-slate-400 mb-3">
                                Puedes adjuntar un documento
                            </p>
                            <div class="border-2 border-dashed border-blue-900/60 hover:border-blue-500/50 bg-[#070b19]/60 rounded-xl p-6 text-center cursor-pointer transition">
                                <i
                                    data-lucide="cloud-upload"
                                    class="w-6 h-6 text-blue-400 mx-auto mb-2"
                                ></i>
                                <p class="text-[11px] font-medium text-slate-300">
                                    Arrastra archivos aquí o haz click para seleccionar
                                </p>
                                <p class="text-[9px] text-slate-500 mt-1">
                                    Formatos permitidos JPG, JPEG, PNG, PDF o MP4
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>