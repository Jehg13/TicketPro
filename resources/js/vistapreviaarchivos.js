document.addEventListener('DOMContentLoaded', function () {

    inicializarPreviewArchivos(
        'evidenciaInput',
        'evidenciasPreview',
        true
    );

    inicializarPreviewArchivos(
        'archivoAvisoInput',
        'archivoAvisoPreview',
        false
    );


    function inicializarPreviewArchivos(
        inputId,
        previewId,
        multiples
    ) {

        const input =
            document.getElementById(inputId);

        const preview =
            document.getElementById(previewId);

        if (!input || !preview) {
            return;
        }


        let archivosSeleccionados = [];


        input.addEventListener(
            'change',
            function () {

                const nuevosArchivos =
                    Array.from(input.files);


                if (!multiples) {

                    archivosSeleccionados = [];

                    if (nuevosArchivos.length > 0) {
                        archivosSeleccionados.push(
                            nuevosArchivos[0]
                        );
                    }

                } else {

                    nuevosArchivos.forEach(
                        function (archivo) {

                            const existe =
                                archivosSeleccionados.some(
                                    function (archivoExistente) {

                                        return (
                                            archivoExistente.name ===
                                                archivo.name &&

                                            archivoExistente.size ===
                                                archivo.size &&

                                            archivoExistente.lastModified ===
                                                archivo.lastModified
                                        );

                                    }
                                );


                            if (!existe) {

                                archivosSeleccionados.push(
                                    archivo
                                );

                            }

                        }
                    );

                }


                actualizarInput();

                mostrarPreviews();

            }
        );


        function actualizarInput() {

            const dataTransfer =
                new DataTransfer();


            archivosSeleccionados.forEach(
                function (archivo) {

                    dataTransfer.items.add(
                        archivo
                    );

                }
            );


            input.files =
                dataTransfer.files;

        }


        function mostrarPreviews() {

            preview.innerHTML = '';


            if (
                archivosSeleccionados.length === 0
            ) {

                preview.classList.add(
                    'hidden'
                );

                return;

            }


            preview.classList.remove(
                'hidden'
            );


            archivosSeleccionados.forEach(
                function (archivo, index) {

                    const extension =
                        archivo.name
                            .split('.')
                            .pop()
                            .toLowerCase();


                    const url =
                        URL.createObjectURL(
                            archivo
                        );


                    const tarjeta =
                        document.createElement(
                            'div'
                        );


                    tarjeta.className =
                        'relative overflow-hidden rounded-xl border border-slate-800 bg-[#060818]';


                    let contenido = '';


                    if (
                        [
                            'jpg',
                            'jpeg',
                            'png'
                        ].includes(
                            extension
                        )
                    ) {

                        contenido = `

                            <div
                                class="aspect-video bg-black flex items-center justify-center overflow-hidden">

                                <img
                                    src="${url}"
                                    class="w-full h-full object-cover"
                                    alt="${escapeHtml(archivo.name)}">

                            </div>

                        `;

                    }

                    else if (
                        extension === 'mp4'
                    ) {

                        contenido = `

                            <div
                                class="aspect-video bg-black flex items-center justify-center overflow-hidden">

                                <video
                                    src="${url}"
                                    controls
                                    class="w-full h-full object-contain">

                                </video>

                            </div>

                        `;

                    }

                    else if (
                        extension === 'pdf'
                    ) {

                        contenido = `

                            <div
                                class="aspect-video bg-[#0b1026] flex flex-col items-center justify-center">

                                <svg
                                    class="w-12 h-12 text-red-400 mb-2"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M7 3h7l5 5v13H7a2 2 0 01-2-2V5a2 2 0 012-2z">
                                    </path>

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M14 3v6h5">
                                    </path>

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M8 15h8M8 18h6">
                                    </path>

                                </svg>

                                <span
                                    class="text-xs text-red-400 font-semibold">

                                    PDF

                                </span>

                            </div>

                        `;

                    }

                    else {

                        contenido = `

                            <div
                                class="aspect-video bg-[#0b1026] flex items-center justify-center">

                                <svg
                                    class="w-10 h-10 text-slate-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z">
                                    </path>

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M14 2v6h6">
                                    </path>

                                </svg>

                            </div>

                        `;

                    }


                    tarjeta.innerHTML = `

                        ${contenido}

                        <button
                            type="button"
                            data-index="${index}"
                            class="absolute top-2 right-2 flex items-center justify-center w-7 h-7 rounded-lg bg-red-500/90 text-white hover:bg-red-500 transition shadow-lg">

                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12">
                                </path>

                            </svg>

                        </button>

                        <div class="p-3">

                            <p
                                class="text-xs text-slate-300 font-medium truncate"
                                title="${escapeHtml(archivo.name)}">

                                ${escapeHtml(archivo.name)}

                            </p>

                            <p
                                class="text-[11px] text-slate-500 mt-1">

                                ${formatearTamano(
                                    archivo.size
                                )}

                            </p>

                        </div>

                    `;


                    const botonEliminar =
                        tarjeta.querySelector(
                            'button'
                        );


                    botonEliminar.addEventListener(
                        'click',
                        function () {

                            eliminarArchivo(
                                index
                            );

                        }
                    );


                    preview.appendChild(
                        tarjeta
                    );


                    setTimeout(
                        function () {

                            URL.revokeObjectURL(
                                url
                            );

                        },
                        100
                    );

                }
            );

        }


        function eliminarArchivo(index) {

            archivosSeleccionados.splice(
                index,
                1
            );


            actualizarInput();

            mostrarPreviews();

        }


        function formatearTamano(bytes) {

            if (bytes === 0) {
                return '0 Bytes';
            }


            const unidades = [
                'Bytes',
                'KB',
                'MB',
                'GB'
            ];


            const indice =
                Math.floor(
                    Math.log(bytes) /
                    Math.log(1024)
                );


            return (
                parseFloat(
                    (
                        bytes /
                        Math.pow(
                            1024,
                            indice
                        )
                    ).toFixed(2)
                ) +
                ' ' +
                unidades[indice]
            );

        }


        function escapeHtml(text) {

            const div =
                document.createElement(
                    'div'
                );


            div.textContent =
                text;


            return div.innerHTML;

        }

    }

});