<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>TicketPro | Dashboard</title>
</head>
<body class="min-h-screen bg-[#070a1c]">

    <div class="flex min-h-screen">
        <div id="sidebarOverlay" onclick="toggleSidebar()"
             class="fixed inset-0 z-40 hidden bg-black/60 lg:hidden"></div>
        <aside id="sidebar"
               class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col overflow-y-auto bg-[#050714] px-5 py-8 shadow-2xl transition-transform duration-300 lg:static lg:z-auto lg:w-64 lg:shrink-0 lg:translate-x-0 lg:shadow-none">

            <div class="mb-8 px-1 text-2xl font-extrabold">
                <span class="text-white">Ticket</span><span class="text-blue-500">Pro</span>
            </div>

            <div class="mb-6 flex items-center gap-3 rounded-xl bg-white/5 px-3 py-3">
                <img src="{{ $usuario['foto'] ?? asset('storage/images/user.png') }}"
                     alt="Foto de {{ Auth::user()->name  ?? 'Usuario' }}"
                     class="h-11 w-11 shrink-0 rounded-full border border-white/10 object-cover">
                <div class="min-w-0">
                    <p class="truncate text-sm font-bold text-white">{{ Auth::user()->name ?? 'Desconocido' }}</p>
                    <p class="truncate text-xs text-slate-400">{{ Auth::user()->departamento->nombre ?? 'Sin departamento' }}</p>
                </div>
            </div>

            <nav class="space-y-2">

                    <a href="#"
                       class="flex items-center gap-3 rounded-xl bg-gradient-to-r from-blue-600 to-blue-500 px-4 py-3 text-sm font-bold text-white shadow-[0_0_20px_rgba(37,99,235,0.35)]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 11l9-8 9 8"/>
                            <path d="M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10"/>
                        </svg>
                        Inicio
                    </a>

                    <a href="#"
                       class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-400 transition hover:bg-white/5 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="4" y="4" width="16" height="16" rx="2"/>
                            <path d="M8 9h8M8 13h5"/>
                        </svg>
                        Mis tickets
                    </a>

                    <a href="#"
                       class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-400 transition hover:bg-white/5 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="4" y="4" width="16" height="16" rx="2"/>
                            <path d="M12 9v6M9 12h6"/>
                        </svg>
                        Crear ticket
                    </a>

                    <a href="#"
                       class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-400 transition hover:bg-white/5 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="8" r="3.2"/>
                            <path d="M5 20c0-3.3 3.1-6 7-6s7 2.7 7 6"/>
                        </svg>
                        Mi perfil
                    </a>

            </nav>

            <form method="POST" action="{{ route('logout') }}" class="mt-6">
                @csrf
                <button type="submit"
                        class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-slate-400 transition hover:bg-white/5 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 4H5a1 1 0 00-1 1v14a1 1 0 001 1h4"/>
                        <path d="M16 17l5-5-5-5"/>
                        <path d="M21 12H9"/>
                    </svg>
                    Cerrar sesión
                </button>
            </form>

        </aside>


        {{-- ================================================================ --}}
        {{-- CONTENIDO PRINCIPAL --}}
        {{-- ================================================================ --}}

        <main class="flex-1 px-5 py-6 sm:px-8 sm:py-8 lg:px-10">

            {{-- ---------- ENCABEZADO ---------- --}}
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div class="flex items-center gap-3">
                    <button type="button" onclick="toggleSidebar()"
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-white/5 text-white transition hover:bg-white/10 lg:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 7h16M4 12h16M4 17h16"/>
                        </svg>
                    </button>

                    <div>
                        <h1 class="text-2xl font-extrabold text-white sm:text-3xl">Bienvenido, {{ Auth::user()->nombre ?? 'Desconocido' }}</h1>
                        <p class="mt-1 text-sm text-slate-400 sm:text-base">
                            Inicio / <span class="font-bold text-white">Dashboard</span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 sm:gap-4">
                    <button type="button"
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-white/5 text-white transition hover:bg-white/10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8a6 6 0 10-12 0c0 7-3 9-3 9h18s-3-2-3-9"/>
                            <path d="M13.73 21a2 2 0 01-3.46 0"/>
                        </svg>
                    </button>

                    <a href="#"
                       class="flex items-center gap-2 rounded-2xl bg-gradient-to-r from-blue-600 to-blue-500 px-5 py-3 text-sm font-bold text-white shadow-[0_0_20px_rgba(37,99,235,0.35)] transition hover:brightness-110 sm:text-base">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5v14M5 12h14"/>
                        </svg>
                        Nuevo ticket
                    </a>
                </div>
            </div>


            {{-- ---------- GRID PRINCIPAL ---------- --}}
            {{-- Orden en móvil (de arriba a abajo): Resumen, Mi información, Tickets recientes, --}}
            {{-- Último ticket, Actividad reciente, Avisos importantes. --}}
            {{-- En escritorio (lg+) se acomodan en dos columnas como en el diseño original. --}}
            <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-12">

                {{-- ---------- RESUMEN DE MIS TICKETS ---------- --}}
                <div class="order-1 rounded-2xl border border-blue-600/70 bg-[#0b0f2a] p-5 shadow-[0_0_25px_rgba(37,99,235,0.12)] lg:order-2 lg:col-span-7">

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/5 text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 3a9 9 0 109 9h-9V3z"/>
                                    <path d="M15 3.5A9 9 0 013.5 15"/>
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-white">Resumen de mis tickets</h2>
                        </div>

                        <div class="rounded-2xl border border-fuchsia-500/60 bg-fuchsia-500/10 px-4 py-1.5 text-center shadow-[0_0_15px_rgba(217,70,239,0.2)]">
                            <p class="text-xs font-bold text-white">Total</p>
                            <p class="-mt-0.5 text-2xl font-extrabold text-fuchsia-400">{{ $resumen['total'] ?? 18 }}</p>
                            <p class="text-[10px] text-slate-400">Todos mis tickets</p>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4">

                        <div class="rounded-xl border border-amber-400/40 bg-amber-700/80 p-4 text-center">
                            <p class="text-3xl font-extrabold text-white">{{ $resumen['abiertos'] ?? 3 }}</p>
                            <p class="mt-1 text-sm font-bold text-white">Abiertos</p>
                            <p class="text-xs text-amber-100/80">Tickets abiertos</p>
                        </div>

                        <div class="rounded-xl border border-blue-500/50 bg-blue-950/70 p-4 text-center">
                            <p class="text-3xl font-extrabold text-blue-400">{{ $resumen['en_proceso'] ?? 4 }}</p>
                            <p class="mt-1 text-sm font-bold text-white">En proceso</p>
                            <p class="text-xs text-slate-400">Tickets en proceso</p>
                        </div>

                        <div class="rounded-xl border border-green-500/50 bg-green-950/60 p-4 text-center">
                            <p class="text-3xl font-extrabold text-green-400">{{ $resumen['solucionados'] ?? 8 }}</p>
                            <p class="mt-1 text-sm font-bold text-white">Solucionados</p>
                            <p class="text-xs text-slate-400">Tickets solucionados</p>
                        </div>

                        <div class="rounded-xl border border-red-500/50 bg-red-950/60 p-4 text-center">
                            <p class="text-3xl font-extrabold text-red-400">{{ $resumen['cancelados'] ?? 1 }}</p>
                            <p class="mt-1 text-sm font-bold text-white">Cancelados</p>
                            <p class="text-xs text-slate-400">Tickets cancelados</p>
                        </div>

                    </div>
                </div>


                {{-- ---------- MI INFORMACIÓN ---------- --}}
                <div class="order-2 rounded-2xl border border-blue-600/70 bg-[#0b0f2a] p-5 shadow-[0_0_25px_rgba(37,99,235,0.12)] lg:order-1 lg:col-span-5">

                    <div class="flex items-center gap-2.5">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/5 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="8" r="3.2"/>
                                <path d="M5 20c0-3.3 3.1-6 7-6s7 2.7 7 6"/>
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-white">Mi informaciòn</h2>
                    </div>

                    <div class="mt-4 flex flex-col items-center gap-4 sm:flex-row sm:items-start">
                        <img src="{{ $usuario['foto'] ?? asset('storage/images/user.png') }}"
                             alt="Foto de {{ Auth::User()->nombre ?? 'Desconocido' }}"
                             class="h-24 w-24 shrink-0 rounded-full border border-white/10 object-cover">

                        <dl class="grid w-full grid-cols-1 gap-y-2.5 text-sm">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="8" r="3.2"/>
                                    <path d="M5 20c0-3.3 3.1-6 7-6s7 2.7 7 6"/>
                                </svg>
                                <dt class="font-semibold text-slate-400">Nombre:</dt>
                                <dd class="ml-auto font-bold text-white">{{ Auth::User()->nombre ?? 'Juan Perez' }}</dd>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="4" y="8" width="16" height="12" rx="1"/>
                                    <path d="M9 8V5a1 1 0 011-1h4a1 1 0 011 1v3"/>
                                </svg>
                                <dt class="font-semibold text-slate-400">Empresa:</dt>
                                <dd class="ml-auto font-bold text-white">{{ Auth::User()->departamento->oficina->empresa->nombre ?? 'CYMEZ' }}</dd>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="4" y="4" width="16" height="16" rx="2"/>
                                    <path d="M8 9h8M8 13h5"/>
                                </svg>
                                <dt class="font-semibold text-slate-400">Departamento:</dt>
                                <dd class="ml-auto font-bold text-white">{{ Auth::User()->departamento->nombre ?? 'Soporte tecnico' }}</dd>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="5" width="18" height="14" rx="2"/>
                                    <path d="M3 7l9 6 9-6"/>
                                </svg>
                                <dt class="font-semibold text-slate-400">Correo:</dt>
                                <dd class="ml-auto font-bold text-white">{{ Auth::User()->email ?? 'jperez@cymez.com' }}</dd>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="4" y="8" width="16" height="12" rx="1"/>
                                    <path d="M9 8V5a1 1 0 011-1h4a1 1 0 011 1v3"/>
                                </svg>
                                <dt class="font-semibold text-slate-400">Oficina:</dt>
                                <dd class="ml-auto font-bold text-white">{{ Auth::User()->departamento->oficina->nombre ?? 'Reynosa, centro' }}</dd>
                            </div>
                            <div class="flex items-start gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-4 w-4 shrink-0 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 21s7-6.5 7-11a7 7 0 10-14 0c0 4.5 7 11 7 11z"/>
                                    <circle cx="12" cy="10" r="2.3"/>
                                </svg>
                                <dt class="font-semibold text-slate-400">Ubicación:</dt>
                                <dd class="ml-auto text-right font-bold text-white">{{ $usuario['ubicacion'] ?? 'Edificio A, piso 2 Area administrativa' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="mt-4 flex flex-col gap-2.5 border-t border-white/10 pt-4 text-sm">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 3v4M8 3v4M4 8h16"/>
                                <rect x="4" y="5" width="16" height="16" rx="2"/>
                            </svg>
                            <dt class="font-semibold text-slate-400">Empleado:</dt>
                            <dd class="ml-auto font-bold text-white">{{ Auth::User()->numeroempleado ?? 'EMP-00125' }}</dd>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 3v4M8 3v4M4 8h16"/>
                                <rect x="4" y="5" width="16" height="16" rx="2"/>
                            </svg>
                            <dt class="font-semibold text-slate-400">Fecha ingreso:</dt>
                            <dd class="ml-auto font-bold text-white">{{ Auth::User()->created_at ?? '10 enero 2024' }}</dd>
                        </div>
                    </div>
                </div>


                {{-- ---------- TICKETS RECIENTES ---------- --}}
                <div class="order-3 rounded-2xl border border-blue-600/70 bg-[#0b0f2a] p-5 shadow-[0_0_25px_rgba(37,99,235,0.12)] lg:order-4 lg:col-span-7">

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/5 text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 6h16M4 12h16M4 18h10"/>
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
                            ['folio' => 'TKT-2026-0015', 'tipo_falla' => 'Equipo de computo', 'estado' => 'En proceso', 'fecha' => '05 Ago 2026', 'soporte' => 'Carlos Martinez'],
                            ['folio' => 'TKT-2026-0015', 'tipo_falla' => 'Impresora', 'estado' => 'Solucionado', 'fecha' => '05 Ago 2026', 'soporte' => 'Carlos Martinez'],
                            ['folio' => 'TKT-2026-0015', 'tipo_falla' => 'VPN / Red', 'estado' => 'Solucionado', 'fecha' => '05 Ago 2026', 'soporte' => 'Carlos Martinez'],
                            ['folio' => 'TKT-2026-0015', 'tipo_falla' => 'Correo outlook', 'estado' => 'En proceso', 'fecha' => '05 Ago 2026', 'soporte' => 'Carlos Martinez'],
                            ['folio' => 'TKT-2026-0015', 'tipo_falla' => 'Acceso a sistema', 'estado' => 'Cancelado', 'fecha' => '05 Ago 2026', 'soporte' => 'Carlos Martinez'],
                        ];

                        $estadoClases = [
                            'En proceso'  => 'bg-blue-600/20 text-blue-400',
                            'Solucionado' => 'bg-green-600/20 text-green-400',
                            'Cancelado'   => 'bg-red-600/20 text-red-400',
                        ];
                    @endphp

                    <div class="mt-4 overflow-x-auto">
                        <table class="w-full min-w-[560px] text-left text-sm">
                            <thead>
                                <tr class="text-xs uppercase tracking-wide text-slate-500">
                                    <th class="pb-3 font-semibold">Folio</th>
                                    <th class="pb-3 font-semibold">Tipo de falla</th>
                                    <th class="pb-3 font-semibold">Estado</th>
                                    <th class="pb-3 font-semibold">Fecha de reporte</th>
                                    <th class="pb-3 font-semibold">Soporte</th>
                                    <th class="pb-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach ($ticketsRecientes as $ticket)
                                    <tr>
                                        <td class="py-3 font-bold text-white">{{ $ticket['folio'] }}</td>
                                        <td class="py-3 text-slate-300">{{ $ticket['tipo_falla'] }}</td>
                                        <td class="py-3">
                                            <span class="inline-block rounded-lg px-2.5 py-1 text-xs font-bold {{ $estadoClases[$ticket['estado']] }}">
                                                {{ $ticket['estado'] }}
                                            </span>
                                        </td>
                                        <td class="py-3 text-slate-300">{{ $ticket['fecha'] }}</td>
                                        <td class="py-3 text-slate-300">{{ $ticket['soporte'] }}</td>
                                        <td class="py-3 text-right">
                                            <a href="#" class="inline-flex text-slate-400 transition hover:text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/>
                                                    <circle cx="12" cy="12" r="3"/>
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
                <div class="order-4 rounded-2xl border border-blue-600/70 bg-[#0b0f2a] p-5 shadow-[0_0_25px_rgba(37,99,235,0.12)] lg:order-3 lg:col-span-5">

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/5 text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="4" y="4" width="16" height="16" rx="2"/>
                                    <path d="M8 9h8M8 13h5"/>
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-white">Ultimo ticket</h2>
                        </div>

                        <a href="#"
                           class="rounded-full bg-blue-600 px-4 py-2 text-xs font-bold text-white transition hover:bg-blue-500">
                            Ver detalles
                        </a>
                    </div>

                    <span class="mt-4 inline-block rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-bold text-white">
                        {{ $ultimoTicket['folio'] ?? 'TKT-2026-0015' }}
                    </span>

                    <div class="mt-3 grid grid-cols-2 gap-x-3 gap-y-3 text-sm">
                        <div>
                            <p class="text-slate-400">Tipo de falla:</p>
                            <p class="font-bold text-white">{{ $ultimoTicket['tipo_falla'] ?? 'Equipo de computo' }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Fecha reporte:</p>
                            <p class="font-bold text-white">{{ $ultimoTicket['fecha_reporte'] ?? '05 ago 2026' }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Departamento:</p>
                            <p class="font-bold text-white">{{ $ultimoTicket['departamento'] ?? 'Recursos humanos' }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Asignado a:</p>
                            <p class="font-bold text-white">{{ $ultimoTicket['asignado_a'] ?? 'Carlos Mtz' }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Sucursal / Oficina:</p>
                            <p class="font-bold text-white">{{ $ultimoTicket['oficina'] ?? 'Reynosa, Centro' }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Tomado por:</p>
                            <p class="font-bold text-white">{{ $ultimoTicket['tomado_por'] ?? 'Carlos Mtz' }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Estado:</p>
                            <span class="mt-1 inline-block rounded-lg bg-blue-600/20 px-2.5 py-1 text-xs font-bold text-blue-400">
                                {{ $ultimoTicket['estado'] ?? 'En proceso' }}
                            </span>
                        </div>
                        <div>
                            <p class="text-slate-400">Asignacion:</p>
                            <p class="font-bold text-white">{{ $ultimoTicket['fecha_asignacion'] ?? '05 ago 2026' }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Prioridad:</p>
                            <span class="mt-1 inline-block rounded-lg bg-red-600/20 px-2.5 py-1 text-xs font-bold text-red-400">
                                {{ $ultimoTicket['prioridad'] ?? 'Alta' }}
                            </span>
                        </div>
                        <div>
                            <p class="text-slate-400">Se soluciono?</p>
                            <span class="mt-1 inline-block rounded-lg bg-red-600/20 px-2.5 py-1 text-xs font-bold text-red-400">
                                {{ $ultimoTicket['solucionado'] ?? 'No' }}
                            </span>
                        </div>
                    </div>
                </div>


                {{-- ---------- ACTIVIDAD RECIENTE ---------- --}}
                <div class="order-5 rounded-2xl border border-blue-600/70 bg-[#0b0f2a] p-5 shadow-[0_0_25px_rgba(37,99,235,0.12)] lg:col-span-5">

                    <div class="flex items-center gap-2.5">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/5 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="9"/>
                                <path d="M12 7v5l3.5 2"/>
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-white">Actividad reciente en mis tickets</h2>
                    </div>

                    @php
                        $actividad = [
                            ['color' => 'bg-green-500', 'fecha' => '05 Ago 2026 - 10:30 AM', 'texto' => 'Carlos Martinez tomo tu ticket TKT-2026-0015'],
                            ['color' => 'bg-blue-500', 'fecha' => '05 Ago 2026 - 10:15 AM', 'texto' => 'Se agrego un comentario a tu ticket TKT-2026-0015'],
                            ['color' => 'bg-blue-500', 'fecha' => '05 Ago 2026 - 10:00 AM', 'texto' => 'Tu ticket TKT-2026-0015 se creo correctamente'],
                        ];
                    @endphp

                    <div class="mt-4">
                        @foreach ($actividad as $index => $item)
                            <div class="relative flex gap-3 pb-5 last:pb-0">
                                @if (!$loop->last)
                                    <span class="absolute left-[5px] top-3 h-full w-px bg-white/10"></span>
                                @endif
                                <span class="relative mt-1.5 h-2.5 w-2.5 shrink-0 rounded-full {{ $item['color'] }}"></span>
                                <div class="text-sm">
                                    <p class="font-bold text-white">{{ $item['fecha'] }}</p>
                                    <p class="text-slate-400">{{ $item['texto'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>


                {{-- ---------- AVISOS IMPORTANTES ---------- --}}
                <div class="order-6 rounded-2xl border border-blue-600/70 bg-[#0b0f2a] p-5 shadow-[0_0_25px_rgba(37,99,235,0.12)] lg:col-span-7">

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/5 text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="9"/>
                                    <path d="M12 8v5M12 16h.01"/>
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
                                'texto' => 'El area de TI dara mantenimiento el 09 de agosto del 2026 de 20:00 PM a 21:00 PM',
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

                    <div class="mt-4 divide-y divide-white/5">
                        @foreach ($avisos as $aviso)
                            <div class="flex gap-3 py-3.5 first:pt-4">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full
                                    @if ($aviso['tipo'] === 'warning') bg-yellow-400
                                    @elseif ($aviso['tipo'] === 'info') bg-blue-500
                                    @else bg-green-500
                                    @endif">
                                    @if ($aviso['tipo'] === 'warning')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 text-[#0b0f2a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 3l10 18H2L12 3z"/>
                                            <path d="M12 10v4M12 17h.01"/>
                                        </svg>
                                    @elseif ($aviso['tipo'] === 'info')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="9"/>
                                            <path d="M12 11v5M12 8h.01"/>
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M5 13l4 4L19 7"/>
                                        </svg>
                                    @endif
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-baseline justify-between gap-x-3">
                                        <p class="text-sm font-bold text-white">{{ $aviso['titulo'] }}</p>
                                        <p class="text-xs text-slate-500">{{ $aviso['fecha'] }}</p>
                                    </div>
                                    <p class="mt-0.5 text-sm text-slate-400">{{ $aviso['texto'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

        </main>

    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>

</body>
</html>