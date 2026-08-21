<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TicketPro - Usuarios</title>

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

            {{-- LOGO --}}
            <div class="flex items-center gap-2 mb-10">

                <span class="text-3xl font-extrabold tracking-wide text-white">
                    Ticket<span class="text-blue-500">Pro</span>
                </span>

            </div>


            {{-- USUARIO ACTUAL --}}

            <div class="flex items-center gap-3 mb-8 p-2 rounded-xl bg-slate-900/40 border border-slate-800/50">

                <img src="{{ auth()->user()->picture
                    ? asset('storage/' . auth()->user()->picture)
                    : asset('storage/profile-photos/user.png') }}"
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
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">

                    <i data-lucide="git-compare-arrows" class="w-5 h-5"></i>

                    <span class="font-medium text-sm">
                        Cambios
                    </span>

                </a>


                {{-- USUARIOS ACTIVO --}}

                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold shadow-lg shadow-blue-600/30">

                    <i data-lucide="users" class="w-5 h-5"></i>

                    <span class="text-sm">
                        Usuarios
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

            <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">

                <div>

                    <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-white">
                        Usuarios
                    </h1>

                    <p class="text-sm text-slate-400 mt-1">
                        Consulta y administra la información de los usuarios del sistema
                    </p>

                </div>


                {{-- PERFIL --}}

                <div class="relative" x-data="{ perfilAbierto: false }">

                    <button type="button" @click="perfilAbierto = !perfilAbierto"
                        class="relative flex items-center gap-3 bg-slate-900/80 border border-slate-800 rounded-full p-1.5 pr-4 hover:bg-slate-800 transition-all duration-200">

                        <img src="{{ auth()->user()->picture
                            ? asset('storage/' . auth()->user()->picture)
                            : asset('storage/profile-photos/user.png') }}"
                            class="w-8 h-8 rounded-full object-cover">

                        <div class="text-left leading-tight hidden sm:block">

                            <p class="text-xs font-semibold text-white">
                                {{ auth()->user()->name ?? 'Desconocido' }}
                            </p>

                            <p class="text-[10px] text-blue-400 font-medium">
                                {{ optional(auth()->user()->departamento)->nombre ?? 'Sin departamento' }}
                            </p>

                        </div>

                        <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform"
                            :class="{ 'rotate-180': perfilAbierto }"></i>

                    </button>


                    <div x-show="perfilAbierto" @click.outside="perfilAbierto = false" x-transition
                        class="absolute right-0 top-full mt-3 w-56 bg-[#0f1535] border border-[#1e295d] rounded-xl shadow-2xl overflow-hidden z-[99999]"
                        style="display:none;">

                        <a href="{{ route('perfiltecnologias') }}"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-slate-300 hover:bg-[#151b3b] hover:text-white transition">

                            <i data-lucide="circle-user-round" class="w-5 h-5"></i>

                            Perfil

                        </a>


                        <div class="border-t border-[#1e295d]"></div>


                        <form method="POST" action="{{ route('logout') }}">

                            @csrf

                            <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-3 text-sm text-slate-300 hover:bg-red-500/10 hover:text-red-400 transition text-left">

                                <i data-lucide="log-out" class="w-5 h-5"></i>

                                Cerrar sesión

                            </button>

                        </form>

                    </div>

                </div>

            </header>



            {{-- ========================================================= --}}
            {{-- TARJETAS --}}
            {{-- ========================================================= --}}

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">


                {{-- TOTAL --}}

                <div
                    class="bg-[#0b1026]/90 border border-blue-900/40 rounded-2xl p-5 flex items-center justify-between shadow-xl">

                    <div>

                        <p class="text-xs text-slate-400 font-medium mb-1">
                            Total de usuarios
                        </p>

                        <h3 class="text-2xl font-bold text-white">
                            248
                        </h3>

                    </div>

                    <div
                        class="w-11 h-11 bg-blue-500/10 text-blue-400 rounded-xl flex items-center justify-center border border-blue-500/20">

                        <i data-lucide="users" class="w-5 h-5"></i>

                    </div>

                </div>



                {{-- ACTIVOS --}}

                <div
                    class="bg-[#0b1026]/90 border border-blue-900/40 rounded-2xl p-5 flex items-center justify-between shadow-xl">

                    <div>

                        <p class="text-xs text-slate-400 font-medium mb-1">
                            Cuentas activas
                        </p>

                        <h3 class="text-2xl font-bold text-white">
                            231
                        </h3>

                    </div>

                    <div
                        class="w-11 h-11 bg-emerald-500/10 text-emerald-400 rounded-xl flex items-center justify-center border border-emerald-500/20">

                        <i data-lucide="user-check" class="w-5 h-5"></i>

                    </div>

                </div>



                {{-- INACTIVOS --}}

                <div
                    class="bg-[#0b1026]/90 border border-blue-900/40 rounded-2xl p-5 flex items-center justify-between shadow-xl">

                    <div>

                        <p class="text-xs text-slate-400 font-medium mb-1">
                            Cuentas inactivas
                        </p>

                        <h3 class="text-2xl font-bold text-white">
                            17
                        </h3>

                    </div>

                    <div
                        class="w-11 h-11 bg-rose-500/10 text-rose-400 rounded-xl flex items-center justify-center border border-rose-500/20">

                        <i data-lucide="user-x" class="w-5 h-5"></i>

                    </div>

                </div>



                {{-- ADMINISTRADORES --}}

                <div
                    class="bg-[#0b1026]/90 border border-blue-900/40 rounded-2xl p-5 flex items-center justify-between shadow-xl">

                    <div>

                        <p class="text-xs text-slate-400 font-medium mb-1">
                            Administradores
                        </p>

                        <h3 class="text-2xl font-bold text-white">
                            8
                        </h3>

                    </div>

                    <div
                        class="w-11 h-11 bg-indigo-500/10 text-indigo-400 rounded-xl flex items-center justify-center border border-indigo-500/20">

                        <i data-lucide="shield-check" class="w-5 h-5"></i>

                    </div>

                </div>

            </div>



            {{-- ========================================================= --}}
            {{-- FILTROS --}}
            {{-- ========================================================= --}}

            <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4 mb-6">


                {{-- FILTROS --}}

                <div class="flex flex-wrap items-center gap-1 bg-[#0b1026] p-1.5 rounded-xl border border-slate-800">

    <button
        class="px-4 py-2 rounded-lg text-xs font-semibold bg-blue-600 text-white transition-all">
        Todos
    </button>

    <button
        class="px-4 py-2 rounded-lg text-xs font-semibold text-slate-400 hover:text-white hover:bg-slate-800/60 transition-all">
        Activos
    </button>

    <button
        class="px-4 py-2 rounded-lg text-xs font-semibold text-slate-400 hover:text-white hover:bg-slate-800/60 transition-all">
        Inactivos
    </button>

    <div class="w-px h-5 bg-slate-700 mx-1"></div>

    <button
        class="px-4 py-2 rounded-lg text-xs font-semibold text-slate-400 hover:text-white hover:bg-slate-800/60 transition-all flex items-center gap-2">
        <i data-lucide="user" class="w-3.5 h-3.5"></i>
        Usuario
    </button>

    <button
        class="px-4 py-2 rounded-lg text-xs font-semibold text-slate-400 hover:text-white hover:bg-slate-800/60 transition-all flex items-center gap-2">
        <i data-lucide="headphones" class="w-3.5 h-3.5"></i>
        Técnico
    </button>

    <button
        class="px-4 py-2 rounded-lg text-xs font-semibold text-slate-400 hover:text-white hover:bg-slate-800/60 transition-all flex items-center gap-2">
        <i data-lucide="briefcase-business" class="w-3.5 h-3.5"></i>
        Gerente TI
    </button>

</div>



                {{-- BUSCADOR --}}

                <div class="relative w-full xl:w-72">

                    <i data-lucide="search" class="absolute left-3.5 top-3 w-4 h-4 text-slate-500"></i>

                    <input type="text" placeholder="Buscar usuario..."
                        class="w-full bg-[#0b1026] border border-slate-800 text-slate-200 text-xs rounded-xl pl-10 pr-4 py-2.5 focus:outline-none focus:border-blue-500">

                </div>

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
                                        Usuario
                                    </th>

                                    <th class="pb-3 font-semibold">
                                        Rol
                                    </th>

                                    <th class="pb-3 font-semibold text-center">
                                        Estado
                                    </th>

                                    <th class="pb-3 font-semibold">
                                        Departamento
                                    </th>

                                    <th class="pb-3 text-center">
                                        Acción
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-slate-800/50 text-slate-300">


                                {{-- USUARIO 1 --}}

                                <tr class="hover:bg-slate-800/20 transition bg-blue-500/5">

                                    <td class="py-4">

                                        <div class="flex items-center gap-3">

                                            <img src="{{ asset('storage/profile-photos/user.png') }}"
                                                class="w-9 h-9 rounded-full object-cover ring-2 ring-blue-500/20">

                                            <div class="min-w-0">

                                                <p class="text-white font-medium truncate">
                                                    Juan Pérez
                                                </p>

                                                <p class="text-[10px] text-slate-500 truncate">
                                                    juan.perez@empresa.com
                                                </p>

                                            </div>

                                        </div>

                                    </td>


                                    <td class="py-4">

                                        <span
                                            class="px-2.5 py-1 rounded-full text-[10px] font-medium bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">

                                            Usuario

                                        </span>

                                    </td>


                                    <td class="py-4 text-center">

                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">

                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>

                                            Activa

                                        </span>

                                    </td>


                                    <td class="py-4 text-slate-400">

                                        Administración

                                    </td>


                                    <td class="py-4">
                                        <div class="flex items-center justify-center gap-2">

                                            {{-- VER --}}
                                            <a href="" title="Ver solicitud"
                                                class="w-8 h-8 rounded-lg flex items-center justify-center
                   bg-slate-800/70 text-slate-400
                   hover:bg-blue-500/10 hover:text-blue-400
                   border border-slate-700/50
                   transition-all duration-200">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                            </a>

                                            {{-- EDITAR --}}
                                            <a href="" title="Editar solicitud"
                                                class="w-8 h-8 rounded-lg flex items-center justify-center
                   bg-slate-800/70 text-slate-400
                   hover:bg-amber-500/10 hover:text-amber-400
                   border border-slate-700/50
                   transition-all duration-200">
                                                <i data-lucide="pencil" class="w-4 h-4"></i>
                                            </a>

                                            {{-- ELIMINAR --}}
                                            <form method="POST" action=""
                                                onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta solicitud?')">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" title="Eliminar solicitud"
                                                    class="w-8 h-8 rounded-lg flex items-center justify-center
                       bg-slate-800/70 text-slate-400
                       hover:bg-rose-500/10 hover:text-rose-400
                       border border-slate-700/50
                       transition-all duration-200">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>

                                </tr>



                                {{-- USUARIO 2 --}}

                                <tr class="hover:bg-slate-800/20 transition">

                                    <td class="py-4">

                                        <div class="flex items-center gap-3">

                                            <img src="{{ asset('storage/profile-photos/user.png') }}"
                                                class="w-9 h-9 rounded-full object-cover">

                                            <div class="min-w-0">

                                                <p class="text-white font-medium truncate">
                                                    Carlos Perales
                                                </p>

                                                <p class="text-[10px] text-slate-500 truncate">
                                                    carlos.perales@empresa.com
                                                </p>

                                            </div>

                                        </div>

                                    </td>


                                    <td class="py-4">

                                        <span
                                            class="px-2.5 py-1 rounded-full text-[10px] font-medium bg-blue-500/10 text-blue-400 border border-blue-500/20">

                                            Técnico

                                        </span>

                                    </td>


                                    <td class="py-4 text-center">

                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">

                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>

                                            Activa

                                        </span>

                                    </td>


                                    <td class="py-4 text-slate-400">

                                        Tecnologías

                                    </td>


                                    <td class="py-4">
                                        <div class="flex items-center justify-center gap-2">

                                            {{-- VER --}}
                                            <a href="" title="Ver solicitud"
                                                class="w-8 h-8 rounded-lg flex items-center justify-center
                   bg-slate-800/70 text-slate-400
                   hover:bg-blue-500/10 hover:text-blue-400
                   border border-slate-700/50
                   transition-all duration-200">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                            </a>

                                            {{-- EDITAR --}}
                                            <a href="" title="Editar solicitud"
                                                class="w-8 h-8 rounded-lg flex items-center justify-center
                   bg-slate-800/70 text-slate-400
                   hover:bg-amber-500/10 hover:text-amber-400
                   border border-slate-700/50
                   transition-all duration-200">
                                                <i data-lucide="pencil" class="w-4 h-4"></i>
                                            </a>

                                            {{-- ELIMINAR --}}
                                            <form method="POST" action=""
                                                onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta solicitud?')">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" title="Eliminar solicitud"
                                                    class="w-8 h-8 rounded-lg flex items-center justify-center
                       bg-slate-800/70 text-slate-400
                       hover:bg-rose-500/10 hover:text-rose-400
                       border border-slate-700/50
                       transition-all duration-200">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>

                                </tr>



                                {{-- USUARIO 3 --}}

                                <tr class="hover:bg-slate-800/20 transition">

                                    <td class="py-4">

                                        <div class="flex items-center gap-3">

                                            <img src="{{ asset('storage/profile-photos/user.png') }}"
                                                class="w-9 h-9 rounded-full object-cover">

                                            <div class="min-w-0">

                                                <p class="text-white font-medium truncate">
                                                    María González
                                                </p>

                                                <p class="text-[10px] text-slate-500 truncate">
                                                    maria.gonzalez@empresa.com
                                                </p>

                                            </div>

                                        </div>

                                    </td>


                                    <td class="py-4">

                                        <span
                                            class="px-2.5 py-1 rounded-full text-[10px] font-medium bg-purple-500/10 text-purple-400 border border-purple-500/20">

                                            Gerente

                                        </span>

                                    </td>


                                    <td class="py-4 text-center">

                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-medium bg-rose-500/10 text-rose-400 border border-rose-500/20">

                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>

                                            Inactiva

                                        </span>

                                    </td>


                                    <td class="py-4 text-slate-400">

                                        Recursos Humanos

                                    </td>


                                    <td class="py-4">
                                        <div class="flex items-center justify-center gap-2">

                                            {{-- VER --}}
                                            <a href="" title="Ver solicitud"
                                                class="w-8 h-8 rounded-lg flex items-center justify-center
                   bg-slate-800/70 text-slate-400
                   hover:bg-blue-500/10 hover:text-blue-400
                   border border-slate-700/50
                   transition-all duration-200">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                            </a>

                                            {{-- EDITAR --}}
                                            <a href="" title="Editar solicitud"
                                                class="w-8 h-8 rounded-lg flex items-center justify-center
                   bg-slate-800/70 text-slate-400
                   hover:bg-amber-500/10 hover:text-amber-400
                   border border-slate-700/50
                   transition-all duration-200">
                                                <i data-lucide="pencil" class="w-4 h-4"></i>
                                            </a>

                                            {{-- ELIMINAR --}}
                                            <form method="POST" action=""
                                                onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta solicitud?')">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" title="Eliminar solicitud"
                                                    class="w-8 h-8 rounded-lg flex items-center justify-center
                       bg-slate-800/70 text-slate-400
                       hover:bg-rose-500/10 hover:text-rose-400
                       border border-slate-700/50
                       transition-all duration-200">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>

                                </tr>



                                {{-- USUARIO 4 --}}

                                <tr class="hover:bg-slate-800/20 transition">

                                    <td class="py-4">

                                        <div class="flex items-center gap-3">

                                            <img src="{{ asset('storage/profile-photos/user.png') }}"
                                                class="w-9 h-9 rounded-full object-cover">

                                            <div class="min-w-0">

                                                <p class="text-white font-medium truncate">
                                                    Roberto Hernández
                                                </p>

                                                <p class="text-[10px] text-slate-500 truncate">
                                                    roberto.h@empresa.com
                                                </p>

                                            </div>

                                        </div>

                                    </td>


                                    <td class="py-4">

                                        <span
                                            class="px-2.5 py-1 rounded-full text-[10px] font-medium bg-amber-500/10 text-amber-400 border border-amber-500/20">

                                            Supervisor

                                        </span>

                                    </td>


                                    <td class="py-4 text-center">

                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">

                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>

                                            Activa

                                        </span>

                                    </td>


                                    <td class="py-4 text-slate-400">

                                        Operaciones

                                    </td>


                                    <td class="py-4">
                                        <div class="flex items-center justify-center gap-2">

                                            {{-- VER --}}
                                            <a href="" title="Ver solicitud"
                                                class="w-8 h-8 rounded-lg flex items-center justify-center
                   bg-slate-800/70 text-slate-400
                   hover:bg-blue-500/10 hover:text-blue-400
                   border border-slate-700/50
                   transition-all duration-200">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                            </a>

                                            {{-- EDITAR --}}
                                            <a href="" title="Editar solicitud"
                                                class="w-8 h-8 rounded-lg flex items-center justify-center
                   bg-slate-800/70 text-slate-400
                   hover:bg-amber-500/10 hover:text-amber-400
                   border border-slate-700/50
                   transition-all duration-200">
                                                <i data-lucide="pencil" class="w-4 h-4"></i>
                                            </a>

                                            {{-- ELIMINAR --}}
                                            <form method="POST" action=""
                                                onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta solicitud?')">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" title="Eliminar solicitud"
                                                    class="w-8 h-8 rounded-lg flex items-center justify-center
                       bg-slate-800/70 text-slate-400
                       hover:bg-rose-500/10 hover:text-rose-400
                       border border-slate-700/50
                       transition-all duration-200">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>



                    {{-- PAGINACIÓN --}}

                    <div
                        class="mt-6 pt-5 border-t border-slate-800/80 flex flex-col sm:flex-row justify-between items-center gap-4">

                        <span class="text-xs text-slate-400">

                            Mostrando

                            <span class="text-white font-medium">
                                1
                            </span>

                            a

                            <span class="text-white font-medium">
                                10
                            </span>

                            de

                            <span class="text-white font-medium">
                                248
                            </span>

                            usuarios

                        </span>


                        <div class="flex items-center gap-1">

                            <button
                                class="w-8 h-8 bg-slate-900 text-slate-600 rounded-lg flex items-center justify-center">

                                <i data-lucide="chevron-left" class="w-4 h-4"></i>

                            </button>


                            <button
                                class="w-8 h-8 rounded-lg bg-blue-600 text-white font-bold text-xs flex items-center justify-center">

                                1

                            </button>


                            <button
                                class="w-8 h-8 rounded-lg bg-slate-800 text-slate-300 hover:bg-slate-700 text-xs flex items-center justify-center">

                                2

                            </button>


                            <button
                                class="w-8 h-8 rounded-lg bg-slate-800 text-slate-300 hover:bg-slate-700 text-xs flex items-center justify-center">

                                3

                            </button>


                            <button
                                class="w-8 h-8 bg-slate-800 text-slate-400 rounded-lg hover:bg-slate-700 flex items-center justify-center">

                                <i data-lucide="chevron-right" class="w-4 h-4"></i>

                            </button>

                        </div>

                    </div>

                </div>



                {{-- ========================================================= --}}
                {{-- DETALLE DEL USUARIO --}}
                {{-- ========================================================= --}}

                <div class="bg-[#0b1026]/90 border border-blue-900/40 rounded-2xl p-5 shadow-xl backdrop-blur-md">

                    <div class="space-y-6">


                        {{-- HEADER --}}

                        <div class="flex items-start justify-between pb-4 border-b border-slate-800">

                            <div>

                                <h2 class="text-sm font-semibold text-white">
                                    Información del usuario
                                </h2>

                                <p class="text-[10px] text-slate-500 mt-1">
                                    Detalle completo de la cuenta
                                </p>

                            </div>


                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">

                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>

                                Activa

                            </span>

                        </div>



                        {{-- FOTO + NOMBRE --}}

                        <div class="flex flex-col items-center text-center">

                            <div class="relative">

                                <img src="{{ asset('storage/profile-photos/user.png') }}"
                                    class="w-24 h-24 rounded-full object-cover ring-4 ring-blue-500/10 border border-slate-700">

                                <div
                                    class="absolute bottom-1 right-1 w-6 h-6 rounded-full bg-emerald-500 border-4 border-[#0b1026]">
                                </div>

                            </div>


                            <h3 class="mt-4 text-lg font-bold text-white">
                                Juan Pérez
                            </h3>

                            <p class="text-xs text-slate-400">
                                juan.perez@empresa.com
                            </p>

                            <span
                                class="mt-2 px-3 py-1 rounded-full text-[10px] font-medium bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">

                                Usuario

                            </span>

                        </div>



                        {{-- INFORMACIÓN GENERAL --}}

                        <div>

                            <p class="text-slate-400 text-[10px] mb-3">
                                Información general
                            </p>


                            <div class="space-y-2">


                                <div class="flex items-center justify-between bg-slate-800/40 rounded-xl p-3">

                                    <div class="flex items-center gap-3">

                                        <div
                                            class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-400 flex items-center justify-center">

                                            <i data-lucide="badge-id-card" class="w-4 h-4"></i>

                                        </div>

                                        <div>

                                            <p class="text-[10px] text-slate-500">
                                                Número de empleado
                                            </p>

                                            <p class="text-xs text-white font-medium">
                                                97234
                                            </p>

                                        </div>

                                    </div>

                                </div>



                                <div class="flex items-center gap-3 bg-slate-800/40 rounded-xl p-3">

                                    <div
                                        class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-400 flex items-center justify-center">

                                        <i data-lucide="building-2" class="w-4 h-4"></i>

                                    </div>

                                    <div>

                                        <p class="text-[10px] text-slate-500">
                                            Departamento
                                        </p>

                                        <p class="text-xs text-white font-medium">
                                            Administración
                                        </p>

                                    </div>

                                </div>



                                <div class="flex items-center gap-3 bg-slate-800/40 rounded-xl p-3">

                                    <div
                                        class="w-8 h-8 rounded-lg bg-purple-500/10 text-purple-400 flex items-center justify-center">

                                        <i data-lucide="shield" class="w-4 h-4"></i>

                                    </div>

                                    <div>

                                        <p class="text-[10px] text-slate-500">
                                            Rol
                                        </p>

                                        <p class="text-xs text-white font-medium">
                                            Usuario
                                        </p>

                                    </div>

                                </div>



                                <div class="flex items-center gap-3 bg-slate-800/40 rounded-xl p-3">

                                    <div
                                        class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center">

                                        <i data-lucide="calendar" class="w-4 h-4"></i>

                                    </div>

                                    <div>

                                        <p class="text-[10px] text-slate-500">
                                            Fecha de registro
                                        </p>

                                        <p class="text-xs text-white font-medium">
                                            10 Agosto 2026
                                        </p>

                                    </div>

                                </div>

                            </div>

                        </div>



                        {{-- CONTACTO --}}

                        <div>

                            <p class="text-slate-400 text-[10px] mb-3">
                                Información de contacto
                            </p>


                            <div class="bg-slate-900/60 border border-slate-800 rounded-xl p-3 space-y-3">


                                <div class="flex items-center gap-3">

                                    <i data-lucide="mail" class="w-4 h-4 text-slate-500"></i>

                                    <div>

                                        <p class="text-[9px] text-slate-500">
                                            Correo electrónico
                                        </p>

                                        <p class="text-xs text-slate-300">
                                            juan.perez@empresa.com
                                        </p>

                                    </div>

                                </div>


                                <div class="flex items-center gap-3">

                                    <i data-lucide="phone" class="w-4 h-4 text-slate-500"></i>

                                    <div>

                                        <p class="text-[9px] text-slate-500">
                                            Teléfono
                                        </p>

                                        <p class="text-xs text-slate-300">
                                            899 123 4567
                                        </p>

                                    </div>

                                </div>

                            </div>

                        </div>



                        {{-- PERMISOS --}}

                        <div>

                            <p class="text-slate-400 text-[10px] mb-3">
                                Permisos
                            </p>

                            <div class="flex flex-wrap gap-2">

                                <span
                                    class="px-2.5 py-1 rounded-lg bg-blue-500/10 border border-blue-500/20 text-[10px] text-blue-400">

                                    Tickets

                                </span>

                                <span
                                    class="px-2.5 py-1 rounded-lg bg-blue-500/10 border border-blue-500/20 text-[10px] text-blue-400">

                                    Comentarios

                                </span>

                                <span
                                    class="px-2.5 py-1 rounded-lg bg-blue-500/10 border border-blue-500/20 text-[10px] text-blue-400">

                                    Solicitudes

                                </span>

                            </div>

                        </div>



                        {{-- ÚLTIMA ACTIVIDAD --}}

                        <div class="pt-4 border-t border-slate-800">

                            <div class="flex items-center justify-between">

                                <div>

                                    <p class="text-[10px] text-slate-500">
                                        Última actividad
                                    </p>

                                    <p class="text-xs text-slate-300 mt-1">
                                        Hoy, 10:42 AM
                                    </p>

                                </div>


                                <div
                                    class="w-9 h-9 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center">

                                    <i data-lucide="activity" class="w-4 h-4"></i>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </main>



    <script>
        lucide.createIcons();
    </script>

</body>

</html>
