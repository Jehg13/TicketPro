<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>TicketPro</title>
</head>

<body class="h-screen overflow-hidden bg-[#02030a]">

    <main class="h-screen lg:grid lg:grid-cols-[38%_62%]">
        <section
            class="relative flex h-screen flex-col justify-center
                   overflow-hidden bg-[#02030a]
                   px-5 py-4
                   sm:px-8
                   lg:px-8
                   xl:px-10">
            <div
                class="pointer-events-none absolute -left-40 -top-40
                       h-96 w-96 rounded-full
                       bg-blue-700/10 blur-3xl">
            </div>

            <div
                class="pointer-events-none absolute -bottom-40 -right-40
                       h-96 w-96 rounded-full
                       bg-blue-600/10 blur-3xl">
            </div>
            <div class="relative z-10 mx-auto w-full max-w-md">
                <div class="mb-4 flex items-center">

                    <span class="text-xs font-bold tracking-widest text-white">
                        TICKET<span class="text-blue-500">PRO</span>
                    </span>
                </div>
                <div
                    class="mb-3 inline-flex items-center gap-2
                           rounded-full border border-blue-500/20
                           bg-blue-500/5 px-2.5 py-1">

                    <span class="relative flex h-1.5 w-1.5">

                        <span
                            class="absolute inline-flex h-full w-full
                                   animate-ping rounded-full
                                   bg-blue-500 opacity-60">
                        </span>
                        <span
                            class="relative inline-flex h-1.5 w-1.5
                                   rounded-full bg-blue-500">
                        </span>
                    </span>
                    <span
                        class="text-[8px] font-bold uppercase
                               tracking-[0.15em] text-blue-300">
                        Plataforma de soporte interno
                    </span>
                </div>
                <h1
                    class="text-3xl font-extrabold leading-[1.02]
                           tracking-tight text-white
                           sm:text-4xl
                           lg:text-[2.6rem]
                           xl:text-[2.85rem]">
                    Bienvenido a
                    <span
                        class="mt-1 block
                               bg-gradient-to-r
                               from-blue-400
                               via-blue-600
                               to-blue-500
                               bg-clip-text
                               text-transparent">
                        TicketPro
                    </span>
                </h1>
                <p class="mt-3 max-w-md text-xs leading-5
                           text-slate-400 sm:text-sm">
                    La plataforma de gestión de tickets interna que
                    conecta equipos, departamentos y ubicaciones
                    para resolver incidencias de forma rápida y eficiente.
                </p>
                <div class="mt-5 grid grid-cols-3 gap-2.5">
                    <div
                        class="group relative flex flex-col
                               items-center overflow-hidden
                               rounded-2xl

                               border border-white/[0.08]
                               bg-gradient-to-b
                               from-white/[0.045]
                               to-white/[0.015]
                               px-2.5 py-3.5
                               text-center
                               shadow-lg shadow-black/10
                               transition-all duration-300
                               hover:-translate-y-1
                               hover:border-blue-500/30
                               hover:from-blue-500/[0.08]
                               hover:to-blue-500/[0.02]
                               hover:shadow-blue-500/10">
                        <div
                            class="absolute left-1/2 top-0
                                   h-px w-12 -translate-x-1/2
                                   bg-gradient-to-r
                                   from-transparent
                                   via-blue-500/60
                                   to-transparent">
                        </div>
                        <div
                            class="relative flex h-12 w-12
                                   items-center justify-center
                                   rounded-xl
                                   border border-blue-400/20
                                   bg-blue-500/[0.08]
                                   shadow-inner
                                   shadow-blue-500/10
                                   transition-all duration-300
                                   group-hover:scale-105
                                   group-hover:border-blue-400/40
                                   group-hover:bg-blue-500/15">
                            <div
                                class="absolute inset-0 rounded-xl
                                       bg-blue-500/5 blur-md">
                            </div>
                            <svg class="relative h-6 w-6 text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M4 14v-2a8 8 0 0116 0v2" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M20 15.5a1.5 1.5 0 00-1.5-1.5H17a1.5 1.5 0 00-1.5 1.5v3A1.5 1.5 0 0017 20h1.5a1.5 1.5 0 001.5-1.5v-3z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M4 15.5A1.5 1.5 0 015.5 14H7a1.5 1.5 0 011.5 1.5v3A1.5 1.5 0 017 20H5.5A1.5 1.5 0 014 18.5v-3z" />
                            </svg>
                        </div>
                        <p
                            class="mt-2.5 text-[9px]
                                   font-semibold leading-3.5
                                   text-slate-200
                                   sm:text-[10px]">
                            Crea y gestiona
                            <br>
                            tus tickets
                        </p>
                    </div>
                    <div
                        class="group relative flex flex-col
                               items-center overflow-hidden
                               rounded-2xl
                               border border-white/[0.08]
                               bg-gradient-to-b
                               from-white/[0.045]
                               to-white/[0.015]
                               px-2.5 py-3.5
                               text-center
                               shadow-lg shadow-black/10
                               transition-all duration-300
                               hover:-translate-y-1
                               hover:border-blue-500/30
                               hover:from-blue-500/[0.08]
                               hover:to-blue-500/[0.02]
                               hover:shadow-blue-500/10">
                        <div
                            class="absolute left-1/2 top-0
                                   h-px w-12 -translate-x-1/2
                                   bg-gradient-to-r
                                   from-transparent
                                   via-blue-500/60
                                   to-transparent">
                        </div>
                        <div
                            class="relative flex h-12 w-12
                                   items-center justify-center
                                   rounded-xl
                                   border border-blue-400/20
                                   bg-blue-500/[0.08]
                                   shadow-inner
                                   shadow-blue-500/10
                                   transition-all duration-300
                                   group-hover:scale-105
                                   group-hover:border-blue-400/40
                                   group-hover:bg-blue-500/15">
                            <div
                                class="absolute inset-0 rounded-xl
                                       bg-blue-500/5 blur-md">
                            </div>
                            <svg class="relative h-6 w-6 text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                                    d="M6 4.5h7l4 4V19a1.5 1.5 0 01-1.5 1.5H6A1.5 1.5 0 014.5 19V6A1.5 1.5 0 016 4.5z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                                    d="M13 4.5V8h4" />
                                <circle cx="10.3" cy="14.3" r="2.3" stroke-width="1.6" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                                    d="M12 16l1.8 1.8" />
                            </svg>
                        </div>
                        <p
                            class="mt-2.5 text-[9px]
                                   font-semibold leading-3.5
                                   text-slate-200
                                   sm:text-[10px]">
                            Reporta problemas
                            <br>
                            fácilmente
                        </p>
                    </div>
                    <div
                        class="group relative flex flex-col
                               items-center overflow-hidden
                               rounded-2xl
                               border border-white/[0.08]
                               bg-gradient-to-b
                               from-white/[0.045]
                               to-white/[0.015]
                               px-2.5 py-3.5
                               text-center
                               shadow-lg shadow-black/10
                               transition-all duration-300
                               hover:-translate-y-1
                               hover:border-blue-500/30
                               hover:from-blue-500/[0.08]
                               hover:to-blue-500/[0.02]
                               hover:shadow-blue-500/10">
                        <div
                            class="absolute left-1/2 top-0
                                   h-px w-12 -translate-x-1/2
                                   bg-gradient-to-r
                                   from-transparent
                                   via-blue-500/60
                                   to-transparent">
                        </div>
                        <div
                            class="relative flex h-12 w-12
                                   items-center justify-center
                                   rounded-xl
                                   border border-blue-400/20
                                   bg-blue-500/[0.08]
                                   shadow-inner
                                   shadow-blue-500/10
                                   transition-all duration-300
                                   group-hover:scale-105
                                   group-hover:border-blue-400/40
                                   group-hover:bg-blue-500/15">
                            <div
                                class="absolute inset-0 rounded-xl
                                       bg-blue-500/5 blur-md">
                            </div>
                            <svg class="relative h-6 w-6 text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                    d="M4 19.5h16" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                    d="M6.5 19.5v-4 M11 19.5v-7 M15.5 19.5v-3" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                    d="M14.5 6l4 0 0 4" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                    d="M18.5 6l-5.3 5.3-2.7-2.7-4 4" />
                            </svg>
                        </div>
                        <p
                            class="mt-2.5 text-[9px]
                                   font-semibold leading-3.5
                                   text-slate-200
                                   sm:text-[10px]">
                            Sigue el progreso
                            <br>
                            de cada solicitud
                        </p>
                    </div>
                </div>
                <div class="mt-5 space-y-2.5">
                    <a href="{{ route('login') }}" rel="noopener noreferrer"
                        class="group relative flex w-full
                               items-center justify-center
                               rounded-xl
                               bg-blue-600
                               px-5 py-3
                               text-xs font-bold text-white
                               shadow-lg shadow-blue-600/20
                               transition-all duration-300
                               hover:-translate-y-0.5
                               hover:bg-blue-500
                               hover:shadow-xl
                               hover:shadow-blue-600/30">
                        <span>
                            INICIAR SESIÓN
                        </span>
                        <span
                            class="absolute right-2.5
                                   flex h-7.5 w-7.5
                                   items-center justify-center
                                   rounded-lg
                                   bg-white/15
                                   transition-all
                                   group-hover:translate-x-1
                                   group-hover:bg-white/25">
                            <svg class="h-3.5 w-3.5 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </span>
                    </a>
                    <a href="{{ route('about') }}" target="_blank" rel="noopener noreferrer"
                        class="group relative flex w-full
                               items-center justify-center
                               rounded-xl
                               border border-white/10
                               bg-white/[0.025]
                               px-5 py-3
                               text-xs font-bold text-white
                               transition-all duration-300
                               hover:border-blue-500/30
                               hover:bg-white/[0.06]">
                        <span>
                            CONOCER MÁS
                        </span>
                        <span
                            class="absolute right-2.5
                                   flex h-7 w-7
                                   items-center justify-center
                                   rounded-full
                                   border border-white/20
                                   bg-white/[0.06]
                                   transition-all duration-300
                                   group-hover:border-blue-400
                                   group-hover:bg-blue-500/10
                                   group-hover:text-blue-300">
                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="9" stroke-width="1.8">
                                </circle>
                                <path stroke-linecap="round" stroke-width="1.8" d="M12 11v5">
                                </path>
                                <circle cx="12" cy="8" r="0.7" fill="currentColor" stroke="none">
                                </circle>
                            </svg>
                        </span>
                    </a>
                </div>
                <div
                    class="mt-4 flex items-center gap-3
                           rounded-xl
                           border border-white/[0.05]
                           bg-white/[0.02]
                           p-2.5">
                    <div
                        class="flex h-9 w-9 shrink-0
                               items-center justify-center
                               rounded-lg
                               bg-blue-600/15
                               ring-1 ring-blue-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9 flex-shrink-0" viewBox="0 0 24 24"
                            fill="white">
                            <path d="M12 1.5l8 3v6.2c0 5.1-3.4 9.5-8 10.8-4.6-1.3-8-5.7-8-10.8V4.5l8-3z" />
                            <path d="M10.2 14.3l-2.4-2.4 1.1-1.1 1.3 1.3 4-4 1.1 1.1-5.1 5.1z" fill="#0d1220" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-white">
                            Seguro, Confiable y Eficiente
                        </p>
                        <p class="mt-0.5 text-[8px]
                                   text-slate-500">
                            Soporte que impulsa tu productividad
                        </p>
                    </div>
                </div>
                <p class="mt-2 text-center text-[7px]
                           tracking-wide text-slate-700">
                    © {{ date('Y') }}
                    TicketPro · Sistema de soporte interno
                </p>
            </div>
        </section>
        <section class="relative hidden h-screen lg:block">
            <img src="{{ asset('storage/images/ticketpro-dashboard.png') }}" alt="TicketPro"
                class="absolute inset-0 h-full w-full object-cover">
        </section>
    </main>
</body>
</html>
