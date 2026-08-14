<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis tickets - TicketPro</title>

    <!-- Carga de Tailwind CSS y JS mediante Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#060818] text-white font-sans antialiased flex h-screen overflow-hidden">

    <!-- SIDEBAR (Oculto en móviles, visible desde 'md:') -->
    <aside
        class="hidden md:flex flex-col w-64 bg-[#0a0e27] border-r border-[#1e295d] px-4 py-6 overflow-y-auto [::-webkit-scrollbar]:w-2 [::-webkit-scrollbar-track]:bg-[#060818] [::-webkit-scrollbar-thumb]:bg-[#1e295d] [::-webkit-scrollbar-thumb]:rounded-full">
        <!-- Logo -->
        <div class="text-3xl font-bold mb-8 px-2 tracking-wide">
            Ticket<span class="text-blue-500">Pro</span>
        </div>

        <!-- Usuario Sidebar -->
        <div class="flex items-center gap-3 mb-10 px-2">
            <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="{{ auth()->user()->name }}"
                class="w-12 h-12 rounded-full border-2 border-gray-500 object-cover">
            <div>
                <h3 class="text-sm font-semibold text-white">{{ Auth::User()->name ?? 'Desconocido' }}</h3>
                <p class="text-xs text-gray-400">{{ Auth::User()->departamento->nombre ?? 'Desconocido' }}</p>
            </div>
        </div>

        <!-- Navegación -->
        <nav class="flex-1 space-y-2">
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-[#151b3b] hover:text-white rounded-lg transition">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                <span class="text-sm font-medium">Inicio</span>
            </a>

            <!-- Opción Activa: Mis tickets -->
            <a href="{{ route('misticketusuario') }}"
                class="flex items-center gap-3 px-4 py-3 bg-blue-600 text-white rounded-lg font-medium shadow-[0_0_15px_rgba(37,99,235,0.4)]">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                    </path>
                </svg>
                <span class="text-sm font-medium">Mis tickets</span>
            </a>

            <a href="{{ route('ticketusuario') }}"
                class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-[#151b3b] hover:text-white rounded-lg transition">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z">
                    </path>
                </svg>
                <span class="text-sm font-medium">Crear ticket</span>
            </a>

            <a href="{{ route('avisosusuario') }}"
                class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-[#151b3b] hover:text-white rounded-lg transition">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
                <span class="text-sm font-medium">Avisos</span>
            </a>

            <a href="{{ route('perfilusuario') }}"
                class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-[#151b3b] hover:text-white rounded-lg transition">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="text-sm font-medium">Mi perfil</span>
            </a>
        </nav>

        <!-- Cerrar Sesión -->
        <div class="mt-auto pt-6">
            <form method="POST" action="{{ route('logout') }}" class="mt-6">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-[#151b3b] hover:text-white rounded-lg transition text-left">

                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>

                    <span class="text-sm font-medium">Cerrar sesión</span>
                </button>
            </form>
        </div>

    </aside>

    <!-- CONTENIDO PRINCIPAL -->
    <main
        class="flex-1 flex flex-col h-screen overflow-y-auto [::-webkit-scrollbar]:w-2 [::-webkit-scrollbar-track]:bg-[#060818] [::-webkit-scrollbar-thumb]:bg-[#1e295d] [::-webkit-scrollbar-thumb]:rounded-full">

        <!-- Header Principal -->
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center px-6 py-6 md:py-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold mb-1 tracking-tight">Mis tickets</h1>
                <p class="text-sm text-gray-400">
                    <span class="text-gray-200 font-medium">Mis tickets</span> / Dashboard
                </p>
            </div>

            <div class="flex items-center gap-6 self-end md:self-auto">
                <div class="relative inline-block text-left">
                    <!-- Botón de Notificaciones -->
                    <button id="notif-button" type="button"
                        class="relative p-2 text-gray-300 hover:text-white bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700/50 rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 group shadow-lg"
                        aria-label="Ver notificaciones">
                        <!-- Icono Campana -->
                        <svg class="w-6 h-6 transition-transform group-hover:scale-110 duration-200" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
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

                            <a href="#" class="flex gap-3 p-4 hover:bg-slate-800/50 transition-colors group">
                                <div
                                    class="w-10 h-10 rounded-full bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-slate-300 leading-relaxed">
                                        Tu despliegue en <strong
                                            class="font-semibold text-white">Vite/Production</strong> se completó con
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

                <div class="flex items-center gap-3 cursor-pointer">
                    <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="{{ auth()->user()->name }}"
                        class="w-10 h-10 rounded-full border border-gray-600 object-cover">
                    <div class="hidden md:block text-right">
                        <p class="text-sm font-semibold leading-tight">{{ Auth::user()->name ?? 'Juan Pérez' }}</p>
                        <p class="text-xs text-gray-400">{{ Auth::user()->departamento->nombre ?? 'Administración' }}
                        </p>
                    </div>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </div>
            </div>
        </header>

        <!-- TARJETA CONTENEDORA DE TABLA DE TICKETS -->
        <div class="px-6 pb-8">
            <section class="bg-[#0f1535] rounded-xl border border-[#1e295d] p-6 shadow-lg">

                <!-- Card Header con Título, Subtítulo y Buscador -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                    <div>
                        <h2 class="text-xl font-bold tracking-wide text-white">Mis tickets</h2>
                        <p class="text-sm text-gray-400 mt-1">Consulta y da seguimiento a todos tus tickes registrados
                        </p>
                    </div>

                    <!-- Buscador -->
                    <form action="{{ route('misticketusuario') }}" method="GET" class="relative w-full md:w-72">

                        <input type="text" name="buscar" value="{{ request('buscar') }}"
                            placeholder="Buscar folio, título o fecha..."
                            class="w-full bg-[#060818] border border-[#1e295d] rounded-lg py-2 pl-9 pr-10 text-sm text-gray-200 placeholder-gray-500 focus:outline-none focus:border-blue-500 transition">

                        <!-- Icono -->
                        <button type="submit"
                            class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 hover:text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z">
                                </path>
                            </svg>
                        </button>

                        <!-- Botón limpiar -->
                        @if (request('buscar'))
                            <a href="{{ route('misticketusuario') }}"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-400"
                                title="Limpiar búsqueda">
                                ✕
                            </a>
                        @endif
                    </form>
                </div>
                <!-- TABLA DE TICKETS (Con scroll horizontal para móviles) -->
                <div
                    class="overflow-x-auto [::-webkit-scrollbar]:h-2 [::-webkit-scrollbar-track]:bg-[#060818] [::-webkit-scrollbar-thumb]:bg-[#1e295d] [::-webkit-scrollbar-thumb]:rounded-full">
                    <table class="w-full text-left border-collapse min-w-[700px]">
                        <thead>
                            <tr class="border-b border-[#1e295d] text-gray-300 font-semibold text-sm">
                                <th class="pb-4 px-4 w-1/6">Folio</th>
                                <th class="pb-4 px-4 w-2/5">Titulo del ticket</th>
                                <th class="pb-4 px-4 text-center">Estado</th>
                                <th class="pb-4 px-4 text-center">Fecha creacion</th>
                                <th class="pb-4 px-4 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#1e295d]/60 text-sm">

                            @forelse ($tickets as $ticket)
                                <tr class="hover:bg-[#151b3b]/40 transition">

                                    <td class="py-5 px-4 font-bold text-gray-200 whitespace-nowrap">
                                        TKT-{{ $ticket->created_at->format('Y') }}-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}
                                    </td>

                                    <td class="py-5 px-4">
                                        <h4 class="font-bold text-white mb-1">
                                            {{ $ticket->titulo }}
                                        </h4>

                                        <p class="text-xs text-gray-400 leading-relaxed max-w-md">
                                            {{ Str::limit($ticket->descripcion, 100) }}
                                        </p>
                                    </td>

                                    <td class="py-5 px-4 text-center whitespace-nowrap">

                                        @if ($ticket->estado === 'solucionado')
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#133d28] text-green-300 border border-green-500/40">
                                                ✓ Solucionado
                                            </span>
                                        @elseif ($ticket->estado === 'pendiente')
                                            <span
                                                class="inline-flex items-center justify-center min-w-[100px] px-3 py-1 rounded-full text-xs font-semibold bg-[#4a4213] text-yellow-300 border border-yellow-500/40">
                                                <span class="w-2 h-2 rounded-full bg-orange-400 mr-2"></span>
                                                Pendiente
                                            </span>
                                        @elseif ($ticket->estado === 'en proceso')
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#1d2757] text-blue-300 border border-blue-500/40">
                                                <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                                                En proceso
                                            </span>
                                        @elseif ($ticket->estado === 'cancelado')
                                            <span
                                                class="inline-flex items-center justify-center min-w-[100px] px-3 py-1 rounded-full text-xs font-semibold bg-[#4d1616] text-red-300 border border-red-500/40">
                                                 Cancelado
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center justify-center min-w-[100px] px-3 py-1 rounded-full text-xs font-semibold bg-[#4a4213] text-yellow-300 border border-yellow-500/40">
                                                {{ $ticket->estado ?? 'Abierto' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-5 px-4 text-center whitespace-nowrap">
                                        <p class="font-bold text-gray-200">
                                            {{ $ticket->created_at->format('d M Y') }}
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            {{ $ticket->created_at->format('h:i A') }}
                                        </p>
                                    </td>
                                    <td class="py-5 px-4 text-center whitespace-nowrap">
                                        <button
                                            class="w-9 h-9 inline-flex items-center justify-center rounded-lg bg-[#1c224d] text-blue-400 hover:bg-blue-600 hover:text-white transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-gray-400">
                                        No tienes tickets registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- FOOTER / PAGINACIÓN DE LA TABLA -->
                <div
                    class="mt-6 pt-4 border-t border-[#1e295d] flex flex-col sm:flex-row justify-between items-center gap-4">

                    <p class="text-sm font-semibold text-gray-200">
                        Mostrando {{ $tickets->firstItem() ?? 0 }}
                        a {{ $tickets->lastItem() ?? 0 }}
                        de {{ $tickets->total() }} tickets
                    </p>

                    <div class="flex items-center gap-2">

                        {{-- Anterior --}}
                        @if ($tickets->onFirstPage())
                            <span
                                class="w-9 h-9 rounded-lg bg-[#060818] border border-[#1e295d] flex items-center justify-center text-gray-600 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </span>
                        @else
                            <a href="{{ $tickets->previousPageUrl() }}"
                                class="w-9 h-9 rounded-lg bg-[#060818] border border-[#1e295d] flex items-center justify-center text-gray-400 hover:text-white hover:border-blue-500 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </a>
                        @endif


                        {{-- Números de página --}}
                        @foreach ($tickets->getUrlRange(1, $tickets->lastPage()) as $page => $url)
                            @if ($page == $tickets->currentPage())
                                <span
                                    class="w-9 h-9 rounded-lg bg-blue-600 text-white font-semibold text-sm shadow-[0_0_10px_rgba(37,99,235,0.4)] flex items-center justify-center">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}"
                                    class="w-9 h-9 rounded-lg bg-[#060818] border border-[#1e295d] text-gray-300 font-semibold text-sm hover:border-blue-500 transition flex items-center justify-center">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach


                        {{-- Siguiente --}}
                        @if ($tickets->hasMorePages())
                            <a href="{{ $tickets->nextPageUrl() }}"
                                class="w-9 h-9 rounded-lg bg-[#060818] border border-[#1e295d] flex items-center justify-center text-gray-400 hover:text-white hover:border-blue-500 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @else
                            <span
                                class="w-9 h-9 rounded-lg bg-[#060818] border border-[#1e295d] flex items-center justify-center text-gray-600 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </span>
                        @endif

                    </div>
                </div>

            </section>
        </div>

    </main>
</body>

</html>
              