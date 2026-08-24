<?php

namespace App\Http\Controllers;

use App\Models\Solucion;
use App\Models\TicketU;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
                'mimes:jpg,jpeg,png,gif,webp,pdf,mp4,mov,avi,doc,docx,xls,xlsx,txt,zip,rar',
            ],
        ]);

        if ($ticket->solucion_id) {
            return response()->json([
                'success' => false,
                'type' => 'error',
                'message' => 'Este ticket ya tiene una solución o cancelación registrada.',
            ], 422);
        }

        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'type' => 'error',
                'message' => 'No hay un usuario autenticado.',
            ], 401);
        }

        $login = $usuario->login;

        if (!$login) {
            return response()->json([
                'success' => false,
                'type' => 'error',
                'message' => 'El usuario autenticado no tiene login.',
            ], 422);
        }

        $firmaBase64 = $request->input('firma');

        if (
            !is_string($firmaBase64) ||
            !preg_match('/^data:image\/png;base64,/', $firmaBase64)
        ) {
            return response()->json([
                'success' => false,
                'type' => 'error',
                'message' => 'La firma no tiene un formato válido.',
            ], 422);
        }

        $firmaBase64 = preg_replace(
            '/^data:image\/png;base64,/',
            '',
            $firmaBase64
        );

        $firmaBinaria = base64_decode($firmaBase64, true);

        if ($firmaBinaria === false) {
            return response()->json([
                'success' => false,
                'type' => 'error',
                'message' => 'No se pudo procesar la firma.',
            ], 422);
        }

        $nombreFirma = 'firma_' . $ticket->id . '_' . uniqid() . '.png';

        $rutaFirma = 'firmas/' . $nombreFirma;

        $firmaGuardada = Storage::disk('public')->put(
            $rutaFirma,
            $firmaBinaria
        );

        if (!$firmaGuardada) {
            return response()->json([
                'success' => false,
                'type' => 'error',
                'message' => 'No se pudo guardar la firma.',
            ], 500);
        }

        $problemaSolucionado = $request->boolean('problema_solucionado');

        $estado = $problemaSolucionado
            ? 'solucionado'
            : 'cancelado';

        $evidencias = [];

        if ($request->hasFile('evidencias')) {
            foreach ($request->file('evidencias') as $archivo) {
                if (!$archivo || !$archivo->isValid()) {
                    continue;
                }

                $ruta = $archivo->store(
                    'evidencia_tickets',
                    'public'
                );

                if (!$ruta) {
                    continue;
                }

                $evidencias[] = [
                    'nombre' => $archivo->getClientOriginalName(),
                    'ruta' => $ruta,
                    'url' => Storage::disk('public')->url($ruta),
                    'tipo' => $archivo->getClientMimeType(),
                    'extension' => strtolower(
                        $archivo->getClientOriginalExtension()
                    ),
                    'tamano' => $archivo->getSize(),
                ];
            }
        }

        Log::info('EVIDENCIAS SOLUCION', [
            'ticket_id' => $ticket->id,
            'cantidad' => count($evidencias),
            'evidencias' => $evidencias,
        ]);

        $solucion = Solucion::create([
            'ticket_id' => $ticket->id,
            'login' => $login,
            'solucionado_por' => $login,
            'problema_solucionado' => $problemaSolucionado,
            'solucion' => $request->input('solucion'),
            'firma' => $rutaFirma,
            'fecha_solucion' => $request->input('fecha_solucion'),
            'nombre_firmante' => $request->input('nombre_firmante'),
            'fecha_firma' => $request->input('fecha_firma'),
            'evidencia' => $evidencias,
        ]);

        $ticket->update([
            'solucion_id' => $solucion->id,
            'estado' => $estado,
        ]);

        $loginUsuarioTicket = $ticket->login;

        if ($loginUsuarioTicket) {
            $tituloNotificacion = $problemaSolucionado
                ? 'Ticket solucionado'
                : 'Ticket cancelado';

            $mensajeNotificacion = $problemaSolucionado
                ? "Tu ticket #{$ticket->folio} fue solucionado correctamente."
                : "Tu ticket #{$ticket->folio} fue cancelado.";

            $urlNotificacion = route(
                'ticketusuario.detalles',
                [
                    'ticket' => $ticket->id,
                ]
            );

            Notificacion::create([
                'login' => $loginUsuarioTicket,
                'tipo' => $problemaSolucionado
                    ? 'ticket_solucionado'
                    : 'ticket_cancelado',
                'titulo' => $tituloNotificacion,
                'mensaje' => $mensajeNotificacion,
                'url' => $urlNotificacion,
                'leida' => false,
                'icono' => $problemaSolucionado
                    ? 'check-circle'
                    : 'x-circle',
                'color' => $problemaSolucionado
                    ? 'green'
                    : 'red',
            ]);
        }

        $ticket->refresh();
        $solucion->refresh();

        $evidenciasRespuesta = $solucion->evidencia;

        if (is_string($evidenciasRespuesta)) {
            $evidenciasRespuesta = json_decode(
                $evidenciasRespuesta,
                true
            );
        }

        if (!is_array($evidenciasRespuesta)) {
            $evidenciasRespuesta = [];
        }

        $evidenciasRespuesta = array_values(
            array_filter(
                $evidenciasRespuesta,
                function ($evidencia) {
                    return is_array($evidencia);
                }
            )
        );

        $evidenciasRespuesta = array_map(
            function ($evidencia) {
                if (
                    empty($evidencia['url']) &&
                    !empty($evidencia['ruta'])
                ) {
                    $evidencia['url'] = Storage::disk('public')->url(
                        $evidencia['ruta']
                    );
                }

                if (
                    empty($evidencia['nombre']) &&
                    !empty($evidencia['ruta'])
                ) {
                    $evidencia['nombre'] = basename(
                        $evidencia['ruta']
                    );
                }

                return $evidencia;
            },
            $evidenciasRespuesta
        );

        Log::info('SOLUCION FINAL', [
            'id' => $solucion->id,
            'evidencias' => $evidenciasRespuesta,
        ]);

        $urlFirma = Storage::disk('public')->url($rutaFirma);

        return response()->json([
            'success' => true,
            'type' => 'success',
            'message' => $problemaSolucionado
                ? 'El ticket fue solucionado correctamente.'
                : 'El ticket fue marcado como cancelado.',
            'estado' => $estado,
            'solucion' => [
                'id' => $solucion->id,
                'ticket_id' => $solucion->ticket_id,
                'login' => $solucion->login,
                'solucionado_por' => $solucion->solucionado_por,
                'problema_solucionado' => $solucion->problema_solucionado,
                'solucion' => $solucion->solucion,
                'firma' => $solucion->firma,
                'url_firma' => $urlFirma,
                'fecha_solucion' => $solucion->fecha_solucion,
                'nombre_firmante' => $solucion->nombre_firmante,
                'fecha_firma' => $solucion->fecha_firma,
                'evidencia' => $evidenciasRespuesta,
                'evidencias' => $evidenciasRespuesta,
                'created_at' => $solucion->created_at,
                'updated_at' => $solucion->updated_at,
            ],
            'evidencias' => $evidenciasRespuesta,
            'ticket' => [
                'id' => $ticket->id,
                'folio' => $ticket->folio,
                'estado' => $estado,
                'solucion_id' => $ticket->solucion_id,
            ],
        ]);
    }
}