import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('ticketModal', () => ({
    openModal: false,
    selectedTicket: {},
    comentarios: [],
    evidencias: [],
    archivoAdjunto: null,
    rutaComentario: '',
    filtro: 'todos',
    busqueda: '',

    abrirTicket(ticket, comentarios) {
        this.selectedTicket = ticket || {};

        this.comentarios = Array.isArray(comentarios)
            ? comentarios
            : [];

        this.evidencias = this.obtenerEvidencias(
            this.selectedTicket
        );

        this.rutaComentario = this.generarRutaComentario(
            this.selectedTicket?.id
        );

        this.openModal = true;

        this.$nextTick(() => {
            if (window.lucide) {
                window.lucide.createIcons();
            }
        });
    },

    cerrarModal() {
        this.openModal = false;
        this.selectedTicket = {};
        this.comentarios = [];
        this.evidencias = [];
        this.rutaComentario = '';
        this.archivoAdjunto = null;
    },

    mostrarTicket(estado, datos = {}) {
        const estadoNormalizado = String(estado || '')
            .trim()
            .toLowerCase();

        if (
            this.filtro !== 'todos' &&
            estadoNormalizado !== this.filtro
        ) {
            return false;
        }

        const textoBusqueda = String(this.busqueda || '')
            .trim()
            .toLowerCase();

        if (!textoBusqueda) {
            return true;
        }

        const textoTicket = [
            datos.folio,
            datos.titulo,
            datos.tipo_falla,
            datos.prioridad,
            datos.estado,
            datos.tomado_por
        ]
            .filter(
                valor =>
                    valor !== null &&
                    valor !== undefined
            )
            .join(' ')
            .toLowerCase();

        return textoTicket.includes(textoBusqueda);
    },

    hayBusquedaYNoHayResultados() {
        const botones = document.querySelectorAll(
            'tbody button[title="Ver ticket"]'
        );

        if (!botones.length) {
            return false;
        }

        let resultados = 0;

        botones.forEach((boton) => {
            const fila = boton.closest('tr');

            if (!fila) {
                return;
            }

            const display =
                window.getComputedStyle(fila).display;

            if (display !== 'none') {
                resultados++;
            }
        });

        return (
            resultados === 0 &&
            (
                this.busqueda.length > 0 ||
                this.filtro !== 'todos'
            )
        );
    },

    limpiarFiltros() {
        this.filtro = 'todos';
        this.busqueda = '';
    },

    capitalizar(valor) {
        if (!valor) {
            return '—';
        }

        return String(valor)
            .replace(/_/g, ' ')
            .replace(
                /\b\w/g,
                letra => letra.toUpperCase()
            );
    },

    formatearFecha(fecha) {
        if (!fecha) {
            return '—';
        }

        const date = new Date(fecha);

        if (isNaN(date.getTime())) {
            return fecha;
        }

        return date.toLocaleString('es-MX', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    },

    avatarUsuario(nombre) {
        const usuario = encodeURIComponent(
            nombre || 'Usuario'
        );

        return `https://ui-avatars.com/api/?name=${usuario}&background=0D8ABC&color=fff`;
    },

    obtenerEvidencias(ticket) {
        if (
            !ticket ||
            typeof ticket !== 'object'
        ) {
            return [];
        }

        if (Array.isArray(ticket.evidencia)) {
            return ticket.evidencia;
        }

        if (Array.isArray(ticket.evidencias)) {
            return ticket.evidencias;
        }

        if (ticket.evidencia) {
            if (typeof ticket.evidencia === 'string') {
                try {
                    const parsed = JSON.parse(
                        ticket.evidencia
                    );

                    if (Array.isArray(parsed)) {
                        return parsed;
                    }
                } catch (error) {
                    return [ticket.evidencia];
                }
            }

            if (
                typeof ticket.evidencia === 'object'
            ) {
                return Object.values(
                    ticket.evidencia
                );
            }
        }

        return [];
    },

    archivoUrl(archivo) {
        if (!archivo) {
            return '#';
        }

        if (typeof archivo === 'object') {
            if (archivo.url_archivo) {
                return archivo.url_archivo;
            }

            if (archivo.url) {
                return archivo.url;
            }

            if (archivo.path) {
                return archivo.path.startsWith('/')
                    ? archivo.path
                    : `/storage/${archivo.path}`;
            }

            if (archivo.archivo) {
                return this.archivoUrl(
                    archivo.archivo
                );
            }
        }

        if (typeof archivo === 'string') {
            if (
                archivo.startsWith('http://') ||
                archivo.startsWith('https://') ||
                archivo.startsWith('/')
            ) {
                return archivo;
            }

            return `/storage/${archivo}`;
        }

        return '#';
    },

    nombreArchivo(archivo) {
        if (!archivo) {
            return 'Archivo';
        }

        if (typeof archivo === 'object') {
            if (archivo.nombre_archivo) {
                return archivo.nombre_archivo;
            }

            if (archivo.name) {
                return archivo.name;
            }

            if (archivo.archivo) {
                return this.nombreArchivo(
                    archivo.archivo
                );
            }
        }

        const url = this.archivoUrl(archivo);

        if (!url || url === '#') {
            return 'Archivo';
        }

        return decodeURIComponent(
            url
                .split('/')
                .pop()
                .split('?')[0]
        );
    },

    extensionArchivo(archivo) {
        const nombre = this.nombreArchivo(archivo);

        if (
            !nombre ||
            nombre === 'Archivo'
        ) {
            return 'FILE';
        }

        const partes = nombre.split('.');

        if (partes.length < 2) {
            return 'FILE';
        }

        return partes
            .pop()
            .toUpperCase();
    },

    esImagen(archivo) {
        const extension = this.extensionArchivo(
            archivo
        ).toLowerCase();

        return [
            'jpg',
            'jpeg',
            'png',
            'gif',
            'webp',
            'bmp',
            'svg'
        ].includes(extension);
    },

    generarRutaComentario(ticketId) {
        if (!ticketId) {
            return '';
        }

        return `/tickets/${ticketId}/comentarios`;
    }
}));