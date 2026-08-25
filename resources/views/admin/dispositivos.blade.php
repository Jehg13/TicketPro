<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TicketPro - Dispositivos</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body x-data="{ menuMovil: false }" class="min-h-screen bg-[#070b19] text-white font-sans antialiased">

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

    <main class="min-h-screen px-4 pb-6 pt-20 sm:px-6 sm:pb-8 sm:pt-20 lg:px-8 lg:py-8 md:ml-[280px] md:pt-8">

        <div class="mx-auto max-w-[1500px]">

            <button type="button" @click="menuMovil = true"
                class="fixed left-4 top-4 z-[99997] flex h-10 w-10 items-center justify-center rounded-xl border border-slate-800 bg-[#0f1535] text-slate-300 transition hover:bg-slate-800 hover:text-white md:hidden">
                <i data-lucide="menu" class="h-5 w-5"></i>
            </button>

            <header class="mb-8 flex flex-col gap-5 md:flex-row md:items-center md:justify-between">

                <div>
                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-11 w-11 items-center justify-center rounded-xl border border-blue-500/20 bg-blue-500/10">
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

                <div class="flex items-center gap-3 self-end sm:gap-4 md:self-auto" x-data="{ perfilAbierto: false }">

                    <div class="relative" x-data="{ notificacionesAbiertas: false }">

                        <button type="button" @click="notificacionesAbiertas = !notificacionesAbiertas"
                            class="relative flex h-10 w-10 items-center justify-center rounded-xl border border-slate-800 bg-slate-900/80 text-slate-400 transition hover:bg-slate-800 hover:text-white">
                            <i data-lucide="bell" class="h-5 w-5"></i>

                            @if (($notificacionesNoLeidas ?? 0) > 0)
                                <span
                                    class="absolute -right-1 -top-1 flex h-[18px] min-w-[18px] items-center justify-center rounded-full border-2 border-[#050814] bg-indigo-600 px-1 text-[9px] font-bold text-white">
                                    {{ $notificacionesNoLeidas > 99 ? '99+' : $notificacionesNoLeidas }}
                                </span>
                            @endif
                        </button>

                        <div x-show="notificacionesAbiertas" x-cloak @click.outside="notificacionesAbiertas = false"
                            x-transition
                            class="absolute right-0 top-full z-[99999] mt-3 w-[340px] max-w-[calc(100vw-32px)] overflow-hidden rounded-2xl border border-[#1e295d] bg-[#0f1535] shadow-2xl">

                            <div class="border-b border-slate-800/80 px-4 py-4">
                                <div class="flex items-center gap-3">

                                    <div
                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-indigo-500/20 bg-indigo-500/10">
                                        <i data-lucide="bell" class="h-4 w-4 text-indigo-400"></i>
                                    </div>

                                    <div>
                                        <h3 class="text-sm font-semibold text-white">
                                            Notificaciones
                                        </h3>

                                        <p class="text-[10px] text-slate-500">
                                            Tienes {{ $notificacionesNoLeidas ?? 0 }} nuevas
                                        </p>
                                    </div>

                                </div>
                            </div>

                            <div class="max-h-[350px] overflow-y-auto">

                                @forelse (($notificaciones ?? collect()) as $notificacion)
                                    <a href="{{ $notificacion->url ?? '#' }}"
                                        class="flex gap-3 border-b border-slate-800/50 px-4 py-4 transition hover:bg-slate-800/40">

                                        <div
                                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-indigo-500/20 bg-indigo-500/10">
                                            <i data-lucide="{{ $notificacion->icono ?? 'bell' }}"
                                                class="h-4 w-4 text-indigo-400"></i>
                                        </div>

                                        <div class="min-w-0">

                                            <p class="text-xs font-semibold text-white">
                                                {{ $notificacion->titulo }}
                                            </p>

                                            <p class="mt-1 text-[11px] text-slate-400">
                                                {{ $notificacion->mensaje }}
                                            </p>

                                            <p class="mt-2 text-[10px] text-slate-600">
                                                {{ $notificacion->created_at->diffForHumans() }}
                                            </p>

                                        </div>

                                    </a>

                                @empty

                                    <div class="px-6 py-10 text-center">

                                        <div
                                            class="mx-auto mb-3 flex h-11 w-11 items-center justify-center rounded-full bg-slate-800/50">
                                            <i data-lucide="bell-off" class="h-5 w-5 text-slate-500"></i>
                                        </div>

                                        <p class="text-xs text-slate-400">
                                            No tienes notificaciones
                                        </p>

                                    </div>
                                @endforelse

                            </div>
                        </div>
                    </div>

                    <button type="button" @click="perfilAbierto = !perfilAbierto"
                        class="flex items-center gap-2 rounded-full border border-slate-800 bg-slate-900/80 p-1.5 pr-3 transition hover:bg-slate-800">

                        <img src="{{ auth()->user()->picture
                            ? asset('storage/' . auth()->user()->picture)
                            : asset('storage/profile-photos/user.png') }}"
                            alt="{{ auth()->user()->name }}"
                            class="h-9 w-9 rounded-full border-2 border-gray-500 object-cover">

                        <div class="hidden text-left sm:block">
                            <p class="max-w-[120px] truncate text-xs font-semibold text-white">
                                {{ auth()->user()->name ?? 'Desconocido' }}
                            </p>

                            <p class="text-[10px] font-medium text-blue-400">
                                {{ auth()->user()->role ?? 'Desconocido' }}
                            </p>
                        </div>

                        <i data-lucide="chevron-down"
                            class="hidden h-4 w-4 text-slate-400 transition-transform sm:block"
                            :class="{ 'rotate-180': perfilAbierto }"></i>

                    </button>

                    <div x-show="perfilAbierto" x-cloak @click.outside="perfilAbierto = false" x-transition
                        class="absolute right-4 top-20 z-[99999] w-56 overflow-hidden rounded-xl border border-[#1e295d] bg-[#0f1535] shadow-2xl">

                        <a href="{{ route('perfiltecnologias') }}"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-slate-300 transition hover:bg-[#151b3b] hover:text-white">
                            <i data-lucide="circle-user-round" class="h-5 w-5"></i>
                            <span>Perfil</span>
                        </a>

                        <div class="border-t border-[#1e295d]"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button type="submit"
                                class="flex w-full items-center gap-3 px-4 py-3 text-left text-sm text-slate-300 transition hover:bg-red-500/10 hover:text-red-400">
                                <i data-lucide="log-out" class="h-5 w-5"></i>
                                <span>Cerrar sesión</span>
                            </button>
                        </form>

                    </div>

                </div>

            </header>

            <div class="grid grid-cols-1 gap-6 xl:grid-cols-12">

                <section class="xl:col-span-4">

                    <div class="overflow-hidden rounded-2xl border border-[#1e295d] bg-[#0f1535] shadow-xl">

                        <div class="border-b border-slate-800/80 px-5 py-5">

                            <div class="flex items-center gap-3">

                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-xl border border-blue-500/20 bg-blue-500/10">
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
                                            <option value="{{ $usuario->login }}">
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

                <section class="xl:col-span-8">

                    <div class="overflow-hidden rounded-2xl border border-[#1e295d] bg-[#0f1535] shadow-xl">

                        <div class="border-b border-slate-800/80 px-5 py-5">

                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                                <div>
                                    <h2 class="text-sm font-semibold text-white">
                                        Dispositivos registrados
                                    </h2>

                                    <p class="mt-1 text-[11px] text-slate-500">
                                        Equipos actualmente vinculados
                                    </p>
                                </div>

                                <div class="flex items-center gap-2">

                                    <div
                                        class="flex items-center gap-2 rounded-lg border border-slate-800 bg-[#0b1026] px-3 py-2">

                                        <i data-lucide="monitor" class="h-4 w-4 text-blue-400"></i>

                                        <span class="text-xs font-semibold text-slate-300">
                                            {{ $dispositivos->count() }}
                                        </span>

                                        <span class="text-[10px] text-slate-600">
                                            equipos
                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="overflow-x-auto">

                            <table class="w-full text-left">

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
                                        <tr class="transition hover:bg-slate-800/20">

                                            <td class="px-5 py-4">

                                                <div class="flex items-center gap-3">

                                                    <div
                                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-blue-500/20 bg-blue-500/10">
                                                        <i data-lucide="user" class="h-4 w-4 text-blue-400"></i>
                                                    </div>

                                                    <div class="min-w-0">

                                                        <p class="truncate text-xs font-semibold text-white">
                                                            {{ $dispositivo->usuario->name ?? 'Sin usuario' }}
                                                        </p>

                                                        <p class="truncate text-[10px] text-slate-500">
                                                            {{ $dispositivo->login }}
                                                        </p>

                                                    </div>

                                                </div>

                                            </td>

                                            <td class="px-5 py-4">

                                                <div class="flex items-center gap-2">

                                                    <i data-lucide="monitor" class="h-4 w-4 text-slate-500"></i>

                                                    <span class="text-xs text-slate-300">
                                                        {{ $dispositivo->nombre_equipo }}
                                                    </span>

                                                </div>

                                            </td>

                                            <td class="px-5 py-4">

                                                <span
                                                    class="inline-flex items-center rounded-lg border border-slate-800 bg-[#0b1026] px-3 py-1.5 font-mono text-[10px] text-slate-400">
                                                    {{ $dispositivo->id_equipo }}
                                                </span>

                                            </td>

                                            <td class="px-5 py-4 text-right">

                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full border border-emerald-500/20 bg-emerald-500/10 px-2.5 py-1 text-[10px] font-semibold text-emerald-400">

                                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>

                                                    Vinculado

                                                </span>

                                            </td>
                                            <td class="px-5 py-4">
                                                <div class="flex items-center justify-end gap-2">

                                                    <button type="button"
                                                        @click="abrirEditar({
                                                            id: {{ $dispositivo->id }},
                                                            login: @js($dispositivo->login),
                                                            nombre_equipo: @js($dispositivo->nombre_equipo),
                                                            id_equipo: @js($dispositivo->id_equipo)
                                                        })"
                                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-blue-500/20 bg-blue-500/10 text-blue-400 transition hover:border-blue-500/40 hover:bg-blue-500/20 hover:text-blue-300"
                                                        title="Editar dispositivo">
                                                        <i data-lucide="pencil" class="h-4 w-4"></i>
                                                    </button>

                                                    {{-- Eliminar --}}
                                                    <form method="POST"
                                                        action="{{ route('dispositivos.destroy', $dispositivo->id) }}"
                                                        onsubmit="return confirm('¿Estás seguro de eliminar este dispositivo?');">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit"
                                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-red-500/20 bg-red-500/10 text-red-400 transition hover:border-red-500/40 hover:bg-red-500/20 hover:text-red-300"
                                                            title="Eliminar dispositivo">
                                                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                                                        </button>
                                                    </form>

                                                </div>
                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td colspan="4" class="px-5 py-16">

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

                    </div>

                </section>

            </div>

        </div>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.lucide) {
                lucide.createIcons();
            }
        });
    </script>

</body>

</html>
