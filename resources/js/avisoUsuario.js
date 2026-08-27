function avisosUsuario() {
    return {
        modalAbierto: false,
        avisoSeleccionado: {},
        menuMovilAbierto: false,

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

        // ============================================================
        // GETTERS
        // ============================================================
actualizarAvisos(data) {

    console.log('======================================');
    console.log('🔄 [AVISOS] actualizarAvisos');
    console.log('📥 Data recibida:', data);

    const avisos = this.normalizarAvisos(data);

    console.log('📦 Avisos extraídos:', avisos);
    console.log('📦 Cantidad extraída:', avisos.length);
    console.log(
        '📦 IDs extraídos:',
        avisos.map(aviso => aviso.id)
    );

    const avisosNormalizados = avisos
        .map(aviso => this.normalizarAviso(aviso))
        .filter(aviso =>
            Object.keys(aviso).length > 0
        );

    console.log(
        '🔧 Avisos normalizados:',
        avisosNormalizados
    );

    console.log(
        '🔧 Cantidad normalizados:',
        avisosNormalizados.length
    );

    console.log(
        '🔧 IDs normalizados:',
        avisosNormalizados.map(aviso => aviso.id)
    );

    console.log(
        '🔴 ANTES de asignar avisosTotales:',
        this.avisosTotales
    );

    this.avisosTotales = avisosNormalizados;

    console.log(
        '🟢 DESPUÉS de asignar avisosTotales:',
        this.avisosTotales
    );

    console.log(
        '🟢 Cantidad DESPUÉS:',
        this.avisosTotales.length
    );

    console.log(
        '🟢 IDs DESPUÉS:',
        this.avisosTotales.map(aviso => aviso.id)
    );

    console.log(
        '🟢 página actual:',
        this.paginaActual
    );

    console.log(
        '🟢 total páginas:',
        this.totalPaginas
    );

    console.log('======================================');

    if (
        this.paginaActual < 1 ||
        this.paginaActual > this.totalPaginas
    ) {
        this.paginaActual = 1;
    }
},
     get avisosPagina() {

    console.log('======================================');
    console.log('📄 [GETTER] avisosPagina');
    console.log('📄 paginaActual:', this.paginaActual);
    console.log('📄 porPagina:', this.porPagina);
    console.log('📄 avisosTotales:', this.avisosTotales);
    console.log(
        '📄 cantidad:',
        this.avisosTotales.length
    );

    const inicio =
        (this.paginaActual - 1) *
        this.porPagina;

    const resultado =
        this.avisosTotales.slice(
            inicio,
            inicio + this.porPagina
        );

    console.log('📄 resultado:', resultado);
    console.log(
        '📄 cantidad resultado:',
        resultado.length
    );

    console.log(
        '📄 IDs resultado:',
        resultado.map(aviso => aviso.id)
    );

    console.log('======================================');

    return resultado;
},

        get totalPaginas() {
            const total = Math.max(
                1,
                Math.ceil(
                    this.avisosTotales.length /
                    this.porPagina
                )
            );

            console.log(
                '📄 [AVISOS] totalPaginas:',
                total
            );

            return total;
        },

        get hayResultados() {
            const resultado =
                this.avisosTotales.length > 0;

            console.log(
                '📄 [AVISOS] hayResultados:',
                resultado
            );

            return resultado;
        },

        // ============================================================
        // INIT
        // ============================================================

        init() {
            console.group(
                '🚀 [AVISOS] INICIANDO avisosUsuario'
            );

            console.log(
                'window.location.href:',
                window.location.href
            );

            console.log(
                'window.location.search:',
                window.location.search
            );

            console.log(
                'window.filtroTipoAvisos:',
                window.filtroTipoAvisos
            );

            console.log(
                'window.busquedaAvisos:',
                window.busquedaAvisos
            );

            console.log(
                'window.avisosTotales:',
                window.avisosTotales
            );

            console.log(
                'window.avisosTotales es array:',
                Array.isArray(window.avisosTotales)
            );

            console.log(
                'avisosTotales inicial:',
                this.avisosTotales
            );

            console.log(
                'IDs iniciales:',
                this.avisosTotales.map(a => a?.id)
            );

            console.log(
                'Filtro inicial:',
                this.filtroTipo
            );

            console.log(
                'Búsqueda inicial:',
                this.busqueda
            );

            console.groupEnd();

            this.filtroTipo = String(
                this.filtroTipo || 'todos'
            ).trim().toLowerCase();

            this.busqueda = String(
                this.busqueda || ''
            ).trim();

            this.paginaActual = 1;

            this.$nextTick(() => {
                console.group(
                    '🔄 [AVISOS] Ejecutando carga inicial'
                );

                const url = new URL(
                    window.location.href
                );

                console.log(
                    'URL antes de modificar:',
                    window.location.href
                );

                /*
                 * IMPORTANTE:
                 * Ya no forzamos filtroTipo = todos.
                 */

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

                if (
                    this.filtroTipo &&
                    this.filtroTipo !== 'todos'
                ) {
                    url.searchParams.set(
                        'tipo',
                        this.filtroTipo
                    );
                } else {
                    url.searchParams.delete(
                        'tipo'
                    );
                }

                console.log(
                    'Filtro que se enviará:',
                    this.filtroTipo
                );

                console.log(
                    'Búsqueda que se enviará:',
                    this.busqueda
                );

                console.log(
                    'URL final:',
                    url.toString()
                );

                console.groupEnd();

                this.cargarAvisos(url);
            });
        },

        // ============================================================
        // NORMALIZAR AVISO
        // ============================================================

        normalizarAviso(aviso) {
            console.group(
                '🔧 [AVISOS] normalizarAviso'
            );

            console.log(
                'Aviso recibido:',
                aviso
            );

            if (
                !aviso ||
                typeof aviso !== 'object'
            ) {
                console.warn(
                    '⚠️ Aviso inválido'
                );

                console.groupEnd();

                return {};
            }

            const item = {
                ...aviso
            };

            console.log(
                'Copia del aviso:',
                item
            );

            if (
                typeof item.afecta_a === 'string'
            ) {
                try {
                    item.afecta_a =
                        JSON.parse(
                            item.afecta_a
                        );

                    console.log(
                        'afecta_a convertido desde JSON:',
                        item.afecta_a
                    );
                } catch (error) {
                    console.error(
                        '❌ Error parseando afecta_a:',
                        error
                    );

                    item.afecta_a = {};
                }
            }

            if (
                !item.afecta_a ||
                typeof item.afecta_a !== 'object' ||
                Array.isArray(item.afecta_a)
            ) {
                console.warn(
                    '⚠️ afecta_a inválido, usando {}'
                );

                item.afecta_a = {};
            }

            if (
                item.tipo !== null &&
                item.tipo !== undefined
            ) {
                item.tipo = String(
                    item.tipo
                )
                    .trim()
                    .toLowerCase();
            }

            console.log(
                'Aviso normalizado:',
                item
            );

            console.groupEnd();

            return item;
        },

        // ============================================================
        // NORMALIZAR RESPUESTA
        // ============================================================

        normalizarAvisos(data) {
            console.group(
                '📦 [AVISOS] normalizarAvisos'
            );

            console.log(
                'Respuesta completa:',
                data
            );

            console.log(
                'Tipo respuesta:',
                typeof data
            );

            console.log(
                'Es array:',
                Array.isArray(data)
            );

            if (!data) {
                console.warn(
                    '⚠️ data está vacío'
                );

                console.groupEnd();

                return [];
            }

            if (Array.isArray(data)) {
                console.log(
                    'Formato detectado: ARRAY'
                );

                console.log(
                    'Cantidad:',
                    data.length
                );

                console.log(
                    'IDs:',
                    data.map(a => a?.id)
                );

                console.groupEnd();

                return data;
            }

            if (
                data.avisos &&
                Array.isArray(data.avisos)
            ) {
                console.log(
                    'Formato detectado: data.avisos'
                );

                console.log(
                    'Cantidad:',
                    data.avisos.length
                );

                console.log(
                    'IDs:',
                    data.avisos.map(a => a?.id)
                );

                console.groupEnd();

                return data.avisos;
            }

            if (
                data.avisos &&
                Array.isArray(data.avisos.data)
            ) {
                console.log(
                    'Formato detectado: data.avisos.data'
                );

                console.log(
                    'Cantidad:',
                    data.avisos.data.length
                );

                console.log(
                    'IDs:',
                    data.avisos.data.map(a => a?.id)
                );

                console.groupEnd();

                return data.avisos.data;
            }

            if (
                data.data &&
                Array.isArray(data.data)
            ) {
                console.log(
                    'Formato detectado: data.data'
                );

                console.groupEnd();

                return data.data;
            }

            if (
                data.data &&
                data.data.avisos &&
                Array.isArray(data.data.avisos)
            ) {
                console.log(
                    'Formato detectado: data.data.avisos'
                );

                console.groupEnd();

                return data.data.avisos;
            }

            if (
                data.resultado &&
                Array.isArray(data.resultado)
            ) {
                console.log(
                    'Formato detectado: data.resultado'
                );

                console.groupEnd();

                return data.resultado;
            }

            if (
                data.resultado &&
                Array.isArray(data.resultado.data)
            ) {
                console.log(
                    'Formato detectado: data.resultado.data'
                );

                console.groupEnd();

                return data.resultado.data;
            }

            console.error(
                '❌ NO SE ENCONTRÓ UNA ESTRUCTURA DE AVISOS'
            );

            console.groupEnd();

            return [];
        },

        // ============================================================
        // ACTUALIZAR AVISOS
        // ============================================================

        actualizarAvisos(data) {
            console.group(
                '🔄 [AVISOS] actualizarAvisos'
            );

            console.log(
                'Data recibida:',
                data
            );

            const avisos =
                this.normalizarAvisos(data);

            console.log(
                'Avisos extraídos:',
                avisos
            );

            console.log(
                'Cantidad extraída:',
                avisos.length
            );

            console.log(
                'IDs extraídos:',
                avisos.map(a => a?.id)
            );

            this.avisosTotales = avisos
                .map(aviso =>
                    this.normalizarAviso(
                        aviso
                    )
                )
                .filter(aviso =>
                    Object.keys(aviso).length > 0
                );

            console.log(
                'avisosTotales después de normalizar:',
                this.avisosTotales
            );

            console.log(
                'Cantidad final:',
                this.avisosTotales.length
            );

            console.log(
                'IDs finales:',
                this.avisosTotales.map(
                    aviso => aviso.id
                )
            );

            console.log(
                'Tipos finales:',
                this.avisosTotales.map(
                    aviso => ({
                        id: aviso.id,
                        tipo: aviso.tipo,
                        titulo: aviso.titulo
                    })
                )
            );

            if (
                this.paginaActual < 1 ||
                this.paginaActual > this.totalPaginas
            ) {
                console.warn(
                    '⚠️ Página fuera de rango. Regresando a 1.'
                );

                this.paginaActual = 1;
            }

            console.log(
                'Página actual final:',
                this.paginaActual
            );

            console.groupEnd();
        },

        // ============================================================
        // CARGAR AVISOS
        // ============================================================

        async cargarAvisos(url) {
            console.group(
                '🌐 [AVISOS] cargarAvisos'
            );

            console.log(
                'URL solicitada:',
                url.toString()
            );

            console.log(
                'Filtro actual:',
                this.filtroTipo
            );

            console.log(
                'Búsqueda actual:',
                this.busqueda
            );

            this.cargando = true;

            try {
                console.log(
                    'Enviando fetch...'
                );

                const response =
                    await fetch(
                        url.toString(),
                        {
                            method: 'GET',
                            headers: {
                                'Accept':
                                    'application/json',

                                'X-Requested-With':
                                    'XMLHttpRequest'
                            },
                            credentials:
                                'same-origin',

                            cache:
                                'no-store'
                        }
                    );

                console.log(
                    'Response:',
                    response
                );

                console.log(
                    'HTTP status:',
                    response.status
                );

                console.log(
                    'HTTP OK:',
                    response.ok
                );

                const contentType =
                    response.headers.get(
                        'content-type'
                    ) || '';

                console.log(
                    'Content-Type:',
                    contentType
                );

                if (!response.ok) {
                    throw new Error(
                        `Error HTTP ${response.status}`
                    );
                }

                if (
                    !contentType.includes(
                        'application/json'
                    )
                ) {
                    const texto =
                        await response.text();

                    console.error(
                        '❌ Respuesta NO JSON:',
                        texto
                    );

                    throw new Error(
                        'El servidor no devolvió JSON'
                    );
                }

                const data =
                    await response.json();

                console.log(
                    '📥 JSON recibido del servidor:',
                    data
                );

                console.log(
                    'Total enviado por Laravel:',
                    data?.total
                );

                console.log(
                    'Avisos enviados por Laravel:',
                    data?.avisos
                );

                console.log(
                    'IDs enviados por Laravel:',
                    Array.isArray(data?.avisos)
                        ? data.avisos.map(
                            aviso => aviso?.id
                        )
                        : 'NO ES ARRAY'
                );

                this.actualizarAvisos(
                    data
                );

                console.log(
                    '✅ Estado Alpine después de actualizar:',
                    this.avisosTotales
                );

                console.log(
                    'IDs Alpine:',
                    this.avisosTotales.map(
                        aviso => aviso.id
                    )
                );

                window.history.replaceState(
                    {},
                    '',
                    url.toString()
                );

                console.log(
                    'URL del navegador actualizada:',
                    window.location.href
                );

                console.groupEnd();

                return true;

            } catch (error) {
                console.error(
                    '❌ Error al cargar avisos:',
                    error
                );

                console.groupEnd();

                return false;

            } finally {
                this.cargando = false;

                console.log(
                    '🏁 cargarAvisos finalizado'
                );
            }
        },

        // ============================================================
        // PAGINACIÓN
        // ============================================================

        irPagina(pagina) {
            pagina = Number(pagina);

            console.log(
                '📄 [AVISOS] irPagina:',
                pagina
            );

            if (
                !Number.isInteger(pagina)
            ) {
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
            if (
                this.paginaActual > 1
            ) {
                this.irPagina(
                    this.paginaActual - 1
                );
            }
        },

        paginaSiguiente() {
            if (
                this.paginaActual <
                this.totalPaginas
            ) {
                this.irPagina(
                    this.paginaActual + 1
                );
            }
        },

        // ============================================================
        // FILTROS
        // ============================================================

        async cambiarFiltro(tipo) {
            console.group(
                '🔎 [AVISOS] cambiarFiltro'
            );

            console.log(
                'Filtro recibido:',
                tipo
            );

            tipo = String(
                tipo || 'todos'
            )
                .trim()
                .toLowerCase();

            const tiposPermitidos = [
                'todos',
                'mantenimiento',
                'incidente',
                'informativo',
                'general'
            ];

            if (
                !tiposPermitidos.includes(
                    tipo
                )
            ) {
                console.warn(
                    '⚠️ Tipo no permitido:',
                    tipo
                );

                tipo = 'todos';
            }

            this.filtroTipo = tipo;
            this.paginaActual = 1;

            const url = new URL(
                window.location.href
            );

            if (
                tipo === 'todos'
            ) {
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

            if (
                texto !== ''
            ) {
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

            console.log(
                'Filtro final:',
                this.filtroTipo
            );

            console.log(
                'URL final:',
                url.toString()
            );

            await this.cargarAvisos(
                url
            );

            console.groupEnd();
        },

        async buscarAvisos() {
            console.group(
                '🔍 [AVISOS] buscarAvisos'
            );

            const texto = String(
                this.busqueda || ''
            ).trim();

            console.log(
                'Texto búsqueda:',
                texto
            );

            const url = new URL(
                window.location.href
            );

            if (
                texto !== ''
            ) {
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
            )
                .trim()
                .toLowerCase();

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

            console.log(
                'URL búsqueda:',
                url.toString()
            );

            await this.cargarAvisos(
                url
            );

            console.groupEnd();
        },

        async limpiarBusqueda() {
            console.log(
                '🧹 [AVISOS] limpiarBusqueda'
            );

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
            )
                .trim()
                .toLowerCase();

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
            console.log(
                '🧹 [AVISOS] limpiarFiltros'
            );

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

        // ============================================================
        // VISIBILIDAD
        // ============================================================

        mostrarAviso(tipo) {
            const filtro = String(
                this.filtroTipo || 'todos'
            )
                .trim()
                .toLowerCase();

            const tipoAviso = String(
                tipo || ''
            )
                .trim()
                .toLowerCase();

            const visible =
                !filtro ||
                filtro === 'todos' ||
                tipoAviso === filtro;

            console.log(
                '👁️ [AVISOS] mostrarAviso:',
                {
                    filtro,
                    tipoAviso,
                    visible
                }
            );

            return visible;
        },

        hayAvisosVisibles() {
            if (
                !Array.isArray(
                    this.avisosTotales
                )
            ) {
                return false;
            }

            const resultado =
                this.avisosTotales.some(
                    aviso =>
                        this.mostrarAviso(
                            aviso.tipo
                        )
                );

            console.log(
                '👁️ [AVISOS] hayAvisosVisibles:',
                resultado
            );

            return resultado;
        },

        hayAvisos() {
            const resultado =
                Array.isArray(
                    this.avisosTotales
                ) &&
                this.avisosTotales.length > 0;

            console.log(
                '📋 [AVISOS] hayAvisos:',
                resultado,
                'cantidad:',
                this.avisosTotales.length
            );

            return resultado;
        },

        hayBusqueda() {
            return String(
                this.busqueda || ''
            )
                .trim()
                .length > 0;
        },

        nombreFiltro() {
            const nombres = {
                todos: 'todos los avisos',
                mantenimiento:
                    'avisos de mantenimiento',
                incidente:
                    'avisos de falla o incidente',
                informativo:
                    'avisos informativos',
                general:
                    'avisos generales'
            };

            return nombres[
                this.filtroTipo
            ] || 'avisos';
        },

        tipoLabel(tipo) {
            const tipoNormalizado =
                String(tipo || '')
                    .trim()
                    .toLowerCase();

            const tipos = {
                mantenimiento:
                    'MANTENIMIENTO',

                incidente:
                    'FALLA / INCIDENTE',

                informativo:
                    'INFORMATIVO',

                general:
                    'GENERAL'
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

            texto = String(texto);

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

            const date = new Date(fecha);

            if (
                isNaN(
                    date.getTime()
                )
            ) {
                return String(fecha);
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
            ).format(date);
        },

        formatearHora(fecha) {
            if (!fecha) {
                return 'No disponible';
            }

            const date = new Date(fecha);

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
            ).format(date);
        },

        // ============================================================
        // MODAL
        // ============================================================

        abrirAviso(aviso) {
            console.log(
                '📖 [AVISOS] abrirAviso:',
                aviso
            );

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
            console.log(
                '❌ [AVISOS] cerrarAviso'
            );

            this.modalAbierto = false;

            document.body.classList.remove(
                'overflow-hidden'
            );

            setTimeout(() => {
                if (
                    !this.modalAbierto
                ) {
                    this.avisoSeleccionado = {};
                }
            }, 200);
        },

        // ============================================================
        // ARCHIVOS
        // ============================================================

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
                archivo.startsWith('http://') ||
                archivo.startsWith('https://') ||
                archivo.startsWith('/')
            ) {
                return archivo;
            }

            if (
                archivo.startsWith('storage/')
            ) {
                return '/' + archivo;
            }

            return '/storage/' + archivo;
        },

        extensionArchivo(archivo) {
            if (!archivo) {
                return '';
            }

            return String(archivo)
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

        // ============================================================
        // DEPARTAMENTOS
        // ============================================================

        obtenerDepartamentos(ids) {
            console.log(
                '🏢 [AVISOS] obtenerDepartamentos:',
                ids
            );

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
                            String(nombre).trim() !== ''
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
                            String(nombre).trim() !== ''
                    );
            }

            return [];
        },

        // ============================================================
        // USUARIOS
        // ============================================================

        obtenerUsuarios(ids) {
            console.log(
                '👤 [AVISOS] obtenerUsuarios:',
                ids
            );

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
        console.log(
            '🟢 [AVISOS] alpine:init detectado'
        );

        Alpine.data(
            'avisosUsuario',
            avisosUsuario
        );

        console.log(
            '🟢 [AVISOS] Alpine.data avisosUsuario registrado'
        );
    }
);