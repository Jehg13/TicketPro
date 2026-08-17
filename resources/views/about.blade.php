<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="icon" type="image/png" href="{{ asset('storage/images/logo.png') }}">
    <title>TicketPro | Ayuda y soporte</title>
</head>
<body class="min-h-screen bg-[#070a1c] pb-8">
    <main class="mx-auto max-w-6xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8">
        <a href="{{ route('welcome') }}"
            class="mb-4 inline-flex items-center gap-2 text-sm font-semibold text-slate-400 transition hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 18l-6-6 6-6" />
            </svg>
            Regresar
        </a>
        <section class="relative flex items-start justify-between gap-4 overflow-hidden">
            <div class="flex flex-col items-center gap-3 text-center sm:flex-row sm:items-start sm:text-left">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/5 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 18h.01" />
                        <path d="M9.5 9a2.5 2.5 0 115 .5c0 1.5-2.5 1.8-2.5 3.5" />
                        <circle cx="12" cy="12" r="9" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-white sm:text-3xl">
                        ¿Necesitas ayuda?
                    </h1>
                    <p class="mt-1 max-w-md text-sm text-slate-400 sm:text-base">
                        Si tienes alguna duda, contacta a nuestro equipo de soporte
                    </p>
                </div>
            </div>
            <div class="hidden h-44 w-48 shrink-0 sm:block md:h-52 md:w-56 lg:h-64 lg:w-64">
                <img src="{{ asset('storage/images/cymez-sin-fondo.png') }}" alt="Agente de soporte TicketPro"
                    class="pointer-events-none h-full w-full object-contain object-top">
            </div>
        </section>
        <section class="mt-10 sm:-mt-20 lg:-mt-34">
            <div class="flex items-center justify-center gap-2.5 sm:justify-start">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/5 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 21v-2a4 4 0 014-4h1" />
                        <circle cx="9" cy="7" r="3.2" />
                        <path d="M14.5 16.5l1.6 1.6 3-3.2" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-white sm:text-2xl">
                    Contacta a soporte
                </h2>
            </div>
            <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                <div
                    class="rounded-2xl border border-blue-600/70 bg-[#0b0f2a] p-5 shadow-[0_0_25px_rgba(37,99,235,0.12)]">
                    <div class="flex flex-col items-center text-center sm:flex-row sm:items-start sm:gap-4 sm:text-left">
                        <div
                            class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-black sm:h-11 sm:w-11">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white sm:h-5 sm:w-5"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="5" width="18" height="14" rx="2" />
                                <path d="M3 7l9 6 9-6" />
                            </svg>
                        </div>
                        <div class="mt-3 sm:mt-0">
                            <h3 class="text-lg font-bold text-white">
                                Correo electrónico
                            </h3>
                            <p class="mt-1 text-sm leading-snug text-slate-400">
                                Envíanos un correo y te responderemos a la brevedad
                            </p>
                            <p class="mt-3 text-sm font-semibold text-white">
                                Soporte@cymez.com
                            </p>
                        </div>
                    </div>
                    <a href="mailto:Soporte@cymez.com"
                        class="mt-4 flex items-center justify-center gap-2 rounded-full border border-white/15 bg-transparent py-2.5 text-sm font-bold text-white transition hover:bg-white/5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="5" width="18" height="14" rx="2" />
                            <path d="M3 7l9 6 9-6" />
                        </svg>
                        Enviar correo
                    </a>
                </div>
                <div
                    class="rounded-2xl border border-blue-600/70 bg-[#0b0f2a] p-5 shadow-[0_0_25px_rgba(37,99,235,0.12)]">
                    <div class="flex flex-col items-center text-center sm:flex-row sm:items-start sm:gap-4 sm:text-left">
                        <div
                            class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-black sm:h-11 sm:w-11">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-green-500 sm:h-5 sm:w-5"
                                viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12.04 2c-5.52 0-10 4.48-10 10 0 1.76.46 3.45 1.34 4.95L2 22l5.2-1.36A9.94 9.94 0 0012.04 22c5.52 0 10-4.48 10-10s-4.48-10-10-10zm0 18.13c-1.6 0-3.13-.43-4.47-1.24l-.32-.19-3.09.81.83-3.02-.21-.31A8.1 8.1 0 013.9 12c0-4.5 3.65-8.13 8.14-8.13 4.48 0 8.13 3.63 8.13 8.13 0 4.48-3.65 8.13-8.13 8.13zm4.47-6.09c-.24-.12-1.44-.71-1.66-.79-.22-.08-.39-.12-.55.12-.16.24-.63.79-.78.95-.14.16-.28.18-.53.06-.24-.12-1.03-.38-1.96-1.21-.72-.64-1.21-1.44-1.35-1.68-.14-.24-.01-.37.11-.49.11-.11.24-.28.36-.42.12-.14.16-.24.24-.4.08-.16.04-.3-.02-.42-.06-.12-.55-1.33-.76-1.82-.2-.48-.4-.42-.55-.42-.14 0-.3-.02-.46-.02-.16 0-.42.06-.64.3-.22.24-.84.82-.84 2s.86 2.32.98 2.48c.12.16 1.7 2.6 4.13 3.64.58.25 1.03.4 1.38.51.58.18 1.11.16 1.53.1.47-.07 1.44-.59 1.64-1.16.2-.57.2-1.06.14-1.16-.06-.1-.22-.16-.46-.28z" />
                            </svg>
                        </div>
                        <div class="mt-3 sm:mt-0">
                            <h3 class="text-lg font-bold text-white">
                                Whatsapp
                            </h3>
                            <p class="mt-1 text-sm leading-snug text-slate-400">
                                Escríbenos a whatsapp para una atención más rápida
                            </p>
                            <p class="mt-3 text-sm font-semibold text-green-500">
                                (899) 123 4567
                            </p>
                        </div>
                    </div>
                    <a href="https://wa.me/8991234567" target="_blank" rel="noopener noreferrer"
                        class="mt-4 flex items-center justify-center gap-2 rounded-full border border-green-500/70 bg-green-500/5 py-2.5 text-sm font-bold text-green-400 shadow-[0_0_15px_rgba(34,197,94,0.25)] transition hover:bg-green-500/10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12.04 2c-5.52 0-10 4.48-10 10 0 1.76.46 3.45 1.34 4.95L2 22l5.2-1.36A9.94 9.94 0 0012.04 22c5.52 0 10-4.48 10-10s-4.48-10-10-10zm0 18.13c-1.6 0-3.13-.43-4.47-1.24l-.32-.19-3.09.81.83-3.02-.21-.31A8.1 8.1 0 013.9 12c0-4.5 3.65-8.13 8.14-8.13 4.48 0 8.13 3.63 8.13 8.13 0 4.48-3.65 8.13-8.13 8.13zm4.47-6.09c-.24-.12-1.44-.71-1.66-.79-.22-.08-.39-.12-.55.12-.16.24-.63.79-.78.95-.14.16-.28.18-.53.06-.24-.12-1.03-.38-1.96-1.21-.72-.64-1.21-1.44-1.35-1.68-.14-.24-.01-.37.11-.49.11-.11.24-.28.36-.42.12-.14.16-.24.24-.4.08-.16.04-.3-.02-.42-.06-.12-.55-1.33-.76-1.82-.2-.48-.4-.42-.55-.42-.14 0-.3-.02-.46-.02-.16 0-.42.06-.64.3-.22.24-.84.82-.84 2s.86 2.32.98 2.48c.12.16 1.7 2.6 4.13 3.64.58.25 1.03.4 1.38.51.58.18 1.11.16 1.53.1.47-.07 1.44-.59 1.64-1.16.2-.57.2-1.06.14-1.16-.06-.1-.22-.16-.46-.28z" />
                        </svg>
                        Mandar mensaje
                    </a>
                </div>
                <div
                    class="rounded-2xl border border-blue-600/70 bg-[#0b0f2a] p-5 shadow-[0_0_25px_rgba(37,99,235,0.12)] sm:col-span-2 lg:col-span-1">
                    <div class="flex flex-col items-center text-center sm:flex-row sm:items-start sm:gap-4 sm:text-left">
                        <div
                            class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-black sm:h-11 sm:w-11">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-fuchsia-500 sm:h-5 sm:w-5"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.13.99.36 1.96.68 2.9a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.18-1.18a2 2 0 012.11-.45c.94.32 1.91.55 2.9.68A2 2 0 0122 16.92z" />
                            </svg>
                        </div>
                        <div class="mt-3 sm:mt-0">
                            <h3 class="text-lg font-bold text-white">
                                Llamar al soporte
                            </h3>
                            <p class="mt-1 text-sm leading-snug text-slate-400">
                                Llámanos directamente en nuestro horario de atención
                            </p>
                            <p class="mt-3 text-sm font-semibold text-fuchsia-400">
                                (899) 123 4567
                            </p>
                        </div>
                    </div>
                    <a href="tel:+8991234567"
                        class="mt-4 flex items-center justify-center gap-2 rounded-full border border-fuchsia-500/70 bg-fuchsia-500/5 py-2.5 text-sm font-bold text-fuchsia-400 shadow-[0_0_15px_rgba(217,70,239,0.25)] transition hover:bg-fuchsia-500/10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.13.99.36 1.96.68 2.9a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.18-1.18a2 2 0 012.11-.45c.94.32 1.91.55 2.9.68A2 2 0 0122 16.92z" />
                        </svg>
                        Llamar ahora
                    </a>
                </div>
            </div>
        </section>
        <section class="mt-10">
            <div class="flex items-center justify-center gap-2.5 sm:justify-start">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-white text-[#0b0f2a]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9.5 9a2.5 2.5 0 115 .5c0 1.5-2.5 1.8-2.5 3.5" />
                        <path d="M12 17h.01" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-white sm:text-2xl">
                    Preguntas frecuentes
                </h2>
            </div>
            <div class="mt-4 grid grid-cols-1 gap-3 lg:grid-cols-2 lg:items-start">
                @php
                    $faqs = [
                        [
                            'q' => '¿Cómo puedo dar seguimiento a un ticket?',
                            'a' => 'Ingresa a la sección "Mis tickets" dentro de tu cuenta de TicketPro. Ahí verás el estado actual, los comentarios del equipo de soporte y podrás agregar información adicional en cualquier momento.',
                        ],
                        [
                            'q' => '¿Cuánto tiempo se tarda en resolver un ticket?',
                            'a' => 'El tiempo depende de la prioridad y complejidad del caso. Los tickets de prioridad alta se atienden en las primeras horas del día hábil, mientras que los de prioridad media o baja pueden tardar de 1 a 3 días hábiles.',
                        ],
                        [
                            'q' => '¿Cuáles son los horarios de soporte?',
                            'a' => 'Nuestro equipo atiende de Lunes a Viernes de 8:00 AM a 17:45 PM. Fuera de este horario puedes seguir creando tickets, pero se atenderán al siguiente día hábil.',
                        ],
                        [
                            'q' => '¿Puedo actualizar mi evidencia?',
                            'a' => 'Sí. Dentro del detalle de tu ticket encontrarás la opción de adjuntar archivos o capturas de pantalla adicionales, incluso si el ticket ya fue creado.',
                        ],
                        [
                            'q' => '¿Qué información debo tener en mi reporte?',
                            'a' => 'Incluye una descripción clara del problema, el área o sistema afectado, capturas de pantalla si es posible, y cualquier mensaje de error que hayas recibido. Mientras más detalle, más rápida será la resolución.',
                        ],
                        [
                            'q' => '¿Cómo sé si mi ticket está solucionado?',
                            'a' => 'Recibirás una notificación por correo y el estado del ticket cambiará a "Cerrado" dentro de la plataforma. También puedes verificarlo en cualquier momento desde "Mis tickets".',
                        ],
                    ];
                @endphp
                @foreach ($faqs as $index => $faq)
                    <div
                        class="rounded-xl border border-blue-600/60 bg-[#0b0f2a] transition hover:border-blue-500">
                        <button type="button" onclick="toggleFaq({{ $index }})"
                            class="group flex w-full items-center justify-between px-5 py-4 text-left text-sm font-semibold text-white sm:text-base">
                            <span>
                                {{ $faq['q'] }}
                            </span>
                            <svg id="faq-icon-{{ $index }}" xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 shrink-0 text-blue-500 transition-transform duration-200"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"
                                stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 6 15 12 9 18" />
                            </svg>
                        </button>
                        <div id="faq-answer-{{ $index }}" class="hidden px-5 pb-4">
                            <p class="border-t border-white/10 pt-3 text-sm leading-relaxed text-slate-400">
                                {{ $faq['a'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
        <section class="mt-8">
            @php
                $ahora = \Carbon\Carbon::now();
                $esDiaHabil = $ahora->isWeekday();
                $horaInicio = $ahora->copy()->setTime(8, 0);
                $horaFin = $ahora->copy()->setTime(17, 45);
                $disponible = $esDiaHabil && $ahora->between($horaInicio, $horaFin);
            @endphp
            <div
                class="flex flex-col gap-4 rounded-2xl bg-[#0b0f2a] p-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-white text-[#0b0f2a]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9" />
                            <path d="M12 7v5l3.5 2" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-base font-bold text-white">
                            Hora de atención
                        </p>
                        <p class="text-sm text-slate-400">
                            Lunes a Viernes de 8:00 AM a 17:45 PM
                        </p>
                    </div>
                </div>
                @if ($disponible)
                    <div class="flex items-center gap-2 sm:pr-2">
                        <span class="relative flex h-2.5 w-2.5">
                            <span
                                class="absolute inline-flex h-full w-full animate-ping rounded-full bg-green-500 opacity-60">
                            </span>
                            <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-green-500"></span>
                        </span>
                        <span class="text-sm font-bold text-green-400">
                            Estamos disponibles
                        </span>
                    </div>
                @else
                    <div class="flex items-center gap-2 sm:pr-2">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-red-500"></span>
                        </span>
                        <span class="text-sm font-bold text-red-400">
                            No estamos disponibles
                        </span>
                    </div>
                @endif
            </div>
        </section>
    </main>
    <script>
        function toggleFaq(index) {
            const answer = document.getElementById('faq-answer-' + index);
            const icon = document.getElementById('faq-icon-' + index);
            if (!answer || !icon) {
                return;
            }
            const isOpen = !answer.classList.contains('hidden');
            if (isOpen) {
                answer.classList.add('hidden');
                icon.classList.remove('rotate-90');
            } else {
                answer.classList.remove('hidden');
                icon.classList.add('rotate-90');
            }
        }
    </script>
</body>
</html>