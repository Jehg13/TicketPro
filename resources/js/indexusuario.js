window.evolucionTickets = function () {
    return {
        periodo: 'semana',

        evolucionTickets: [],

        puntosGrafica: [],
        pathGrafica: '',

        maxGrafica: 1,

        promedioEvolucion: 0,
        maximoEvolucion: 0,
        minimoEvolucion: 0,

        async cargarPeriodo(periodo) {
            this.periodo = periodo;

            try {
                const url = new URL(
                    '/tecnologias/evolucion',
                    window.location.origin
                );

                url.searchParams.set('periodo', periodo);

                const parametros = new URLSearchParams(
                    window.location.search
                );

                const fechaInicio = parametros.get('fecha_inicio');
                const fechaFin = parametros.get('fecha_fin');

                if (fechaInicio) {
                    url.searchParams.set(
                        'fecha_inicio',
                        fechaInicio
                    );
                }

                if (fechaFin) {
                    url.searchParams.set(
                        'fecha_fin',
                        fechaFin
                    );
                }

                const response = await fetch(url.toString(), {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error(
                        `Error HTTP ${response.status}`
                    );
                }

                const data = await response.json();

                this.evolucionTickets =
                    Array.isArray(data.evolucionTickets)
                        ? data.evolucionTickets
                        : [];

                this.promedioEvolucion =
                    Number(data.promedioEvolucion) || 0;

                this.maximoEvolucion =
                    Number(data.maximoEvolucion) || 0;

                this.minimoEvolucion =
                    Number(data.minimoEvolucion) || 0;

                this.generarGrafica();

            } catch (error) {

                console.error(
                    'Error al cargar evolución de tickets:',
                    error
                );

                this.evolucionTickets = [];
                this.puntosGrafica = [];
                this.pathGrafica = '';

                this.maxGrafica = 1;

                this.promedioEvolucion = 0;
                this.maximoEvolucion = 0;
                this.minimoEvolucion = 0;
            }
        },

        generarGrafica() {

            const datos = Array.isArray(this.evolucionTickets)
                ? this.evolucionTickets
                : [];

            if (datos.length === 0) {

                this.puntosGrafica = [];
                this.pathGrafica = '';
                this.maxGrafica = 1;

                return;
            }

            const valores = datos.map((dia) => {
                return Number(dia.total) || 0;
            });

            this.maxGrafica = Math.max(
                ...valores,
                1
            );

            const ancho = 600;
            const alto = 120;

            const margenX = 10;
            const margenY = 15;

            const cantidad = datos.length;

            this.puntosGrafica = [];

            datos.forEach((dia, index) => {

                const total =
                    Number(dia.total) || 0;

                let x;

                if (cantidad === 1) {

                    x = ancho / 2;

                } else {

                    x =
                        margenX +
                        (
                            (ancho - margenX * 2) /
                            (cantidad - 1)
                        ) * index;
                }

                let y =
                    alto -
                    margenY -
                    (
                        total /
                        this.maxGrafica
                    ) *
                    (
                        alto -
                        margenY * 2
                    );

                if (!Number.isFinite(x)) {
                    x = 0;
                }

                if (!Number.isFinite(y)) {
                    y = alto - margenY;
                }

                this.puntosGrafica.push({
                    x: Number(x.toFixed(2)),
                    y: Number(y.toFixed(2)),
                    total: total
                });
            });

            this.generarLinea();
        },

        generarLinea() {

            if (
                !Array.isArray(this.puntosGrafica) ||
                this.puntosGrafica.length === 0
            ) {
                this.pathGrafica = '';
                return;
            }

            const partes = [];

            this.puntosGrafica.forEach((punto, index) => {

                if (
                    !punto ||
                    typeof punto.x !== 'number' ||
                    typeof punto.y !== 'number'
                ) {
                    return;
                }

                const comando =
                    index === 0
                        ? 'M'
                        : 'L';

                partes.push(
                    `${comando} ${punto.x} ${punto.y}`
                );
            });

            this.pathGrafica = partes.join(' ');
        }
    };
};


window.filtroFechas = function () {

    return {

        abierto: false,

        fechaInicio: '',
        fechaFin: '',

        textoFecha: 'Seleccionar fechas',

        init() {

            const parametros =
                new URLSearchParams(
                    window.location.search
                );

            this.fechaInicio =
                parametros.get('fecha_inicio') || '';

            this.fechaFin =
                parametros.get('fecha_fin') || '';

            this.actualizarTexto();
        },

        actualizarTexto() {

            if (
                !this.fechaInicio ||
                !this.fechaFin
            ) {
                this.textoFecha =
                    'Seleccionar fechas';

                return;
            }

            const inicio =
                this.formatearFecha(
                    this.fechaInicio
                );

            const fin =
                this.formatearFecha(
                    this.fechaFin
                );

            this.textoFecha =
                `${inicio} – ${fin}`;
        },

        formatearFecha(fecha) {

            if (!fecha) {
                return '';
            }

            const partes =
                fecha.split('-');

            if (partes.length !== 3) {
                return fecha;
            }

            return (
                `${partes[2]}/` +
                `${partes[1]}/` +
                `${partes[0]}`
            );
        },

        aplicar() {

            if (
                !this.fechaInicio ||
                !this.fechaFin
            ) {

                alert(
                    'Selecciona una fecha inicial y una fecha final.'
                );

                return;
            }

            if (
                this.fechaInicio >
                this.fechaFin
            ) {

                alert(
                    'La fecha inicial no puede ser mayor que la fecha final.'
                );

                return;
            }

            const url =
                new URL(
                    window.location.href
                );

            url.searchParams.set(
                'fecha_inicio',
                this.fechaInicio
            );

            url.searchParams.set(
                'fecha_fin',
                this.fechaFin
            );

            window.location.href =
                url.toString();
        },

        limpiar() {

            this.fechaInicio = '';
            this.fechaFin = '';

            const url =
                new URL(
                    window.location.href
                );

            url.searchParams.delete(
                'fecha_inicio'
            );

            url.searchParams.delete(
                'fecha_fin'
            );

            window.location.href =
                url.toString();
        }
    };
};