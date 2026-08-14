<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>TicketPro - Recuperar contraseña</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="min-h-screen bg-[#070b19] text-white flex items-center justify-center px-4">

    <!-- CONTENEDOR PRINCIPAL -->
    <div class="w-full max-w-md">

        <!-- LOGO / ENCABEZADO -->
        <div class="text-center mb-8">

            <div
                class="w-16 h-16 mx-auto mb-4 rounded-2xl
                       bg-blue-600/10
                       border border-blue-500/20
                       flex items-center justify-center">

                <svg
                    class="w-8 h-8 text-blue-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 7a2 2 0 012 2v1m0 0a2 2 0 012 2v5a2 2 0 01-2 2H7a2 2 0 01-2-2v-5a2 2 0 012-2m10 0V7a5 5 0 00-10 0v3m5 4v2" />

                </svg>

            </div>

            <h1 class="text-2xl font-bold tracking-wide">
                Recuperar contraseña
            </h1>

            <p class="text-sm text-gray-400 mt-2">
                Ingresa tu correo electrónico y te enviaremos
                instrucciones para recuperar tu contraseña.
            </p>

        </div>


        <!-- TARJETA -->
        <div
            class="bg-[#0f1535]
                   border border-[#1e295d]
                   rounded-2xl
                   p-6 sm:p-8
                   shadow-2xl shadow-black/20">

            <!-- MENSAJE DE SESIÓN -->
            @if (session('status'))

                <div
                    class="mb-5
                           rounded-xl
                           border border-emerald-500/30
                           bg-emerald-500/10
                           px-4 py-3">

                    <div class="flex items-start gap-3">

                        <svg
                            class="w-5 h-5 text-emerald-400 shrink-0 mt-0.5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 13l4 4L19 7" />

                        </svg>

                        <p class="text-sm text-emerald-300">
                            {{ session('status') }}
                        </p>

                    </div>

                </div>

            @endif


            <!-- ERRORES -->
            @if ($errors->any())

                <div
                    class="mb-5
                           rounded-xl
                           border border-red-500/30
                           bg-red-500/10
                           px-4 py-3">

                    <div class="flex items-start gap-3">

                        <svg
                            class="w-5 h-5 text-red-400 shrink-0 mt-0.5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 9v2m0 4h.01M12 5a7 7 0 100 14 7 7 0 000-14z" />

                        </svg>

                        <div class="space-y-1">

                            @foreach ($errors->all() as $error)

                                <p class="text-sm text-red-300">
                                    {{ $error }}
                                </p>

                            @endforeach

                        </div>

                    </div>

                </div>

            @endif


            <!-- FORMULARIO -->
            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">

                @csrf


                <!-- CORREO -->
                <div>

                    <label
                        for="email"
                        class="block text-sm font-semibold text-gray-300 mb-2">

                        Correo electrónico

                    </label>

                    <div class="relative">

                        <div
                            class="absolute inset-y-0 left-0
                                   pl-3
                                   flex items-center
                                   pointer-events-none">

                            <svg
                                class="w-5 h-5 text-gray-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />

                            </svg>

                        </div>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="email"
                            placeholder="correo@ejemplo.com"
                            class="w-full
                                   bg-[#0b102b]
                                   border border-[#1e295d]
                                   rounded-xl
                                   py-3
                                   pl-11
                                   pr-4
                                   text-sm
                                   text-white
                                   placeholder-gray-500
                                   outline-none
                                   transition
                                   focus:border-blue-500
                                   focus:ring-2
                                   focus:ring-blue-500/20">

                    </div>

                </div>


                <!-- BOTÓN -->
                <button
                    type="submit"
                    class="w-full
                           bg-blue-600
                           hover:bg-blue-500
                           active:bg-blue-700
                           text-white
                           font-semibold
                           rounded-xl
                           py-3
                           px-4
                           transition
                           duration-200
                           flex
                           items-center
                           justify-center
                           gap-2
                           shadow-lg
                           shadow-blue-900/20">

                    <svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />

                    </svg>

                    Enviar enlace de recuperación

                </button>

            </form>


            <!-- REGRESAR -->
            <div class="mt-6 pt-6 border-t border-[#1e295d] text-center">

                <a
                    href="{{ route('login') }}"
                    class="inline-flex
                           items-center
                           gap-2
                           text-sm
                           font-semibold
                           text-blue-400
                           hover:text-blue-300
                           transition">

                    <svg
                        class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />

                    </svg>

                    Volver al inicio de sesión

                </a>

            </div>

        </div>


        <!-- PIE -->
        <p class="text-center text-xs text-gray-600 mt-6">
            © {{ date('Y') }} TicketPro. Todos los derechos reservados.
        </p>

    </div>

</body>
</html>
