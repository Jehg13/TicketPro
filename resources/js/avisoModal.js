document.addEventListener('alpine:init', () => {

    Alpine.data('avisosModal', () => ({
        abierto: false,

        aviso: {},

        abrirAviso(aviso) {
            this.aviso = aviso || {};
            this.abierto = true;

            document.body.classList.add('overflow-hidden');
        },

        cerrarAviso() {
            this.abierto = false;

            document.body.classList.remove('overflow-hidden');

            setTimeout(() => {
                this.aviso = {};
            }, 200);
        },

        capitalizar(texto) {
            if (!texto) {
                return '';
            }

            return texto.charAt(0).toUpperCase() + texto.slice(1);
        },

        nombreTipo() {
            if (this.aviso.tipo === 'mantenimiento') {
                return 'Mantenimiento';
            }

            if (this.aviso.tipo === 'incidente') {
                return 'Incidente';
            }

            if (this.aviso.tipo === 'informativo') {
                return 'Informativo';
            }

            return 'General';
        },

        colorTipo() {
            if (this.aviso.tipo === 'mantenimiento') {
                return 'bg-amber-500';
            }

            if (this.aviso.tipo === 'incidente') {
                return 'bg-red-500';
            }

            if (this.aviso.tipo === 'informativo') {
                return 'bg-cyan-500';
            }

            return 'bg-blue-500';
        },

        badgeTipo() {
            if (this.aviso.tipo === 'mantenimiento') {
                return 'bg-amber-500/10 text-amber-400 border-amber-500/20';
            }

            if (this.aviso.tipo === 'incidente') {
                return 'bg-red-500/10 text-red-400 border-red-500/20';
            }

            if (this.aviso.tipo === 'informativo') {
                return 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20';
            }

            return 'bg-blue-500/10 text-blue-400 border-blue-500/20';
        },

        iconoClase() {
            if (this.aviso.tipo === 'mantenimiento') {
                return 'bg-amber-500/10 border-amber-500/20 text-amber-400';
            }

            if (this.aviso.tipo === 'incidente') {
                return 'bg-red-500/10 border-red-500/20 text-red-400';
            }

            if (this.aviso.tipo === 'informativo') {
                return 'bg-cyan-500/10 border-cyan-500/20 text-cyan-400';
            }

            return 'bg-blue-500/10 border-blue-500/20 text-blue-400';
        },

        badgeImportancia() {
            if (this.aviso.importancia === 'critica') {
                return 'border-red-500/30 bg-red-500/10 text-red-400';
            }

            if (this.aviso.importancia === 'alta') {
                return 'border-orange-500/30 bg-orange-500/10 text-orange-400';
            }

            if (this.aviso.importancia === 'media') {
                return 'border-yellow-500/30 bg-yellow-500/10 text-yellow-400';
            }

            return 'border-blue-500/30 bg-blue-500/10 text-blue-400';
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
                year: 'numeric'
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
                minute: '2-digit'
            });
        },

        obtenerAfectados() {
            let afecta = this.aviso.afecta_a;

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
                return 'Departamentos seleccionados';
            }

            if (afecta.tipo === 'usuarios') {
                return 'Usuarios seleccionados';
            }

            return 'Todos los usuarios';
        },

        obtenerUrlArchivo(archivo) {
            if (!archivo) {
                return '';
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