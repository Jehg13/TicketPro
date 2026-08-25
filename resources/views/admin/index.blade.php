<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TicketPro - Tecnologías / Soporte</title>

    <link rel="icon" type="image/png" href="{{ asset('storage/images/logo.png') }}">

    @vite(['resources/css/app.css', 'resources/js/indexusuario.js', 'resources/js/app.js'])

    <script src="https://unpkg.com/lucide@latest"></script>

</head>

<body class="bg-[#050814] text-white font-sans min-h-screen antialiased">

    <div x-data="{ menuMovil: false }">

        <aside :class="menuMovil ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
            class="fixed inset-y-0 left-0 z-[99999] w-64 border-r border-slate-800/60 bg-[#0a0f24] p-6 flex flex-col justify-between transition-transform duration-300">

            <div>

                <div class="flex items-center justify-between mb-10">

                    <div class="flex items-center gap-2">

                        <span class="text-3xl font-extrabold tracking-wide text-white">
                            Ticket<span class="text-blue-500">Pro</span>
                        </span>

                    </div>

                    <button type="button" @click="menuMovil = false"
                        class="flex md:hidden h-9 w-9 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition">

                        <i data-lucide="x" class="w-5 h-5"></i>

                    </button>

                </div>

                <div class="flex items-center gap-3 mb-8 p-2 rounded-xl bg-slate-900/40 border border-slate-800/50">

                    <img src="{{ auth()->user()->picture
                        ? asset('storage/' . auth()->user()->picture)
                        : asset('storage/profile-photos/user.png') }}"
                        alt="{{ auth()->user()->name }}"
                        class="w-12 h-12 rounded-full border-2 border-gray-500 object-cover">

                    <div class="overflow-hidden">

                        <h4 class="text-sm font-semibold text-slate-200 truncate">
                            {{ Auth::user()->name ?? 'Desconocido' }}
                        </h4>

                        <p class="text-xs text-slate-400 truncate">
                            {{ Auth::user()->role ?? 'Desconocido' }}
                        </p>

                    </div>

                </div>

                <nav class="space-y-2">

                    <a href="{{ route('tecnologias') }}" @click="menuMovil = false"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold shadow-lg shadow-blue-600/30 transition">

                        <i data-lucide="layout-dashboard" class="w-5 h-5"></i>

                        <span class="text-sm">
                            Inicio
                        </span>

                    </a>

                    <a href="{{ route('tickettecnologias') }}" @click="menuMovil = false"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">

                        <i data-lucide="ticket-check" class="w-5 h-5"></i>

                        <span class="font-medium text-sm">
                            Tickets
                        </span>

                    </a>

                    @if (auth()->check() && auth()->user()->role === 'Gerente Ti' && auth()->user()->priv_admin === 'Y')
                        <a href="{{ route('cambiostecnologias') }}" @click="menuMovil = false"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">

                            <i data-lucide="git-compare-arrows" class="w-5 h-5"></i>

                            <span class="text-sm">
                                Cambios
                            </span>

                        </a>

                        <a href="{{ route('usuarios.tecnologias') }}" @click="menuMovil = false"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">

                            <i data-lucide="users" class="w-5 h-5"></i>

                            <span class="text-sm">
                                Usuarios
                            </span>

                        </a>
                    @endif
                    <a href="{{ route('dispositivos') }}"
                    class="flex items-center gap-3 px-4 py-3
                           rounded-xl text-slate-400
                           hover:bg-slate-800/50 hover:text-white transition">
                    <i data-lucide="monitor-smartphone" class="h-5 w-5 shrink-0"></i>
                    <span class="text-sm font-medium">Dispositivos</span>
                </a>

                    <a href="{{ route('avisostecnologias') }}" @click="menuMovil = false"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">

                        <i data-lucide="megaphone" class="w-5 h-5"></i>

                        <span class="font-medium text-sm">
                            Avisos
                        </span>

                    </a>

                    <a href="{{ route('perfiltecnologias') }}" @click="menuMovil = false"
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


        <div x-show="menuMovil" x-cloak x-transition.opacity @click="menuMovil = false"
            class="fixed inset-0 z-[99998] bg-black/70 backdrop-blur-sm md:hidden">
        </div>


        <main class="md:ml-64 min-h-screen p-4 sm:p-6 md:p-8">

            <div class="max-w-[1400px] mx-auto space-y-6">


                <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">


                    <div class="flex items-start gap-3 w-full md:w-auto">

                        <button type="button" @click="menuMovil = true"
                            class="flex md:hidden shrink-0 items-center justify-center w-10 h-10 rounded-xl bg-slate-900/80 border border-slate-800 text-slate-400 hover:text-white hover:bg-slate-800 transition">

                            <i data-lucide="menu" class="w-5 h-5"></i>

                        </button>


                        <div>

                            <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-white">
                                Tecnologías / Soporte
                            </h1>

                            <p class="text-sm text-slate-400 mt-1">
                                Dashboard de estadísticas y métricas del soporte técnico.
                            </p>

                        </div>

                    </div>


                    <div class="flex items-center gap-3 self-end md:self-auto">

