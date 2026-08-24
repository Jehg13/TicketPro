function registrarPerfilSeguridad() {

    if (typeof Alpine === 'undefined') {
        console.error('Alpine.js no está disponible.');
        return;
    }

    // Evita registrar el componente más de una vez
    if (Alpine.data('perfilSeguridad')) {
        return;
    }

    Alpine.data('perfilSeguridad', () => ({

        modalPassword: false,
        modalMFA: false,
        confirmar: false,

        cargandoMFA: false,

        mfaCodigo: '',
        mfaMensaje: '',

        qrCodeUrl: '',
        secretKey: '',

        loginOriginal:
            window.perfilSeguridadConfig?.loginOriginal ?? '',

        emailOriginal:
            window.perfilSeguridadConfig?.emailOriginal ?? '',

        loginModificado: false,
        emailModificado: false,

        init() {

            // console.log('perfilSeguridad inicializado');

        },

        verificarCambios() {

            const form = this.$refs.form;

            if (!form) {
                return;
            }

            const login =
                form.querySelector('[name="login"]');

            const email =
                form.querySelector('[name="email"]');

            this.loginModificado = login
                ? login.value.trim() !==
                  this.loginOriginal.trim()
                : false;

            this.emailModificado = email
                ? email.value.trim() !==
                  this.emailOriginal.trim()
                : false;
        },

        abrirConfirmacion() {

            this.verificarCambios();

            this.confirmar = true;

            this.$nextTick(() => {

                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }

            });
        },

        cerrarConfirmacion() {

            this.confirmar = false;

        },

        async abrirMFA() {

            // console.log('Abriendo configuración MFA...');

            this.modalMFA = true;

            this.cargandoMFA = true;

            this.mfaMensaje = '';
            this.mfaCodigo = '';

            this.qrCodeUrl = '';
            this.secretKey = '';

            try {

                const url =
                    window.perfilSeguridadConfig?.mfaConfigurar;

                if (!url) {

                    throw new Error(
                        'No está configurada la ruta mfaConfigurar.'
                    );
                }

                const response = await fetch(
                    url,
                    {
                        method: 'GET',

                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }
                );

                if (!response.ok) {

                    throw new Error(
                        `HTTP ${response.status}`
                    );
                }

                const data =
                    await response.json();

                // console.log(
                //     'RESPUESTA MFA:',
                //     data
                // );

                if (!data.success) {

                    this.mfaMensaje =
                        data.message ||
                        'No se pudo configurar la verificación MFA.';

                    return;
                }

                this.qrCodeUrl =
                    data.qrCodeUrl || '';

                this.secretKey =
                    data.secretKey || '';

                this.$nextTick(() => {

                    const contenedor =
                        document.getElementById('mfaQr');

                    if (!contenedor) {

                        console.error(
                            'No existe el contenedor #mfaQr'
                        );

                        return;
                    }

                    contenedor.innerHTML = '';

                    if (!this.qrCodeUrl) {

                        this.mfaMensaje =
                            'No se recibió la información para generar el código QR.';

                        return;
                    }

                    if (typeof QRCode === 'undefined') {

                        console.error(
                            'QRCode no está cargado.'
                        );

                        this.mfaMensaje =
                            'No se pudo cargar el generador del código QR.';

                        return;
                    }

                    new QRCode(
                        contenedor,
                        {
                            text: this.qrCodeUrl,

                            width: 220,
                            height: 220,

                            colorDark: '#000000',
                            colorLight: '#ffffff',

                            correctLevel:
                                QRCode.CorrectLevel.H
                        }
                    );

                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }

                });

            } catch (error) {

                console.error(
                    'Error generando MFA:',
                    error
                );

                this.mfaMensaje =
                    'Ocurrió un error al preparar la configuración MFA.';

            } finally {

                this.cargandoMFA = false;

            }
        },

        cerrarMFA() {

            this.modalMFA = false;

            this.mfaCodigo = '';
            this.mfaMensaje = '';

            this.cargandoMFA = false;

            this.qrCodeUrl = '';
            this.secretKey = '';

            this.$nextTick(() => {

                const contenedor =
                    document.getElementById('mfaQr');

                if (contenedor) {

                    contenedor.innerHTML = '';

                }

            });
        },

        async confirmarMFA() {

            if (
                !this.mfaCodigo ||
                this.mfaCodigo.length !== 6
            ) {

                this.mfaMensaje =
                    'Ingresa un código de 6 dígitos.';

                return;
            }

            if (!/^\d{6}$/.test(this.mfaCodigo)) {

                this.mfaMensaje =
                    'El código debe contener únicamente 6 números.';

                return;
            }

            this.cargandoMFA = true;

            this.mfaMensaje = '';

            try {

                const csrfToken =
                    document
                        .querySelector(
                            'meta[name="csrf-token"]'
                        )
                        ?.getAttribute('content');

                if (!csrfToken) {

                    throw new Error(
                        'No se encontró el token CSRF.'
                    );
                }

                const url =
                    window.perfilSeguridadConfig?.mfaActivar;

                if (!url) {

                    throw new Error(
                        'No está configurada la ruta mfaActivar.'
                    );
                }

                const response =
                    await fetch(
                        url,
                        {
                            method: 'POST',

                            headers: {
                                'Content-Type':
                                    'application/json',

                                'X-CSRF-TOKEN':
                                    csrfToken,

                                'Accept':
                                    'application/json',

                                'X-Requested-With':
                                    'XMLHttpRequest'
                            },

                            body: JSON.stringify({
                                codigo:
                                    this.mfaCodigo
                            })
                        }
                    );

                if (!response.ok) {

                    const texto =
                        await response.text();

                    console.error(
                        'RESPUESTA HTTP MFA:',
                        response.status,
                        texto
                    );

                    throw new Error(
                        `HTTP ${response.status}`
                    );
                }

                const data =
                    await response.json();

                // console.log(
                //     'RESPUESTA VERIFICACIÓN MFA:',
                //     data
                // );

                if (!data.success) {

                    this.mfaMensaje =
                        data.message ||
                        'El código MFA no es válido.';

                    return;
                }

                this.mfaCodigo = '';

                this.mfaMensaje = '';

                this.modalMFA = false;

                window.location.reload();

            } catch (error) {

                console.error(
                    'Error verificando MFA:',
                    error
                );

                this.mfaMensaje =
                    'No fue posible verificar el código MFA.';

            } finally {

                this.cargandoMFA = false;

            }
        },

        async desactivarMFA() {

            if (
                !this.mfaCodigo ||
                this.mfaCodigo.length !== 6
            ) {

                this.mfaMensaje =
                    'Ingresa el código de 6 dígitos.';

                return;
            }

            if (!/^\d{6}$/.test(this.mfaCodigo)) {

                this.mfaMensaje =
                    'El código debe contener únicamente 6 números.';

                return;
            }

            this.cargandoMFA = true;

            this.mfaMensaje = '';

            try {

                const csrfToken =
                    document
                        .querySelector(
                            'meta[name="csrf-token"]'
                        )
                        ?.getAttribute('content');

                if (!csrfToken) {

                    throw new Error(
                        'No se encontró el token CSRF.'
                    );
                }

                const url =
                    window.perfilSeguridadConfig?.mfaDesactivar;

                if (!url) {

                    throw new Error(
                        'No está configurada la ruta mfaDesactivar.'
                    );
                }

                const response =
                    await fetch(
                        url,
                        {
                            method: 'POST',

                            headers: {
                                'Content-Type':
                                    'application/json',

                                'X-CSRF-TOKEN':
                                    csrfToken,

                                'Accept':
                                    'application/json',

                                'X-Requested-With':
                                    'XMLHttpRequest'
                            },

                            body: JSON.stringify({
                                codigo:
                                    this.mfaCodigo
                            })
                        }
                    );

                const data =
                    await response.json();

                // console.log(
                //     'RESPUESTA DESACTIVAR MFA:',
                //     data
                // );

                if (
                    !response.ok ||
                    !data.success
                ) {

                    this.mfaMensaje =
                        data.message ||
                        'El código no es válido.';

                    return;
                }

                this.modalMFA = false;

                this.mfaCodigo = '';

                if (data.redirect) {

                    window.location.href =
                        data.redirect;

                } else {

                    window.location.reload();

                }

            } catch (error) {

                console.error(
                    'Error desactivando MFA:',
                    error
                );

                this.mfaMensaje =
                    'Ocurrió un error al desactivar MFA.';

            } finally {

                this.cargandoMFA = false;

            }
        }

    }));
}

if (typeof Alpine !== 'undefined') {

    registrarPerfilSeguridad();

} else {

    document.addEventListener(
        'alpine:init',
        registrarPerfilSeguridad,
        {
            once: true
        }
    );

}