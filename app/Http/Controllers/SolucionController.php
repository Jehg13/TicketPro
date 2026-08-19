<?php

namespace App\Http\Controllers;

use App\Models\Solucion;
use App\Models\TicketU;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SolucionController extends Controller
{
    public function store(Request $request, TicketU $ticket)
    {
        $request->validate([
            'solucion' => [
                'required',
                'string',
                'max:10000',
            ],

            'problema_solucionado' => [
                'required',
                'boolean',
            ],

            'fecha_solucion' => [
                'required',
                'date',
            ],

            'nombre_firmante' => [
                'required',
                'string',
                'max:255',
            ],

            'fecha_firma' => [
                'nullable',
                'date',
            ],

            'firma' => [
                'required',
                'string',
            ],

            'evidencias' => [
                'nullable',
                'array',
                'max:10',
            ],

            'evidencias.*' => [
                'file',
                'max:10240',
                'mimes:jpg,jpeg,png,gif,webp,pdf,mp4,mov,avi',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | VERIFICAR SI YA TIENE SOLUCIÓN
        |--------------------------------------------------------------------------
        */

        if ($ticket->solucion_id) {
            return response()->json([
                'success' => false,
                'message' =>
                    'Este ticket ya tiene una solución o cancelación registrada.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDAR FIRMA
        |--------------------------------------------------------------------------
        */

        $firmaBase64 = $request->input('firma');

        if (
            !is_string($firmaBase64) ||
            !preg_match(
                '/^data:image\/png;base64,/',
                $firmaBase64
            )
        ) {
            return response()->json([
                'success' => false,
                'message' =>
                    'La firma no tiene un formato válido.',
            ], 422);
        }

        $firmaBase64 = preg_replace(
            '/^data:image\/png;base64,/',
            '',
            $firmaBase64
        );

        $firmaBinaria = base64_decode(
            $firmaBase64,
            true
        );

        if ($firmaBinaria === false) {
            return response()->json([
                'success' => false,
                'message' =>
                    'No se pudo procesar la firma.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | GUARDAR FIRMA
        |--------------------------------------------------------------------------
        */

        $nombreArchivo =
            'firma_' .
            $ticket->id .
            '_' .
            uniqid() .
            '.png';

        $rutaFirma =
            'firmas/' .
            $nombreArchivo;

        $firmaGuardada =
            Storage::disk('public')->put(
                $rutaFirma,
                $firmaBinaria
            );

        if (!$firmaGuardada) {
            return response()->json([
                'success' => false,
                'message' =>
                    'No se pudo guardar la firma.',
            ], 500);
        }

        /*
        |--------------------------------------------------------------------------
        | DETERMINAR ESTADO
        |--------------------------------------------------------------------------
        */

        $problemaSolucionado =
            $request->boolean(
                'problema_solucionado'
            );

        $estado =
            $problemaSolucionado
                ? 'solucionado'
                : 'cancelado';

        /*
        |--------------------------------------------------------------------------
        | GUARDAR EVIDENCIAS
        |--------------------------------------------------------------------------
        */

        $evidencias = [];

        if ($request->hasFile('evidencias')) {

            foreach (
                $request->file('evidencias')
                as $archivo
            ) {

                if (!$archivo->isValid()) {
                    continue;
                }

                $ruta =
                    $archivo->store(
                        'evidencias_soluciones',
                        'public'
                    );

                $evidencias[] = [
                    'nombre' =>
                        $archivo->getClientOriginalName(),

                    'ruta' =>
                        $ruta,

                    'url' =>
                        Storage::disk('public')
                            ->url($ruta),

                    'tipo' =>
                        $archivo->getClientMimeType(),

                    'extension' =>
                        strtolower(
                            $archivo->getClientOriginalExtension()
                        ),

                    'tamano' =>
                        $archivo->getSize(),
                ];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | CREAR SOLUCIÓN
        |--------------------------------------------------------------------------
        */

        $solucion = Solucion::create([
            'ticket_id' =>
                $ticket->id,

            'solucionado_por' =>
                Auth::id(),

            'problema_solucionado' =>
                $problemaSolucionado,

            'solucion' =>
                $request->input('solucion'),

            'firma' =>
                $rutaFirma,

            'fecha_solucion' =>
                $request->input('fecha_solucion'),

            'nombre_firmante' =>
                $request->input('nombre_firmante'),

            'fecha_firma' =>
                $request->input('fecha_firma'),

            'evidencia' =>
                $evidencias,
        ]);

        /*
        |--------------------------------------------------------------------------
        | ACTUALIZAR TICKET
        |--------------------------------------------------------------------------
        */

        $ticket->update([
            'solucion_id' =>
                $solucion->id,

            'estado' =>
                $estado,
        ]);

        /*
        |--------------------------------------------------------------------------
        | CREAR NOTIFICACIÓN PARA EL USUARIO DEL TICKET
        |--------------------------------------------------------------------------
        |
        | El ticket tiene un user_id que corresponde al usuario
        | que creó el ticket.
        |
        */

        if ($ticket->user_id) {

            $tituloNotificacion =
                $problemaSolucionado
                    ? 'Ticket solucionado'
                    : 'Ticket cancelado';

            $mensajeNotificacion =
                $problemaSolucionado
                    ? "Tu ticket #{$ticket->folio} fue solucionado correctamente."
                    : "Tu ticket #{$ticket->folio} fue cancelado.";

            /*
            |--------------------------------------------------------------------------
            | IMPORTANTE
            |--------------------------------------------------------------------------
            |
            | Aquí puedes cambiar la URL cuando tengas la ruta específica
            | para abrir directamente el ticket del usuario.
            |
            */

            $urlNotificacion = route(
                'ticketusuario.detalles',
                $ticket->id
            );

            Notificacion::create([
                'user_id' =>
                    $ticket->user_id,

                'tipo' =>
                    $problemaSolucionado
                        ? 'ticket_solucionado'
                        : 'ticket_cancelado',

                'titulo' =>
                    $tituloNotificacion,

                'mensaje' =>
                    $mensajeNotificacion,

                'url' =>
                    $urlNotificacion,

                'leida' =>
                    false,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | ACTUALIZAR MODELOS
        |--------------------------------------------------------------------------
        */

        $ticket->refresh();

        $solucion->refresh();

        /*
        |--------------------------------------------------------------------------
        | URL DE FIRMA
        |--------------------------------------------------------------------------
        */

        $urlFirma =
            Storage::disk('public')
                ->url($rutaFirma);

        /*
        |--------------------------------------------------------------------------
        | RESPUESTA
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => true,

            'message' =>
                $problemaSolucionado
                    ? 'El ticket fue solucionado correctamente.'
                    : 'El ticket fue marcado como cancelado.',

            'estado' =>
                $estado,

            'solucion' => [
                'id' =>
                    $solucion->id,

                'ticket_id' =>
                    $solucion->ticket_id,

                'solucionado_por' =>
                    $solucion->solucionado_por,

                'problema_solucionado' =>
                    $solucion->problema_solucionado,

                'solucion' =>
                    $solucion->solucion,

                'firma' =>
                    $solucion->firma,

                'url_firma' =>
                    $urlFirma,

                'fecha_solucion' =>
                    $solucion->fecha_solucion,

                'nombre_firmante' =>
                    $solucion->nombre_firmante,

                'fecha_firma' =>
                    $solucion->fecha_firma,

                'evidencia' =>
                    $solucion->evidencia ?? [],

                'evidencias' =>
                    $solucion->evidencia ?? [],

                'created_at' =>
                    $solucion->created_at,

                'updated_at' =>
                    $solucion->updated_at,
            ],

            'evidencias' =>
                $solucion->evidencia ?? [],

            'ticket' => [
                'id' =>
                    $ticket->id,

                'folio' =>
                    $ticket->folio,

                'estado' =>
                    $estado,

                'solucion_id' =>
                    $ticket->solucion_id,
            ],
        ]);
    }
}