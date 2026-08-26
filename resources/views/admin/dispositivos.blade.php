<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>TicketPro - Dispositivos</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body x-data="dispositivosPage()" class="min-h-screen bg-[#070b19] font-sans text-white antialiased">

    <aside :class="menuMovil ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
        class="fixed inset-y-0 left-0 z-[99999] flex w-[280px] max-w-[85vw] flex-col justify-between border-r border-slate-800/60 bg-[#0a0f24] p-5 transition-transform duration-300 sm:p-6">
        <div>

            <div class="mb-8 flex items-center justify-between sm:mb-10">
                <span class="text-2xl font-extrabold tracking-wide text-white sm:text-3xl">
                    Ticket<span class="text-blue-500">Pro</span>
                </span>

                <button type="button" @click="menuMovil = false"
                    class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-800 hover:text-white md:hidden">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>

            <div class="mb-8 flex items-center gap-3 rounded-xl border border-slate-800/50 bg-slate-900/40 p-2">
                <img src="{{ auth()->user()->picture
                    ? asset('storage/' . auth()->user()->picture)
                    : asset('storage/profile-photos/user.png') }}"
                    alt="{{ auth()->user()->name }}"
                    class="h-11 w-11 shrink-0 rounded-full border-2 border-gray-500 object-cover sm:h-12 sm:w-12">

                <div class="min-w-0 overflow-hidden">
                    <h4 class="truncate text-sm font-semibold text-slate-200">
                        {{ auth()->user()->name ?? 'Desconocido' }}
                    </h4>

                    <p class="truncate text-xs text-slate-400">
                        {{ auth()->user()->role ?? 'Desconocido' }}
                    </p>
                </div>
            </div>

            <nav class="space-y-2">

                <a href="{{ route('tecnologias') }}"
                    class="flex items-center gap-3 rounded-xl px-4 py-3 text-slate-400 transition hover:bg-slate-800/50 hover:text-white">
                    <i data-lucide="layout-dashboard" class="h-5 w-5 shrink-0"></i>
                    <span class="text-sm font-medium">Inicio</span>
                </a>

                <a href="{{ route('tickettecnologias') }}"
                    class="flex items-center gap-3 rounded-xl px-4 py-3 text-slate-400 transition hover:bg-slate-800/50 hover:text-white">
                    <i data-lucide="ticket-check" class="h-5 w-5 shrink-0"></i>
                    <span class="text-sm font-medium">Tickets</span>
                </a>

                @if (auth()->check() && auth()->user()->role === 'Gerente Ti' && auth()->user()->priv_admin === 'Y')
                    <a href="{{ route('cambiostecnologias') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-3 text-slate-400 transition hover:bg-slate-800/50 hover:text-white">
                        <i data-lucide="git-compare-arrows" class="h-5 w-5 shrink-0"></i>
                        <span class="text-sm">Cambios</span>
                    </a>

                    <a href="{{ route('usuarios.tecnologias') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-3 text-slate-400 transition hover:bg-slate-800/50 hover:text-white">
                        <i data-lucide="users" class="h-5 w-5 shrink-0"></i>
                        <span class="text-sm">Usuarios</span>
                    </a>
                @endif

                <a href="{{ route('dispositivos') }}"
                    class="flex items-center gap-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-4 py-3 font-semibold text-white shadow-lg shadow-blue-600/30">
                    <i data-lucide="monitor-smartphone" class="h-5 w-5 shrink-0"></i>
                    <span class="text-sm font-medium">Dispositivos</span>
                </a>

                <a href="{{ route('avisostecnologias') }}"
                    class="flex items-center gap-3 rounded-xl px-4 py-3 text-slate-400 transition hover:bg-slate-800/50 hover:text-white">
                    <i data-lucide="megaphone" class="h-5 w-5 shrink-0"></i>
                    <span class="text-sm font-medium">Avisos</span>
                </a>

                <a href="{{ route('perfiltecnologias') }}"
                    class="flex items-center gap-3 rounded-xl px-4 py-3 text-slate-400 transition hover:bg-slate-800/50 hover:text-white">
                    <i data-lucide="circle-user-round" class="h-5 w-5 shrink-0"></i>
                    <span class="text-sm font-medium">Mi perfil</span>
                </a>

            </nav>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit"
                class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-slate-400 transition hover:bg-red-500/10 hover:text-red-400">
                <i data-lucide="log-out" class="h-5 w-5 shrink-0"></i>

                <span class="text-sm font-medium">
                    Cerrar sesión
                </span>
            </button>
        </form>
    </aside>

    <div x-show="menuMovil" x-cloak x-transition.opacity @click="menuMovil = false"
        class="fixed inset-0 z-[99998] bg-black/70 backdrop-blur-sm md:hidden"></div>

    <main class="min-h-screen px-4 pb-6 pt-20 sm:px-6 sm:pb-8 sm:pt-20 md:ml-[280px] md:pt-8 lg:px-8 lg:py-8">

        <div class="mx-auto max-w-[1500px]">

            <button type="button" @click="menuMovil = true"
                class="fixed left-4 top-4 z-[99997] flex h-10 w-10 items-center justify-center rounded-xl border border-slate-800 bg-[#0f1535] text-slate-300 transition hover:bg-slate-800 hover:text-white md:hidden">
                <i data-lucide="menu" class="h-5 w-5"></i>
            </button>

            <header class="mb-8 flex flex-col gap-5 md:flex-row md:items-center md:justify-between">

                <div>
                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-blue-500/20 bg-blue-500/10">
                            <i data-lucide="monitor-smartphone" class="h-5 w-5 text-blue-400"></i>
                        </div>

                        <div>
                            <h1 class="text-2xl font-bold tracking-tight text-white sm:text-3xl">
                                Dispositivos
                            </h1>

                            <p class="mt-1 text-xs text-slate-400 sm:text-sm">
                                Administra y vincula los equipos de los usuarios
                            </p>
                        </div>

                    </div>
                </div>

                <div class="flex items-center gap-3 self-end sm:gap-4 md:self-auto">

                    <div class="relative" x-data="{ notificacionesAbiertas: false }">

                        <button type="button" @click="notificacionesAbiertas = !notificacionesAbiertas"
                            class="relative
                                   flex items-center justify-center
                                   w-10 h-10
                                   rounded-xl
                                   bg-slate-900/80
                                   border border-slate-800
                                   text-slate-400
                                   hover:text-white
                                   hover:bg-slate-800
                                   transition">

                            <i data-lucide="bell" class="w-5 h-5"></i>

                            @if ($notificacionesNoLeidas > 0)
                                <span
                                    class="absolute -top-1 -right-1
                                           min-w-[18px] h-[18px]
                                           px-1
                                           flex items-center justify-center
                                           rounded-full
                                           bg-indigo-600
                                           border-2 border-[#050814]
                                           text-[9px]
                                           font-bold text-white">
                                    {{ $notificacionesNoLeidas > 99 ? '99+' : $notificacionesNoLeidas }}
                                </span>
                            @endif

                        </button>


                        {{-- DROPDOWN NOTIFICACIONES --}}
                        <div x-show="notificacionesAbiertas" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                            x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                            @click.outside="notificacionesAbiertas = false"
                            class="fixed
                                   left-3 right-3
                                   top-16
                                   sm:absolute
                                   sm:left-auto
                                   sm:right-0
                                   sm:top-full
                                   sm:mt-3
                                   w-auto
                                   sm:w-[360px]
                                   max-h-[calc(100vh-80px)]
                                   bg-[#0f1535]
                                   border border-[#1e295d]
                                   rounded-2xl
                                   shadow-2xl
                                   shadow-black/40
                                   overflow-hidden
                                   z-[99999]"
                            style="display:none;">

                            <div
                                class="flex items-center justify-between
                                       px-4 py-4
                                       border-b border-slate-800/80
                                       gap-3">

                                <div class="flex items-center gap-2 min-w-0">

                                    <div
                                        class="w-8 h-8 shrink-0
                                               rounded-lg
                                               bg-indigo-500/10
                                               border border-indigo-500/20
                                               flex items-center justify-center">
                                        <i data-lucide="bell" class="w-4 h-4 text-indigo-400"></i>
                                    </div>

                                    <div class="min-w-0">

                                        <h3
                                            class="text-sm font-semibold
                                                   text-white truncate">
                                            Notificaciones
                                        </h3>

                                        <p
                                            class="text-[10px]
                                                   text-slate-500 truncate">
                                            Tienes {{ $notificacionesNoLeidas }} nuevas
                                        </p>

                                    </div>

                                </div>

                                @if ($notificacionesNoLeidas > 0)
                                    <form method="POST" action="{{ route('notificaciones.marcarLeidas') }}"
                                        class="shrink-0">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                            class="text-[11px]
                                                   font-medium
                                                   text-indigo-400
                                                   hover:text-indigo-300
                                                   transition
                                                   whitespace-nowrap">
                                            Marcar leídas
                                        </button>

                                    </form>
                                @endif

                            </div>


                            <div class="max-h-[400px] overflow-y-auto">

                                @forelse ($notificaciones as $notificacion)
                                    <a href="{{ $notificacion->url ?? '#' }}"
                                        class="group flex gap-3
                                               px-4 py-4
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
                                                class="w-5 h-5 text-indigo-400"></i>

                                        </div>

                                        <div class="flex-1 min-w-0">

                                            <div
                                                class="flex items-start
                                                       justify-between
                                                       gap-2">

                                                <p
                                                    class="text-xs
                                                           font-semibold
                                                           text-white
                                                           group-hover:text-indigo-400
                                                           transition
                                                           break-words
                                                           min-w-0">
                                                    {{ $notificacion->titulo }}
                                                </p>

                                                @if (!$notificacion->leida)
                                                    <span
                                                        class="w-2 h-2
                                                               shrink-0
                                                               mt-1.5
                                                               rounded-full
                                                               bg-indigo-500"></span>
                                                @endif

                                            </div>

                                            <p
                                                class="mt-1
                                                       text-[11px]
                                                       leading-relaxed
                                                       text-slate-400
                                                       break-words">
                                                {{ $notificacion->mensaje }}
                                            </p>

                                            <p
                                                class="mt-2
                                                       text-[10px]
                                                       text-slate-500">
                                                {{ $notificacion->created_at->diffForHumans() }}
                                            </p>

                                        </div>

                                    </a>

                                @empty

                                    <div class="px-6 py-10 text-center">

                                        <div
                                            class="mx-auto mb-3
                                                   w-12 h-12
                                                   rounded-full
                                                   bg-slate-800/50
                                                   border border-slate-800
                                                   flex items-center justify-center">
                                            <i data-lucide="bell-off" class="w-5 h-5 text-slate-500"></i>
                                        </div>

                                        <p
                                            class="text-xs
                                                   font-medium
                                                   text-slate-400">
                                            No tienes notificaciones
                                        </p>

                                        <p
                                            class="text-[10px]
                                                   text-slate-600
                                                   mt-1">
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
                                    <p
                                        class="text-[10px]
                                               text-center
                                               text-slate-500">
                                        Mostrando tus notificaciones recientes
                                    </p>
                                </div>
                            @endif

                        </div>

                    </div>

                    <div class="relative z-[100]" x-data="{ perfilAbierto: false, configuracionAbierta: false }">
                        <button id="profile-button" type="button" @click="perfilAbierto = !perfilAbierto"
                            class="relative flex items-center gap-2 sm:gap-3 bg-slate-900/80 border border-slate-800 rounded-full p-1.5 pr-2 sm:pr-4 hover:bg-slate-800 transition">
                            <img src="{{ auth()->user()->picture ? asset('storage/' . auth()->user()->picture) : asset('storage/profile-photos/user.png') }}"
                                alt="{{ auth()->user()->name }}"
                                class="w-9 h-9 sm:w-12 sm:h-12 rounded-full border-2 border-gray-500 object-cover">
                            <div class="text-left leading-tight hidden sm:block">
                                <p class="text-xs font-semibold text-white max-w-[130px] truncate">
                                    {{ auth()->user()->name ?? 'Desconocido' }}</p>
                                <p class="text-[10px] text-blue-400 font-medium">
                                    {{ auth()->user()->role ?? 'Desconocido' }}</p>
                            </div>
                            <i data-lucide="chevron-down"
                                class="hidden sm:block w-4 h-4 text-slate-400 ml-1 transition-transform duration-200"
                                :class="{ 'rotate-180': perfilAbierto }"></i>
                        </button>
                        <div x-show="perfilAbierto" @click.outside="perfilAbierto = false" x-transition
                            class="absolute right-0 top-full mt-3 w-60 bg-[#0f1535] border border-[#1e295d] rounded-xl shadow-2xl overflow-hidden z-[99999]"
                            style="display:none;">
                            <button type="button" @click="configuracionAbierta = !configuracionAbierta"
                                class="w-full flex items-center gap-3 px-4 py-3 text-sm text-slate-300 hover:bg-[#151b3b] hover:text-white transition text-left">
                                <i data-lucide="settings" class="w-5 h-5 text-slate-400"></i>
                                <span class="flex-1">Configuración</span>
                                <i data-lucide="chevron-down"
                                    class="w-4 h-4 text-slate-500 transition-transform duration-200"
                                    :class="{ 'rotate-180': configuracionAbierta }"></i>
                            </button>
                            <div x-show="configuracionAbierta" x-transition
                                class="border-t border-[#1e295d] bg-[#0b1026]/50">
                                <a href="{{ route('perfiltecnologias') }}"
                                    class="flex items-center gap-3 px-5 py-3 text-sm text-slate-400 hover:bg-[#151b3b] hover:text-white transition">
                                    <i data-lucide="circle-user-round" class="w-4 h-4 text-blue-400"></i>
                                    <span>Mi perfil</span>
                                </a>
                                @if (auth()->check() &&
                                        trim((string) auth()->user()->role) === 'Gerente Ti' &&
                                        strtoupper(trim((string) auth()->user()->priv_admin)) === 'Y')
                                    <a href="{{ route('backups') }}"
                                        class="flex items-center gap-3 px-5 py-3 text-sm text-slate-400 hover:bg-[#151b3b] hover:text-white transition">
                                        <i data-lucide="database-backup" class="w-4 h-4 text-emerald-400"></i>
                                        <span>Backups</span>
                                    </a>
                                @endif
                            </div>
                            <div class="border-t border-[#1e295d]"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-3 text-sm text-slate-300 hover:bg-red-500/10 hover:text-red-400 transition text-left">
                                    <i data-lucide="log-out" class="w-5 h-5"></i>
                                    <span>Cerrar sesión</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <div class="grid grid-cols-1 gap-6 xl:grid-cols-12">

                <section class="xl:col-span-4">

                    <div class="overflow-hidden rounded-2xl border border-[#1e295d] bg-[#0f1535] shadow-xl">

                        <div class="border-b border-slate-800/80 px-5 py-5">

                            <div class="flex items-center gap-3">

                                <div
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-blue-500/20 bg-blue-500/10">
                                    <i data-lucide="link" class="h-5 w-5 text-blue-400"></i>
                                </div>

                                <div>
                                    <h2 class="text-sm font-semibold text-white">
                                        Vincular dispositivo
                                    </h2>

                                    <p class="mt-0.5 text-[11px] text-slate-500">
                                        Asigna un equipo a un usuario
                                    </p>
                                </div>

                            </div>

                        </div>

                        <form method="POST" action="{{ route('dispositivos.store') }}" class="space-y-5 p-5">
                            @csrf

                            <div>

                                <label class="mb-2 block text-xs font-semibold text-slate-300">
                                    Usuario
                                </label>

                                <div class="relative">

                                    <i data-lucide="user"
                                        class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500"></i>

                                    <select name="login" required
                                        class="w-full appearance-none rounded-xl border border-slate-800 bg-[#0b1026] py-3 pl-10 pr-10 text-sm text-slate-200 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">
                                        <option value="">
                                            Selecciona un usuario
                                        </option>

                                        @foreach ($usuarios as $usuario)
                                            <option value="{{ $usuario->login }}" @selected(old('login') == $usuario->login)>
                                                {{ $usuario->name }} — {{ $usuario->login }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <i data-lucide="chevron-down"
                                        class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500"></i>

                                </div>

                                @error('login')
                                    <p class="mt-2 text-[11px] text-red-400">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                            <div>

                                <label class="mb-2 block text-xs font-semibold text-slate-300">
                                    Nombre del equipo
                                </label>

                                <div class="relative">

                                    <i data-lucide="monitor"
                                        class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500"></i>

                                    <input type="text" name="nombre_equipo" value="{{ old('nombre_equipo') }}"
                                        required maxlength="255" placeholder="Ej. PC-OFICINA-01"
                                        class="w-full rounded-xl border border-slate-800 bg-[#0b1026] py-3 pl-10 pr-4 text-sm text-white outline-none transition placeholder:text-slate-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">

                                </div>

                                @error('nombre_equipo')
                                    <p class="mt-2 text-[11px] text-red-400">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                            <div>

                                <label class="mb-2 block text-xs font-semibold text-slate-300">
                                    ID del equipo
                                </label>

                                <div class="relative">

                                    <i data-lucide="fingerprint"
                                        class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500"></i>

                                    <input type="text" name="id_equipo" value="{{ old('id_equipo') }}" required
                                        maxlength="255" placeholder="Ej. DESKTOP-A8F32K"
                                        class="w-full rounded-xl border border-slate-800 bg-[#0b1026] py-3 pl-10 pr-4 text-sm text-white outline-none transition placeholder:text-slate-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">

                                </div>

                                <p class="mt-2 text-[10px] text-slate-600">
                                    Este identificador debe ser único.
                                </p>

                                @error('id_equipo')
                                    <p class="mt-2 text-[11px] text-red-400">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                            <button type="submit"
                                class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:from-blue-500 hover:to-indigo-500">
                                <i data-lucide="link-2" class="h-4 w-4"></i>
                                Vincular dispositivo
                            </button>

                        </form>

                    </div>

                </section>

                <section class="min-w-0 xl:col-span-8">

                    <div class="overflow-hidden rounded-2xl border border-[#1e295d] bg-[#0f1535] shadow-xl">

                        <div class="border-b border-slate-800/80 px-5 py-5">

                            <div class="flex flex-col gap-5">

                                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                                    <div>
                                        <h2 class="text-sm font-semibold text-white">
                                            Dispositivos registrados
                                        </h2>

                                        <p class="mt-1 text-[11px] text-slate-500">
                                            Equipos actualmente registrados en TicketPro
                                        </p>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-2">

                                        <div
                                            class="flex items-center gap-2 rounded-lg border border-slate-800 bg-[#0b1026] px-3 py-2">
                                            <i data-lucide="monitor" class="h-4 w-4 text-blue-400"></i>

                                            <span class="text-xs font-semibold text-slate-300">
                                                {{ $dispositivos->count() }}
                                            </span>

                                            <span class="text-[10px] text-slate-600">
                                                total
                                            </span>
                                        </div>

                                        <div
                                            class="flex items-center gap-2 rounded-lg border border-emerald-500/20 bg-emerald-500/5 px-3 py-2">

                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>

                                            <span class="text-xs font-semibold text-emerald-400">
                                                {{ $dispositivos->filter(fn($d) => strtolower(trim($d->estado ?? 'vinculado')) === 'vinculado')->count() }}
                                            </span>

                                            <span class="text-[10px] text-slate-600">
                                                vinculados
                                            </span>

                                        </div>

                                        <div
                                            class="flex items-center gap-2 rounded-lg border border-amber-500/20 bg-amber-500/5 px-3 py-2">

                                            <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>

                                            <span class="text-xs font-semibold text-amber-400">
                                                {{ $dispositivos->filter(fn($d) => strtolower(trim($d->estado ?? 'vinculado')) === 'desvinculado')->count() }}
                                            </span>

                                            <span class="text-[10px] text-slate-600">
                                                desvinculados
                                            </span>

                                        </div>

                                    </div>

                                </div>

                                <div class="flex flex-col gap-3 lg:flex-row">

                                    <div class="relative flex-1">

                                        <i data-lucide="search"
                                            class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500"></i>

                                        <input type="text" x-model="busqueda"
                                            placeholder="Buscar usuario, login, equipo o ID..."
                                            class="w-full rounded-xl border border-slate-800 bg-[#0b1026] py-3 pl-10 pr-4 text-xs text-white outline-none transition placeholder:text-slate-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">

                                        <button type="button" x-show="busqueda.length > 0" x-cloak
                                            @click="busqueda = ''"
                                            class="absolute right-3 top-1/2 flex h-6 w-6 -translate-y-1/2 items-center justify-center rounded-md text-slate-500 transition hover:bg-slate-800 hover:text-white">
                                            <i data-lucide="x" class="h-3.5 w-3.5"></i>
                                        </button>

                                    </div>

                                    <div class="relative lg:w-52">

                                        <i data-lucide="filter"
                                            class="absolute left-3 top-1/2 z-10 h-4 w-4 -translate-y-1/2 text-slate-500"></i>

                                        <select x-model="filtroEstado"
                                            class="w-full appearance-none rounded-xl border border-slate-800 bg-[#0b1026] py-3 pl-10 pr-10 text-xs font-medium text-slate-300 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">
                                            <option value="todos">
                                                Todos los dispositivos
                                            </option>

                                            <option value="vinculado">
                                                Vinculados
                                            </option>

                                            <option value="desvinculado">
                                                Desvinculados
                                            </option>
                                        </select>

                                        <i data-lucide="chevron-down"
                                            class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500"></i>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[1000px] text-left">
                                <thead>
                                    <tr class="border-b border-slate-800/80 bg-[#0b1026]">
                                        <th
                                            class="px-5 py-4 text-[10px] font-semibold uppercase tracking-wider text-slate-500">
                                            Usuario
                                        </th>
                                        <th
                                            class="px-5 py-4 text-[10px] font-semibold uppercase tracking-wider text-slate-500">
                                            Equipo
                                        </th>
                                        <th
                                            class="px-5 py-4 text-[10px] font-semibold uppercase tracking-wider text-slate-500">
                                            ID del equipo
                                        </th>
                                        <th
                                            class="px-5 py-4 text-right text-[10px] font-semibold uppercase tracking-wider text-slate-500">
                                            Estado
                                        </th>
                                        <th
                                            class="px-5 py-4 text-right text-[10px] font-semibold uppercase tracking-wider text-slate-500">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-slate-800/60">
                                    @forelse ($dispositivos as $dispositivo)
                                        @php
                                            $estadoDispositivo = strtolower(trim($dispositivo->estado ?? 'vinculado'));
                                        @endphp

                                        <tr class="transition hover:bg-slate-800/20">
                                            <td class="px-5 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-blue-500/20 bg-blue-500/10">
                                                        <i data-lucide="user" class="h-4 w-4 text-blue-400"></i>
                                                    </div>

                                                    <div class="min-w-0">
                                                        <p
                                                            class="max-w-[180px] truncate text-xs font-semibold text-white">
                                                            {{ $dispositivo->usuario->name ?? 'Sin usuario' }}
                                                        </p>

                                                        <p class="max-w-[180px] truncate text-[10px] text-slate-500">
                                                            {{ $dispositivo->login }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-5 py-4">
                                                <div class="flex items-center gap-2">
                                                    <i data-lucide="monitor"
                                                        class="h-4 w-4 shrink-0 text-slate-500"></i>

                                                    <span class="max-w-[180px] truncate text-xs text-slate-300">
                                                        {{ $dispositivo->nombre_equipo }}
                                                    </span>
                                                </div>
                                            </td>

                                            <td class="px-5 py-4">
                                                <span
                                                    class="inline-flex max-w-[180px] items-center truncate rounded-lg border border-slate-800 bg-[#0b1026] px-3 py-1.5 font-mono text-[10px] text-slate-400">
                                                    {{ $dispositivo->id_equipo }}
                                                </span>
                                            </td>

                                            <td class="px-5 py-4 text-right">
                                                @if ($estadoDispositivo === 'vinculado')
                                                    <span
                                                        class="inline-flex items-center gap-1.5 rounded-full border border-emerald-500/20 bg-emerald-500/10 px-2.5 py-1 text-[10px] font-semibold text-emerald-400">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                                                        Vinculado
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center gap-1.5 rounded-full border border-amber-500/20 bg-amber-500/10 px-2.5 py-1 text-[10px] font-semibold text-amber-400">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                                                        Desvinculado
                                                    </span>
                                                @endif
                                            </td>

                                            <td class="px-5 py-4">
                                                <div class="flex items-center justify-end gap-2">
                                                    <button type="button"
                                                        @click="abrirEditar({
                                    id: {{ $dispositivo->id }},
                                    login: @js($dispositivo->login),
                                    nombre_equipo: @js($dispositivo->nombre_equipo),
                                    id_equipo: @js($dispositivo->id_equipo),
                                    estado: @js($dispositivo->estado ?? 'vinculado')
                                })"
                                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-blue-500/20 bg-blue-500/10 text-blue-400 transition hover:border-blue-500/40 hover:bg-blue-500/20 hover:text-blue-300"
                                                        title="Editar dispositivo">
                                                        <i data-lucide="pencil" class="h-4 w-4"></i>
                                                    </button>

                                                    <button type="button"
                                                        @click="abrirCambioEstado({
                                    id: {{ $dispositivo->id }},
                                    login: @js($dispositivo->login),
                                    nombre_equipo: @js($dispositivo->nombre_equipo),
                                    id_equipo: @js($dispositivo->id_equipo),
                                    estadoActual: @js($dispositivo->estado),
                                    nuevoEstado: @js($dispositivo->estado === 'vinculado' ? 'desvinculado' : 'vinculado')
                                })"
                                                        class="flex h-9 w-9 items-center justify-center rounded-lg border transition
                                    {{ $dispositivo->estado === 'vinculado'
                                        ? 'border-amber-500/20 bg-amber-500/10 text-amber-400 hover:border-amber-500/40 hover:bg-amber-500/20 hover:text-amber-300'
                                        : 'border-emerald-500/20 bg-emerald-500/10 text-emerald-400 hover:border-emerald-500/40 hover:bg-emerald-500/20 hover:text-emerald-300' }}"
                                                        title="{{ $dispositivo->estado === 'vinculado' ? 'Desvincular dispositivo' : 'Vincular dispositivo' }}">
                                                        <i data-lucide="{{ $dispositivo->estado === 'vinculado' ? 'unlink' : 'link-2' }}"
                                                            class="h-4 w-4">
                                                        </i>
                                                    </button>

                                                    <button type="button"
                                                        @click="abrirEliminar({
                                    id: {{ $dispositivo->id }},
                                    nombre_equipo: @js($dispositivo->nombre_equipo),
                                    login: @js($dispositivo->login),
                                    usuario: @js($dispositivo->usuario->name ?? 'Sin usuario')
                                })"
                                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-red-500/20 bg-red-500/10 text-red-400 transition hover:border-red-500/40 hover:bg-red-500/20 hover:text-red-300"
                                                        title="Eliminar dispositivo">
                                                        <i data-lucide="trash-2" class="h-4 w-4"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-5 py-16">
                                                <div class="flex flex-col items-center justify-center text-center">
                                                    <div
                                                        class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl border border-slate-800 bg-slate-800/40">
                                                        <i data-lucide="monitor-off"
                                                            class="h-6 w-6 text-slate-600"></i>
                                                    </div>

                                                    <h3 class="text-sm font-semibold text-slate-400">
                                                        No hay dispositivos registrados
                                                    </h3>

                                                    <p class="mt-1 max-w-sm text-[11px] text-slate-600">
                                                        Los dispositivos que vincules aparecerán aquí.
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-8 px-2 pb-2 sm:px-4">
                            <div
                                class="flex flex-col items-center justify-between gap-5 border-t border-slate-800/80 pt-5 sm:flex-row">

                                <span class="text-center text-xs text-slate-400 sm:text-left">
                                    Mostrando
                                    <span class="font-medium text-slate-200">
                                        {{ $dispositivos->firstItem() ?? 0 }}
                                    </span>
                                    a
                                    <span class="font-medium text-slate-200">
                                        {{ $dispositivos->lastItem() ?? 0 }}
                                    </span>
                                    de
                                    <span class="font-medium text-slate-200">
                                        {{ $dispositivos->total() }}
                                    </span>
                                    dispositivos
                                </span>

                                <div class="flex items-center gap-2">

                                    @if ($dispositivos->onFirstPage())
                                        <span
                                            class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-900 text-slate-600">
                                            <i data-lucide="chevron-left" class="h-4 w-4"></i>
                                        </span>
                                    @else
                                        <a href="{{ $dispositivos->previousPageUrl() }}"
                                            class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-800 text-slate-400 transition-all duration-200 hover:bg-slate-700 hover:text-white">
                                            <i data-lucide="chevron-left" class="h-4 w-4"></i>
                                        </a>
                                    @endif

                                    @foreach ($dispositivos->getUrlRange(max(1, $dispositivos->currentPage() - 2), min($dispositivos->lastPage(), $dispositivos->currentPage() + 2)) as $page => $url)
                                        <a href="{{ $url }}"
                                            class="flex h-9 w-9 items-center justify-center rounded-xl text-xs transition-all duration-200
                    {{ $page == $dispositivos->currentPage()
                        ? 'bg-blue-600 font-bold text-white shadow-lg shadow-blue-600/20'
                        : 'bg-slate-800 text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                                            {{ $page }}
                                        </a>
                                    @endforeach

                                    @if ($dispositivos->hasMorePages())
                                        <a href="{{ $dispositivos->nextPageUrl() }}"
                                            class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-800 text-slate-400 transition-all duration-200 hover:bg-slate-700 hover:text-white">
                                            <i data-lucide="chevron-right" class="h-4 w-4"></i>
                                        </a>
                                    @else
                                        <span
                                            class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-900 text-slate-600">
                                            <i data-lucide="chevron-right" class="h-4 w-4"></i>
                                        </span>
                                    @endif

                                </div>
                            </div>
                        </div>

                    </div>

                </section>

            </div>

        </div>

    </main>

    <div x-show="modalEditar" x-cloak x-transition.opacity
        class="fixed inset-0 z-[100000] flex items-center justify-center bg-black/80 p-4 backdrop-blur-sm"
        @keydown.escape.window="cerrarEditar()">
        <div x-show="modalEditar" x-transition @click.outside="cerrarEditar()"
            class="w-full max-w-lg overflow-hidden rounded-2xl border border-[#1e295d] bg-[#0f1535] shadow-2xl">

            <div class="flex items-center justify-between border-b border-slate-800/80 px-5 py-5">

                <div class="flex items-center gap-3">

                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl border border-blue-500/20 bg-blue-500/10">
                        <i data-lucide="pencil" class="h-5 w-5 text-blue-400"></i>
                    </div>

                    <div>
                        <h2 class="text-sm font-semibold text-white">
                            Editar dispositivo
                        </h2>

                        <p class="mt-0.5 text-[11px] text-slate-500">
                            Actualiza la información del equipo
                        </p>
                    </div>

                </div>

                <button type="button" @click="cerrarEditar()"
                    class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-800 hover:text-white">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>

            </div>

            <form :action="'{{ url('/dispositivos') }}/' + dispositivoSeleccionado.id" method="POST"
                class="space-y-5 p-5">

                @csrf
                @method('PUT')

                <div>
                    <label class="mb-2 block text-xs font-semibold text-slate-300">
                        Usuario
                    </label>

                    <div class="relative">

                        <i data-lucide="user"
                            class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500"></i>

                        <select name="login" x-model="dispositivoSeleccionado.login" required
                            class="w-full appearance-none rounded-xl border border-slate-800 bg-[#0b1026] py-3 pl-10 pr-10 text-sm text-slate-200 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">
                            @foreach ($usuarios as $usuario)
                                <option value="{{ $usuario->login }}">
                                    {{ $usuario->name }} — {{ $usuario->login }}
                                </option>
                            @endforeach
                        </select>

                        <i data-lucide="chevron-down"
                            class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500"></i>

                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-xs font-semibold text-slate-300">
                        Nombre del equipo
                    </label>

                    <div class="relative">

                        <i data-lucide="monitor"
                            class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500"></i>

                        <input type="text" name="nombre_equipo" x-model="dispositivoSeleccionado.nombre_equipo"
                            required maxlength="255"
                            class="w-full rounded-xl border border-slate-800 bg-[#0b1026] py-3 pl-10 pr-4 text-sm text-white outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">

                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-xs font-semibold text-slate-300">
                        ID del equipo
                    </label>

                    <div class="relative">

                        <i data-lucide="fingerprint"
                            class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500"></i>

                        <input type="text" name="id_equipo" x-model="dispositivoSeleccionado.id_equipo" required
                            maxlength="255"
                            class="w-full rounded-xl border border-slate-800 bg-[#0b1026] py-3 pl-10 pr-4 font-mono text-sm text-white outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">

                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-xs font-semibold text-slate-300">
                        Estado
                    </label>

                    <div class="relative">

                        <i data-lucide="circle-check"
                            class="absolute left-3 top-1/2 z-10 h-4 w-4 -translate-y-1/2 text-slate-500"></i>

                        <select name="estado" x-model="dispositivoSeleccionado.estado" required
                            class="w-full appearance-none rounded-xl border border-slate-800 bg-[#0b1026] py-3 pl-10 pr-10 text-sm text-slate-200 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">
                            <option value="vinculado">
                                Vinculado
                            </option>

                            <option value="desvinculado">
                                Desvinculado
                            </option>
                        </select>

                        <i data-lucide="chevron-down"
                            class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500"></i>

                    </div>
                </div>

                <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">

                    <button type="button" @click="cerrarEditar()"
                        class="rounded-xl border border-slate-800 bg-[#0b1026] px-5 py-3 text-sm font-semibold text-slate-300 transition hover:bg-slate-800 hover:text-white">
                        Cancelar
                    </button>

                    <button type="submit"
                        class="flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:from-blue-500 hover:to-indigo-500">
                        <i data-lucide="save" class="h-4 w-4"></i>
                        Guardar cambios
                    </button>

                </div>

            </form>

        </div>
    </div>

    <div x-show="modalCambioEstado" x-cloak x-transition.opacity
        class="fixed inset-0 z-[100001] flex items-center justify-center bg-black/80 p-4 backdrop-blur-sm"
        @keydown.escape.window="cerrarCambioEstado()">

        <div x-show="modalCambioEstado" x-transition @click.outside="cerrarCambioEstado()"
            class="w-full max-w-md overflow-hidden rounded-2xl border border-[#1e295d] bg-[#0f1535] shadow-2xl">

            <div class="flex items-start gap-4 border-b border-slate-800/80 px-5 py-5">

                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border"
                    :class="cambioEstado.nuevoEstado === 'vinculado' ?
                        'border-emerald-500/20 bg-emerald-500/10' :
                        'border-amber-500/20 bg-amber-500/10'">
                    <i :data-lucide="cambioEstado.nuevoEstado === 'vinculado' ? 'link-2' : 'unlink'" class="h-5 w-5"
                        :class="cambioEstado.nuevoEstado === 'vinculado' ?
                            'text-emerald-400' :
                            'text-amber-400'"></i>
                </div>

                <div class="min-w-0 flex-1">

                    <h2 class="text-sm font-semibold text-white">
                        <span x-show="cambioEstado.nuevoEstado === 'vinculado'">
                            Vincular dispositivo
                        </span>

                        <span x-show="cambioEstado.nuevoEstado === 'desvinculado'">
                            Desvincular dispositivo
                        </span>
                    </h2>

                    <p class="mt-1 text-[11px] text-slate-500">
                        Confirma el cambio de estado del dispositivo.
                    </p>

                </div>

                <button type="button" @click="cerrarCambioEstado()"
                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-800 hover:text-white">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>

            </div>

            <div class="px-5 py-5">

                <div class="rounded-xl border p-4"
                    :class="cambioEstado.nuevoEstado === 'vinculado' ?
                        'border-emerald-500/10 bg-emerald-500/5' :
                        'border-amber-500/10 bg-amber-500/5'">

                    <div class="flex items-start gap-3">

                        <i :data-lucide="cambioEstado.nuevoEstado === 'vinculado' ? 'circle-check' : 'triangle-alert'"
                            class="mt-0.5 h-4 w-4 shrink-0"
                            :class="cambioEstado.nuevoEstado === 'vinculado' ?
                                'text-emerald-400' :
                                'text-amber-400'"></i>

                        <div>

                            <p class="text-xs font-semibold text-slate-200">

                                <span x-show="cambioEstado.nuevoEstado === 'vinculado'">
                                    ¿Deseas vincular este dispositivo?
                                </span>

                                <span x-show="cambioEstado.nuevoEstado === 'desvinculado'">
                                    ¿Deseas desvincular este dispositivo?
                                </span>

                            </p>

                            <p class="mt-1 text-[11px] leading-relaxed text-slate-500">

                                <span x-show="cambioEstado.nuevoEstado === 'vinculado'">
                                    El dispositivo quedará marcado como vinculado al usuario.
                                </span>

                                <span x-show="cambioEstado.nuevoEstado === 'desvinculado'">
                                    El dispositivo quedará marcado como desvinculado del usuario.
                                </span>

                            </p>

                        </div>

                    </div>

                </div>

                <div class="mt-4 rounded-xl border border-slate-800 bg-[#0b1026] p-4">

                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-blue-500/20 bg-blue-500/10">
                            <i data-lucide="monitor" class="h-4 w-4 text-blue-400"></i>
                        </div>

                        <div class="min-w-0">

                            <p class="truncate text-xs font-semibold text-white" x-text="cambioEstado.nombre_equipo">
                            </p>

                            <p class="mt-0.5 truncate text-[10px] text-slate-500" x-text="cambioEstado.login"></p>

                        </div>

                    </div>

                </div>

            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-slate-800/80 px-5 py-4 sm:flex-row sm:justify-end">

                <button type="button" @click="cerrarCambioEstado()"
                    class="rounded-xl border border-slate-800 bg-[#0b1026] px-5 py-3 text-sm font-semibold text-slate-300 transition hover:bg-slate-800 hover:text-white">
                    Cancelar
                </button>

                <form method="POST" :action="'{{ url('/dispositivos') }}/' + cambioEstado.id" class="m-0">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="login" :value="cambioEstado.login">

                    <input type="hidden" name="nombre_equipo" :value="cambioEstado.nombre_equipo">

                    <input type="hidden" name="id_equipo" :value="cambioEstado.id_equipo">

                    <input type="hidden" name="estado" :value="cambioEstado.nuevoEstado">

                    <button type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-semibold text-white shadow-lg transition sm:w-auto"
                        :class="cambioEstado.nuevoEstado === 'vinculado' ?
                            'bg-gradient-to-r from-emerald-600 to-green-600 shadow-emerald-600/20 hover:from-emerald-500 hover:to-green-500' :
                            'bg-gradient-to-r from-amber-600 to-orange-600 shadow-amber-600/20 hover:from-amber-500 hover:to-orange-500'">

                        <i :data-lucide="cambioEstado.nuevoEstado === 'vinculado' ? 'link-2' : 'unlink'"
                            class="h-4 w-4"></i>

                        <span x-show="cambioEstado.nuevoEstado === 'vinculado'">
                            Vincular dispositivo
                        </span>

                        <span x-show="cambioEstado.nuevoEstado === 'desvinculado'">
                            Desvincular dispositivo
                        </span>

                    </button>

                </form>

            </div>

        </div>

    </div>

    <div x-show="modalEliminar" x-cloak x-transition.opacity
        class="fixed inset-0 z-[100002] flex items-center justify-center bg-black/80 p-4 backdrop-blur-sm"
        @keydown.escape.window="cerrarEliminar()">

        <div x-show="modalEliminar" x-transition @click.outside="cerrarEliminar()"
            class="w-full max-w-md overflow-hidden rounded-2xl border border-[#1e295d] bg-[#0f1535] shadow-2xl">

            <div class="flex items-start gap-4 border-b border-slate-800/80 px-5 py-5">

                <div
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-red-500/20 bg-red-500/10">
                    <i data-lucide="trash-2" class="h-5 w-5 text-red-400"></i>
                </div>

                <div class="min-w-0 flex-1">

                    <h2 class="text-sm font-semibold text-white">
                        Eliminar dispositivo
                    </h2>

                    <p class="mt-1 text-[11px] text-slate-500">
                        Esta acción eliminará permanentemente el dispositivo.
                    </p>

                </div>

                <button type="button" @click="cerrarEliminar()"
                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-800 hover:text-white">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>

            </div>

            <div class="px-5 py-5">

                <div class="rounded-xl border border-red-500/10 bg-red-500/5 p-4">

                    <div class="flex items-start gap-3">

                        <i data-lucide="triangle-alert" class="mt-0.5 h-4 w-4 shrink-0 text-red-400"></i>

                        <div>

                            <p class="text-xs font-semibold text-slate-200">
                                ¿Estás seguro de eliminar este dispositivo?
                            </p>

                            <p class="mt-1 text-[11px] leading-relaxed text-slate-500">
                                El dispositivo será eliminado de TicketPro y esta acción no se puede deshacer.
                            </p>

                        </div>

                    </div>

                </div>

                <div class="mt-4 rounded-xl border border-slate-800 bg-[#0b1026] p-4">

                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-blue-500/20 bg-blue-500/10">
                            <i data-lucide="monitor" class="h-4 w-4 text-blue-400"></i>
                        </div>

                        <div class="min-w-0">

                            <p class="truncate text-xs font-semibold text-white"
                                x-text="dispositivoEliminar.nombre_equipo"></p>

                            <p class="mt-0.5 truncate text-[10px] text-slate-500" x-text="dispositivoEliminar.login">
                            </p>

                            <p class="mt-0.5 truncate text-[10px] text-slate-600"
                                x-text="dispositivoEliminar.usuario"></p>

                        </div>

                    </div>

                </div>

            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-slate-800/80 px-5 py-4 sm:flex-row sm:justify-end">

                <button type="button" @click="cerrarEliminar()"
                    class="rounded-xl border border-slate-800 bg-[#0b1026] px-5 py-3 text-sm font-semibold text-slate-300 transition hover:bg-slate-800 hover:text-white">
                    Cancelar
                </button>

                <form method="POST" :action="'{{ url('/dispositivos') }}/' + dispositivoEliminar.id"
                    class="m-0">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-red-600 to-rose-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-red-600/20 transition hover:from-red-500 hover:to-rose-500 sm:w-auto">
                        <i data-lucide="trash-2" class="h-4 w-4"></i>
                        Eliminar dispositivo
                    </button>

                </form>

            </div>

        </div>

    </div>

    <script>
        function dispositivosPage() {
            return {
                menuMovil: false,

                modalEditar: false,
                modalCambioEstado: false,
                modalEliminar: false,

                busqueda: '',
                filtroEstado: 'todos',

                dispositivoSeleccionado: {
                    id: null,
                    login: '',
                    nombre_equipo: '',
                    id_equipo: '',
                    estado: 'vinculado'
                },

                cambioEstado: {
                    id: null,
                    login: '',
                    nombre_equipo: '',
                    id_equipo: '',
                    estadoActual: 'vinculado',
                    nuevoEstado: 'desvinculado'
                },

                dispositivoEliminar: {
                    id: null,
                    nombre_equipo: '',
                    login: '',
                    usuario: ''
                },

                abrirEditar(dispositivo) {
                    this.dispositivoSeleccionado = {
                        id: dispositivo.id,
                        login: dispositivo.login ?? '',
                        nombre_equipo: dispositivo.nombre_equipo ?? '',
                        id_equipo: dispositivo.id_equipo ?? '',
                        estado: this.normalizarEstado(dispositivo.estado)
                    };

                    this.modalEditar = true;

                    this.$nextTick(() => {
                        this.crearIconos();
                    });
                },

                cerrarEditar() {
                    this.modalEditar = false;

                    this.dispositivoSeleccionado = {
                        id: null,
                        login: '',
                        nombre_equipo: '',
                        id_equipo: '',
                        estado: 'vinculado'
                    };
                },

                abrirCambioEstado(dispositivo) {
                    const estadoActual = this.normalizarEstado(dispositivo.estadoActual);

                    this.cambioEstado = {
                        id: dispositivo.id,
                        login: dispositivo.login ?? '',
                        nombre_equipo: dispositivo.nombre_equipo ?? '',
                        id_equipo: dispositivo.id_equipo ?? '',
                        estadoActual: estadoActual,
                        nuevoEstado: estadoActual === 'vinculado' ?
                            'desvinculado' : 'vinculado'
                    };

                    this.modalCambioEstado = true;

                    this.$nextTick(() => {
                        this.crearIconos();
                    });
                },

                cerrarCambioEstado() {
                    this.modalCambioEstado = false;

                    this.cambioEstado = {
                        id: null,
                        login: '',
                        nombre_equipo: '',
                        id_equipo: '',
                        estadoActual: 'vinculado',
                        nuevoEstado: 'desvinculado'
                    };
                },

                abrirEliminar(dispositivo) {
                    this.dispositivoEliminar = {
                        id: dispositivo.id,
                        nombre_equipo: dispositivo.nombre_equipo ?? '',
                        login: dispositivo.login ?? '',
                        usuario: dispositivo.usuario ?? 'Sin usuario'
                    };

                    this.modalEliminar = true;

                    this.$nextTick(() => {
                        this.crearIconos();
                    });
                },

                cerrarEliminar() {
                    this.modalEliminar = false;

                    this.dispositivoEliminar = {
                        id: null,
                        nombre_equipo: '',
                        login: '',
                        usuario: ''
                    };
                },

                normalizarEstado(estado) {
                    const valor = String(estado ?? '')
                        .trim()
                        .toLowerCase();

                    return valor === 'desvinculado' ?
                        'desvinculado' :
                        'vinculado';
                },

                coincide(nombre, login, equipo, idEquipo, estado) {
                    const texto = String(this.busqueda ?? '')
                        .toLowerCase()
                        .trim();

                    const contenido = [
                            nombre,
                            login,
                            equipo,
                            idEquipo
                        ]
                        .map(valor => String(valor ?? ''))
                        .join(' ')
                        .toLowerCase();

                    const estadoNormalizado = this.normalizarEstado(estado);

                    const coincideBusqueda =
                        texto === '' ||
                        contenido.includes(texto);

                    const coincideEstado =
                        this.filtroEstado === 'todos' ||
                        estadoNormalizado === this.filtroEstado;

                    return coincideBusqueda && coincideEstado;
                },

                hayResultados() {
                    const filas = document.querySelectorAll(
                        'tbody tr[x-show]'
                    );

                    for (const fila of filas) {
                        if (fila.offsetParent !== null) {
                            return true;
                        }
                    }

                    return false;
                },

                crearIconos() {
                    if (window.lucide) {
                        lucide.createIcons();
                    }
                }
            };
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('dispositivosPage', dispositivosPage);
        });

        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) {
                lucide.createIcons();
            }
        });
    </script>
    <script>
        document.addEventListener('alpine:init', () => {

            Alpine.data('dispositivos', () => ({

                modalCambioEstado: false,

                cambioEstado: {
                    id: null,
                    login: '',
                    nombre_equipo: '',
                    id_equipo: '',
                    estadoActual: '',
                    nuevoEstado: ''
                },

                abrirCambioEstado(dispositivo) {

                    this.cambioEstado = {
                        id: dispositivo.id,
                        login: dispositivo.login,
                        nombre_equipo: dispositivo.nombre_equipo,
                        id_equipo: dispositivo.id_equipo,
                        estadoActual: dispositivo.estadoActual,
                        nuevoEstado: dispositivo.nuevoEstado
                    };

                    this.modalCambioEstado = true;

                    this.$nextTick(() => {
                        if (window.lucide) {
                            lucide.createIcons();
                        }
                    });
                },

                cerrarCambioEstado() {
                    this.modalCambioEstado = false;
                }

            }));

        });
    </script>
</body>

</html>
