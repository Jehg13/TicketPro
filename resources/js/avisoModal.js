document.addEventListener('alpine:init', () => {

    Alpine.data('avisosModal', () => ({

        abierto: false,

        aviso: {
            tipo: '',
            importancia: '',
            titulo: '',
            descripcion: '',
            fecha_inicio: null,
            afecta_a: null,
            archivo: null
        },

        abrirAviso(aviso) {

            this.aviso = aviso || {
                tipo: '',
                importancia: '',
                titulo: '',
                descripcion: '',
                fecha_inicio: null,
                afecta_a: null,
                archivo: null
            };

            this.abierto = true;

            document.body.classList.add('overflow-hidden');

            this.$nextTick(() => {

                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }

            });
        },

        cerrarAviso() {

            this.abierto = false;

            document.body.classList.remove('overflow-hidden');

            setTimeout(() => {

                if (!this.abierto) {

                    this.aviso = {
                        tipo: '',
                        importancia: '',
                        titulo: '',
                        descripcion: '',
                        fecha_inicio: null,
                        afecta_a: null,
                        archivo: null
                    };

                }

            }, 200);
        },

        capitalizar(texto) {

            if (!texto) {
                return '';
            }

            return texto.charAt(0).toUpperCase() +
                texto.slice(1);
        },

        nombreTipo() {

            const tipos = {
                mantenimiento: 'Mantenimiento',
                incidente: 'Incidente',
                informativo: 'Informativo'
            };

            return tipos[this.aviso.tipo] || 'General';
        },

        colorTipo() {

            const colores = {
                mantenimiento: 'bg-amber-500',
                incidente: 'bg-red-500',
                informativo: 'bg-cyan-500'
            };

            return colores[this.aviso.tipo] || 'bg-blue-500';
        },

        badgeTipo() {

            const clases = {
                mantenimiento:
                    'bg-amber-500/10 text-amber-400 border-amber-500/20',

                incidente:
                    'bg-red-500/10 text-red-400 border-red-500/20',

                informativo:
                    'bg-cyan-500/10 text-cyan-400 border-cyan-500/20'
            };

            return clases[this.aviso.tipo] ||
                'bg-blue-500/10 text-blue-400 border-blue-500/20';
        },

        iconoClase() {

            const clases = {
                mantenimiento:
                    'bg-amber-500/10 border-amber-500/20 text-amber-400',

                incidente:
                    'bg-red-500/10 border-red-500/20 text-red-400',

                informativo:
                    'bg-cyan-500/10 border-cyan-500/20 text-cyan-400'
            };

            return clases[this.aviso.tipo] ||
                'bg-blue-500/10 border-blue-500/20 text-blue-400';
        },

        badgeImportancia() {

            const clases = {
                critica:
                    'border-red-500/30 bg-red-500/10 text-red-400',

                alta:
                    'border-orange-500/30 bg-orange-500/10 text-orange-400',

                media:
                    'border-yellow-500/30 bg-yellow-500/10 text-yellow-400',

                normal:
                    'border-blue-500/30 bg-blue-500/10 text-blue-400'
            };

            return clases[this.aviso.importancia] ||
                'border-blue-500/30 bg-blue-500/10 text-blue-400';
        },

        formatearFecha(fecha) {

            if (!fecha) {
                return 'No disponible';
            }

            const date = new Date(fecha);

            if (isNaN(date.getTime())) {
                return 'No disponible';
            }

            return date.toLocaleDateString('es-MX', {
                day: '2-digit',
                month: 'long',
                year: 'numeric',
                timeZone: 'America/Matamoros'
            });
        },

        formatearHora(fecha) {

            if (!fecha) {
                return 'No disponible';
            }

            const date = new Date(fecha);

            if (isNaN(date.getTime())) {
                return 'No disponible';
            }

            return date.toLocaleTimeString('es-MX', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true,
                timeZone: 'America/Matamoros'
            });
        },

        obtenerAfectados() {

            let afecta = this.aviso?.afecta_a;

            if (!afecta) {
                return 'Todos los usuarios';
            }

            if (typeof afecta === 'string') {

                try {
                    afecta = JSON.parse(afecta);
                } catch (error) {
                    return 'Todos los usuarios';
                }

            }

            if (!afecta || typeof afecta !== 'object') {
                return 'Todos los usuarios';
            }

            if (afecta.tipo === 'todos') {
                return 'Todos los usuarios';
            }

            if (afecta.tipo === 'departamentos') {

                if (
                    Array.isArray(afecta.nombres) &&
                    afecta.nombres.length
                ) {
                    return afecta.nombres.join(', ');
                }

                return 'Departamentos seleccionados';
            }

            if (afecta.tipo === 'usuarios') {

                if (
                    Array.isArray(afecta.nombres) &&
                    afecta.nombres.length
                ) {
                    return afecta.nombres.join(', ');
                }

                return 'Usuarios seleccionados';
            }

            return 'Todos los usuarios';
        },

        obtenerUrlArchivo(archivo) {

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

            return '/storage/' + archivo;
        },

        esImagen(archivo) {

            if (!archivo) {
                return false;
            }

            const extension = archivo
                .split('?')[0]
                .split('.')
                .pop()
                .toLowerCase();

            return [
                'jpg',
                'jpeg',
                'png',
                'gif',
                'webp',
                'svg'
            ].includes(extension);
        }

    }));

});