<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Restablecer contraseña | TicketPro</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>


<body
    class="min-h-screen bg-[#070b19] text-white flex items-center justify-center px-4 py-10">

    <div
        x-data="{
            password: '',
            passwordConfirmation: '',

            get tieneLongitud() {
                return this.password.length >= 8;
            },

            get tieneMayuscula() {
                return /[A-Z]/.test(this.password);
            },

            get tieneMinuscula() {
                return /[a-z]/.test(this.password);
            },

            get tieneNumero() {
                return /[0-9]/.test(this.password);
            },

            get tieneSimbolo() {
                return /[^A-Za-z0-9]/.test(this.password);
            },

            get coinciden() {
                return this.password !== '' &&
                       this.passwordConfirmation !== '' &&
                       this.password === this.passwordConfirmation;
            },

            get contraseñaValida() {
                return this.tieneLongitud &&
                       this.tieneMayuscula &&
                       this.tieneMinuscula &&
                       this.tieneNumero &&
                       this.tieneSimbolo &&
                       this.coinciden;
            }
        }"
        class="w-full max-w-md">


        <!-- LOGO -->

        <div class="text-center mb-8">

            <div
                class="inline-flex items-center justify-center
                       px-5 py-2.5
                       rounded-xl
                       bg-[#111a3d]
                       border border-[#1e295d]
                       shadow-lg shadow-black/20">

                <span
                    class="text-xl font-black tracking-[0.18em] text-blue-400">

                    TICKET

                </span>

                <span
                    class="text-xl font-black tracking-[0.18em] text-white">

                    PRO

                </span>

            </div>


            <p
                class="mt-3 text-xs text-slate-500 tracking-wide">

                Sistema de soporte

            </p>

        </div>



        <!-- CARD -->

        <div
            class="bg-[#0f1535]
                   border border-[#1e295d]
                   rounded-2xl
                   shadow-2xl shadow-black/30
                   overflow-hidden">


            <!-- HEADER -->

            <div
                class="px-6 sm:px-8 pt-8 pb-6
                       bg-[#0b102b]
                       border-b border-[#1e295d]">

                <div class="flex items-center gap-4">


                    <div
                        class="w-12 h-12
                               rounded-xl
                               bg-blue-500/10
                               border border-blue-500/20
                               flex items-center justify-center
                               shrink-0">

                        <svg
                            class="w-6 h-6 text-blue-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />

                        </svg>

                    </div>


                    <div>

                        <p
                            class="text-[11px]
                                   font-bold
                                   uppercase
                                   tracking-[0.15em]
                                   text-blue-400">

                            Seguridad de cuenta

                        </p>


                        <h1
                            class="mt-1
                                   text-xl
                                   sm:text-2xl
                                   font-bold
                                   text-white">

                            Nueva contraseña

                        </h1>

                    </div>

                </div>

            </div>



            <!-- CONTENT -->

            <div
                class="px-6 sm:px-8 py-8">


                <p
                    class="text-sm
                           text-slate-400
                           leading-relaxed
                           mb-7">

                    Crea una nueva contraseña para recuperar el acceso
                    a tu cuenta de TicketPro.

                </p>



                <!-- ERRORES -->

                @if ($errors->any())

                    <div
                        class="mb-6
                               rounded-xl
                               border border-red-500/30
                               bg-red-950/30
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

                                    <p class="text-xs text-red-300">

                                        {{ $error }}

                                    </p>

                                @endforeach

                            </div>

                        </div>

                    </div>

                @endif



                <!-- FORM -->

                <form
                    method="POST"
                    action="{{ route('password.update') }}"
                    class="space-y-5">

                    @csrf


                    <input
                        type="hidden"
                        name="token"
                        value="{{ $token }}">



                    <!-- EMAIL -->

                    <div>

                        <label
                            for="email"
                            class="block mb-2
                                   text-xs
                                   font-semibold
                                   uppercase
                                   tracking-wider
                                   text-slate-300">

                            Correo electrónico

                        </label>


                        <div class="relative">


                            <div
                                class="absolute inset-y-0 left-0
                                       flex items-center
                                       pl-3.5
                                       pointer-events-none">

                                <svg
                                    class="w-5 h-5 text-slate-500"
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
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email', $email) }}"
                                required
                                autocomplete="email"
                                class="w-full
                                       bg-[#0b102b]
                                       border border-[#1e295d]
                                       rounded-xl
                                       py-3.5
                                       pl-11
                                       pr-4
                                       text-sm
                                       text-white
                                       placeholder-slate-600
                                       outline-none
                                       transition
                                       focus:border-blue-500
                                       focus:ring-2
                                       focus:ring-blue-500/10">

                        </div>

                    </div>



                    <!-- PASSWORD -->

                    <div>

                        <label
                            for="password"
                            class="block mb-2
                                   text-xs
                                   font-semibold
                                   uppercase
                                   tracking-wider
                                   text-slate-300">

                            Nueva contraseña

                        </label>


                        <div class="relative">


                            <div
                                class="absolute inset-y-0 left-0
                                       flex items-center
                                       pl-3.5
                                       pointer-events-none">

                                <svg
                                    class="w-5 h-5 text-slate-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />

                                </svg>

                            </div>


                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                placeholder="Ingresa tu nueva contraseña"
                                x-model="password"
                                class="w-full
                                       bg-[#0b102b]
                                       border border-[#1e295d]
                                       rounded-xl
                                       py-3.5
                                       pl-11
                                       pr-4
                                       text-sm
                                       text-white
                                       placeholder-slate-600
                                       outline-none
                                       transition
                                       focus:border-blue-500
                                       focus:ring-2
                                       focus:ring-blue-500/10">

                        </div>

                    </div>



                    <!-- CONFIRM PASSWORD -->

                    <div>

                        <label
                            for="password_confirmation"
                            class="block mb-2
                                   text-xs
                                   font-semibold
                                   uppercase
                                   tracking-wider
                                   text-slate-300">

                            Confirmar contraseña

                        </label>


                        <div class="relative">


                            <div
                                class="absolute inset-y-0 left-0
                                       flex items-center
                                       pl-3.5
                                       pointer-events-none">

                                <svg
                                    class="w-5 h-5 text-slate-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622C17.176 19.29 21 14.591 21 9c0-.695-.059-1.376-.172-2.04z" />

                                </svg>

                            </div>


                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="Repite tu nueva contraseña"
                                x-model="passwordConfirmation"
                                class="w-full
                                       bg-[#0b102b]
                                       border border-[#1e295d]
                                       rounded-xl
                                       py-3.5
                                       pl-11
                                       pr-4
                                       text-sm
                                       text-white
                                       placeholder-slate-600
                                       outline-none
                                       transition
                                       focus:border-blue-500
                                       focus:ring-2
                                       focus:ring-blue-500/10">

                        </div>



                        <!-- COINCIDENCIA -->

                        <div
                            x-show="passwordConfirmation.length > 0"
                            x-transition
                            class="mt-2">

                            <p
                                x-show="coinciden"
                                class="flex items-center gap-1.5 text-[11px] text-emerald-400">

                                <span
                                    class="w-1.5 h-1.5 rounded-full bg-emerald-400">
                                </span>

                                Las contraseñas coinciden.

                            </p>


                            <p
                                x-show="!coinciden"
                                class="flex items-center gap-1.5 text-[11px] text-red-400">

                                <span
                                    class="w-1.5 h-1.5 rounded-full bg-red-400">
                                </span>

                                Las contraseñas no coinciden.

                            </p>

                        </div>

                    </div>



                    <!-- REQUISITOS -->

                    <div
                        class="rounded-xl
                               border border-[#1e295d]
                               bg-[#0b102b]
                               px-4 py-4">

                        <div class="flex items-center justify-between mb-3">

                            <p
                                class="text-xs
                                       font-semibold
                                       text-slate-300">

                                Requisitos de seguridad

                            </p>


                            <span
                                x-show="contraseñaValida"
                                x-transition
                                class="text-[10px]
                                       font-bold
                                       uppercase
                                       tracking-wider
                                       text-emerald-400">

                                Contraseña segura

                            </span>

                        </div>


                        <div class="space-y-2">


                            <!-- LONGITUD -->

                            <p
                                class="flex items-center gap-2 text-[11px]"
                                :class="tieneLongitud
                                    ? 'text-emerald-400'
                                    : 'text-slate-500'">

                                <span
                                    class="flex items-center justify-center
                                           w-4 h-4
                                           rounded-full
                                           border"
                                    :class="tieneLongitud
                                        ? 'border-emerald-500 bg-emerald-500/10'
                                        : 'border-slate-700'">

                                    <svg
                                        x-show="tieneLongitud"
                                        class="w-2.5 h-2.5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="3"
                                            d="M5 13l4 4L19 7" />

                                    </svg>

                                </span>

                                Mínimo 8 caracteres

                            </p>



                            <!-- MAYUSCULA -->

                            <p
                                class="flex items-center gap-2 text-[11px]"
                                :class="tieneMayuscula
                                    ? 'text-emerald-400'
                                    : 'text-slate-500'">

                                <span
                                    class="flex items-center justify-center
                                           w-4 h-4
                                           rounded-full
                                           border"
                                    :class="tieneMayuscula
                                        ? 'border-emerald-500 bg-emerald-500/10'
                                        : 'border-slate-700'">

                                    <svg
                                        x-show="tieneMayuscula"
                                        class="w-2.5 h-2.5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="3"
                                            d="M5 13l4 4L19 7" />

                                    </svg>

                                </span>

                                Una letra mayúscula

                            </p>



                            <!-- MINUSCULA -->

                            <p
                                class="flex items-center gap-2 text-[11px]"
                                :class="tieneMinuscula
                                    ? 'text-emerald-400'
                                    : 'text-slate-500'">

                                <span
                                    class="flex items-center justify-center
                                           w-4 h-4
                                           rounded-full
                                           border"
                                    :class="tieneMinuscula
                                        ? 'border-emerald-500 bg-emerald-500/10'
                                        : 'border-slate-700'">

                                    <svg
                                        x-show="tieneMinuscula"
                                        class="w-2.5 h-2.5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="3"
                                            d="M5 13l4 4L19 7" />

                                    </svg>

                                </span>

                                Una letra minúscula

                            </p>



                            <!-- NUMERO -->

                            <p
                                class="flex items-center gap-2 text-[11px]"
                                :class="tieneNumero
                                    ? 'text-emerald-400'
                                    : 'text-slate-500'">

                                <span
                                    class="flex items-center justify-center
                                           w-4 h-4
                                           rounded-full
                                           border"
                                    :class="tieneNumero
                                        ? 'border-emerald-500 bg-emerald-500/10'
                                        : 'border-slate-700'">

                                    <svg
                                        x-show="tieneNumero"
                                        class="w-2.5 h-2.5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="3"
                                            d="M5 13l4 4L19 7" />

                                    </svg>

                                </span>

                                Un número

                            </p>



                            <!-- SIMBOLO -->

                            <p
                                class="flex items-center gap-2 text-[11px]"
                                :class="tieneSimbolo
                                    ? 'text-emerald-400'
                                    : 'text-slate-500'">

                                <span
                                    class="flex items-center justify-center
                                           w-4 h-4
                                           rounded-full
                                           border"
                                    :class="tieneSimbolo
                                        ? 'border-emerald-500 bg-emerald-500/10'
                                        : 'border-slate-700'">

                                    <svg
                                        x-show="tieneSimbolo"
                                        class="w-2.5 h-2.5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="3"
                                            d="M5 13l4 4L19 7" />

                                    </svg>

                                </span>

                                Un símbolo especial
                                <span class="text-slate-600">
                                    (! @ # $ % &)
                                </span>

                            </p>

                        </div>

                    </div>



                    <!-- BUTTON -->

                    <button
                        type="submit"
                        :disabled="!contraseñaValida"
                        :class="contraseñaValida
                            ? 'bg-blue-600 hover:bg-blue-500 active:bg-blue-700 cursor-pointer shadow-blue-950/30 hover:shadow-blue-900/40'
                            : 'bg-slate-800 text-slate-600 cursor-not-allowed border border-slate-700 shadow-none'"
                        class="w-full
                               flex items-center
                               justify-center
                               gap-2
                               text-white
                               font-semibold
                               text-sm
                               rounded-xl
                               py-3.5
                               transition
                               duration-200
                               shadow-lg">

                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 13l4 4L19 7" />

                        </svg>

                        Restablecer contraseña

                    </button>

                </form>



                <!-- VOLVER -->

                <div class="mt-6 text-center">

                    <a
                        href="{{ route('login') }}"
                        class="inline-flex items-center
                               gap-2
                               text-xs
                               font-semibold
                               text-slate-500
                               hover:text-blue-400
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

        </div>



        <!-- FOOTER -->

        <div class="text-center mt-6">

            <p class="text-[11px] text-slate-600">

                © {{ date('Y') }} TicketPro

            </p>

            <p class="text-[10px] text-slate-700 mt-1">

                Este proceso está protegido y es confidencial.

            </p>

        </div>

    </div>


</body>

</html>
