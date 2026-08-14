document.addEventListener('DOMContentLoaded', function () {

    const formulario = document.getElementById('formComentario');
    const listaComentarios = document.getElementById('listaComentarios');
    const botonEnviar = document.getElementById('btnEnviarComentario');
    const inputComentario = document.getElementById('inputComentario');

    if (!formulario) {
        console.warn('No se encontró #formComentario');
        return;
    }

    let ticketActual = null;

    /*
     * ---------------------------------------------------------
     * CARGAR TICKET Y COMENTARIOS
     * ---------------------------------------------------------
     */

    window.cargarTicketComentarios = function (ticketId, comentarios = []) {

        ticketActual = ticketId;

        formulario.action = `/tickets/${ticketId}/comentarios`;

        formulario.reset();

        if (listaComentarios) {
            listaComentarios.innerHTML = '';
        }

        if (Array.isArray(comentarios) && comentarios.length > 0) {

            comentarios.forEach(function (comentario) {
                agregarComentario(comentario);
            });

        } else {

            mostrarSinComentarios();

        }

        if (listaComentarios) {
            setTimeout(function () {
                listaComentarios.scrollTop = listaComentarios.scrollHeight;
            }, 50);
        }
    };


    /*
     * ---------------------------------------------------------
     * OBTENER EL TICKET ACTUAL DESDE ALPINE
     * ---------------------------------------------------------
     */

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

        const modal =
            document.querySelector('[x-data*="ticketModal"]');

        if (modal && modal._x_dataStack) {

            for (const data of modal._x_dataStack) {

                if (
                    data &&
                    data.selectedTicket &&
                    data.selectedTicket.id
                ) {

                    ticketActual =
                        data.selectedTicket.id;

                    return ticketActual;
                }
            }
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

            setTimeout(function () {

                const modal =
                    document.querySelector(
                        '[x-data*="ticketModal"]'
                    );

                if (!modal || !modal._x_dataStack) {
                    return;
                }

                for (const data of modal._x_dataStack) {

                    if (
                        data &&
                        data.selectedTicket &&
                        data.selectedTicket.id
                    ) {

                        ticketActual =
                            data.selectedTicket.id;

                        if (formulario) {

                            formulario.action =
                                `/tickets/${ticketActual}/comentarios`;

                        }

                        break;
                    }
                }

            }, 0);
        }
    );


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

            formulario.action =
                `/tickets/${idTicket}/comentarios`;

            const formData =
                new FormData(formulario);

            const mensaje =
                formData.get('mensaje');

            const archivo =
                formData.get('archivo');

            if (
                (!mensaje || mensaje.trim() === '') &&
                (!archivo || archivo.size === 0)
            ) {

                alert(
                    'Escribe un comentario o selecciona un archivo.'
                );

                return;
            }

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

                if (
                    !response.ok ||
                    !data.success
                ) {

                    throw new Error(
                        data?.message ||
                        'No se pudo enviar el comentario.'
                    );

                }

                const sinComentarios =
                    document.getElementById(
                        'sinComentarios'
                    );

                if (sinComentarios) {
                    sinComentarios.remove();
                }

                if (data.comentario) {

                    agregarComentario(
                        data.comentario
                    );

                }

                formulario.reset();

                limpiarArchivoAlpine();

                if (listaComentarios) {

                    setTimeout(function () {

                        listaComentarios.scrollTop =
                            listaComentarios.scrollHeight;

                    }, 50);

                }

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


    /*
     * ---------------------------------------------------------
     * ENTER PARA ENVIAR
     * ---------------------------------------------------------
     */

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


    /*
     * ---------------------------------------------------------
     * LIMPIAR ARCHIVO DE ALPINE
     * ---------------------------------------------------------
     */

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

        } catch (error) {

            console.warn(
                'No se pudo limpiar archivoAdjunto:',
                error
            );
        }
    }


    /*
     * ---------------------------------------------------------
     * AGREGAR COMENTARIO
     * ---------------------------------------------------------
     */

    function agregarComentario(comentario) {

        if (!listaComentarios) {
            return;
        }

        const usuario =
            comentario.usuario || {};

        const nombreUsuario =
            usuario.name ||
            'Usuario';

        const avatar =
            'https://ui-avatars.com/api/?name=' +
            encodeURIComponent(nombreUsuario) +
            '&background=0D8ABC&color=fff';

        let archivoHTML = '';

        if (comentario.archivo) {

            const nombreArchivo =
                comentario.nombre_archivo ||
                comentario.archivo.split('/').pop();

            const urlArchivo =
                comentario.url_archivo ||
                comentario.archivo;

            const extension =
                nombreArchivo
                    .split('.')
                    .pop()
                    .toUpperCase();

            const imagenes = [
                'JPG',
                'JPEG',
                'PNG',
                'GIF',
                'WEBP',
                'SVG'
            ];

            const esImagen =
                imagenes.includes(extension);

            if (esImagen) {

                archivoHTML = `

                    <a
                        href="${escapeHTML(urlArchivo)}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="block w-36 rounded-xl overflow-hidden
                        bg-slate-900 border border-slate-700/80
                        hover:border-blue-500/60 transition"
                    >

                        <img
                            src="${escapeHTML(urlArchivo)}"
                            alt="${escapeHTML(nombreArchivo)}"
                            class="w-36 h-20 object-cover"
                        >

                        <div
                            class="px-2 py-1.5
                            flex items-center justify-between gap-2
                            border-t border-slate-800"
                        >

                            <span
                                class="text-[9px]
                                text-slate-300 truncate"
                            >
                                ${escapeHTML(nombreArchivo)}
                            </span>

                            <span
                                class="px-1 py-0.5 rounded
                                bg-blue-600 text-white
                                font-bold text-[8px]"
                            >
                                ${escapeHTML(extension)}
                            </span>

                        </div>

                    </a>

                `;

            } else {

                const color =
                    extension === 'PDF'
                        ? 'bg-red-600'
                        : 'bg-blue-600';

                archivoHTML = `

                    <a
                        href="${escapeHTML(urlArchivo)}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="group block w-40 h-20
                        rounded-xl bg-slate-900
                        border border-slate-700/80 p-2
                        hover:border-blue-500/60
                        hover:bg-slate-800 transition"
                    >

                        <div
                            class="flex items-center gap-2 mb-2"
                        >

                            <div
                                class="w-8 h-8 rounded-lg
                                bg-blue-600/20
                                flex items-center justify-center"
                            >

                                <i
                                    data-lucide="file"
                                    class="w-4 h-4 text-blue-400"
                                ></i>

                            </div>

                        </div>

                        <div
                            class="flex items-center
                            justify-between gap-2
                            text-[9px] text-slate-300
                            pt-1 border-t border-slate-800"
                        >

                            <span class="truncate">
                                ${escapeHTML(nombreArchivo)}
                            </span>

                            <span
                                class="px-1 py-0.5 rounded
                                ${color}
                                text-white font-bold text-[8px]"
                            >
                                ${escapeHTML(extension)}
                            </span>

                        </div>

                    </a>

                `;
            }
        }

        const elemento =
            document.createElement('div');

        elemento.className =
            'flex items-start gap-3';

        elemento.innerHTML = `

            <img
                src="${avatar}"
                class="w-8 h-8 rounded-full
                object-cover shrink-0
                border border-blue-400/30"
                alt="${escapeHTML(nombreUsuario)}"
            >

            <div class="flex-1 min-w-0">

                <div
                    class="flex items-center
                    gap-2 mb-1 flex-wrap"
                >

                    <span
                        class="text-xs font-bold text-white"
                    >
                        ${escapeHTML(nombreUsuario)}
                    </span>

                    <span
                        class="px-2 py-0.5
                        rounded-full text-[9px]
                        font-semibold
                        bg-indigo-600/30
                        text-indigo-300
                        border border-indigo-500/40"
                    >
                        ${escapeHTML(
                            usuario.rol || 'Usuario'
                        )}
                    </span>

                    <span
                        class="text-[10px]
                        text-slate-500 ml-auto"
                    >
                        ${escapeHTML(
                            comentario.fecha || ''
                        )}
                    </span>

                </div>

                ${
                    comentario.mensaje
                        ? `
                            <p
                                class="text-xs
                                text-slate-300 mb-2
                                whitespace-pre-line"
                            >
                                ${escapeHTML(
                                    comentario.mensaje
                                )}
                            </p>
                        `
                        : ''
                }

                ${archivoHTML}

            </div>
        `;

        listaComentarios.appendChild(
            elemento
        );

        if (
            typeof lucide !== 'undefined'
        ) {

            lucide.createIcons();

        }
    }


    /*
     * ---------------------------------------------------------
     * SIN COMENTARIOS
     * ---------------------------------------------------------
     */

    function mostrarSinComentarios() {

        if (!listaComentarios) {
            return;
        }

        listaComentarios.innerHTML = `

            <div
                id="sinComentarios"
                class="flex flex-col
                items-center justify-center
                py-16 text-slate-500"
            >

                <div
                    class="w-12 h-12 rounded-full
                    bg-slate-800/60
                    flex items-center
                    justify-center mb-3"
                >

                    <i
                        data-lucide="message-square-off"
                        class="w-5 h-5"
                    ></i>

                </div>

                <p class="text-xs">
                    Aún no hay comentarios.
                </p>

                <span
                    class="text-[10px]
                    text-slate-600 mt-1"
                >
                    Sé el primero en agregar
                    un comentario.
                </span>

            </div>
        `;

        if (
            typeof lucide !== 'undefined'
        ) {

            lucide.createIcons();

        }
    }


    /*
     * ---------------------------------------------------------
     * ESCAPAR HTML
     * ---------------------------------------------------------
     */

    function escapeHTML(text) {

        if (
            text === null ||
            text === undefined
        ) {

            return '';

        }

        const div =
            document.createElement('div');

        div.textContent =
            String(text);

        return div.innerHTML;
    }

}); 