<!-- NOTIFICACIONES + PERFIL -->
                <div class="flex items-center gap-4 self-end md:self-auto">

                    <div class="flex items-center gap-6">

                        <!-- NOTIFICACIONES -->

<div
    class="relative"
    x-data="{ notificacionesAbiertas: false }">

    <button
        type="button"
        @click="notificacionesAbiertas = !notificacionesAbiertas"
        @click.outside="notificacionesAbiertas = false"
        class="relative flex items-center justify-center w-10 h-10 rounded-xl bg-slate-900/80 border border-slate-800 text-slate-400 hover:text-white hover:bg-slate-800 transition-all duration-200 focus:outline-none">

        <i data-lucide="bell" class="w-5 h-5"></i>

        @if ($notificacionesNoLeidas > 0)
            <span
                class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 flex items-center justify-center rounded-full bg-indigo-600 border-2 border-[#050814] text-[9px] font-bold text-white">
                {{ $notificacionesNoLeidas > 99 ? '99+' : $notificacionesNoLeidas }}
            </span>
        @endif
    </button>

    <div
        x-show="notificacionesAbiertas"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
        @click.outside="notificacionesAbiertas = false"
        class="fixed left-2 right-2 top-16 w-auto max-w-none sm:absolute sm:left-auto sm:right-0 sm:top-full sm:mt-3 sm:w-[360px] sm:max-w-[360px] bg-[#0f1535] border border-[#1e295d] rounded-2xl shadow-2xl shadow-black/40 overflow-hidden z-[99999]"
        style="display: none;">

        <div class="flex items-center justify-between px-4 py-4 border-b border-slate-800/80 gap-3">

            <div class="flex items-center gap-2 min-w-0">

                <div
                    class="w-8 h-8 shrink-0 rounded-lg bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center">

                    <i data-lucide="bell" class="w-4 h-4 text-indigo-400"></i>

                </div>

                <div class="min-w-0">

                    <h3 class="text-sm font-semibold text-white truncate">
                        Notificaciones
                    </h3>

                    <p class="text-[10px] text-slate-500 truncate">
                        Tienes {{ $notificacionesNoLeidas }} nuevas
                    </p>

                </div>

            </div>

            @if ($notificacionesNoLeidas > 0)
                <form
                    method="POST"
                    action="{{ route('notificaciones.marcarLeidas') }}"
                    class="shrink-0">

                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        class="text-[11px] font-medium text-indigo-400 hover:text-indigo-300 transition-colors whitespace-nowrap">

                        Marcar leídas

                    </button>

                </form>
            @endif

        </div>

        <div class="max-h-[400px] overflow-y-auto">

            @forelse ($notificaciones as $notificacion)

                <a
                    href="{{ $notificacion->url ?? '#' }}"
                    class="group flex gap-3 px-4 py-4 border-b border-slate-800/50 transition-colors hover:bg-slate-800/40 {{ !$notificacion->leida ? 'bg-indigo-500/[0.04]' : '' }}">

                    <div
                        class="w-10 h-10 shrink-0 rounded-xl border border-indigo-500/20 bg-indigo-500/10 flex items-center justify-center">

                        <i
                            data-lucide="{{ $notificacion->icono ?? 'bell' }}"
                            class="w-5 h-5 text-indigo-400">
                        </i>

                    </div>

                    <div class="flex-1 min-w-0">

                        <div class="flex items-start justify-between gap-2">

                            <p
                                class="text-xs font-semibold text-white group-hover:text-indigo-400 transition-colors break-words min-w-0">
                                {{ $notificacion->titulo }}
                            </p>

                            @if (!$notificacion->leida)

                                <span
                                    class="w-2 h-2 shrink-0 mt-1.5 rounded-full bg-indigo-500">
                                </span>

                            @endif

                        </div>

                        <p class="mt-1 text-[11px] leading-relaxed text-slate-400 break-words">
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

                        <i
                            data-lucide="bell-off"
                            class="w-5 h-5 text-slate-500">
                        </i>

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

            <div class="px-4 py-3 border-t border-slate-800/80 bg-[#0b1026]">

                <p class="text-[10px] text-center text-slate-500">
                    Mostrando tus notificaciones recientes
                </p>

            </div>

        @endif

    </div>

</div>


                        <!-- PERFIL -->
                        <div
                            class="relative z-[100]"
                            x-data="{ perfilAbierto: false }">

                            <button
                                id="profile-button"
                                type="button"
                                @click="perfilAbierto = !perfilAbierto"
                                class="relative flex items-center gap-3 bg-slate-900/80 border border-slate-800 rounded-full p-1.5 pr-4 hover:bg-slate-800 transition-all duration-200 focus:outline-none">

                                <img
                                    src="{{ auth()->user()->picture
                                        ? asset('storage/' . auth()->user()->picture)
                                        : asset('storage/profile-photos/user.png') }}"
                                    alt="{{ auth()->user()->name }}"
                                    class="w-12 h-12 rounded-full border-2 border-gray-500 object-cover">

                                <div class="text-left leading-tight hidden sm:block">

                                    <p class="text-xs font-semibold text-white">
                                        {{ auth()->user()->name ?? 'Desconocido' }}
                                    </p>

                                    <p class="text-[10px] text-blue-400 font-medium">
                                        {{ auth()->user()->role ?? 'Desconocido' }}
                                    </p>

                                </div>

                                <i
                                    data-lucide="chevron-down"
                                    class="w-4 h-4 text-slate-400 ml-1 transition-transform duration-200"
                                    :class="{ 'rotate-180': perfilAbierto }">
                                </i>

                            </button>


                            <div
                                x-show="perfilAbierto"
                                @click.outside="perfilAbierto = false"
                                x-transition
                                class="absolute right-0 top-full mt-3 w-56 bg-[#0f1535] border border-[#1e295d] rounded-xl shadow-2xl overflow-hidden z-[99999]"
                                style="display:none;">

                                <a
                                    href="{{ route('perfiltecnologias') }}"
                                    class="flex items-center gap-3 px-4 py-3 text-sm text-slate-300 hover:bg-[#151b3b] hover:text-white transition-colors">

                                    <i
                                        data-lucide="circle-user-round"
                                        class="w-5 h-5 text-slate-400">
                                    </i>

                                    <span>
                                        Perfil
                                    </span>

                                </a>

                                <div class="border-t border-[#1e295d]"></div>

                                <form method="POST" action="{{ route('logout') }}">

                                    @csrf

                                    <button
                                        type="submit"
                                        class="w-full flex items-center gap-3 px-4 py-3 text-sm text-slate-300 hover:bg-red-500/10 hover:text-red-400 transition-colors text-left">

                                        <i
                                            data-lucide="log-out"
                                            class="w-5 h-5">
                                        </i>

                                        <span>
                                            Cerrar sesión
                                        </span>

                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>


                </header>
                <div class="flex justify-end gap-3">

                    <div x-data="filtroFechas()" class="relative">

                        <button type="button" @click="abierto = !abierto"
                            class="flex items-center gap-2 bg-[#0b1026] border border-slate-800/80 px-4 py-2 rounded-xl text-xs text-slate-300 hover:bg-slate-800 transition">

                            <i data-lucide="calendar" class="w-4 h-4 text-slate-400"></i>

                            <span x-text="textoFecha"></span>

                            <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-slate-400 ml-1"></i>

                        </button>

                        <div x-show="abierto" @click.outside="abierto = false" x-transition
                            class="absolute right-0 mt-2 z-50 bg-[#0b1026] border border-slate-800 rounded-xl p-4 w-72"
                            style="display:none;">

                            <div class="space-y-3">

                                <div>
                                    <label class="block text-xs text-slate-400 mb-1">
                                        Fecha inicial
                                    </label>

                                    <input type="date" x-model="fechaInicio"
                                        class="w-full bg-[#070b19] border border-slate-800 rounded-lg px-3 py-2 text-sm text-white">
                                </div>

                                <div>
                                    <label class="block text-xs text-slate-400 mb-1">
                                        Fecha final
                                    </label>

                                    <input type="date" x-model="fechaFin"
                                        class="w-full bg-[#070b19] border border-slate-800 rounded-lg px-3 py-2 text-sm text-white">
                                </div>

                                <div class="flex gap-2 pt-2">

                                    <button type="button" @click="limpiar()"
                                        class="flex-1 px-3 py-2 rounded-lg bg-slate-800 text-xs text-slate-300">
                                        Limpiar
                                    </button>

                                    <button type="button" @click="aplicar()"
                                        class="flex-1 px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-xs text-white">
                                        Aplicar
                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

                    <div class="p-4 rounded-2xl bg-[#0b1026] border border-slate-800/80">

                        <div class="flex items-center gap-3">

                            <div class="p-2.5 rounded-xl bg-blue-900/30 text-blue-400">
                                <i data-lucide="ticket" class="w-5 h-5"></i>
                            </div>

                            <div>
                                <p class="text-xs text-slate-400">
                                    Tickets abiertos
                                </p>

                                <h3 class="text-2xl font-bold text-white mt-0.5">
                                    {{ $ticketsAbiertos }}
                                </h3>
                            </div>

                        </div>

                        <p class="text-[11px] text-emerald-400 font-medium mt-3 flex items-center gap-1">
                            {{ $textoSemana }}

                            @if ($subtextoSemana)
                                <span class="text-slate-400 font-normal">
                                    {{ $subtextoSemana }}
                                </span>

                                <i data-lucide="{{ $porcentajeSemana >= 0 ? 'arrow-up-right' : 'arrow-down-right' }}"
                                    class="w-3 h-3">
                                </i>
                            @endif
                        </p>

                    </div>

                    <div class="p-4 rounded-2xl bg-[#0b1026] border border-slate-800/80">

                        <div class="flex items-center gap-3">

                            <div class="p-2.5 rounded-xl bg-purple-900/30 text-purple-400">
                                <i data-lucide="clock" class="w-5 h-5"></i>
                            </div>

                            <div>
                                <p class="text-xs text-slate-400">
                                    Tickets pendientes
                                </p>

                                <h3 class="text-2xl font-bold text-white mt-0.5">
                                    {{ $ticketsPendientes }}
                                </h3>
                            </div>

                        </div>

                        <p class="text-[11px] text-emerald-400 font-medium mt-3 flex items-center gap-1">
                            {{ $textoSemana }}

                            @if ($subtextoSemana)
                                <span class="text-slate-400 font-normal">
                                    {{ $subtextoSemana }}
                                </span>

                                <i data-lucide="{{ $porcentajeSemana >= 0 ? 'arrow-up-right' : 'arrow-down-right' }}"
                                    class="w-3 h-3">
                                </i>
                            @endif
                        </p>

                    </div>

                    <div class="p-4 rounded-2xl bg-[#0b1026] border border-slate-800/80">

                        <div class="flex items-center gap-3">

                            <div class="p-2.5 rounded-xl bg-emerald-900/30 text-emerald-400">
                                <i data-lucide="check-circle" class="w-5 h-5"></i>
                            </div>

                            <div>
                                <p class="text-xs text-slate-400">
                                    Tickets resueltos
                                </p>

                                <h3 class="text-2xl font-bold text-white mt-0.5">
                                    {{ $ticketsResueltos }}
                                </h3>
                            </div>

                        </div>

                        <p class="text-[11px] text-emerald-400 font-medium mt-3 flex items-center gap-1">
                            {{ $textoSemana }}

                            @if ($subtextoSemana)
                                <span class="text-slate-400 font-normal">
                                    {{ $subtextoSemana }}
                                </span>

                                <i data-lucide="{{ $porcentajeSemana >= 0 ? 'arrow-up-right' : 'arrow-down-right' }}"
                                    class="w-3 h-3">
                                </i>
                            @endif
                        </p>

                    </div>

                    <div class="p-4 rounded-2xl bg-[#0b1026] border border-slate-800/80">

                        <div class="flex items-center gap-3">

                            <div class="p-2.5 rounded-xl bg-amber-900/30 text-amber-400">
                                <i data-lucide="timer" class="w-5 h-5"></i>
                            </div>

                            <div>
                                <p class="text-xs text-slate-400">
                                    Tiempo promedio de atención
                                </p>

                                <h3 class="text-2xl font-bold text-white mt-0.5">
                                    {{ $tiempoPromedio }}
                                </h3>
                            </div>

                        </div>

                        <p
                            class="text-[11px] font-medium mt-3 flex items-center gap-1 {{ $porcentajeTiempo !== null && $porcentajeTiempo <= 0 ? 'text-emerald-400' : 'text-rose-400' }}">

                            {{ $textoTiempo }}

                            @if ($subtextoTiempo)
                                <span class="text-slate-400 font-normal">
                                    {{ $subtextoTiempo }}
                                </span>

                                <i data-lucide="{{ $porcentajeTiempo !== null && $porcentajeTiempo <= 0 ? 'arrow-down-right' : 'arrow-up-right' }}"
                                    class="w-3 h-3">
                                </i>
                            @endif

                        </p>

                    </div>

                    <div class="p-4 rounded-2xl bg-[#0b1026] border border-slate-800/80">

                        <div class="flex items-center gap-3">

                            <div class="p-2.5 rounded-xl bg-blue-900/30 text-blue-400">
                                <i data-lucide="bar-chart-2" class="w-5 h-5"></i>
                            </div>

                            <div>
                                <p class="text-xs text-slate-400">
                                    Tickets del mes
                                </p>

                                <h3 class="text-2xl font-bold text-white mt-0.5">
                                    {{ $ticketsMes }}
                                </h3>
                            </div>

                        </div>

                        <p
                            class="text-[11px] font-medium mt-3 flex items-center gap-1 {{ $porcentajeMes >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">

                            {{ $textoMes }}

                            @if ($subtextoMes)
                                <span class="text-slate-400 font-normal">
                                    {{ $subtextoMes }}
                                </span>

                                <i data-lucide="{{ $porcentajeMes >= 0 ? 'arrow-up-right' : 'arrow-down-right' }}"
                                    class="w-3 h-3">
                                </i>
                            @endif

                        </p>

                    </div>

                </section>

                <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <div class="p-5 rounded-2xl bg-[#0b1026] border border-slate-800/80 flex flex-col justify-between">

                        <div>

                            <div class="flex items-center gap-2 mb-1">
                                <i data-lucide="alert-triangle" class="w-4 h-4 text-blue-400"></i>

                                <h3 class="text-sm font-semibold text-white">
                                    Quejas recurrentes
                                </h3>
                            </div>

                            <p class="text-xs text-slate-400 mb-6">
                                Problemas más reportados por los usuarios.
                            </p>

                            <div class="space-y-3.5 text-xs">

                                @forelse ($quejasRecurrentes as $queja)
                                    <div>

                                        <div class="flex justify-between mb-1">

                                            <span class="text-slate-300">
                                                {{ $queja->tipo_falla }}
                                            </span>

                                            <span class="font-medium text-white">
                                                {{ $queja->total }}
                                            </span>

                                        </div>

                                        <div class="w-full bg-slate-800/60 h-2 rounded-full overflow-hidden">

                                            <div class="bg-blue-600 h-full rounded-full"
                                                style="width: {{ $queja->porcentaje }}%">
                                            </div>

                                        </div>

                                    </div>

                                @empty

                                    <div class="text-center py-6 text-slate-500">
                                        No hay datos de quejas todavía.
                                    </div>
                                @endforelse

                            </div>

                            @if ($quejasRecurrentes->count() > 0)
                                <div class="flex justify-between text-[10px] text-slate-500 mt-4 px-1">
                                    <span>0</span>
                                    <span>25%</span>
                                    <span>50%</span>
                                    <span>75%</span>
                                    <span>100%</span>
                                </div>
                            @endif

                        </div>

                        <a href="{{ route('tickettecnologias') }}"
                            class="mt-6 pt-4 border-t border-slate-800/60 flex items-center justify-center gap-1.5 text-xs text-slate-400 hover:text-white transition">

                            <span>Ver todas las quejas</span>

                            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>

                        </a>

                    </div>

                    <div class="p-5 rounded-2xl bg-[#0b1026] border border-slate-800/80 flex flex-col justify-between">

                        <div>

                            <div class="flex items-center gap-2 mb-1">

                                <i data-lucide="monitor" class="w-4 h-4 text-blue-400"></i>

                                <h3 class="text-sm font-semibold text-white">
                                    Equipo con más fallas
                                </h3>

                            </div>

                            <p class="text-xs text-slate-400 mb-4">
                                Equipos con mayor número de incidencias.
                            </p>

                            <div class="overflow-x-auto">

                                <table class="w-full text-left text-xs">

                                    <thead>

                                        <tr class="text-slate-400 border-b border-slate-800/80">

                                            <th class="pb-2 font-normal">
                                                Equipo
                                            </th>

                                            <th class="pb-2 font-normal">
                                                Tipo
                                            </th>

                                            <th class="pb-2 font-normal text-center">
                                                Fallas
                                            </th>

                                            <th class="pb-2 font-normal text-right">
                                                Última incidencia
                                            </th>

                                        </tr>

                                    </thead>

                                    <tbody class="divide-y divide-slate-800/40 text-slate-300">

                                        @forelse ($equipos as $equipo)
                                            <tr>

                                                <td class="py-2.5 flex items-center gap-2 font-medium text-white">

                                                    <i data-lucide="{{ $equipo->icono }}"
                                                        class="w-3.5 h-3.5 text-slate-400">
                                                    </i>

                                                    {{ $equipo->equipo }}

                                                </td>

                                                <td class="py-2.5 text-slate-400">
                                                    {{ $equipo->tipo }}
                                                </td>

                                                <td class="py-2.5 text-center">
                                                    {{ $equipo->fallas }}
                                                </td>

                                                <td class="py-2.5 text-right text-slate-400">
                                                    {{ $equipo->ultima_incidencia->format('d M') }}
                                                </td>

                                            </tr>

                                        @empty

                                            <tr>
                                                <td colspan="4" class="py-6 text-center text-slate-500">
                                                    No hay incidencias registradas.
                                                </td>
                                            </tr>
                                        @endforelse

                                    </tbody>

                                </table>

                            </div>

                        </div>

                        @if ($equipoMayorRecurrencia)
                            <div
                                class="mt-4 p-2.5 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center gap-2 text-xs text-amber-400">

                                <i data-lucide="alert-triangle" class="w-4 h-4 shrink-0"></i>

                                <span>
                                    Equipo con mayor recurrencia:
                                    <strong class="text-blue-400">
                                        {{ $equipoMayorRecurrencia->equipo }}
                                    </strong>
                                </span>

                            </div>
                        @endif

                    </div>

                    <div class="p-5 rounded-2xl bg-[#0b1026] border border-slate-800/80 flex flex-col justify-between">

                        <div>

                            <div class="flex items-center gap-2 mb-1">

                                <i data-lucide="map-pin" class="w-4 h-4 text-blue-400"></i>

                                <h3 class="text-sm font-semibold text-white">
                                    ¿Dónde hay más tickets?
                                </h3>

                            </div>

                            <p class="text-xs text-slate-400 mb-6">
                                Tickets generados por ubicación / sucursal.
                            </p>

                            <div class="space-y-3.5 text-xs">

                                @forelse ($ubicaciones as $ubicacion)
                                    <div>

                                        <div class="flex justify-between mb-1">

                                            <span class="text-slate-300">
                                                {{ $ubicacion->nombre }}
                                            </span>

                                            <span class="font-medium text-white">
                                                {{ $ubicacion->total }}
                                            </span>

                                        </div>

                                        <div class="w-full bg-slate-800/60 h-2 rounded-full overflow-hidden">

                                            <div class="bg-cyan-500 h-full rounded-full"
                                                style="width: {{ $ubicacion->porcentaje }}%">
                                            </div>

                                        </div>

                                    </div>

                                @empty

                                    <div class="py-6 text-center text-slate-500">
                                        No hay tickets registrados.
                                    </div>
                                @endforelse

                            </div>

                            <div class="flex justify-between text-[10px] text-slate-500 mt-4 px-1">
                                <span>0</span>
                                <span>20</span>
                                <span>40</span>
                                <span>60</span>
                                <span>80</span>
                                <span>100</span>
                            </div>

                        </div>

                    </div>

                </section>

                <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <div class="lg:col-span-2 p-5 rounded-2xl bg-[#0b1026] border border-slate-800/80 flex flex-col justify-between"
                        x-data="evolucionTickets()" x-init="cargarPeriodo('semana')">

                        <div>

                            <!-- HEADER -->
                            <div class="flex items-center justify-between mb-1">

                                <div class="flex items-center gap-2">

                                    <i data-lucide="trending-up" class="w-4 h-4 text-blue-400"></i>

                                    <h3 class="text-sm font-semibold text-white">
                                        Evolución de tickets
                                    </h3>

                                </div>

                                <!-- PERIODOS -->
                                <div
                                    class="flex items-center bg-slate-900 border border-slate-800 rounded-lg p-0.5 text-xs text-slate-400">

                                    <button type="button" @click="cargarPeriodo('hoy')"
                                        :class="periodo === 'hoy'
                                            ?
                                            'bg-blue-600 text-white font-medium' :
                                            'hover:text-white'"
                                        class="px-2.5 py-1 rounded-md transition">
                                        Hoy
                                    </button>

                                    <button type="button" @click="cargarPeriodo('semana')"
                                        :class="periodo === 'semana'
                                            ?
                                            'bg-blue-600 text-white font-medium' :
                                            'hover:text-white'"
                                        class="px-2.5 py-1 rounded-md transition">
                                        Semana
                                    </button>

                                    <button type="button" @click="cargarPeriodo('mes')"
                                        :class="periodo === 'mes'
                                            ?
                                            'bg-blue-600 text-white font-medium' :
                                            'hover:text-white'"
                                        class="px-2.5 py-1 rounded-md transition">
                                        Mes
                                    </button>

                                    <button type="button" @click="cargarPeriodo('año')"
                                        :class="periodo === 'año'
                                            ?
                                            'bg-blue-600 text-white font-medium' :
                                            'hover:text-white'"
                                        class="px-2.5 py-1 rounded-md transition">
                                        Año
                                    </button>

                                </div>

                            </div>

                            <p class="text-xs text-slate-400 mb-6">
                                Comportamiento de tickets en el periodo seleccionado.
                            </p>


                            <!-- GRAFICA -->
                            <div class="relative w-full h-44">

                                <!-- LINEAS DE REFERENCIA -->
                                <div
                                    class="absolute inset-0 flex flex-col justify-between text-[10px] text-slate-600 pointer-events-none">

                                    <div class="border-b border-slate-800/60 w-full">
                                        <span x-text="maxGrafica"></span>
                                    </div>

                                    <div class="border-b border-slate-800/60 w-full">
                                        <span x-text="Math.round(maxGrafica * 0.75)"></span>
                                    </div>

                                    <div class="border-b border-slate-800/60 w-full">
                                        <span x-text="Math.round(maxGrafica * 0.50)"></span>
                                    </div>

                                    <div class="border-b border-slate-800/60 w-full">
                                        <span x-text="Math.round(maxGrafica * 0.25)"></span>
                                    </div>

                                    <div class="w-full">
                                        <span>0</span>
                                    </div>

                                </div>


                                <!-- SVG -->
                                <svg class="absolute inset-0 w-full h-full overflow-visible" viewBox="0 0 600 120"
                                    preserveAspectRatio="none" aria-hidden="true">

                                    <!-- LINEA -->
                                    <polyline
                                        :points="puntosGrafica
                                            .map(p => `${p.x},${p.y}`)
                                            .join(' ')"
                                        fill="none" stroke="#2563eb" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round"></polyline>


                                    <!-- PUNTOS -->
                                    <g
                                        x-html="puntosGrafica.map(p => `
                            <circle
                                cx='${p.x}'
                                cy='${p.y}'
                                r='4'
                                fill='#2563eb'
                            ></circle>
                        `).join('')">
                                    </g>

                                </svg>

                            </div>


                            <!-- FECHAS -->
                            <div class="relative mt-2 px-6 overflow-hidden">

                                <div class="flex justify-between text-[11px] text-slate-400 w-full">

                                    <template x-for="(dia, index) in evolucionTickets" :key="index">

                                        <span x-text="dia.fecha" class="whitespace-nowrap"
                                            :class="{
                                                'hidden': periodo === 'hoy' &&
                                                    index % 3 !== 0
                                            }"></span>

                                    </template>

                                </div>

                            </div>


                            <!-- ESTADISTICAS -->
                            <div class="grid grid-cols-3 gap-4 pt-4 mt-6 border-t border-slate-800/60 text-xs">

                                <!-- PROMEDIO -->
                                <div class="flex items-center gap-3">

                                    <div class="p-2 rounded-xl bg-blue-500/10 text-blue-400">

                                        <i data-lucide="target" class="w-4 h-4"></i>

                                    </div>

                                    <div>

                                        <p class="text-slate-400 text-[11px]">
                                            Promedio
                                        </p>

                                        <p class="text-lg font-bold text-white" x-text="promedioEvolucion"></p>

                                    </div>

                                </div>


                                <!-- MAXIMO -->
                                <div class="flex items-center gap-3">

                                    <div class="p-2 rounded-xl bg-blue-500/10 text-blue-400">

                                        <i data-lucide="trending-up" class="w-4 h-4"></i>

                                    </div>

                                    <div>

                                        <p class="text-slate-400 text-[11px]">
                                            Máximo
                                        </p>

                                        <p class="text-lg font-bold text-white" x-text="maximoEvolucion"></p>

                                    </div>

                                </div>


                                <!-- MINIMO -->
                                <div class="flex items-center gap-3">

                                    <div class="p-2 rounded-xl bg-rose-500/10 text-rose-400">

                                        <i data-lucide="trending-down" class="w-4 h-4"></i>

                                    </div>

                                    <div>

                                        <p class="text-slate-400 text-[11px]">
                                            Mínimo
                                        </p>

                                        <p class="text-lg font-bold text-white" x-text="minimoEvolucion"></p>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>

            </div>

        </main>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        </script>

</body>

</html>
