function avisosUsuario() {
    return {
        modalAbierto: false,
        avisoSeleccionado: {},

        filtroTipo: String(
            window.filtroTipoAvisos ||
            new URLSearchParams(window.location.search).get('tipo') ||
            'todos'
        ).trim().toLowerCase(),

        busqueda: String(
            window.busquedaAvisos ||
            new URLSearchParams(window.location.search).get('buscar') ||
            ''
        ).trim(),

        avisosTotales: Array.isArray(window.avisosTotales)
            ? window.avisosTotales
            : [],

        departamentos: Array.isArray(window.departamentos)
            ? window.departamentos
            : (window.departamentos || {}),

        usuarios: Array.isArray(window.usuarios)
            ? window.usuarios
            : [],

        paginaActual: 1,
        porPagina: 5,
        cargando: false,

        get avisosPagina() {
            const inicio = (this.paginaActual - 1) * this.porPagina;

            return this.avisosTotales.slice(
                inicio,
                inicio + this.porPagina
            );
        },

        get totalPaginas() {
            return Math.max(
                1,
                Math.ceil(
                    this.avisosTotales.length / this.porPagina
                )
            );
        },

        get hayResultados() {
            return this.avisosTotales.length > 0;
        },

        init() {
            this.filtroTipo = String(
                this.filtroTipo || 'todos'
            ).trim().toLowerCase();

            this.busqueda = String(
                this.busqueda || ''
            ).trim();

            this.paginaActual = 1;

            this.$nextTick(() => {
                const url = new URL(
                    window.location.href
                );

                url.searchParams.delete('tipo');
                url.searchParams.delete('page');

                if (this.busqueda) {
                    url.searchParams.set(
                        'buscar',
                        this.busqueda
                    );
                } else {
                    url.searchParams.delete(
                        'buscar'
                    );
                }

                this.filtroTipo = 'todos';

                this.cargarAvisos(url);
            });
        },

        actualizarPaginaInicial() {
            const paginaUrl = Number(
                new URLSearchParams(
                    window.location.search
                ).get('page')
            );

            if (
                Number.isInteger(paginaUrl) &&
                paginaUrl > 0
            ) {
                this.paginaActual = paginaUrl;
            }

            if (
                this.paginaActual < 1 ||
                this.paginaActual > this.totalPaginas
            ) {
                this.paginaActual = 1;
            }
        },

        normalizarAviso(aviso) {
            if (!aviso || typeof aviso !== 'object') {
                return {};
            }

            const item = {
                ...aviso
            };

            if (typeof item.afecta_a === 'string') {
                try {
                    item.afecta_a = JSON.parse(
                        item.afecta_a
                    );
                } catch (error) {
                    item.afecta_a = {};
                }
            }

            if (
                !item.afecta_a ||
                typeof item.afecta_a !== 'object' ||
                Array.isArray(item.afecta_a)
            ) {
                item.afecta_a = {};
            }

            if (
                item.tipo !== null &&
                item.tipo !== undefined
            ) {
                item.tipo = String(
                    item.tipo
                ).trim().toLowerCase();
            }

            return item;
        },

        normalizarAvisos(data) {
            if (!data) {
                return [];
            }

            if (Array.isArray(data)) {
                return data;
            }

            if (
                data.avisos &&
                Array.isArray(data.avisos)
            ) {
                return data.avisos;
            }

            if (
                data.avisos &&
                Array.isArray(data.avisos.data)
            ) {
                return data.avisos.data;
            }

            if (
                data.data &&
                Array.isArray(data.data)
            ) {
                return data.data;
            }

            if (
                data.data &&
                data.data.avisos &&
                Array.isArray(data.data.avisos)
            ) {
                return data.data.avisos;
            }

            if (
                data.resultado &&
                Array.isArray(data.resultado)
            ) {
                return data.resultado;
            }

            if (
                data.resultado &&
                Array.isArray(data.resultado.data)
            ) {
                return data.resultado.data;
            }

            return [];
        },

        actualizarAvisos(data) {
            const avisos = this.normalizarAvisos(data);

            this.avisosTotales = avisos
                .map(aviso =>
                    this.normalizarAviso(aviso)
                )
                .filter(aviso =>
                    Object.keys(aviso).length > 0
                );

            if (
                this.paginaActual < 1 ||
                this.paginaActual > this.totalPaginas
            ) {
                this.paginaActual = 1;
            }
        },

        irPagina(pagina) {
            pagina = Number(pagina);

            if (!Number.isInteger(pagina)) {
                return;
            }

            if (
                pagina < 1 ||
                pagina > this.totalPaginas
            ) {
                return;
            }

            this.paginaActual = pagina;

            const url = new URL(
                window.location.href
            );

            if (pagina > 1) {
                url.searchParams.set(
                    'page',
                    pagina
                );
            } else {
                url.searchParams.delete(
                    'page'
                );
            }

            window.history.replaceState(
                {},
                '',
                url.toString()
            );
        },

        paginaAnterior() {
            if (this.paginaActual > 1) {
                this.irPagina(
                    this.paginaActual - 1
                );
            }
        },

        paginaSiguiente() {
            if (
                this.paginaActual < this.totalPaginas
            ) {
                this.irPagina(
                    this.paginaActual + 1
                );
            }
        },

        async cargarAvisos(url) {
            this.cargando = true;

            try {
                const response = await fetch(
                    url.toString(),
                    {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin',
                        cache: 'no-store'
                    }
                );

                if (!response.ok) {
                    throw new Error(
                        `Error HTTP ${response.status}`
                    );
                }

                const contentType =
                    response.headers.get(
                        'content-type'
                    ) || '';

                if (
                    !contentType.includes(
                        'application/json'
                    )
                ) {
                    const texto = await response.text();

                    console.error(
                        'Respuesta del servidor:',
                        texto
                    );

                    throw new Error(
                        'El servidor no devolvió JSON'
                    );
                }

                const data =
                    await response.json();

                this.actualizarAvisos(data);

                window.history.replaceState(
                    {},
                    '',
                    url.toString()
                );

                return true;
            } catch (error) {
                console.error(
                    'Error al cargar avisos:',
                    error
                );

                return false;
            } finally {
                this.cargando = false;
            }
        },

        async cambiarFiltro(tipo) {
            tipo = String(
                tipo || 'todos'
            ).trim().toLowerCase();

            const tiposPermitidos = [
                'todos',
                'mantenimiento',
                'incidente',
                'informativo',
                'general'
            ];

            if (
                !tiposPermitidos.includes(tipo)
            ) {
                tipo = 'todos';
            }

            this.filtroTipo = tipo;
            this.paginaActual = 1;

            const url = new URL(
                window.location.href
            );

            if (tipo === 'todos') {
                url.searchParams.delete(
                    'tipo'
                );
            } else {
                url.searchParams.set(
                    'tipo',
                    tipo
                );
            }

            const texto = String(
                this.busqueda || ''
            ).trim();

            if (texto !== '') {
                url.searchParams.set(
                    'buscar',
                    texto
                );
            } else {
                url.searchParams.delete(
                    'buscar'
                );
            }

            url.searchParams.delete(
                'page'
            );

            await this.cargarAvisos(
                url
            );
        },

        async buscarAvisos() {
            const texto = String(
                this.busqueda || ''
            ).trim();

            const url = new URL(
                window.location.href
            );

            if (texto !== '') {
                url.searchParams.set(
                    'buscar',
                    texto
                );
            } else {
                url.searchParams.delete(
                    'buscar'
                );
            }

            const tipo = String(
                this.filtroTipo || 'todos'
            ).trim().toLowerCase();

            if (
                tipo &&
                tipo !== 'todos'
            ) {
                url.searchParams.set(
                    'tipo',
                    tipo
                );
            } else {
                url.searchParams.delete(
                    'tipo'
                );
            }

            url.searchParams.delete(
                'page'
            );

            this.paginaActual = 1;

            await this.cargarAvisos(
                url
            );
        },

        async limpiarBusqueda() {
            this.busqueda = '';
            this.paginaActual = 1;

            const url = new URL(
                window.location.href
            );

            url.searchParams.delete(
                'buscar'
            );

            url.searchParams.delete(
                'page'
            );

            const tipo = String(
                this.filtroTipo || 'todos'
            ).trim().toLowerCase();

            if (
                tipo &&
                tipo !== 'todos'
            ) {
                url.searchParams.set(
                    'tipo',
                    tipo
                );
            } else {
                url.searchParams.delete(
                    'tipo'
                );
            }

            await this.cargarAvisos(
                url
            );
        },

        limpiarFiltros() {
            this.filtroTipo = 'todos';
            this.busqueda = '';
            this.paginaActual = 1;

            const url = new URL(
                window.location.href
            );

            url.searchParams.delete(
                'tipo'
            );

            url.searchParams.delete(
                'buscar'
            );

            url.searchParams.delete(
                'page'
            );

            window.location.href =
                url.toString();
        },

        mostrarAviso(tipo) {
            const filtro = String(
                this.filtroTipo || 'todos'
            ).trim().toLowerCase();

            const tipoAviso = String(
                tipo || ''
            ).trim().toLowerCase();

            if (
                !filtro ||
                filtro === 'todos'
            ) {
                return true;
            }

            return tipoAviso === filtro;
        },

        hayAvisosVisibles() {
            if (
                !Array.isArray(
                    this.avisosTotales
                )
            ) {
                return false;
            }

            return this.avisosTotales.some(
                aviso =>
                    this.mostrarAviso(
                        aviso.tipo
                    )
            );
        },

        hayAvisos() {
            return Array.isArray(
                this.avisosTotales
            ) &&
                this.avisosTotales.length > 0;
        },

        hayBusqueda() {
            return String(
                this.busqueda || ''
            ).trim().length > 0;
        },

        nombreFiltro() {
            const nombres = {
                todos: 'todos los avisos',
                mantenimiento: 'avisos de mantenimiento',
                incidente: 'avisos de falla o incidente',
                informativo: 'avisos informativos',
                general: 'avisos generales'
            };

            return nombres[
                this.filtroTipo
            ] || 'avisos';
        },

        tipoLabel(tipo) {
            const tipoNormalizado = String(
                tipo || ''
            ).trim().toLowerCase();

            const tipos = {
                mantenimiento: 'MANTENIMIENTO',
                incidente: 'FALLA / INCIDENTE',
                informativo: 'INFORMATIVO',
                general: 'GENERAL'
            };

            return tipos[
                tipoNormalizado
            ] || 'GENERAL';
        },

        capitalizar(texto) {
            if (
                texto === null ||
                texto === undefined
            ) {
                return '';
            }

            texto = String(
                texto
            );

            if (!texto) {
                return '';
            }

            return texto.charAt(0).toUpperCase() +
                texto.slice(1);
        },

        formatearFecha(fecha) {
            if (!fecha) {
                return 'No especificada';
            }

            const date = new Date(
                fecha
            );

            if (
                isNaN(
                    date.getTime()
                )
            ) {
                return String(
                    fecha
                );
            }

            return new Intl.DateTimeFormat(
                'es-MX',
                {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                }
            ).format(
                date
            );
        },

        formatearHora(fecha) {
            if (!fecha) {
                return 'No disponible';
            }

            const date = new Date(
                fecha
            );

            if (
                isNaN(
                    date.getTime()
                )
            ) {
                return 'No disponible';
            }

            return new Intl.DateTimeFormat(
                'es-MX',
                {
                    hour: '2-digit',
                    minute: '2-digit'
                }
            ).format(
                date
            );
        },

        abrirAviso(aviso) {
            if (
                !aviso ||
                typeof aviso !== 'object'
            ) {
                return;
            }

            const avisoData =
                this.normalizarAviso(
                    aviso
                );

            this.avisoSeleccionado =
                avisoData;

            this.modalAbierto = true;

            document.body.classList.add(
                'overflow-hidden'
            );
        },

        cerrarAviso() {
            this.modalAbierto = false;

            document.body.classList.remove(
                'overflow-hidden'
            );

            setTimeout(() => {
                if (!this.modalAbierto) {
                    this.avisoSeleccionado = {};
                }
            }, 200);
        },

        urlArchivo(archivo) {
            if (!archivo) {
                return '';
            }

            archivo = String(
                archivo
            ).trim();

            if (!archivo) {
                return '';
            }

            if (
                archivo.startsWith(
                    'http://'
                ) ||
                archivo.startsWith(
                    'https://'
                ) ||
                archivo.startsWith('/')
            ) {
                return archivo;
            }

            if (
                archivo.startsWith(
                    'storage/'
                )
            ) {
                return '/' + archivo;
            }

            return '/storage/' + archivo;
        },

        extensionArchivo(archivo) {
            if (!archivo) {
                return '';
            }

            return String(
                archivo
            )
                .split('?')[0]
                .split('#')[0]
                .split('.')
                .pop()
                .toLowerCase();
        },

        esImagen(archivo) {
            return [
                'jpg',
                'jpeg',
                'png',
                'gif',
                'webp',
                'svg',
                'bmp'
            ].includes(
                this.extensionArchivo(
                    archivo
                )
            );
        },

        esPdf(archivo) {
            return this.extensionArchivo(
                archivo
            ) === 'pdf';
        },

        esVideo(archivo) {
            return [
                'mp4',
                'webm',
                'ogg',
                'mov',
                'm4v'
            ].includes(
                this.extensionArchivo(
                    archivo
                )
            );
        },

        obtenerDepartamentos(ids) {
            if (!Array.isArray(ids)) {
                return [];
            }

            const idsNumeros = ids
                .map(id => Number(id))
                .filter(
                    id => !isNaN(id)
                );

            if (
                this.departamentos &&
                !Array.isArray(
                    this.departamentos
                ) &&
                typeof this.departamentos ===
                'object'
            ) {
                return idsNumeros
                    .map(id =>
                        this.departamentos[id]
                    )
                    .filter(
                        nombre =>
                            nombre !== null &&
                            nombre !== undefined &&
                            String(
                                nombre
                            ).trim() !== ''
                    );
            }

            if (
                Array.isArray(
                    this.departamentos
                )
            ) {
                return this.departamentos
                    .filter(
                        departamento =>
                            idsNumeros.includes(
                                Number(
                                    departamento.id
                                )
                            )
                    )
                    .map(
                        departamento =>
                            departamento.nombre
                    )
                    .filter(
                        nombre =>
                            nombre !== null &&
                            nombre !== undefined &&
                            String(
                                nombre
                            ).trim() !== ''
                    );
            }

            return [];
        },

        obtenerUsuarios(ids) {
            if (
                !Array.isArray(ids) ||
                !Array.isArray(
                    this.usuarios
                )
            ) {
                return [];
            }

            const idsNumeros = ids
                .map(id => Number(id))
                .filter(
                    id => !isNaN(id)
                );

            return this.usuarios.filter(
                usuario =>
                    idsNumeros.includes(
                        Number(
                            usuario.id
                        )
                    )
            );
        }
    };
}

document.addEventListener(
    'alpine:init',
    () => {
        Alpine.data(
            'avisosUsuario',
            avisosUsuario
        );
    }
);