<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/png" href="{{ asset('storage/images/logo.png') }}">
    <script src="https://unpkg.com/lucide@latest"></script>
    <title>TicketPro | Dashboard</title>
</head>

<body class="bg-[#060818] text-white font-sans antialiased flex h-screen overflow-hidden">
    <div class="flex min-h-screen w-full">
        <div id="sidebarOverlay" onclick="toggleSidebar()" class="fixed inset-0 z-40 hidden bg-black/60 lg:hidden">
        </div>
        <aside
            class="hidden md:flex flex-col w-64 bg-[#0a0e27] border-r border-[#1e295d] px-4 py-6 overflow-y-auto [::-webkit-scrollbar]:w-2 [::-webkit-scrollbar-track]:bg-[#060818] [::-webkit-scrollbar-thumb]:bg-[#1e295d] [::-webkit-scrollbar-thumb]:rounded-full">
            <div class="mb-8 px-2 text-3xl font-bold tracking-wide">
                Ticket<span class="text-blue-500">Pro</span>
            </div>
            <div class="mb-10 flex items-center gap-3 px-2">
                <img src="{{ auth()->user()->picture
                    ? asset('storage/' . auth()->user()->picture)
                    : asset('storage/profile-photos/user.png') }}"
                    alt="{{ auth()->user()->name }}"
                    class="h-12 w-12 rounded-full border-2 border-gray-500 object-cover">
                <div>
                    <h3 class="text-sm font-semibold text-white">
                        {{ Auth::user()->name ?? 'Desconocido' }}
                    </h3>
                    <p class="text-xs text-gray-400">
                        {{ Auth::user()->departamento->nombre ?? 'Desconocido' }}
                    </p>
                </div>
            </div>
            <nav class="flex-1 space-y-2">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 rounded-lg bg-blue-600 px-4 py-3 font-medium text-white shadow-[0_0_15px_rgba(37,99,235,0.4)]">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    <span class="text-sm font-medium">
                        Inicio
                    </span>
                </a>
                <a href="{{ route('misticketusuario') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-3 text-gray-300 transition hover:bg-[#151b3b] hover:text-white">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                        </path>
                    </svg>
                    <span class="text-sm font-medium">
                        Mis tickets
                    </span>
                </a>
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
            <div class="mt-auto pt-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex w-full items-center gap-3 rounded-lg px-4 py-3 text-left text-gray-300 transition hover:bg-[#151b3b] hover:text-white">

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
        <main
            class="flex-1 flex flex-col h-screen overflow-y-auto px-6 py-6 md:py-8 [::-webkit-scrollbar]:w-2 [::-webkit-scrollbar-track]:bg-[#060818] [::-webkit-scrollbar-thumb]:bg-[#1e295d] [::-webkit-scrollbar-thumb]:rounded-full">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div class="flex items-center gap-3">
                    <button type="button" onclick="toggleSidebar()"
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#151b3b] text-white transition hover:bg-[#1c244d] lg:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 7h16M4 12h16M4 17h16" />
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-2xl font-extrabold text-white sm:text-3xl">
                            Bienvenido, {{ Auth::user()->name ?? 'Desconocido' }}
                        </h1>
                        <p class="mt-1 text-sm text-gray-400 sm:text-base">
                            Inicio /
                            <span class="font-bold text-white">
                                Dashboard
                            </span>
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="relative inline-block text-left">
                        <div class="relative" x-data="{ notificacionesAbiertas: false }">
                            <button type="button" @click="notificacionesAbiertas = !notificacionesAbiertas"
                                @click.outside="notificacionesAbiertas = false"
                                class="relative flex items-center justify-center w-10 h-10 rounded-xl
                       bg-slate-900/80 border border-slate-800
                       text-slate-400 hover:text-white hover:bg-slate-800
                       transition-all duration-200 focus:outline-none">
                                <i data-lucide="bell" class="w-5 h-5"></i>
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
                                <div class="max-h-[400px] overflow-y-auto">
                                    @forelse ($notificaciones as $notificacion)
                                        <a href="{{ $notificacion->url ?? '#' }}"
                                            class="group flex gap-3 px-4 py-4
                                   border-b border-slate-800/50
                                   transition-colors
                                   hover:bg-slate-800/40
                                   {{ !$notificacion->leida ? 'bg-indigo-500/[0.04]' : '' }}">
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
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-start justify-between gap-2">
                                                    <p
                                                        class="text-xs font-semibold
                                               text-white
                                               group-hover:text-indigo-400
                                               transition-colors">
                                                        {{ $notificacion->titulo }}
                                                    </p>
                                                    @if (!$notificacion->leida)
                                                        <span
                                                            class="w-2 h-2 shrink-0 mt-1.5
                                                   rounded-full
                                                   bg-indigo-500">
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="mt-1 text-[11px] leading-relaxed text-slate-400">
                                                    {{ $notificacion->mensaje }}
                                                </p>
                                                <p class="mt-2 text-[10px] text-slate-500">
                                                    {{ $notificacion->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </a>
                                    @empty
                                        <div class="px-6 py-10 text-center">
                                            <div
                                                class="mx-auto mb-3 w-12 h-12 rounded-full bg-slate-800/50 border border-slate-800 flex items-center justify-center">
                                                <i data-lucide="bell-off" class="w-5 h-5 text-slate-500"></i>
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
                    <a href="{{ route('ticketusuario') }}"
                        class="flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-bold text-white shadow-[0_0_15px_rgba(37,99,235,0.4)] transition hover:bg-blue-700 sm:text-base">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5v14M5 12h14" />
                        </svg>
                        Nuevo ticket
                    </a>
                </div>
            </div>
            <div class="mt-6 flex flex-col gap-4 pb-8 lg:flex-row lg:items-start">
                <div class="order-2 flex flex-col gap-4 lg:order-1 lg:w-5/12">
                    <div class="rounded-xl border border-[#1e295d] bg-[#0f1535] p-5 shadow-lg">
                        <div class="flex items-center gap-2.5">

                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#151b3b] text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="12" cy="8" r="3.2" />
                                    <path d="M5 20c0-3.3 3.1-6 7-6s7 2.7 7 6" />
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-white">
                                Mi información
                            </h2>
                        </div>
                        <div class="mt-4 flex flex-col items-center gap-4 sm:flex-row sm:items-start">
                            <img src="{{ auth()->user()->picture
                                ? asset('storage/' . auth()->user()->picture)
                                : asset('storage/profile-photos/user.png') }}"
                                alt="{{ auth()->user()->name ?? 'Usuario' }}"
                                class="h-24 w-24 shrink-0 rounded-full border border-gray-600 object-cover"
                                onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Usuario') }}&background=0D8ABC&color=fff';">
                            <div class="w-full">
                                <h3 class="mb-3 text-xs font-bold uppercase tracking-wider text-blue-400">
                                    Información laboral
                                </h3>
                                <dl class="grid w-full grid-cols-1 gap-y-2.5 text-sm">
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-gray-500"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="4" y="4" width="16" height="16" rx="2" />
                                            <path d="M8 9h8M8 13h5" />
                                        </svg>
                                        <dt class="font-semibold text-gray-400">
                                            Departamento:
                                        </dt>
                                        <dd class="ml-auto font-bold text-white">
                                            {{ Auth::user()->departamento->nombre ?? 'N/A' }}
                                        </dd>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-gray-500"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="4" y="8" width="16" height="12" rx="1" />
                                            <path d="M9 8V5a1 1 0 011-1h4a1 1 0 011 1v3" />
                                        </svg>
                                        <dt class="font-semibold text-gray-400">
                                            Oficina:
                                        </dt>
                                        <dd class="ml-auto font-bold text-white">
                                            {{ Auth::user()->departamento->oficina->nombre ?? 'N/A' }}
                                        </dd>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-gray-500"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="3" width="18" height="18" rx="2" />
                                            <path d="M8 21V7h8v14M8 11h8M8 15h8" />
                                        </svg>
                                        <dt class="font-semibold text-gray-400">
                                            Empresa:
                                        </dt>
                                        <dd class="ml-auto font-bold text-white">
                                            {{ Auth::user()->departamento->oficina->empresa->empresa ?? 'N/A' }}
                                        </dd>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-gray-500"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="8" r="3.2" />
                                            <path d="M5 20c0-3.3 3.1-6 7-6s7 2.7 7 6" />
                                        </svg>
                                        <dt class="font-semibold text-gray-400">
                                            Login:
                                        </dt>
                                        <dd class="ml-auto font-bold text-white">
                                            {{ Auth::user()->login ?? 'N/A' }}
                                        </dd>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-gray-500"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="4" width="18" height="16" rx="2" />
                                            <circle cx="9" cy="10" r="2.5" />
                                            <path d="M14 9h4M14 13h4M6 16h6" />
                                        </svg>

                                        <dt class="font-semibold text-gray-400">
                                            Rol:
                                        </dt>

                                        <dd class="ml-auto font-bold text-white">
                                            {{ Auth::user()->role ?? 'N/A' }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                        <div class="mt-5 border-t border-[#1e295d] pt-4">
                            <h3 class="mb-3 text-xs font-bold uppercase tracking-wider text-blue-400">
                                Información personal
                            </h3>
                            <dl class="flex flex-col gap-2.5 text-sm">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-gray-500"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="8" r="3.2" />
                                        <path d="M5 20c0-3.3 3.1-6 7-6s7 2.7 7 6" />
                                    </svg>
                                    <dt class="font-semibold text-gray-400">
                                        Nombre:
                                    </dt>
                                    <dd class="ml-auto font-bold text-white">
                                        {{ Auth::user()->name ?? 'N/A' }}
                                    </dd>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-gray-500"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="5" width="18" height="14" rx="2" />
                                        <path d="M3 7l9 6 9-6" />
                                    </svg>
                                    <dt class="font-semibold text-gray-400">
                                        Correo:
                                    </dt>
                                    <dd class="ml-auto font-bold text-white break-all text-right">
                                        {{ Auth::user()->email ?? 'N/A' }}
                                    </dd>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-gray-500"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 16.92v3a2 2 0 01-2.18 2
                                 19.79 19.79 0 01-8.63-3.07
                                 19.5 19.5 0 01-6-6
                                 19.79 19.79 0 01-3.07-8.67
                                 A2 2 0 014.11 2h3
                                 a2 2 0 012 1.72
                                 12.84 12.84 0 00.7 2.81
                                 2 2 0 01-.45 2.11L8.09 9.91
                                 a16 16 0 006 6l1.27-1.27
                                 a2 2 0 012.11-.45
                                 12.84 12.84 0 002.81.7
                                 A2 2 0 0122 16.92z" />
                                    </svg>
                                    <dt class="font-semibold text-gray-400">
                                        Teléfono:
                                    </dt>
                                    <dd class="ml-auto font-bold text-white">
                                        {{ Auth::user()->phone ?? 'No proporcionado' }}
                                    </dd>
                                </div>
                                {{-- <div class="flex items-start gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-4 w-4 shrink-0 text-gray-500"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 21s7-6.5 7-11a7 7 0 10-14 0c0 4.5 7 11 7 11z" />
                                        <circle cx="12" cy="10" r="2.3" />
                                    </svg>
                                    <dt class="font-semibold text-gray-400">
                                        Ubicación:
                                    </dt>
                                    <dd class="ml-auto text-right font-bold text-white">
                                        {{ $usuario['ubicacion'] ?? 'Edificio A, piso 2 Area administrativa' }}
                                    </dd>
                                </div> --}}
                            </dl>
                        </div>
                        <div class="mt-4 border-t border-[#1e295d] pt-4">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-gray-500"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 3v4M8 3v4M4 8h16" />
                                    <rect x="4" y="5" width="16" height="16" rx="2" />
                                </svg>
                                <dt class="font-semibold text-gray-400">
                                    Empleado:
                                </dt>
                                <dd class="ml-auto font-bold text-white">
                                    {{ Auth::user()->numero_empleado->numero_empleado ?? 'No proporcionado' }}
                                </dd>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-xl border border-[#1e295d] bg-[#0f1535] p-5 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div
                                    class="flex h-8 w-8 items-center justify-center
                           rounded-lg bg-[#151b3b] text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="4" y="4" width="16" height="16" rx="2" />
                                        <path d="M8 9h8M8 13h5" />
                                    </svg>
                                </div>
                                <h2 class="text-lg font-bold text-white">
                                    Último ticket
                                </h2>
                            </div>
                            @if ($ultimoTicket)
                                <a href="{{ route('ticketusuario.detalles', ['ticket' => $ultimoTicket['id']]) }}"
                                    class="rounded-full bg-blue-600
                           px-4 py-2
                           text-xs font-bold text-white
                           transition hover:bg-blue-700">
                                    Ver detalles
                                </a>
                            @endif
                        </div>
                        @if ($ultimoTicket)
                            <span
                                class="mt-4 inline-block
                       rounded-lg
                       bg-blue-600
                       px-3 py-1.5
                       text-xs font-bold text-white">
                                {{ $ultimoTicket['folio'] ?? 'N/A' }}
                            </span>
                            <div class="mt-3 grid grid-cols-2 gap-x-3 gap-y-3 text-sm">
                                <div>
                                    <p class="text-gray-400">Tipo de falla:</p>
                                    <p class="font-bold text-white">{{ $ultimoTicket['tipo_falla'] ?? 'N/A' }} </p>
                                </div>
                                <div>
                                    <p class="text-gray-400">Fecha reporte:</p>
                                    <p class="font-bold text-white">{{ $ultimoTicket['fecha_reporte'] ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-400">Departamento:</p>
                                    <p class="font-bold text-white">{{ $ultimoTicket['departamento'] ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-400">Asignado a:</p>
                                    <p class="font-bold text-white">{{ $ultimoTicket['asignado_a'] ?? 'N/A' }}</p>

                                </div>
                                <div>
                                    <p class="text-gray-400">Sucursal / Oficina:</p>
                                    <p class="font-bold text-white">{{ $ultimoTicket['oficina'] ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-400">Tomado por:</p>
                                    <p class="font-bold text-white">{{ $ultimoTicket['tomado_por'] ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-400">Estado:</p>
                                    <span
                                        class="mt-1 inline-block
                           rounded-lg
                           bg-[#1d2757]
                           px-2.5 py-1
                           text-xs font-bold text-blue-400">
                                        {{ $ultimoTicket['estado'] ?? 'N/A' }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-gray-400">Asignación:</p>
                                    <p class="font-bold text-white">{{ $ultimoTicket['fecha_asignacion'] ?? 'N/A' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-400">Prioridad:</p>
                                    <span
                                        class="mt-1 inline-block
                           rounded-lg
                           bg-[#4d1616]
                           px-2.5 py-1
                           text-xs font-bold text-red-400">
                                        {{ $ultimoTicket['prioridad'] ?? 'N/A' }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-gray-400">
                                        ¿Se solucionó?
                                    </p>
                                    @php
                                        $estadoTicket = strtolower(trim($ultimoTicket['estado'] ?? ''));
                                    @endphp
                                    @if (in_array($estadoTicket, ['pendiente', 'en proceso']))
                                        <span
                                            class="mt-1 inline-block rounded-lg
                       bg-[#3d3514]
                       px-2.5 py-1
                       text-xs font-bold text-yellow-400">
                                            Pendiente
                                        </span>
                                    @elseif ($estadoTicket === 'solucionado')
                                        <span
                                            class="mt-1 inline-block rounded-lg
                       bg-[#06331e]
                       px-2.5 py-1
                       text-xs font-bold text-emerald-400">
                                            Sí
                                        </span>
                                    @elseif ($estadoTicket === 'cancelado')
                                        <span
                                            class="mt-1 inline-block rounded-lg
                       bg-[#4d1616]
                       px-2.5 py-1
                       text-xs font-bold text-red-400">
                                            No
                                        </span>
                                    @else
                                        <span
                                            class="mt-1 inline-block rounded-lg
                       bg-[#1d2757]
                       px-2.5 py-1
                       text-xs font-bold text-gray-400">
                                            Pendiente
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div
                                class="mt-4
                       rounded-xl
                       border border-yellow-500/20
                       bg-yellow-500/5
                       px-4 py-3">
                                <p class="text-sm font-bold text-yellow-400">
                                    No hay tickets
                                </p>
                                <p class="mt-1 text-xs text-gray-400">
                                    Actualmente no tienes ningún ticket registrado.
                                </p>
                            </div>
                            <div class="mt-4 grid grid-cols-2 gap-x-3 gap-y-3 text-sm">
                                <div>
                                    <p class="text-gray-400">
                                        Tipo de falla:
                                    </p>
                                    <p class="font-bold text-white">
                                        N/A
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-400">
                                        Fecha reporte:
                                    </p>
                                    <p class="font-bold text-white">
                                        N/A
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-400">
                                        Departamento:
                                    </p>
                                    <p class="font-bold text-white">
                                        N/A
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-400">
                                        Asignado a:
                                    </p>
                                    <p class="font-bold text-white">
                                        N/A
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-400">
                                        Sucursal / Oficina:
                                    </p>

                                    <p class="font-bold text-white">
                                        N/A
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-400">
                                        Tomado por:
                                    </p>
                                    <p class="font-bold text-white">
                                        N/A
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-400">
                                        Estado:
                                    </p>
                                    <span
                                        class="mt-1 inline-block
                           rounded-lg
                           bg-[#1d2757]
                           px-2.5 py-1
                           text-xs font-bold text-gray-400">
                                        N/A
                                    </span>
                                </div>
                                <div>
                                    <p class="text-gray-400">
                                        Asignación:
                                    </p>
                                    <p class="font-bold text-white">
                                        N/A
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-400">
                                        Prioridad:
                                    </p>
                                    <span
                                        class="mt-1 inline-block
                           rounded-lg
                           bg-[#1d2757]
                           px-2.5 py-1
                           text-xs font-bold text-gray-400">
                                        N/A
                                    </span>
                                </div>
                                <div>
                                    <p class="text-gray-400">
                                        ¿Se solucionó?
                                    </p>

                                    @if (is_null($ultimoTicket['solucionado'] ?? null))
                                        <span
                                            class="mt-1 inline-block rounded-lg
                       bg-[#1d2757]
                       px-2.5 py-1
                       text-xs font-bold text-gray-400">
                                            Pendiente
                                        </span>
                                    @elseif ((int) $ultimoTicket['solucionado'] === 1)
                                        <span
                                            class="mt-1 inline-block rounded-lg
                       bg-emerald-500/10
                       border border-emerald-500/20
                       px-2.5 py-1
                       text-xs font-bold text-emerald-400">
                                            Sí
                                        </span>
                                    @elseif ((int) $ultimoTicket['solucionado'] === 0)
                                        <span
                                            class="mt-1 inline-block rounded-lg
                       bg-red-500/10
                       border border-red-500/20
                       px-2.5 py-1
                       text-xs font-bold text-red-400">
                                            No
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="rounded-xl border border-[#1e295d] bg-[#0f1535] p-5 shadow-lg">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#151b3b] text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="9" />
                                    <path d="M12 7v5l3.5 2" />
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-white">
                                Actividad reciente en mis tickets
                            </h2>
                        </div>
                        <div class="mt-4">
                            @forelse ($actividad as $item)
                                <div class="relative flex gap-3 pb-5 last:pb-0">
                                    @if (!$loop->last)
                                        <span class="absolute left-[5px] top-3 h-full w-px bg-white/10"></span>
                                    @endif
                                    <span
                                        class="relative mt-1.5 h-2.5 w-2.5 shrink-0 rounded-full {{ $item['color'] }}">
                                    </span>
                                    <div class="text-sm">
                                        <p class="font-bold text-white">
                                            {{ $item['fecha']->timezone('America/Matamoros')->format('d M Y - h:i A') }}
                                        </p>
                                        <p class="text-gray-400">
                                            {{ $item['texto'] }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="py-8 text-center">
                                    <p class="text-sm text-gray-500">
                                        No hay actividad reciente en tus tickets.
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="order-1 flex flex-col gap-4 lg:order-2 lg:w-7/12">
                    <div class="rounded-xl border border-[#1e295d] bg-[#0f1535] p-4 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#151b3b] text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 3a9 9 0 109 9h-9V3z" />
                                        <path d="M15 3.5A9 9 0 013.5 15" />
                                    </svg>
                                </div>

                                <h2 class="text-lg font-bold text-white">
                                    Resumen de mis tickets
                                </h2>
                            </div>

                            <div
                                class="rounded-xl border border-fuchsia-500/40 bg-[#4a174f]/40 px-4 py-1.5 text-center shadow-[0_0_15px_rgba(217,70,239,0.2)]">
                                <p class="text-[10px] font-bold text-white">
                                    Total
                                </p>

                                <p class="text-xl font-extrabold leading-tight text-fuchsia-400">
                                    {{ $resumen['total'] }}
                                </p>

                                <p class="text-[9px] text-gray-400">
                                    Todos mis tickets
                                </p>
                            </div>
                        </div>

                        <div class="mt-3 grid grid-cols-2 gap-2.5 sm:grid-cols-4">
                            <div class="rounded-lg border border-yellow-500/40 bg-[#4a4213] px-3 py-3 text-center">
                                <p class="text-2xl font-extrabold leading-none text-white">
                                    {{ $resumen['abiertos'] }}
                                </p>

                                <p class="mt-1 text-xs font-bold text-white">
                                    Abiertos
                                </p>

                                <p class="text-[10px] text-amber-100/80">
                                    Tickets abiertos
                                </p>
                            </div>

                            <div class="rounded-lg border border-blue-500/40 bg-[#1d2757] px-3 py-3 text-center">
                                <p class="text-2xl font-extrabold leading-none text-blue-400">
                                    {{ $resumen['en_proceso'] }}
                                </p>

                                <p class="mt-1 text-xs font-bold text-white">
                                    En proceso
                                </p>

                                <p class="text-[10px] text-gray-400">
                                    Tickets en proceso
                                </p>
                            </div>

                            <div class="rounded-lg border border-green-500/40 bg-[#133d28] px-3 py-3 text-center">
                                <p class="text-2xl font-extrabold leading-none text-green-400">
                                    {{ $resumen['solucionados'] }}
                                </p>

                                <p class="mt-1 text-xs font-bold text-white">
                                    Solucionados
                                </p>

                                <p class="text-[10px] text-gray-400">
                                    Tickets solucionados
                                </p>
                            </div>

                            <div class="rounded-lg border border-red-500/40 bg-[#4d1616] px-3 py-3 text-center">
                                <p class="text-2xl font-extrabold leading-none text-red-400">
                                    {{ $resumen['cancelados'] }}
                                </p>

                                <p class="mt-1 text-xs font-bold text-white">
                                    Cancelados
                                </p>

                                <p class="text-[10px] text-gray-400">
                                    Tickets cancelados
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-xl border border-[#1e295d] bg-[#0f1535] p-5 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#151b3b] text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 6h16M4 12h16M4 18h10" />
                                    </svg>
                                </div>
                                <h2 class="text-lg font-bold text-white">
                                    Mis tickets recientes
                                </h2>
                            </div>
                            <a href="{{ route('misticketusuario') }}"
                                class="text-sm font-bold text-blue-400 transition hover:text-blue-300">
                                Ver todos
                            </a>
                        </div>
                        <div class="mt-4 overflow-x-auto">
                            <table class="w-full min-w-[560px] text-left text-sm">
                                <thead>
                                    <tr class="text-xs uppercase tracking-wide text-gray-500">
                                        <th class="pb-3 font-semibold">
                                            Folio
                                        </th>
                                        <th class="pb-3 font-semibold">
                                            Tipo de falla
                                        </th>
                                        <th class="pb-3 font-semibold">
                                            Estado
                                        </th>
                                        <th class="pb-3 font-semibold">
                                            Fecha de reporte
                                        </th>
                                        <th class="pb-3 font-semibold">
                                            Soporte
                                        </th>
                                        <th class="pb-3 text-right">
                                            Ver
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#1e295d]/60">
                                    @forelse ($ticketsRecientes as $ticket)
                                        @php
                                            $estado = match ($ticket->estado) {
                                                'pendiente' => 'Abierto',
                                                'en proceso' => 'En proceso',
                                                'solucionado' => 'Solucionado',
                                                'cancelado' => 'Cancelado',
                                                default => ucfirst($ticket->estado ?? 'Desconocido'),
                                            };
                                            $estadoClase = match ($ticket->estado) {
                                                'pendiente' => 'bg-[#4a4213] text-yellow-400',
                                                'en proceso' => 'bg-[#1d2757] text-blue-400',
                                                'solucionado' => 'bg-[#133d28] text-green-400',
                                                'cancelado' => 'bg-[#4d1616] text-red-400',
                                                default => 'bg-slate-700 text-slate-300',
                                            };
                                        @endphp
                                        <tr class="transition hover:bg-[#151b3b]/40">
                                            <td class="py-3 font-bold text-white">
                                                {{ $ticket->folio }}
                                            </td>
                                            <td class="py-3 text-gray-300">
                                                {{ $ticket->tipo_falla ?? 'No especificado' }}
                                            </td>
                                            <td class="py-3">
                                                <span
                                                    class="inline-block rounded-lg px-2.5 py-1 text-xs font-bold {{ $estadoClase }}">
                                                    {{ $estado }}
                                                </span>
                                            </td>
                                            <td class="py-3 text-gray-300">
                                                {{ $ticket->created_at ? $ticket->created_at->timezone('America/Matamoros')->format('d M Y') : '—' }}
                                            </td>
                                            <td class="py-3 text-gray-300">
                                                @if ($ticket->tomadoPor)
                                                    {{ $ticket->tomadoPor->name }}
                                                @else
                                                    <span class="text-gray-500">
                                                        Sin asignar
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-3 text-right">
                                                <a href="{{ route('ticketusuario.detalles', $ticket->id) }}"
                                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 transition hover:bg-blue-500/10 hover:text-blue-400"
                                                    title="Ver ticket">
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
                                    @empty
                                        <tr>
                                            <td colspan="6" class="py-10 text-center text-sm text-gray-500">
                                                No tienes tickets registrados.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="rounded-2xl border border-[#1e295d] bg-[#0f1535] p-5 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-blue-500/20 bg-gradient-to-br from-blue-500/15 to-indigo-500/10 text-blue-400 shadow-inner">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M18 8a6 6 0 00-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9" />
                                        <path d="M10 21h4" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-lg font-bold tracking-tight text-white">
                                        Avisos importantes
                                    </h2>
                                    <p class="text-[11px] text-gray-500">
                                        Información reciente para ti
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('avisosusuario') }}"
                                class="group flex items-center gap-1.5 rounded-lg px-2.5 py-2 text-xs font-bold text-blue-400 transition-all duration-200 hover:bg-blue-500/10 hover:text-blue-300">
                                Ver todos
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-3.5 w-3.5 transition-transform duration-200 group-hover:translate-x-0.5"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14" />
                                    <path d="m12 5 7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                        <div x-data="avisosModal()" @keydown.escape.window="cerrarAviso()" class="relative">
                            <div class="mt-4 space-y-2.5">
                                @forelse ($avisos as $aviso)
                                    @php
                                        $config = match ($aviso->tipo) {
                                            'mantenimiento' => [
                                                'label' => 'Mantenimiento',
                                                'iconBg' => 'bg-amber-500/10',
                                                'iconBorder' => 'border-amber-500/20',
                                                'iconText' => 'text-amber-400',
                                                'accent' => 'bg-amber-500',
                                                'badge' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                            ],
                                            'incidente' => [
                                                'label' => 'Incidente',
                                                'iconBg' => 'bg-red-500/10',
                                                'iconBorder' => 'border-red-500/20',
                                                'iconText' => 'text-red-400',
                                                'accent' => 'bg-red-500',
                                                'badge' => 'bg-red-500/10 text-red-400 border-red-500/20',
                                            ],
                                            'informativo' => [
                                                'label' => 'Informativo',
                                                'iconBg' => 'bg-cyan-500/10',
                                                'iconBorder' => 'border-cyan-500/20',
                                                'iconText' => 'text-cyan-400',
                                                'accent' => 'bg-cyan-500',
                                                'badge' => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20',
                                            ],
                                            default => [
                                                'label' => 'General',
                                                'iconBg' => 'bg-blue-500/10',
                                                'iconBorder' => 'border-blue-500/20',
                                                'iconText' => 'text-blue-400',
                                                'accent' => 'bg-blue-500',
                                                'badge' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                            ],
                                        };
                                        $afecta = $aviso->afecta_a;

                                        if (is_string($afecta)) {
                                            $decoded = json_decode($afecta, true);

                                            if (json_last_error() === JSON_ERROR_NONE) {
                                                $afecta = $decoded;
                                            }
                                        }
                                        if (is_array($afecta) && ($afecta['tipo'] ?? null) === 'todos') {
                                            $afectaTexto = 'Todos los usuarios';
                                        } elseif (is_array($afecta) && ($afecta['tipo'] ?? null) === 'departamentos') {
                                            $ids = $afecta['ids'] ?? [];
                                            $nombres = \App\Models\Departamento::whereIn('id', $ids)
                                                ->orderBy('nombre')
                                                ->pluck('nombre')
                                                ->toArray();
                                            $afectaTexto = !empty($nombres)
                                                ? implode(', ', $nombres)
                                                : 'Departamentos seleccionados';
                                        } elseif (is_array($afecta) && ($afecta['tipo'] ?? null) === 'usuarios') {
                                            $ids = $afecta['ids'] ?? [];
                                            $nombres = \App\Models\User::whereIn('id', $ids)
                                                ->orderBy('name')
                                                ->pluck('name')
                                                ->toArray();
                                            $afectaTexto = !empty($nombres)
                                                ? implode(', ', $nombres)
                                                : 'Usuarios seleccionados';
                                        } else {
                                            $afectaTexto = 'Todos los usuarios';
                                        }
                                    @endphp
                                    <div
                                        class="group relative overflow-hidden rounded-xl border border-[#1e295d]/90 bg-gradient-to-r from-[#0b102b] to-[#0d1330] transition-all duration-200 hover:border-blue-500/30 hover:shadow-md hover:shadow-blue-950/20">
                                        <div class="absolute inset-y-0 left-0 w-[3px] {{ $config['accent'] }}"></div>
                                        <div class="flex min-h-[112px] items-center gap-4 p-3.5 sm:p-4">
                                            <div
                                                class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl border {{ $config['iconBg'] }} {{ $config['iconBorder'] }} {{ $config['iconText'] }} shadow-inner transition-transform duration-200 group-hover:scale-[1.03]">
                                                @if ($aviso->tipo === 'mantenimiento')
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="1.7" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path
                                                            d="M14.7 6.3a4.2 4.2 0 00-5.9 5.9L4 17l3 3 4.8-4.8a4.2 4.2 0 005.9-5.9l-2.2 2.2-2.9-.9-.9-2.9z" />
                                                    </svg>
                                                @elseif ($aviso->tipo === 'incidente')
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="1.7" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path d="M12 3l9 17H3L12 3z" />
                                                        <path d="M12 9v4" />
                                                        <path d="M12 16h.01" />
                                                    </svg>
                                                @elseif ($aviso->tipo === 'informativo')
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="1.7" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <circle cx="12" cy="12" r="9" />
                                                        <path d="M12 11v5" />
                                                        <path d="M12 8h.01" />
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="1.7" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path d="M4 6h16" />
                                                        <path d="M4 12h16" />
                                                        <path d="M4 18h10" />
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-center justify-between gap-3">
                                                    <span
                                                        class="inline-flex rounded-md border px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider {{ $config['badge'] }}">
                                                        {{ $config['label'] }}
                                                    </span>
                                                    <span
                                                        class="flex shrink-0 items-center gap-1 text-[10px] text-gray-500">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="1.8" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <rect x="3" y="4" width="18" height="18"
                                                                rx="2" />
                                                            <path d="M16 2v4M8 2v4M3 10h18" />
                                                        </svg>
                                                        {{ optional($aviso->fecha_inicio)->timezone('America/Matamoros')->format('d M Y') }}
                                                    </span>
                                                </div>
                                                <h3
                                                    class="mt-1.5 truncate text-sm font-bold text-white transition-colors duration-200 group-hover:text-blue-300">
                                                    {{ $aviso->titulo }}
                                                </h3>
                                                <p class="mt-1 line-clamp-1 text-xs leading-5 text-gray-400">
                                                    {{ $aviso->descripcion }}
                                                </p>
                                                <div class="mt-2 flex items-center justify-between gap-3">
                                                    <div
                                                        class="flex min-w-0 items-center gap-1.5 text-[10px] text-gray-500">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-3 w-3 shrink-0" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="1.8"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2" />
                                                            <circle cx="9" cy="7" r="4" />
                                                            <path d="M22 21v-2a4 4 0 00-3-3.87" />
                                                            <path d="M16 3.13a4 4 0 010 7.75" />
                                                        </svg>
                                                        <span class="truncate">
                                                            {{ $afectaTexto }}
                                                        </span>
                                                    </div>
                                                    @if ($aviso->importancia)
                                                        <span
                                                            class="inline-flex items-center gap-1.5 rounded-md border px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider
                                        @if ($aviso->importancia === 'critica') border-red-500/30 bg-red-500/10 text-red-400
                                        @elseif ($aviso->importancia === 'alta')
                                            border-orange-500/30 bg-orange-500/10 text-orange-400
                                        @elseif ($aviso->importancia === 'media')
                                            border-yellow-500/30 bg-yellow-500/10 text-yellow-400
                                        @else
                                            border-blue-500/30 bg-blue-500/10 text-blue-400 @endif">
                                                            <span
                                                                class="h-1.5 w-1.5 rounded-full
                                            @if ($aviso->importancia === 'critica') bg-red-400
                                            @elseif ($aviso->importancia === 'alta')
                                                bg-orange-400
                                            @elseif ($aviso->importancia === 'media')
                                                bg-yellow-400
                                            @else
                                                bg-blue-400 @endif"></span>
                                                            {{ ucfirst($aviso->importancia) }}
                                                        </span>
                                                    @endif
                                                    <button type="button"
                                                        @click="abrirAviso(@js($aviso))"
                                                        class="group/ver inline-flex shrink-0 items-center gap-1.5 rounded-lg border border-blue-500/10 bg-blue-500/5 px-2.5 py-1.5 text-[10px] font-bold text-blue-400 transition-all duration-200 hover:border-blue-500/30 hover:bg-blue-500/10 hover:text-blue-300">
                                                        Ver aviso
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-3 w-3 transition-transform duration-200 group-hover/ver:translate-x-0.5"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path d="M5 12h14" />
                                                            <path d="m12 5 7 7-7 7" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div
                                        class="rounded-xl border border-dashed border-[#26335f] bg-[#0b102b]/60 px-5 py-8 text-center">
                                        <div
                                            class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-xl border border-[#26335f] bg-[#151b3b] text-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M18 8a6 6 0 00-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9" />
                                                <path d="M10 21h4" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-semibold text-gray-400">No hay avisos disponibles</p>
                                        <p class="mt-1 text-xs text-gray-600">No se han publicado avisos recientemente.
                                        </p>
                                    </div>
                                @endforelse
                            </div>
                            <div x-cloak x-show="abierto" x-transition.opacity
                                class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
                                @click.self="cerrarAviso()">
                                <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
                                <div x-cloak x-show="abierto" @keydown.escape.window="cerrarAviso()"
                                    class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-6"
                                    role="dialog" aria-modal="true">
                                    <div x-show="abierto" x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                        @click="cerrarAviso()" class="absolute inset-0 bg-black/70 backdrop-blur-sm">
                                    </div>
                                    <div x-show="abierto" x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 translate-y-4 scale-[0.97]"
                                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                        x-transition:leave-end="opacity-0 translate-y-4 scale-[0.97]" @click.stop
                                        class="relative flex w-full max-w-2xl flex-col overflow-hidden rounded-2xl border border-[#26335f] bg-[#0b102b] shadow-2xl shadow-black/60">
                                        <div class="h-[3px] w-full" :class="colorTipo()"></div>
                                        <div
                                            class="border-b border-[#1e295d] bg-gradient-to-r from-[#101638] to-[#0c122e] px-5 py-4 sm:px-6">
                                            <div class="flex items-start gap-4">
                                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border shadow-lg"
                                                    :class="iconoClase()">
                                                    <template x-if="aviso.tipo === 'mantenimiento'">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="1.8" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path
                                                                d="M14.7 6.3a4.2 4.2 0 00-5.9 5.9L4 17l3 3 4.8-4.8a4.2 4.2 0 005.9-5.9l-2.2 2.2-2.9-.9-.9-2.9z" />
                                                        </svg>
                                                    </template>
                                                    <template x-if="aviso.tipo === 'incidente'">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="1.8" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path d="M12 3l9 17H3L12 3z" />
                                                            <path d="M12 9v4" />
                                                            <path d="M12 16h.01" />
                                                        </svg>
                                                    </template>
                                                    <template x-if="aviso.tipo === 'informativo'">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="1.8" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <circle cx="12" cy="12" r="9" />
                                                            <path d="M12 11v5" />
                                                            <path d="M12 8h.01" />
                                                        </svg>
                                                    </template>
                                                    <template
                                                        x-if="aviso.tipo !== 'mantenimiento' && aviso.tipo !== 'incidente' && aviso.tipo !== 'informativo'">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="1.8" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path d="M4 6h16" />
                                                            <path d="M4 12h16" />
                                                            <path d="M4 18h10" />
                                                        </svg>
                                                    </template>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex flex-wrap items-center gap-2">
                                                        <span
                                                            class="inline-flex items-center rounded-md border px-2 py-1 text-[9px] font-bold uppercase tracking-wider"
                                                            :class="badgeTipo()" x-text="nombreTipo()"></span>
                                                        <span x-show="aviso.importancia"
                                                            class="inline-flex items-center gap-1.5 rounded-md border px-2 py-1 text-[9px] font-bold uppercase tracking-wider"
                                                            :class="badgeImportancia()">
                                                            <span class="h-1.5 w-1.5 rounded-full"
                                                                :class="{
                                                                    'bg-red-400': aviso.importancia === 'critica',
                                                                    'bg-orange-400': aviso.importancia === 'alta',
                                                                    'bg-yellow-400': aviso.importancia === 'media',
                                                                    'bg-blue-400': aviso.importancia === 'normal'
                                                                }"></span>
                                                            <span x-text="capitalizar(aviso.importancia)"></span>
                                                        </span>
                                                    </div>
                                                    <h2 class="mt-2 text-base font-bold leading-6 text-white sm:text-lg"
                                                        x-text="aviso.titulo || 'Aviso'"></h2>
                                                </div>
                                                <button type="button" @click="cerrarAviso()"
                                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-transparent text-gray-500 transition hover:border-[#26335f] hover:bg-white/5 hover:text-white"
                                                    aria-label="Cerrar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="1.8" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path d="M18 6L6 18" />
                                                        <path d="M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="max-h-[65vh] overflow-y-auto px-5 py-5 sm:px-6">
                                            <div>
                                                <div class="mb-2 flex items-center gap-2">
                                                    <div class="h-1 w-5 rounded-full bg-blue-500"></div>
                                                    <p
                                                        class="text-[10px] font-bold uppercase tracking-[0.18em] text-gray-500">
                                                        Descripción</p>
                                                </div>
                                                <div
                                                    class="relative overflow-hidden rounded-xl border border-[#1e295d] bg-[#080d24] p-4">
                                                    <div class="absolute left-0 top-0 h-full w-[2px]"
                                                        :class="colorTipo()"></div>
                                                    <p class="whitespace-pre-line text-sm leading-6 text-gray-300"
                                                        x-text="aviso.descripcion || 'Sin descripción disponible.'">
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="mt-5">
                                                <div class="mb-3 flex items-center gap-2">
                                                    <div class="h-1 w-5 rounded-full bg-blue-500"></div>
                                                    <p
                                                        class="text-[10px] font-bold uppercase tracking-[0.18em] text-gray-500">
                                                        Información del aviso</p>
                                                </div>
                                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                                    <div
                                                        class="group rounded-xl border border-[#1e295d] bg-[#080d24] p-3.5 transition hover:border-blue-500/20 hover:bg-[#0a102b]">
                                                        <div class="flex items-center gap-3">
                                                            <div
                                                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-blue-500/10 bg-blue-500/10 text-blue-400">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-4 w-4" viewBox="0 0 24 24"
                                                                    fill="none" stroke="currentColor"
                                                                    stroke-width="1.8" stroke-linecap="round"
                                                                    stroke-linejoin="round">
                                                                    <rect x="3" y="4" width="18" height="18"
                                                                        rx="2" />
                                                                    <path d="M16 2v4M8 2v4M3 10h18" />
                                                                </svg>
                                                            </div>
                                                            <div class="min-w-0">
                                                                <p
                                                                    class="text-[9px] font-bold uppercase tracking-wider text-gray-600">
                                                                    Fecha</p>
                                                                <p class="mt-0.5 text-xs font-semibold text-gray-300"
                                                                    x-text="formatearFecha(aviso.fecha_inicio)"></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="group rounded-xl border border-[#1e295d] bg-[#080d24] p-3.5 transition hover:border-cyan-500/20 hover:bg-[#0a102b]">
                                                        <div class="flex items-center gap-3">
                                                            <div
                                                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-cyan-500/10 bg-cyan-500/10 text-cyan-400">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-4 w-4" viewBox="0 0 24 24"
                                                                    fill="none" stroke="currentColor"
                                                                    stroke-width="1.8" stroke-linecap="round"
                                                                    stroke-linejoin="round">
                                                                    <circle cx="12" cy="12" r="9" />
                                                                    <path d="M12 7v5l3 2" />
                                                                </svg>
                                                            </div>
                                                            <div class="min-w-0">
                                                                <p
                                                                    class="text-[9px] font-bold uppercase tracking-wider text-gray-600">
                                                                    Hora</p>
                                                                <p class="mt-0.5 text-xs font-semibold text-gray-300"
                                                                    x-text="formatearHora(aviso.fecha_inicio)"></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="rounded-xl border border-[#1e295d] bg-[#080d24] p-3.5 transition hover:border-purple-500/20 hover:bg-[#0a102b] sm:col-span-2">
                                                        <div class="flex items-start gap-3">
                                                            <div
                                                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-purple-500/10 bg-purple-500/10 text-purple-400">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-4 w-4" viewBox="0 0 24 24"
                                                                    fill="none" stroke="currentColor"
                                                                    stroke-width="1.8" stroke-linecap="round"
                                                                    stroke-linejoin="round">
                                                                    <path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2" />
                                                                    <circle cx="9" cy="7" r="4" />
                                                                    <path d="M22 21v-2a4 4 0 00-3-3.87" />
                                                                    <path d="M16 3.13a4 4 0 010 7.75" />
                                                                </svg>
                                                            </div>
                                                            <div class="min-w-0">
                                                                <p
                                                                    class="text-[9px] font-bold uppercase tracking-wider text-gray-600">
                                                                    Dirigido a</p>
                                                                <p class="mt-0.5 text-xs font-semibold leading-5 text-gray-300"
                                                                    x-text="obtenerAfectados()"></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <template x-if="aviso.archivo">
                                                        <div class="mt-5">
                                                            <p class="mb-2 text-xs font-bold uppercase text-gray-500">
                                                                Archivo adjunto</p>
                                                            <template x-if="esImagen(aviso.archivo)">
                                                                <div>
                                                                    <img :src="obtenerUrlArchivo(aviso.archivo)"
                                                                        alt="Archivo adjunto"
                                                                        class="max-h-72 w-full rounded-xl object-contain bg-[#080d24]">
                                                                    <a :href="obtenerUrlArchivo(aviso.archivo)"
                                                                        target="_blank"
                                                                        class="mt-2 inline-block text-xs text-blue-400 hover:text-blue-300">
                                                                        Abrir imagen
                                                                    </a>
                                                                </div>
                                                            </template>
                                                            <template x-if="!esImagen(aviso.archivo)">
                                                                <a :href="obtenerUrlArchivo(aviso.archivo)"
                                                                    target="_blank" rel="noopener noreferrer"
                                                                    class="inline-flex rounded-lg bg-blue-500/10 px-4 py-2 text-xs font-semibold text-blue-400">
                                                                    Abrir archivo
                                                                </a>
                                                            </template>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="flex items-center justify-between border-t border-[#1e295d] bg-[#080d24]/80 px-5 py-3.5 sm:px-6">
                                            <div class="hidden items-center gap-2 text-[10px] text-gray-600 sm:flex">
                                                <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                                Aviso publicado
                                            </div>
                                            <button type="button" @click="cerrarAviso()"
                                                class="inline-flex items-center gap-2 rounded-lg border border-[#26335f] bg-[#111832] px-4 py-2 text-xs font-semibold text-gray-300 transition hover:border-blue-500/30 hover:bg-[#151d3d] hover:text-white">
                                                Cerrar
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M18 6L6 18" />
                                                    <path d="M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
