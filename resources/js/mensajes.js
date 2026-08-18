document.addEventListener('DOMContentLoaded', function () {

    const formulario = document.getElementById('formComentario');
    const botonEnviar = document.getElementById('btnEnviarComentario');
    const inputComentario = document.getElementById('inputComentario');

    if (!formulario) {
        return;
    }

    let ticketActual = null;
    let enviandoComentario = false;

    function obtenerAlpineTicketModal() {

        const modal = document.querySelector('[x-data*="ticketModal"]');

        if (!modal || !modal._x_dataStack) {
            return null;
        }

        for (const data of modal._x_dataStack) {
            if (data) {
                return data;
            }
        }

        return null;
    }

    function obtenerFechaComentario(comentario) {

        if (!comentario) {
            return 0;
        }

        const fecha =
            comentario.created_at ||
            comentario.fecha ||
            comentario.createdAt;

        if (!fecha) {
            return 0;
        }

        const timestamp = new Date(fecha).getTime();

        return isNaN(timestamp) ? 0 : timestamp;
    }

    function ordenarComentarios(comentarios) {

        if (!Array.isArray(comentarios)) {
            return [];
        }

        return [...comentarios].sort(function (a, b) {

            const fechaA = obtenerFechaComentario(a);
            const fechaB = obtenerFechaComentario(b);

            if (fechaA !== fechaB) {
                return fechaB - fechaA;
            }

            const idA = Number(a?.id || 0);
            const idB = Number(b?.id || 0);

            return idB - idA;
        });
    }

    function actualizarComentariosAlpine(comentarios) {

        const alpineData = obtenerAlpineTicketModal();

        if (!alpineData) {
            return;
        }

        alpineData.comentarios = ordenarComentarios(comentarios);

        if (typeof alpineData.$nextTick === 'function') {

            alpineData.$nextTick(function () {

                if (window.lucide) {
                    window.lucide.createIcons();
                }

            });
        }
    }

    window.cargarTicketComentarios = function (
        ticketId,
        comentarios = []
    ) {

        ticketActual = ticketId;

        formulario.action =
            `/tickets/${ticketId}/comentarios`;

        formulario.reset();

        if (Array.isArray(comentarios)) {
            actualizarComentariosAlpine(comentarios);
        }
    };

    function obtenerTicketActual() {

        if (ticketActual) {
            return ticketActual;
        }

        const formularioData =
            formulario._x_dataStack?.[0];

        if (formularioData?.selectedTicket?.id) {

            ticketActual =
                formularioData.selectedTicket.id;

            return ticketActual;
        }

        const alpineData =
            obtenerAlpineTicketModal();

        if (alpineData?.selectedTicket?.id) {

            ticketActual =
                alpineData.selectedTicket.id;

            return ticketActual;
        }

        return null;
    }

    function agregarComentarioAlpine(comentario) {

        if (!comentario) {
            return;
        }

        const alpineData =
            obtenerAlpineTicketModal();

        if (!alpineData) {
            return;
        }

        if (!Array.isArray(alpineData.comentarios)) {
            alpineData.comentarios = [];
        }

        const comentarioId =
            comentario.id
                ? String(comentario.id)
                : null;

        const existe =
            comentarioId &&
            alpineData.comentarios.some(function (item) {

                return (
                    item &&
                    item.id &&
                    String(item.id) === comentarioId
                );

            });

        if (existe) {
            return;
        }

        alpineData.comentarios = [
            comentario,
            ...alpineData.comentarios
        ];

        alpineData.comentarios =
            ordenarComentarios(
                alpineData.comentarios
            );

        if (typeof alpineData.$nextTick === 'function') {

            alpineData.$nextTick(function () {

                if (window.lucide) {
                    window.lucide.createIcons();
                }

            });
        }
    }

    formulario.addEventListener(
        'submit',
        async function (e) {

            e.preventDefault();
            e.stopPropagation();

            if (enviandoComentario) {
                return;
            }

            const idTicket = obtenerTicketActual();

            if (!idTicket) {

                alert(
                    'Selecciona un ticket antes de enviar un comentario.'
                );

                return;
            }

            formulario.action =
                `/tickets/${idTicket}/comentarios`;

            const formData =
                new FormData(formulario);

            const mensaje =
                formData.get('mensaje');

            const archivo =
                formData.get('archivo');

            const mensajeVacio =
                !mensaje ||
                mensaje.trim() === '';

            const archivoVacio =
                !archivo ||
                archivo.size === 0;

            if (mensajeVacio && archivoVacio) {

                alert(
                    'Escribe un comentario o selecciona un archivo.'
                );

                return;
            }

            enviandoComentario = true;

            if (botonEnviar) {

                botonEnviar.disabled = true;

                botonEnviar.classList.add(
                    'opacity-50',
                    'pointer-events-none'
                );
            }

            try {

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

                const contentType =
                    response.headers.get('content-type');

                let data = null;

                if (
                    contentType &&
                    contentType.includes('application/json')
                ) {

                    data = await response.json();

                } else {

                    throw new Error(
                        'El servidor no devolvió una respuesta JSON válida.'
                    );
                }

                if (!response.ok || !data.success) {

                    throw new Error(
                        data?.message ||
                        'No se pudo enviar el comentario.'
                    );
                }

                if (data.comentario) {
                    console.log('COMENTARIO AJAX:', data.comentario);
console.log('USUARIO AJAX:', data.comentario?.usuario);
console.log('FOTO AJAX:', data.comentario?.usuario?.foto);
                    agregarComentarioAlpine(
                        data.comentario
                    );
                }

                formulario.reset();

                limpiarArchivoAlpine();

            } catch (error) {

                alert(
                    error.message ||
                    'Ocurrió un error al enviar el comentario.'
                );

            } finally {

                enviandoComentario = false;

                if (botonEnviar) {

                    botonEnviar.disabled = false;

                    botonEnviar.classList.remove(
                        'opacity-50',
                        'pointer-events-none'
                    );
                }
            }
        }
    );

    if (inputComentario) {

        inputComentario.addEventListener(
            'keydown',
            function (e) {

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

    function limpiarArchivoAlpine() {

        try {

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

                            data.archivoAdjunto = null;
                        }

                    }
                );
            }

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

                            data.archivoAdjunto = null;
                        }

                    }
                );
            }

            const inputArchivo =
                formulario.querySelector(
                    'input[type="file"]'
                );

            if (inputArchivo) {
                inputArchivo.value = '';
            }

        } catch (error) {
        }
    }

    document.addEventListener(
        'alpine:initialized',
        function () {

            if (window.lucide) {
                window.lucide.createIcons();
            }

        }
    );

    document.addEventListener(
        'click',
        function (event) {

            const boton =
                event.target.closest('button');

            if (!boton) {
                return;
            }

            setTimeout(function () {

                const alpineData =
                    obtenerAlpineTicketModal();

                if (
                    !alpineData ||
                    !alpineData.selectedTicket ||
                    !alpineData.selectedTicket.id
                ) {
                    return;
                }

                const nuevoTicket =
                    alpineData.selectedTicket.id;

                if (ticketActual !== nuevoTicket) {

                    ticketActual =
                        nuevoTicket;

                    formulario.action =
                        `/tickets/${ticketActual}/comentarios`;
                }

            }, 0);
        }
    );

});