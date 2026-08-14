<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TicketPro - Mi Perfil</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="bg-[#070b19] text-white font-sans min-h-screen antialiased">

    <!-- SIDEBAR -->
    <aside
        class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0a0f24] border-r border-slate-800/60 p-6 hidden md:flex flex-col justify-between">

        <div>

            <!-- LOGO -->
            <div class="flex items-center gap-2 mb-10">

                <span class="text-3xl font-extrabold tracking-wide text-white">
                    Ticket<span class="text-blue-500">Pro</span>
                </span>

            </div>


            <!-- USUARIO -->
            <div
                class="flex items-center gap-3 mb-8 p-2 rounded-xl bg-slate-900/40 border border-slate-800/50">

                <img
                    src="{{ asset('storage/' . auth()->user()->foto) }}"
                    alt="{{ auth()->user()->name }}"
                    class="w-10 h-10 rounded-full object-cover ring-2 ring-blue-500/30"
                >

                <div class="overflow-hidden">

                    <h4 class="text-sm font-semibold text-slate-200 truncate">
                        {{ auth()->user()->name ?? 'Desconocido' }}
                    </h4>

                    <p class="text-xs text-slate-400 truncate">
                        {{ auth()->user()->departamento->nombre ?? 'Sin departamento' }}
                    </p>

                </div>

            </div>


            <!-- MENU -->
            <nav class="space-y-2">

                <a
                    href="{{ route('tecnologias') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition"
                >
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>

                    <span class="font-medium text-sm">
                        Inicio
                    </span>
                </a>


                <a
                    href="{{ route('tickettecnologias') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition"
                >
                    <i data-lucide="ticket-check" class="w-5 h-5"></i>

                    <span class="font-medium text-sm">
                        Tickets
                    </span>
                </a>


                <a
                    href="{{ route('cambiostecnologias') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition"
                >
                    <i data-lucide="git-compare-arrows" class="w-5 h-5"></i>

                    <span class="font-medium text-sm">
                        Cambios
                    </span>
                </a>


                <a
                    href="{{ route('avisostecnologias') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition"
                >
                    <i data-lucide="megaphone" class="w-5 h-5"></i>

                    <span class="font-medium text-sm">
                        Avisos
                    </span>
                </a>


                <!-- ACTIVO -->
                <a
                    href="{{ route('perfiltecnologias') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold shadow-lg shadow-blue-600/30 transition"
                >
                    <i data-lucide="circle-user-round" class="w-5 h-5"></i>

                    <span class="font-medium text-sm">
                        Mi perfil
                    </span>
                </a>

            </nav>

        </div>


        <!-- LOGOUT -->
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


    <!-- CONTENIDO -->
    <main class="md:ml-64 min-h-screen p-6 md:p-8">

        <div class="max-w-[1400px] mx-auto">


            <!-- HEADER -->
            <header
                class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8"
            >

                <div>

                    <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-white">
                        Mi perfil
                    </h1>

                    <p class="text-sm text-slate-400 mt-1">
                        Gestión y actualización directa de tu información administrativa
                    </p>

                </div>


                <!-- USUARIO HEADER -->
                <div class="flex items-center gap-4 self-end md:self-auto">

                    <!-- NOTIFICACIONES -->
                    <button
                        type="button"
                        class="relative p-2.5 rounded-full bg-slate-900/80 border border-slate-800 text-slate-300 hover:text-white hover:border-slate-700 transition"
                    >

                        <i data-lucide="bell" class="w-5 h-5"></i>

                        <span
                            class="absolute top-1.5 right-1.5 w-2 h-2 bg-blue-500 rounded-full"
                        ></span>

                    </button>


                    <!-- PERFIL -->
                    <div
                        class="flex items-center gap-3 bg-slate-900/80 border border-slate-800 rounded-full p-1.5 pr-4"
                    >

                        <img
                            src="{{ asset('storage/' . auth()->user()->foto) }}"
                            alt="{{ auth()->user()->name }}"
                            class="w-8 h-8 rounded-full object-cover"
                        >

                        <div class="text-left leading-tight hidden sm:block">

                            <p class="text-xs font-semibold text-white">
                                {{ auth()->user()->name ?? 'Desconocido' }}
                            </p>

                            <p class="text-[10px] text-blue-400 font-medium">
                                {{ auth()->user()->departamento->nombre ?? 'Sin departamento' }}
                            </p>

                        </div>

                        <i
                            data-lucide="chevron-down"
                            class="w-4 h-4 text-slate-400 ml-1"
                        ></i>

                    </div>

                </div>

            </header>


            <!-- GRID PRINCIPAL -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">


                <!-- COLUMNA IZQUIERDA -->
                <div class="lg:col-span-2 space-y-6">


                    <!-- INFORMACIÓN PERSONAL -->
                    <div
                        class="bg-[#0b1026]/90 border border-blue-900/40 rounded-2xl p-6 shadow-xl backdrop-blur-md"
                    >

                        <!-- HEADER CARD -->
                        <div
                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 pb-4 border-b border-slate-800/80"
                        >

                            <div class="flex items-center gap-3">

                                <div
                                    class="p-2.5 rounded-xl bg-blue-600/20 text-blue-400 shrink-0"
                                >
                                    <i data-lucide="user-check" class="w-5 h-5"></i>
                                </div>

                                <div>

                                    <h2 class="text-base font-bold text-white">
                                        Información personal y laboral
                                    </h2>

                                    <p class="text-xs text-slate-400 mt-0.5">
                                        Como Administrador de TI puedes modificar tus datos de contacto directamente.
                                    </p>

                                </div>

                            </div>


                            <span
                                class="self-start sm:self-auto px-3 py-1 rounded-full text-[10px] font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/30 whitespace-nowrap"
                            >
                                Modo Administrador
                            </span>

                        </div>


                        <!-- FORMULARIO -->
                        <form
                            action="#"
                            method="POST"
                            class="space-y-5"
                        >

                            @csrf


                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                                <!-- NOMBRE -->
                                <div class="space-y-1.5">

                                    <label
                                        class="flex items-center justify-between text-xs font-semibold text-slate-300"
                                    >

                                        <span class="flex items-center gap-1.5">

                                            <i
                                                data-lucide="user"
                                                class="w-3.5 h-3.5 text-blue-400"
                                            ></i>

                                            Nombre completo

                                        </span>

                                        <span
                                            class="text-[10px] text-blue-400 font-normal flex items-center gap-1"
                                        >

                                            <i
                                                data-lucide="pen"
                                                class="w-3 h-3"
                                            ></i>

                                            Editable

                                        </span>

                                    </label>


                                    <input
                                        type="text"
                                        name="name"
                                        value="{{ auth()->user()->name }}"
                                        class="w-full bg-[#030712] border border-slate-700/80 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition"
                                    >

                                </div>


                                <!-- EMPRESA -->
                                <div class="space-y-1.5">

                                    <label
                                        class="flex items-center justify-between text-xs font-semibold text-slate-400"
                                    >

                                        <span class="flex items-center gap-1.5">

                                            <i
                                                data-lucide="building-2"
                                                class="w-3.5 h-3.5 text-slate-500"
                                            ></i>

                                            Empresa

                                        </span>

                                        <i
                                            data-lucide="lock"
                                            class="w-3.5 h-3.5 text-slate-500"
                                        ></i>

                                    </label>


                                    <div
                                        class="w-full bg-[#030712]/50 border border-slate-800/80 rounded-xl px-4 py-2.5 text-xs text-slate-400 flex items-center justify-between gap-3"
                                    >

                                        <span class="truncate">
                                            Cymez
                                        </span>

                                        <span
                                            class="text-[9px] uppercase tracking-wider text-slate-500 bg-slate-900 px-2 py-0.5 rounded border border-slate-800 whitespace-nowrap"
                                        >
                                            Fijo
                                        </span>

                                    </div>

                                </div>


                                <!-- CORREO -->
                                <div class="space-y-1.5">

                                    <label
                                        class="flex items-center justify-between text-xs font-semibold text-slate-300"
                                    >

                                        <span class="flex items-center gap-1.5">

                                            <i
                                                data-lucide="mail"
                                                class="w-3.5 h-3.5 text-blue-400"
                                            ></i>

                                            Correo electrónico

                                        </span>

                                        <span
                                            class="text-[10px] text-blue-400 font-normal flex items-center gap-1"
                                        >

                                            <i
                                                data-lucide="pen"
                                                class="w-3 h-3"
                                            ></i>

                                            Editable

                                        </span>

                                    </label>


                                    <input
                                        type="email"
                                        name="email"
                                        value="{{ auth()->user()->email }}"
                                        class="w-full bg-[#030712] border border-slate-700/80 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition"
                                    >

                                </div>


                                <!-- OFICINA -->
                                <div class="space-y-1.5">

                                    <label
                                        class="flex items-center justify-between text-xs font-semibold text-slate-400"
                                    >

                                        <span class="flex items-center gap-1.5">

                                            <i
                                                data-lucide="map-pin"
                                                class="w-3.5 h-3.5 text-slate-500"
                                            ></i>

                                            Oficina / Sucursal

                                        </span>

                                        <i
                                            data-lucide="lock"
                                            class="w-3.5 h-3.5 text-slate-500"
                                        ></i>

                                    </label>


                                    <div
                                        class="w-full bg-[#030712]/50 border border-slate-800/80 rounded-xl px-4 py-2.5 text-xs text-slate-400 flex items-center justify-between gap-3"
                                    >

                                        <span class="truncate">
                                            Reynosa, Centro
                                        </span>

                                        <span
                                            class="text-[9px] uppercase tracking-wider text-slate-500 bg-slate-900 px-2 py-0.5 rounded border border-slate-800 whitespace-nowrap"
                                        >
                                            Fijo
                                        </span>

                                    </div>

                                </div>


                                <!-- DEPARTAMENTO -->
                                <div class="space-y-1.5">

                                    <label
                                        class="flex items-center justify-between text-xs font-semibold text-slate-300"
                                    >

                                        <span class="flex items-center gap-1.5">

                                            <i
                                                data-lucide="briefcase-business"
                                                class="w-3.5 h-3.5 text-blue-400"
                                            ></i>

                                            Departamento

                                        </span>

                                        <span
                                            class="text-[10px] text-blue-400 font-normal flex items-center gap-1"
                                        >

                                            <i
                                                data-lucide="pen"
                                                class="w-3 h-3"
                                            ></i>

                                            Editable

                                        </span>

                                    </label>


                                    <input
                                        type="text"
                                        name="departamento"
                                        value="{{ auth()->user()->departamento->nombre ?? '' }}"
                                        class="w-full bg-[#030712] border border-slate-700/80 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition"
                                    >

                                </div>


                                <!-- UBICACIÓN -->
                                <div class="space-y-1.5">

                                    <label
                                        class="flex items-center justify-between text-xs font-semibold text-slate-400"
                                    >

                                        <span class="flex items-center gap-1.5">

                                            <i
                                                data-lucide="building"
                                                class="w-3.5 h-3.5 text-slate-500"
                                            ></i>

                                            Ubicación física

                                        </span>

                                        <i
                                            data-lucide="lock"
                                            class="w-3.5 h-3.5 text-slate-500"
                                        ></i>

                                    </label>


                                    <div
                                        class="w-full bg-[#030712]/50 border border-slate-800/80 rounded-xl px-4 py-2.5 text-xs text-slate-400 flex items-center justify-between gap-3"
                                    >

                                        <span class="truncate">
                                            Edificio A, piso 2
                                        </span>

                                        <span
                                            class="text-[9px] uppercase tracking-wider text-slate-500 bg-slate-900 px-2 py-0.5 rounded border border-slate-800 whitespace-nowrap"
                                        >
                                            Fijo
                                        </span>

                                    </div>

                                </div>

                            </div>


                            <!-- GUARDAR -->
                            <div class="pt-4 flex justify-end">

                                <button
                                    type="submit"
                                    class="flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 shadow-lg shadow-blue-600/30 hover:opacity-90 transition"
                                >

                                    <i
                                        data-lucide="save"
                                        class="w-4 h-4"
                                    ></i>

                                    Guardar cambios

                                </button>

                            </div>

                        </form>

                    </div>


                    <!-- SEGURIDAD -->
                    <div
                        class="bg-[#0b1026]/90 border border-blue-900/40 rounded-2xl p-6 shadow-xl backdrop-blur-md"
                    >

                        <div class="flex items-center gap-3 mb-4">

                            <div
                                class="p-2.5 rounded-xl bg-blue-600/20 text-blue-400 shrink-0"
                            >

                                <i
                                    data-lucide="shield-check"
                                    class="w-5 h-5"
                                ></i>

                            </div>

                            <div>

                                <h2 class="text-base font-bold text-white">
                                    Seguridad de tu cuenta
                                </h2>

                                <p class="text-xs text-slate-400">
                                    Administra las credenciales de acceso a tu perfil administrativo
                                </p>

                            </div>

                        </div>


                        <div
                            class="bg-[#030712] border border-slate-800 rounded-xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4"
                        >

                            <div class="flex items-center gap-3">

                                <div
                                    class="p-2.5 rounded-lg bg-slate-900 border border-slate-800 text-slate-400"
                                >

                                    <i
                                        data-lucide="key-round"
                                        class="w-5 h-5"
                                    ></i>

                                </div>

                                <div>

                                    <h4 class="text-xs font-bold text-white">
                                        Contraseña de acceso
                                    </h4>

                                    <p class="text-[11px] text-slate-400 mt-0.5">
                                        Última actualización: 10 Ago 2026
                                    </p>

                                </div>

                            </div>


                            <button
                                type="button"
                                class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold bg-slate-900 text-slate-200 border border-slate-700 hover:bg-slate-800 transition shrink-0"
                            >

                                <i
                                    data-lucide="shield"
                                    class="w-4 h-4 text-blue-400"
                                ></i>

                                Actualizar contraseña

                            </button>

                        </div>

                    </div>

                </div>


                <!-- COLUMNA DERECHA -->
                <div class="space-y-6">


                    <!-- FOTO -->
                    <div
                        class="bg-[#0b1026]/90 border border-blue-900/40 rounded-2xl p-6 shadow-xl backdrop-blur-md text-center"
                    >

                        <div class="flex items-center justify-center gap-2 mb-5">

                            <i
                                data-lucide="camera"
                                class="w-4 h-4 text-blue-400"
                            ></i>

                            <h3 class="text-sm font-bold text-white">
                                Foto de perfil
                            </h3>

                        </div>


                        <!-- PREVIEW -->
                        <div
                            class="relative w-36 h-36 mx-auto mb-4 group"
                        >

                            <img
                                id="profile-preview"
                                src="{{ asset('storage/' . auth()->user()->foto) }}"
                                alt="{{ auth()->user()->name }}"
                                class="w-full h-full rounded-full object-cover ring-4 ring-blue-500/30 group-hover:ring-blue-500/60 transition duration-300"
                            >


                            <!-- BOTÓN CÁMARA -->
                            <label
                                for="avatar-input"
                                class="absolute bottom-1 right-1 w-10 h-10 bg-blue-600 hover:bg-blue-500 text-white rounded-full flex items-center justify-center border-2 border-[#0b1026] cursor-pointer shadow-lg transition"
                            >

                                <i
                                    data-lucide="camera"
                                    class="w-4 h-4"
                                ></i>

                            </label>


                            <input
                                type="file"
                                id="avatar-input"
                                name="foto"
                                class="hidden"
                                accept="image/jpeg,image/png"
                            >

                        </div>


                        <p class="text-[11px] text-slate-400">
                            Formatos permitidos: JPG, PNG
                        </p>

                        <p class="text-[10px] text-slate-500 mb-6">
                            Tamaño máximo: 2 MB
                        </p>


                        <div class="grid grid-cols-2 gap-3">

                            <label
                                for="avatar-input"
                                class="px-3 py-2 rounded-xl text-xs font-semibold bg-blue-600 hover:bg-blue-500 text-white shadow-md shadow-blue-600/30 transition cursor-pointer text-center"
                            >

                                <i
                                    data-lucide="camera"
                                    class="w-3.5 h-3.5 inline-block mr-1"
                                ></i>

                                Actualizar foto

                            </label>


                            <button
                                type="button"
                                class="px-3 py-2 rounded-xl text-xs font-semibold bg-slate-900 border border-slate-800 text-rose-400 hover:bg-rose-500/10 transition"
                            >

                                <i
                                    data-lucide="trash-2"
                                    class="w-3.5 h-3.5 inline-block mr-1"
                                ></i>

                                Eliminar foto

                            </button>

                        </div>

                    </div>


                    <!-- INFORMACIÓN DE CUENTA -->
                    <div
                        class="bg-[#0b1026]/90 border border-blue-900/40 rounded-2xl p-6 shadow-xl backdrop-blur-md space-y-4"
                    >

                        <div
                            class="flex items-center gap-2 pb-3 border-b border-slate-800"
                        >

                            <i
                                data-lucide="circle-info"
                                class="w-4 h-4 text-blue-400"
                            ></i>

                            <h3 class="text-sm font-bold text-white">
                                Información de la cuenta
                            </h3>

                        </div>


                        <div class="grid grid-cols-2 gap-4 text-xs">

                            <div>

                                <p class="text-[10px] text-slate-400">
                                    Fecha de creación
                                </p>

                                <p class="font-semibold text-white mt-0.5">
                                    {{ auth()->user()->created_at?->translatedFormat('d F Y') ?? 'No disponible' }}
                                </p>

                            </div>


                            <div>

                                <p class="text-[10px] text-slate-400">
                                    Rol en el sistema
                                </p>

                                <span
                                    class="inline-block mt-0.5 px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/20 text-blue-300 border border-blue-500/30"
                                >
                                    Administrador
                                </span>

                            </div>

                        </div>


                        <!-- ESTADO -->
                        <div>

                            <p class="text-[10px] text-slate-400 mb-1">
                                Estado de la cuenta
                            </p>

                            <span
                                class="px-3 py-1 rounded-lg text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 inline-flex items-center gap-1.5"
                            >

                                <span
                                    class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"
                                ></span>

                                Activa

                            </span>

                        </div>


                        <!-- PRIVILEGIOS -->
                        <div class="pt-2">

                            <div
                                class="bg-[#030712] border border-blue-900/30 rounded-xl p-3 flex items-start gap-3"
                            >

                                <i
                                    data-lucide="shield-check"
                                    class="w-5 h-5 text-blue-400 shrink-0 mt-0.5"
                                ></i>


                                <div class="text-[11px]">

                                    <p class="font-semibold text-slate-200">
                                        Privilegios elevados
                                    </p>

                                    <p class="text-slate-400 text-[10px] leading-relaxed mt-0.5">
                                        Los datos de cuenta y empresa están sincronizados con el directorio activo de Tecnologías.
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </main>
</body>
</html>