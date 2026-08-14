<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>TicketPro | Dashboard</title>
</head>

<body class="bg-[#060818] text-white font-sans antialiased flex h-screen overflow-hidden">

    <div class="flex min-h-screen">
        <div id="sidebarOverlay" onclick="toggleSidebar()" class="fixed inset-0 z-40 hidden bg-black/60 lg:hidden"></div>
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
                    class="flex items-center gap-3 px-4 py-3 bg-blue-600 text-white rounded-lg font-medium shadow-[0_0_15px_rgba(37,99,235,0.4)]">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    <span class="text-sm font-medium">Inicio</span>
                </a>

                <!-- Opción Activa: Mis tickets -->
                <a href="{{ route('misticketusuario') }}"
                    class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-[#151b3b] hover:text-white rounded-lg transition">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <svg class="w-5 h-5 text-gray/400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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



        {{-- ================================================================ --}}
        {{-- CONTENIDO PRINCIPAL --}}
        {{-- ================================================================ --}}

        <main
            class="flex-1 flex flex-col h-screen overflow-y-auto px-6 py-6 md:py-8 [::-webkit-scrollbar]:w-2 [::-webkit-scrollbar-track]:bg-[#060818] [::-webkit-scrollbar-thumb]:bg-[#1e295d] [::-webkit-scrollbar-thumb]:rounded-full">

            {{-- ---------- ENCABEZADO ---------- --}}
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div class="flex items-center gap-3">
                    <button type="button" onclick="toggleSidebar()"
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#151b3b] text-white transition hover:bg-[#151b3b] lg:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 7h16M4 12h16M4 17h16" />
                        </svg>
                    </button>

                    <div>
                        <h1 class="text-2xl font-extrabold text-white sm:text-3xl">Bienvenido,
                            {{ Auth::user()->name ?? 'Desconocido' }}</h1>
                        <p class="mt-1 text-sm text-gray-400 sm:text-base">
                            Inicio / <span class="font-bold text-white">Dashboard</span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 sm:gap-4">
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
                                                class="font-semibold text-white">Vite/Production</strong> se completó
                                            con éxito.
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



                    <a href="{{ route('ticketusuario')}}"
                        class="flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-bold text-white shadow-[0_0_15px_rgba(37,99,235,0.4)] transition hover:bg-blue-700 sm:text-base">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5v14M5 12h14" />
                        </svg>
                        Nuevo ticket
                    </a>
                </div>
            </div>

            <div class="px-0 pb-8 mt-8 grid grid-cols-1 gap-6 lg:grid-cols-12">
                <div class="order-1 rounded-xl border-[#1e295d] bg-[#0f1535] p-5 shadow-lg lg:order-2 lg:col-span-7">

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#151b3b] text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M12 3a9 9 0 109 9h-9V3z" />
                                    <path d="M15 3.5A9 9 0 013.5 15" />
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-white">Resumen de mis tickets</h2>
                        </div>

                        <div
                            class="rounded-xl border border-fuchsia-500/40 bg-[#4a174f]/40 px-4 py-1.5 text-center shadow-[0_0_15px_rgba(217,70,239,0.2)]">
                            <p class="text-xs font-bold text-white">Total</p>
                            <p class="-mt-0.5 text-2xl font-extrabold text-fuchsia-400">{{ $resumen['total'] ?? 18 }}
                            </p>
                            <p class="text-[10px] text-gray-400">Todos mis tickets</p>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4">

                        <div class="rounded-lg border border-yellow-500/40 bg-[#4a4213] p-4 text-center">
                            <p class="text-3xl font-extrabold text-white">{{ $resumen['abiertos'] ?? 3 }}</p>
                            <p class="mt-1 text-sm font-bold text-white">Abiertos</p>
                            <p class="text-xs text-amber-100/80">Tickets abiertos</p>
                        </div>

                        <div class="rounded-lg border border-blue-500/40 bg-[#1d2757] p-4 text-center">
                            <p class="text-3xl font-extrabold text-blue-400">{{ $resumen['en_proceso'] ?? 4 }}</p>
                            <p class="mt-1 text-sm font-bold text-white">En proceso</p>
                            <p class="text-xs text-gray-400">Tickets en proceso</p>
                        </div>

                        <div class="rounded-lg border border-green-500/40 bg-[#133d28] p-4 text-center">
                            <p class="text-3xl font-extrabold text-green-400">{{ $resumen['solucionados'] ?? 8 }}</p>
                            <p class="mt-1 text-sm font-bold text-white">Solucionados</p>
                            <p class="text-xs text-gray-400">Tickets solucionados</p>
                        </div>

                        <div class="rounded-lg border border-red-500/40 bg-[#4d1616] p-4 text-center">
                            <p class="text-3xl font-extrabold text-red-400">{{ $resumen['cancelados'] ?? 1 }}</p>
                            <p class="mt-1 text-sm font-bold text-white">Cancelados</p>
                            <p class="text-xs text-gray-400">Tickets cancelados</p>
                        </div>

                    </div>
                </div>

                <div class="order-2 rounded-xl border-[#1e295d] bg-[#0f1535] p-5 shadow-lg lg:order-1 lg:col-span-5">

                    <div class="flex items-center gap-2.5">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#151b3b] text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="8" r="3.2" />
                                <path d="M5 20c0-3.3 3.1-6 7-6s7 2.7 7 6" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-white">Mi informaciòn</h2>
                    </div>

                    <div class="mt-4 flex flex-col items-center gap-4 sm:flex-row sm:items-start">
                        <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="{{ auth()->user()->name }}"
                            class="h-24 w-24 shrink-0 rounded-full border border-gray-600 object-cover">

                        <dl class="grid w-full grid-cols-1 gap-y-2.5 text-sm">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-gray-500"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="8" r="3.2" />
                                    <path d="M5 20c0-3.3 3.1-6 7-6s7 2.7 7 6" />
                                </svg>
                                <dt class="font-semibold text-gray-400">Nombre:</dt>
                                <dd class="ml-auto font-bold text-white">{{ Auth::User()->nombre ?? 'Juan Perez' }}
                                </dd>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-gray-500"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="4" y="8" width="16" height="12" rx="1" />
                                    <path d="M9 8V5a1 1 0 011-1h4a1 1 0 011 1v3" />
                                </svg>
                                <dt class="font-semibold text-gray-400">Empresa:</dt>
                                <dd class="ml-auto font-bold text-white">
                                    {{ Auth::User()->departamento->oficina->empresa->empresa ?? 'Desconocido' }}</dd>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-gray-500"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="4" y="4" width="16" height="16" rx="2" />
                                    <path d="M8 9h8M8 13h5" />
                                </svg>
                                <dt class="font-semibold text-gray-400">Departamento:</dt>
                                <dd class="ml-auto font-bold text-white">
                                    {{ Auth::User()->departamento->nombre ?? 'Desconocido' }}</dd>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-gray-500"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="5" width="18" height="14" rx="2" />
                                    <path d="M3 7l9 6 9-6" />
                                </svg>
                                <dt class="font-semibold text-gray-400">Correo:</dt>
                                <dd class="ml-auto font-bold text-white">{{ Auth::User()->email ?? 'Desconocido' }}
                                </dd>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-gray-500"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="4" y="8" width="16" height="12" rx="1" />
                                    <path d="M9 8V5a1 1 0 011-1h4a1 1 0 011 1v3" />
                                </svg>
                                <dt class="font-semibold text-gray-400">Oficina:</dt>
                                <dd class="ml-auto font-bold text-white">
                                    {{ Auth::User()->departamento->oficina->nombre ?? 'Desconocido' }}</dd>
                            </div>
                            <div class="flex items-start gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-4 w-4 shrink-0 text-gray-500"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 21s7-6.5 7-11a7 7 0 10-14 0c0 4.5 7 11 7 11z" />
                                    <circle cx="12" cy="10" r="2.3" />
                                </svg>
                                <dt class="font-semibold text-gray-400">Ubicación:</dt>
                                <dd class="ml-auto text-right font-bold text-white">
                                    {{ $usuario['ubicacion'] ?? 'Edificio A, piso 2 Area administrativa' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="mt-4 flex flex-col gap-2.5 border-t border-[#1e295d] pt-4 text-sm">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-gray-500"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 3v4M8 3v4M4 8h16" />
                                <rect x="4" y="5" width="16" height="16" rx="2" />
                            </svg>
                            <dt class="font-semibold text-gray-400">Empleado:</dt>
                            <dd class="ml-auto font-bold text-white">
                                {{ Auth::User()->numeroempleado ?? 'Desconocido' }}</dd>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-gray-500"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 3v4M8 3v4M4 8h16" />
                                <rect x="4" y="5" width="16" height="16" rx="2" />
                            </svg>
                            <dt class="font-semibold text-gray-400">Fecha ingreso:</dt>
                            <dd class="ml-auto font-bold text-white">{{ Auth::User()->created_at ?? '10 enero 2024' }}
                            </dd>
                        </div>
                    </div>
                </div>

                <div class="order-3 rounded-xl border-[#1e295d] bg-[#0f1535] p-5 shadow-lg lg:order-4 lg:col-span-7">

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#151b3b] text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M4 6h16M4 12h16M4 18h10" />
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-white">Mis tickets recientes</h2>
                        </div>

                        <a href="#" class="text-sm font-bold text-blue-400 hover:text-blue-300">
                            Ver todos
                        </a>
                    </div>

                    @php
                        $ticketsRecientes = [
                            [
                                'folio' => 'TKT-2026-0015',
                                'tipo_falla' => 'Equipo de computo',
                                'estado' => 'En proceso',
                                'fecha' => '05 Ago 2026',
                                'soporte' => 'Carlos Martinez',
                            ],
                            [
                                'folio' => 'TKT-2026-0015',
                                'tipo_falla' => 'Impresora',
                                'estado' => 'Solucionado',
                                'fecha' => '05 Ago 2026',
                                'soporte' => 'Carlos Martinez',
                            ],
                            [
                                'folio' => 'TKT-2026-0015',
                                'tipo_falla' => 'VPN / Red',
                                'estado' => 'Solucionado',
                                'fecha' => '05 Ago 2026',
                                'soporte' => 'Carlos Martinez',
                            ],
                            [
                                'folio' => 'TKT-2026-0015',
                                'tipo_falla' => 'Correo outlook',
                                'estado' => 'En proceso',
                                'fecha' => '05 Ago 2026',
                                'soporte' => 'Carlos Martinez',
                            ],
                            [
                                'folio' => 'TKT-2026-0015',
                                'tipo_falla' => 'Acceso a sistema',
                                'estado' => 'Cancelado',
                                'fecha' => '05 Ago 2026',
                                'soporte' => 'Carlos Martinez',
                            ],
                        ];

                        $estadoClases = [
                            'En proceso' => 'bg-[#1d2757] text-blue-400',
                            'Solucionado' => 'bg-[#133d28] text-green-400',
                            'Cancelado' => 'bg-[#4d1616] text-red-400',
                        ];
                    @endphp

                    <div class="mt-4 overflow-x-auto">
                        <table class="w-full min-w-[560px] text-left text-sm">
                            <thead>
                                <tr class="text-xs uppercase tracking-wide text-gray-500">
                                    <th class="pb-3 font-semibold">Folio</th>
                                    <th class="pb-3 font-semibold">Tipo de falla</th>
                                    <th class="pb-3 font-semibold">Estado</th>
                                    <th class="pb-3 font-semibold">Fecha de reporte</th>
                                    <th class="pb-3 font-semibold">Soporte</th>
                                    <th class="pb-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#1e295d]/60">
                                @foreach ($ticketsRecientes as $ticket)
                                    <tr>
                                        <td class="py-3 font-bold text-white">{{ $ticket['folio'] }}</td>
                                        <td class="py-3 text-gray-300">{{ $ticket['tipo_falla'] }}</td>
                                        <td class="py-3">
                                            <span
                                                class="inline-block rounded-lg px-2.5 py-1 text-xs font-bold {{ $estadoClases[$ticket['estado']] }}">
                                                {{ $ticket['estado'] }}
                                            </span>
                                        </td>
                                        <td class="py-3 text-gray-300">{{ $ticket['fecha'] }}</td>
                                        <td class="py-3 text-gray-300">{{ $ticket['soporte'] }}</td>
                                        <td class="py-3 text-right">
                                            <a href="#"
                                                class="inline-flex text-gray-400 transition hover:text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="1.8" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z" />
                                                    <circle cx="12" cy="12" r="3" />
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>


                {{-- ---------- ÚLTIMO TICKET ---------- --}}
                <div class="order-4 rounded-xl border-[#1e295d] bg-[#0f1535] p-5 shadow-lg lg:order-3 lg:col-span-5">

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#151b3b] text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <rect x="4" y="4" width="16" height="16" rx="2" />
                                    <path d="M8 9h8M8 13h5" />
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-white">Ultimo ticket</h2>
                        </div>

                        <a href="#"
                            class="rounded-full bg-blue-600 px-4 py-2 text-xs font-bold text-white transition hover:bg-blue-600">
                            Ver detalles
                        </a>
                    </div>

                    <span class="mt-4 inline-block rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-bold text-white">
                        {{ $ultimoTicket['folio'] ?? 'TKT-2026-0015' }}
                    </span>

                    <div class="mt-3 grid grid-cols-2 gap-x-3 gap-y-3 text-sm">
                        <div>
                            <p class="text-gray-400">Tipo de falla:</p>
                            <p class="font-bold text-white">{{ $ultimoTicket['tipo_falla'] ?? 'Equipo de computo' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-400">Fecha reporte:</p>
                            <p class="font-bold text-white">{{ $ultimoTicket['fecha_reporte'] ?? '05 ago 2026' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400">Departamento:</p>
                            <p class="font-bold text-white">{{ $ultimoTicket['departamento'] ?? 'Recursos humanos' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-400">Asignado a:</p>
                            <p class="font-bold text-white">{{ $ultimoTicket['asignado_a'] ?? 'Carlos Mtz' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400">Sucursal / Oficina:</p>
                            <p class="font-bold text-white">{{ $ultimoTicket['oficina'] ?? 'Reynosa, Centro' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400">Tomado por:</p>
                            <p class="font-bold text-white">{{ $ultimoTicket['tomado_por'] ?? 'Carlos Mtz' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400">Estado:</p>
                            <span
                                class="mt-1 inline-block rounded-lg bg-[#1d2757] px-2.5 py-1 text-xs font-bold text-blue-400">
                                {{ $ultimoTicket['estado'] ?? 'En proceso' }}
                            </span>
                        </div>
                        <div>
                            <p class="text-gray-400">Asignacion:</p>
                            <p class="font-bold text-white">{{ $ultimoTicket['fecha_asignacion'] ?? '05 ago 2026' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-400">Prioridad:</p>
                            <span
                                class="mt-1 inline-block rounded-lg bg-[#4d1616] px-2.5 py-1 text-xs font-bold text-red-400">
                                {{ $ultimoTicket['prioridad'] ?? 'Alta' }}
                            </span>
                        </div>
                        <div>
                            <p class="text-gray-400">Se soluciono?</p>
                            <span
                                class="mt-1 inline-block rounded-lg bg-[#4d1616] px-2.5 py-1 text-xs font-bold text-red-400">
                                {{ $ultimoTicket['solucionado'] ?? 'No' }}
                            </span>
                        </div>
                    </div>
                </div>


                {{-- ---------- ACTIVIDAD RECIENTE ---------- --}}
                <div class="order-5 rounded-xl border-[#1e295d] bg-[#0f1535] p-5 shadow-lg lg:col-span-5">

                    <div class="flex items-center gap-2.5">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#151b3b] text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="9" />
                                <path d="M12 7v5l3.5 2" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-white">Actividad reciente en mis tickets</h2>
                    </div>

                    @php
                        $actividad = [
                            [
                                'color' => 'bg-green-600',
                                'fecha' => '05 Ago 2026 - 10:30 AM',
                                'texto' => 'Carlos Martinez tomo tu ticket TKT-2026-0015',
                            ],
                            [
                                'color' => 'bg-blue-600',
                                'fecha' => '05 Ago 2026 - 10:15 AM',
                                'texto' => 'Se agrego un comentario a tu ticket TKT-2026-0015',
                            ],
                            [
                                'color' => 'bg-blue-600',
                                'fecha' => '05 Ago 2026 - 10:00 AM',
                                'texto' => 'Tu ticket TKT-2026-0015 se creo correctamente',
                            ],
                        ];
                    @endphp

                    <div class="mt-4">
                        @foreach ($actividad as $index => $item)
                            <div class="relative flex gap-3 pb-5 last:pb-0">
                                @if (!$loop->last)
                                    <span class="absolute left-[5px] top-3 h-full w-px bg-white/10"></span>
                                @endif
                                <span
                                    class="relative mt-1.5 h-2.5 w-2.5 shrink-0 rounded-full {{ $item['color'] }}"></span>
                                <div class="text-sm">
                                    <p class="font-bold text-white">{{ $item['fecha'] }}</p>
                                    <p class="text-gray-400">{{ $item['texto'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>


                {{-- ---------- AVISOS IMPORTANTES ---------- --}}
                <div class="order-6 rounded-xl border-[#1e295d] bg-[#0f1535] p-5 shadow-lg lg:col-span-7">

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#151b3b] text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="9" />
                                    <path d="M12 8v5M12 16h.01" />
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-white">Avisos importantes</h2>
                        </div>

                        <a href="#" class="text-sm font-bold text-blue-400 hover:text-blue-300">
                            Ver todos los avisos
                        </a>
                    </div>

                    @php
                        $avisos = [
                            [
                                'tipo' => 'warning',
                                'titulo' => 'Mantenimiento programado',
                                'fecha' => '05 de agosto',
                                'texto' =>
                                    'El area de TI dara mantenimiento el 09 de agosto del 2026 de 20:00 PM a 21:00 PM',
                            ],
                            [
                                'tipo' => 'info',
                                'titulo' => 'Actualizacion del sistema',
                                'fecha' => '03 de agosto',
                                'texto' => 'Ya esta disponible la nueva actualización del sistema interno',
                            ],
                            [
                                'tipo' => 'success',
                                'titulo' => 'Politica de seguridad',
                                'fecha' => '01 de agosto',
                                'texto' => 'Recuerda mantener tus credenciales seguras y no compartir',
                            ],
                        ];
                    @endphp

                    <div class="mt-4 divide-y divide-[#1e295d]/60">
                        @foreach ($avisos as $aviso)
                            <div class="flex gap-3 py-3.5 first:pt-4">
                                <div
                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full
                                    @if ($aviso['tipo'] === 'warning') bg-[#4a4213]
                                    @elseif ($aviso['tipo'] === 'info') bg-blue-600
                                    @else bg-green-600 @endif">
                                    @if ($aviso['tipo'] === 'warning')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 text-[#0b0f2a]"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 3l10 18H2L12 3z" />
                                            <path d="M12 10v4M12 17h.01" />
                                        </svg>
                                    @elseif ($aviso['tipo'] === 'info')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 text-white"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="9" />
                                            <path d="M12 11v5M12 8h.01" />
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 text-white"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M5 13l4 4L19 7" />
                                        </svg>
                                    @endif
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-baseline justify-between gap-x-3">
                                        <p class="text-sm font-bold text-white">{{ $aviso['titulo'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $aviso['fecha'] }}</p>
                                    </div>
                                    <p class="mt-0.5 text-sm text-gray-400">{{ $aviso['texto'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

        </main>

    </div>
</body>

</html>
