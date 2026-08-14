document.addEventListener('DOMContentLoaded', function () {

    const formulario =
        document.getElementById('formComentario');

    const listaComentarios =
        document.getElementById('listaComentarios');

    const botonEnviar =
        document.getElementById('btnEnviarComentario');

    const inputComentario =
        document.getElementById('inputComentario');


    /*
     * ---------------------------------------------------------
     * VERIFICAR FORMULARIO
     * ---------------------------------------------------------
     */

    if (!formulario) {

        console.warn(
            'No se encontró #formComentario'
        );

        return;
    }


    let ticketActual = null;


    /*
     * ---------------------------------------------------------
     * CARGAR TICKET Y COMENTARIOS
     * ---------------------------------------------------------
     *
     * Alpine es el encargado de mostrar los comentarios.
     *
     * ---------------------------------------------------------
     */

    window.cargarTicketComentarios =
        function (
            ticketId,
            comentarios = []
        ) {

            ticketActual =
                ticketId;


            formulario.action =
                `/tickets/${ticketId}/comentarios`;


            formulario.reset();

        };


    /*
     * ---------------------------------------------------------
     * OBTENER DATOS DE ALPINE
     * ---------------------------------------------------------
     */

    function obtenerAlpineTicketModal() {

        const modal =
            document.querySelector(
                '[x-data*="ticketModal"]'
            );


        if (
            !modal ||
            !modal._x_dataStack
        ) {

            return null;

        }


        for (
            const data
            of modal._x_dataStack
        ) {

            if (data) {

                return data;

            }

        }


        return null;

    }


    /*
     * ---------------------------------------------------------
     * OBTENER TICKET ACTUAL
     * ---------------------------------------------------------
     */

    function obtenerTicketActual() {

        /*
         * Si ya tenemos el ticket guardado,
         * utilizarlo directamente.
         */

        if (ticketActual) {

            return ticketActual;

        }


        /*
         * Intentar obtenerlo desde el formulario.
         */

        const formularioData =
            formulario._x_dataStack?.[0];


        if (
            formularioData?.selectedTicket?.id
        ) {

            ticketActual =
                formularioData.selectedTicket.id;

            return ticketActual;

        }


        /*
         * Intentar obtenerlo desde Alpine.
         */

        const alpineData =
            obtenerAlpineTicketModal();


        if (
            alpineData?.selectedTicket?.id
        ) {

            ticketActual =
                alpineData.selectedTicket.id;

            return ticketActual;

        }


        return null;

    }


    /*
     * ---------------------------------------------------------
     * DETECTAR CUANDO ALPINE SELECCIONA UN TICKET
     * ---------------------------------------------------------
     */

    document.addEventListener(
        'click',
        function (event) {

            const boton =
                event.target.closest('button');


            if (!boton) {

                return;

            }


            /*
             * Esperar a que Alpine termine de actualizar
             * selectedTicket.
             */

            setTimeout(
                function () {

                    const alpineData =
                        obtenerAlpineTicketModal();


                    if (
                        !alpineData ||
                        !alpineData.selectedTicket ||
                        !alpineData.selectedTicket.id
                    ) {

                        return;

                    }


                    ticketActual =
                        alpineData.selectedTicket.id;


                    formulario.action =
                        `/tickets/${ticketActual}/comentarios`;

                },
                0
            );

        }
    );


    /*
     * ---------------------------------------------------------
     * AGREGAR COMENTARIO AL ESTADO DE ALPINE
     * ---------------------------------------------------------
     *
     * IMPORTANTE:
     *
     * Usamos unshift() en lugar de push().
     *
     * push():
     *
     * comentario viejo
     * comentario viejo
     * comentario nuevo
     *
     * unshift():
     *
     * comentario nuevo
     * comentario viejo
     * comentario viejo
     *
     * De esta manera el comentario nuevo aparece ARRIBA.
     *
     * ---------------------------------------------------------
     */

    function agregarComentarioAlpine(comentario) {

        if (!comentario) {

            return;

        }


        const alpineData =
            obtenerAlpineTicketModal();


        if (!alpineData) {

            console.warn(
                'No se encontró el componente Alpine ticketModal.'
            );

            return;

        }


        /*
         * Asegurarnos de que comentarios sea un array.
         */

        if (
            !Array.isArray(
                alpineData.comentarios
            )
        ) {

            alpineData.comentarios = [];

        }


        /*
         * Evitar comentarios duplicados.
         */

        if (
            comentario.id &&
            alpineData.comentarios.some(
                function (item) {

                    return (
                        item &&
                        item.id === comentario.id
                    );

                }
            )
        ) {

            return;

        }


        /*
         * -----------------------------------------------------
         * AGREGAR AL PRINCIPIO
         * -----------------------------------------------------
         *
         * Antes teníamos:
         *
         * alpineData.comentarios.push(comentario);
         *
         * Ahora usamos:
         *
         * alpineData.comentarios.unshift(comentario);
         *
         * -----------------------------------------------------
         */

        alpineData.comentarios.unshift(
            comentario
        );


        /*
         * Alpine actualizará automáticamente:
         *
         * x-if="comentarios.length === 0"
         *
         * y:
         *
         * x-for="comentario in comentarios"
         *
         */


        /*
         * Esperar a que Alpine termine de renderizar.
         */

        if (
            alpineData.$nextTick
        ) {

            alpineData.$nextTick(
                function () {

                    /*
                     * Inicializar iconos Lucide.
                     */

                    if (
                        window.lucide
                    ) {

                        window.lucide.createIcons();

                    }


                    /*
                     * Mantener el scroll arriba porque
                     * los comentarios nuevos aparecen arriba.
                     */

                    if (listaComentarios) {

                        listaComentarios.scrollTop =
                            0;

                    }

                }
            );

        } else {

            /*
             * Fallback por si $nextTick no está disponible.
             */

            setTimeout(
                function () {

                    if (
                        window.lucide
                    ) {

                        window.lucide.createIcons();

                    }


                    if (listaComentarios) {

                        listaComentarios.scrollTop =
                            0;

                    }

                },
                50
            );

        }

    }


    /*
     * ---------------------------------------------------------
     * ENVIAR COMENTARIO
     * ---------------------------------------------------------
     */

    formulario.addEventListener(
        'submit',
        async function (e) {

            e.preventDefault();

            e.stopPropagation();


            /*
             * Obtener ticket actual.
             */

            const idTicket =
                obtenerTicketActual();


            if (!idTicket) {

                console.error(
                    'No hay un ticket seleccionado.'
                );


                alert(
                    'Selecciona un ticket antes de enviar un comentario.'
                );


                return;

            }


            /*
             * Actualizar action.
             */

            formulario.action =
                `/tickets/${idTicket}/comentarios`;


            /*
             * Crear FormData.
             */

            const formData =
                new FormData(formulario);


            const mensaje =
                formData.get('mensaje');


            const archivo =
                formData.get('archivo');


            /*
             * Verificar mensaje o archivo.
             */

            if (
                (!mensaje ||
                    mensaje.trim() === '') &&
                (!archivo ||
                    archivo.size === 0)
            ) {

                alert(
                    'Escribe un comentario o selecciona un archivo.'
                );


                return;

            }


            /*
             * Deshabilitar botón.
             */

            if (botonEnviar) {

                botonEnviar.disabled =
                    true;


                botonEnviar.classList.add(
                    'opacity-50',
                    'pointer-events-none'
                );

            }


            try {

                /*
                 * -------------------------------------------------
                 * PETICIÓN AL SERVIDOR
                 * -------------------------------------------------
                 */

                const response =
                    await fetch(
                        formulario.action,
                        {
                            method: 'POST',

                            body: formData,

                            headers: {

                                'X-Requested-With':
                                    'XMLHttpRequest',

                                'Accept':
                                    'application/json'

                            }

                        }
                    );


                /*
                 * Obtener tipo de respuesta.
                 */

                const contentType =
                    response.headers.get(
                        'content-type'
                    );


                let data = null;


                if (
                    contentType &&
                    contentType.includes(
                        'application/json'
                    )
                ) {

                    data =
                        await response.json();

                } else {

                    throw new Error(
                        'El servidor no devolvió una respuesta JSON válida.'
                    );

                }


                /*
                 * Verificar respuesta.
                 */

                if (
                    !response.ok ||
                    !data.success
                ) {

                    throw new Error(
                        data?.message ||
                        'No se pudo enviar el comentario.'
                    );

                }


                /*
                 * -------------------------------------------------
                 * AGREGAR NUEVO COMENTARIO ARRIBA
                 * -------------------------------------------------
                 */

                if (data.comentario) {

                    agregarComentarioAlpine(
                        data.comentario
                    );

                }


                /*
                 * -------------------------------------------------
                 * LIMPIAR FORMULARIO
                 * -------------------------------------------------
                 */

                formulario.reset();


                /*
                 * Limpiar archivo de Alpine.
                 */

                limpiarArchivoAlpine();


            } catch (error) {

                console.error(
                    'Error al enviar comentario:',
                    error
                );


                alert(
                    error.message ||
                    'Ocurrió un error al enviar el comentario.'
                );


            } finally {

                /*
                 * Volver a habilitar botón.
                 */

                if (botonEnviar) {

                    botonEnviar.disabled =
                        false;


                    botonEnviar.classList.remove(
                        'opacity-50',
                        'pointer-events-none'
                    );

                }

            }

        }
    );


    /*
     * ---------------------------------------------------------
     * ENTER PARA ENVIAR
     * ---------------------------------------------------------
     */

    if (inputComentario) {

        inputComentario.addEventListener(
            'keydown',
            function (e) {

                /*
                 * Enter sin Shift = enviar.
                 *
                 * Shift + Enter = salto de línea.
                 */

                if (
                    e.key === 'Enter' &&
                    !e.shiftKey
                ) {

                    e.preventDefault();

                    e.stopPropagation();


                    formulario.requestSubmit();

                }

            }
        );

    }


    /*
     * ---------------------------------------------------------
     * LIMPIAR ARCHIVO DE ALPINE
     * ---------------------------------------------------------
     */

    function limpiarArchivoAlpine() {

        try {

            /*
             * Revisar formulario.
             */

            if (
                formulario._x_dataStack &&
                formulario._x_dataStack.length
            ) {

                formulario._x_dataStack.forEach(
                    function (data) {

                        if (
                            data &&
                            Object.prototype.hasOwnProperty.call(
                                data,
                                'archivoAdjunto'
                            )
                        ) {

                            data.archivoAdjunto =
                                null;

                        }

                    }
                );

            }


            /*
             * Revisar modal.
             */

            const modal =
                document.querySelector(
                    '[x-data*="ticketModal"]'
                );


            if (
                modal &&
                modal._x_dataStack
            ) {

                modal._x_dataStack.forEach(
                    function (data) {

                        if (
                            data &&
                            Object.prototype.hasOwnProperty.call(
                                data,
                                'archivoAdjunto'
                            )
                        ) {

                            data.archivoAdjunto =
                                null;

                        }

                    }
                );

            }


            /*
             * Limpiar físicamente el input file.
             */

            const inputArchivo =
                formulario.querySelector(
                    'input[type="file"]'
                );


            if (inputArchivo) {

                inputArchivo.value =
                    '';

            }

        } catch (error) {

            console.warn(
                'No se pudo limpiar archivoAdjunto:',
                error
            );

        }

    }


    /*
     * ---------------------------------------------------------
     * INICIALIZAR LUCIDE
     * ---------------------------------------------------------
     */

    document.addEventListener(
        'alpine:initialized',
        function () {

            if (
                window.lucide
            ) {

                window.lucide.createIcons();

            }

        }
    );


    /*
     * ---------------------------------------------------------
     * OBSERVAR CAMBIOS DEL MODAL
     * ---------------------------------------------------------
     *
     * Mantiene actualizada la referencia del ticket.
     *
     * ---------------------------------------------------------
     */

    setInterval(
        function () {

            const alpineData =
                obtenerAlpineTicketModal();


            if (
                alpineData?.selectedTicket?.id
            ) {

                const nuevoTicket =
                    alpineData.selectedTicket.id;


                if (
                    ticketActual !== nuevoTicket
                ) {

                    ticketActual =
                        nuevoTicket;


                    formulario.action =
                        `/tickets/${ticketActual}/comentarios`;

                }

            }

        },
        500
    );

});