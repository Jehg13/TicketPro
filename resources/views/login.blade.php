<!DOCTYPE html>
<html lang="es" class="h-full overflow-hidden">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TicketPro | Iniciar sesión</title>

    <link rel="icon" type="image/png"
        href="{{ asset('storage/images/logo.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full w-screen overflow-hidden">

    <div class="relative h-screen w-screen flex">

        {{-- ========================================================= --}}
        {{-- FONDOS --}}
        {{-- ========================================================= --}}

        <div class="absolute inset-0 flex">

            <img
                src="{{ asset('storage/images/login-left.png') }}"
                alt="Fondo lateral izquierdo"
                class="w-1/2 h-full object-cover">

            <img
                src="{{ asset('storage/images/login-right.png') }}"
                alt="Fondo lateral derecho"
                class="w-1/2 h-full object-cover">

        </div>

        <div class="absolute inset-0 bg-black/10 pointer-events-none"></div>


        {{-- ========================================================= --}}
        {{-- LOGIN --}}
        {{-- ========================================================= --}}

        <div class="relative z-10 flex items-center justify-center w-full h-full px-4">

            <div
                class="w-full max-w-md
                       bg-[#0b0f19]/95
                       border border-blue-600
                       rounded-2xl
                       shadow-[0_0_40px_rgba(37,99,235,0.25)]
                       px-8 py-7">

                {{-- LOGO / TITULO --}}

                <div class="text-center">

                    <h1 class="text-4xl font-extrabold tracking-tight">

                        <span class="text-white">
                            TICKET
                        </span>

                        <span class="text-brandblue-500">
                            PRO
                        </span>

                    </h1>

                    <p class="text-gray-400 text-sm mt-1">
                        Plataforma de soporte interno
                    </p>

                </div>


                {{-- BIENVENIDA --}}

                <div class="text-center mt-5">

                    <h2 class="text-2xl font-bold text-white">
                        Bienvenido de nuevo
                    </h2>

                    <p class="text-gray-400 text-sm mt-1 leading-snug">
                        Inicia sesión para continuar<br>
                        y gestionar tus tickets
                    </p>

                </div>


                {{-- ================================================= --}}
                {{-- FORMULARIO LOGIN --}}
                {{-- ================================================= --}}

                <form
                    method="POST"
                    action="{{ route('login.process') }}"
                    class="mt-5 space-y-3">

                    @csrf


                    {{-- USUARIO --}}

                    <div>

                        <label
                            for="email"
                            class="block text-white text-sm font-semibold mb-1">

                            Correo o usuario:

                        </label>

                        <input
                            id="email"
                            name="email"
                            type="text"
                            required
                            autofocus
                            autocomplete="username"
                            value="{{ old('email') }}"
                            placeholder="Correo electrónico o usuario"
                            class="w-full rounded-lg
                                   bg-[#161b28]
                                   border border-blue-600/70
                                   text-white text-sm
                                   px-3 py-2.5
                                   placeholder-gray-500
                                   focus:outline-none
                                   focus:ring-2
                                   focus:ring-brandblue-500
                                   focus:border-brandblue-500">

                        @error('email')

                            <p class="text-red-400 text-xs mt-1">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- CONTRASEÑA --}}

                    <div>

                        <label
                            for="password"
                            class="block text-white text-sm font-semibold mb-1">

                            Contraseña:

                        </label>

                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            autocomplete="current-password"
                            class="w-full rounded-lg
                                   bg-[#161b28]
                                   border border-blue-600/70
                                   text-white text-sm
                                   px-3 py-2.5
                                   placeholder-gray-500
                                   focus:outline-none
                                   focus:ring-2
                                   focus:ring-brandblue-500
                                   focus:border-brandblue-500">

                        @error('password')

                            <p class="text-red-400 text-xs mt-1">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- RECORDAR / OLVIDE CONTRASEÑA --}}

                    <div class="flex items-center justify-between pt-1">

                        <label
                            class="flex items-center gap-2
                                   text-white text-sm
                                   cursor-pointer
                                   select-none">

                            <input
                                type="checkbox"
                                name="remember"
                                class="w-4 h-4 rounded
                                       bg-[#161b28]
                                       border-blue-600
                                       text-brandblue-500
                                       focus:ring-brandblue-500">

                            Recordarme

                        </label>


                        @if (Route::has('olvidecontraseña'))

                            <a
                                href="{{ route('olvidecontraseña') }}"
                                class="text-gray-300 text-sm
                                       hover:text-white
                                       hover:underline">

                                ¿Olvidaste tu contraseña?

                            </a>

                        @endif

                    </div>


                    {{-- BOTON LOGIN --}}

                    <button
                        type="submit"
                        class="w-full mt-1
                               flex items-center justify-center gap-2
                               rounded-lg py-2.5
                               text-white text-sm
                               font-bold tracking-wide uppercase
                               bg-gradient-to-r
                               from-brandblue-400
                               to-brandblue-600
                               hover:from-brandblue-500
                               hover:to-brandblue-600
                               shadow-[0_4px_14px_rgba(37,99,235,0.5)]
                               transition">

                        Iniciar sesión

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2.5"
                            stroke-linecap="round"
                            stroke-linejoin="round">

                            <line
                                x1="5"
                                y1="12"
                                x2="19"
                                y2="12">
                            </line>

                            <polyline
                                points="12 5 19 12 12 19">
                            </polyline>

                        </svg>

                    </button>

                </form>


                {{-- ================================================= --}}
                {{-- ACCESO CORPORATIVO --}}
                {{-- ================================================= --}}

                <div class="flex items-center gap-3 mt-5">

                    <div class="flex-1 h-px bg-gray-600/60"></div>

                    <span class="text-gray-400 text-xs whitespace-nowrap">
                        Acceso corporativo
                    </span>

                    <div class="flex-1 h-px bg-gray-600/60"></div>

                </div>


                {{-- ================================================= --}}
                {{-- AVISO --}}
                {{-- ================================================= --}}

                <div
                    class="mt-4
                           border border-blue-600/70
                           rounded-xl
                           bg-[#0d1220]
                           px-4 py-3
                           flex items-start gap-3">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-9 h-9 flex-shrink-0"
                        viewBox="0 0 24 24"
                        fill="white">

                        <path
                            d="M12 1.5l8 3v6.2c0 5.1-3.4 9.5-8 10.8-4.6-1.3-8-5.7-8-10.8V4.5l8-3z" />

                        <path
                            d="M10.2 14.3l-2.4-2.4 1.1-1.1 1.3 1.3 4-4 1.1 1.1-5.1 5.1z"
                            fill="#0d1220" />

                    </svg>


                    <div>

                        <p class="text-white text-sm font-bold">
                            Acceso exclusivo
                        </p>

                        <p class="text-gray-400 text-xs leading-snug mt-0.5">
                            Esta plataforma es de uso exclusivo para personal
                            autorizado en la empresa
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- MODAL MFA --}}
    {{-- ========================================================= --}}

    @if (session('mfa_required'))

        <div
            id="mfaModal"
            class="fixed inset-0 z-[200]
                   flex items-center justify-center
                   bg-black/80
                   backdrop-blur-sm
                   px-4">

            <div
                class="relative
                       w-full max-w-sm
                       bg-[#0b0f19]
                       border border-blue-500/40
                       rounded-2xl
                       shadow-[0_0_60px_rgba(37,99,235,0.30)]
                       px-6 py-7">


                {{-- ICONO --}}

                <div
                    class="mx-auto
                           flex h-16 w-16
                           items-center justify-center
                           rounded-full
                           bg-blue-500/10
                           border border-blue-500/30">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-8 h-8 text-blue-400"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round">

                        <rect
                            x="5"
                            y="11"
                            width="14"
                            height="10"
                            rx="2">
                        </rect>

                        <path d="M8 11V8a4 4 0 018 0v3"></path>

                        <circle
                            cx="12"
                            cy="16"
                            r="1">
                        </circle>

                    </svg>

                </div>


                {{-- TITULO --}}

                <div class="text-center mt-5">

                    <h2 class="text-xl font-bold text-white">
                        Verificación en dos pasos
                    </h2>

                    <p class="text-gray-400 text-sm mt-2 leading-relaxed">

                        Tu cuenta tiene activada la autenticación
                        de dos factores.

                        <br>

                        Abre tu aplicación

                        <span class="text-white font-semibold">
                            Google Authenticator
                        </span>

                        e introduce el código de 6 dígitos.

                    </p>

                </div>


                {{-- ERROR ESPECIFICO MFA --}}

                @if (session('mfa_error'))

                    <div
                        class="mt-5
                               rounded-lg
                               border border-red-500/30
                               bg-red-500/10
                               px-4 py-3">

                        <p class="text-red-400 text-sm text-center">
                            {{ session('mfa_error') }}
                        </p>

                    </div>

                @endif


               <form
    method="POST"
    action="{{ route('mfa.verificar') }}"
    class="mt-6">

    @csrf

    <label
        for="codigo"
        class="block text-white text-sm font-semibold mb-2 text-center">

        Código de autenticación

    </label>

    <input
        id="codigo"
        name="codigo"
        type="text"
        inputmode="numeric"
        autocomplete="one-time-code"
        maxlength="6"
        minlength="6"
        pattern="[0-9]{6}"
        required
        autofocus
        placeholder="000000"
        class="w-full rounded-xl
               bg-[#161b28]
               border border-blue-600/70
               text-white
               text-center
               text-2xl
               font-bold
               tracking-[0.5em]
               px-4 py-3
               placeholder-gray-600
               focus:outline-none
               focus:ring-2
               focus:ring-blue-500
               focus:border-blue-500">

    @error('codigo')
        <p class="text-red-400 text-xs text-center mt-2">
            {{ $message }}
        </p>
    @enderror

    <button
        type="submit"
        class="w-full mt-5 rounded-xl py-3
               text-white text-sm font-bold tracking-wide uppercase
               bg-gradient-to-r
               from-brandblue-400
               to-brandblue-600
               hover:from-brandblue-500
               hover:to-brandblue-600
               shadow-[0_4px_14px_rgba(37,99,235,0.5)]
               transition">

        Verificar código

    </button>

</form>


                {{-- AYUDA --}}

                <div
                    class="mt-5 pt-4
                           border-t border-slate-800
                           text-center">

                    <p class="text-gray-500 text-xs leading-relaxed">

                        El código cambia automáticamente cada
                        30 segundos.

                    </p>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- JS MFA --}}
        {{-- ========================================================= --}}

        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const input = document.getElementById('codigo');

                if (!input) {
                    return;
                }

                input.focus();

                input.addEventListener('input', function() {

                    this.value = this.value
                        .replace(/\D/g, '')
                        .slice(0, 6);

                });

            });
        </script>

    @endif


    {{-- ========================================================= --}}
    {{-- MODAL ERROR LOGIN --}}
    {{-- ========================================================= --}}
    {{-- IMPORTANTE:
         NO mostrar este modal cuando MFA está activo,
         porque el error MFA tiene su propio mensaje dentro
         del modal MFA.
    --}}
    {{-- ========================================================= --}}

    @if (session('error') && !session('mfa_required'))

        <div
            id="errorModal"
            class="fixed inset-0 z-[150]
                   flex items-center justify-center
                   bg-black/70
                   backdrop-blur-sm
                   px-4">

            <div
                class="relative
                       w-full max-w-sm
                       bg-[#0b0f19]/95
                       border border-red-500/40
                       rounded-2xl
                       shadow-[0_0_40px_rgba(239,68,68,0.2)]
                       px-6 py-6">


                {{-- CERRAR --}}

                <button
                    type="button"
                    onclick="closeErrorModal()"
                    class="absolute right-3 top-3
                           flex h-7 w-7
                           items-center justify-center
                           rounded-lg
                           text-gray-500
                           hover:text-white
                           hover:bg-white/5
                           transition">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">

                        <line
                            x1="6"
                            y1="6"
                            x2="18"
                            y2="18">
                        </line>

                        <line
                            x1="6"
                            y1="18"
                            x2="18"
                            y2="6">
                        </line>

                    </svg>

                </button>


                {{-- ICONO ERROR --}}

                <div
                    class="mx-auto
                           flex h-14 w-14
                           items-center justify-center
                           rounded-full
                           bg-red-500/10
                           border border-red-500/30">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-7 h-7 text-red-400"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round">

                        <circle
                            cx="12"
                            cy="12"
                            r="9">
                        </circle>

                        <line
                            x1="12"
                            y1="8"
                            x2="12"
                            y2="13">
                        </line>

                        <circle
                            cx="12"
                            cy="16"
                            r="0.8"
                            fill="currentColor"
                            stroke="none">
                        </circle>

                    </svg>

                </div>


                {{-- TEXTO --}}

                <div class="mt-4 text-center">

                    <p class="text-white text-base font-bold">
                        No se pudo iniciar sesión
                    </p>

                    <p class="text-gray-400 text-sm mt-1.5 leading-snug">
                        {{ session('error') }}
                    </p>

                </div>


                {{-- BOTON --}}

                <button
                    type="button"
                    onclick="closeErrorModal()"
                    class="mt-5
                           w-full
                           rounded-lg py-2.5
                           text-white text-sm
                           font-bold
                           tracking-wide uppercase
                           bg-gradient-to-r
                           from-brandblue-400
                           to-brandblue-600
                           hover:from-brandblue-500
                           hover:to-brandblue-600
                           shadow-[0_4px_14px_rgba(37,99,235,0.5)]
                           transition">

                    Entendido

                </button>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- JS ERROR --}}
        {{-- ========================================================= --}}

        <script>
            function closeErrorModal() {

                const modal =
                    document.getElementById('errorModal');

                if (modal) {
                    modal.classList.add('hidden');
                }

            }


            document.addEventListener('keydown', function(event) {

                if (event.key === 'Escape') {
                    closeErrorModal();
                }

            });


            const errorModal =
                document.getElementById('errorModal');

            if (errorModal) {

                errorModal.addEventListener('click', function(event) {

                    if (event.target === this) {
                        closeErrorModal();
                    }

                });

            }
        </script>

    @endif

</body>

</html>
