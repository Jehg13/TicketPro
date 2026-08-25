<?php
namespace App\Http\Controllers;
use App\Models\Solucion;
use App\Models\TicketU;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
class SolucionController extends Controller
{
    public function store(Request $request, TicketU $ticket)
    {
        $request->validate([
            'solucion'=>['required','string','max:10000'],
            'problema_solucionado'=>['required','boolean'],
            'fecha_solucion'=>['required','date'],
            'nombre_firmante'=>['required','string','max:255'],
            'fecha_firma'=>['nullable','date'],
            'firma'=>['required','string'],
            'evidencias'=>['nullable','array','max:10'],
            'evidencias.*'=>['file','max:10240','mimes:jpg,jpeg,png,gif,webp,pdf,mp4,webm,mov,avi,mkv,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar'],
        ]);
        if($ticket->solucion_id){
            return response()->json([
                'success'=>false,
                'type'=>'error',
                'message'=>'Este ticket ya tiene una solución o cancelación registrada.',
            ],422);
        }
        $usuario=Auth::user();
        if(!$usuario){
            return response()->json([
                'success'=>false,
                'type'=>'error',
                'message'=>'No hay un usuario autenticado.',
            ],401);
        }
        $login=$usuario->login;
        if(!$login){
            return response()->json([
                'success'=>false,
                'type'=>'error',
                'message'=>'El usuario autenticado no tiene login.',
            ],422);
        }
        $firmaBase64=$request->input('firma');
        if(!is_string($firmaBase64)||!preg_match('/^data:image\/png;base64,/', $firmaBase64)){
            return response()->json([
                'success'=>false,
                'type'=>'error',
                'message'=>'La firma no tiene un formato válido.',
            ],422);
        }
        $firmaBase64=preg_replace('/^data:image\/png;base64,/','',$firmaBase64);
        $firmaBinaria=base64_decode($firmaBase64,true);
        if($firmaBinaria===false){
            return response()->json([
                'success'=>false,
                'type'=>'error',
                'message'=>'No se pudo procesar la firma.',
            ],422);
        }
        $nombreFirma='firma_'.$ticket->id.'_'.uniqid().'.png';
        $rutaFirma='firmas/'.$nombreFirma;
        $firmaGuardada=Storage::disk('public')->put($rutaFirma,$firmaBinaria);
        if(!$firmaGuardada){
            return response()->json([
                'success'=>false,
                'type'=>'error',
                'message'=>'No se pudo guardar la firma.',
            ],500);
        }
        $problemaSolucionado=$request->boolean('problema_solucionado');
        $estado=$problemaSolucionado?'solucionado':'cancelado';
        $evidencias=[];
        $archivosGuardados=[];
        if($request->hasFile('evidencias')){
            foreach($request->file('evidencias') as $archivo){
                if(!$archivo||!$archivo->isValid()){
                    continue;
                }
                $ruta=$archivo->store('evidencia_tickets','public');
                if(!$ruta){
                    continue;
                }
                $archivosGuardados[]=$ruta;
                $evidencias[]=$ruta;
            }
        }
        try{
            DB::beginTransaction();
            $solucion=Solucion::create([
                'ticket_id'=>$ticket->id,
                'login'=>$login,
                'solucionado_por'=>$login,
                'problema_solucionado'=>$problemaSolucionado,
                'solucion'=>$request->input('solucion'),
                'firma'=>$rutaFirma,
                'fecha_solucion'=>$request->input('fecha_solucion'),
                'nombre_firmante'=>$request->input('nombre_firmante'),
                'fecha_firma'=>$request->input('fecha_firma'),
                'evidencia'=>$evidencias,
            ]);
            $ticket->update([
                'solucion_id'=>$solucion->id,
                'estado'=>$estado,
            ]);
            $loginUsuarioTicket=$ticket->login;
            if($loginUsuarioTicket){
                $tituloNotificacion=$problemaSolucionado?'Ticket solucionado':'Ticket cancelado';
                $mensajeNotificacion=$problemaSolucionado
                    ?"Tu ticket #{$ticket->folio} fue solucionado correctamente."
                    :"Tu ticket #{$ticket->folio} fue cancelado.";
                $urlNotificacion=route('ticketusuario.detalles',['ticket'=>$ticket->id]);
                Notificacion::create([
                    'login'=>$loginUsuarioTicket,
                    'tipo'=>$problemaSolucionado?'ticket_solucionado':'ticket_cancelado',
                    'titulo'=>$tituloNotificacion,
                    'mensaje'=>$mensajeNotificacion,
                    'url'=>$urlNotificacion,
                    'leida'=>false,
                    'icono'=>$problemaSolucionado?'check-circle':'x-circle',
                    'color'=>$problemaSolucionado?'green':'red',
                ]);
            }
            DB::commit();
        }catch(\Throwable $e){
            DB::rollBack();
            Storage::disk('public')->delete($rutaFirma);
            foreach($archivosGuardados as $ruta){
                Storage::disk('public')->delete($ruta);
            }
            Log::error('ERROR AL GUARDAR SOLUCION',[
                'ticket_id'=>$ticket->id,
                'error'=>$e->getMessage(),
                'trace'=>$e->getTraceAsString(),
            ]);
            return response()->json([
                'success'=>false,
                'type'=>'error',
                'message'=>'No se pudo guardar la solución.',
            ],500);
        }
        $ticket->refresh();
        $solucion->refresh();
        $evidenciasGuardadas=$solucion->evidencia;
        if(is_string($evidenciasGuardadas)){
            $evidenciasGuardadas=json_decode($evidenciasGuardadas,true);
        }
        if(!is_array($evidenciasGuardadas)){
            $evidenciasGuardadas=[];
        }
        $tipos=[
            'jpg'=>'image/jpeg',
            'jpeg'=>'image/jpeg',
            'png'=>'image/png',
            'gif'=>'image/gif',
            'webp'=>'image/webp',
            'bmp'=>'image/bmp',
            'svg'=>'image/svg+xml',
            'pdf'=>'application/pdf',
            'mp4'=>'video/mp4',
            'webm'=>'video/webm',
            'mov'=>'video/quicktime',
            'avi'=>'video/x-msvideo',
            'mkv'=>'video/x-matroska',
            'doc'=>'application/msword',
            'docx'=>'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls'=>'application/vnd.ms-excel',
            'xlsx'=>'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'ppt'=>'application/vnd.ms-powerpoint',
            'pptx'=>'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'txt'=>'text/plain',
            'zip'=>'application/zip',
            'rar'=>'application/vnd.rar',
        ];
        $evidenciasRespuesta=[];
        foreach($evidenciasGuardadas as $evidencia){
            if(is_array($evidencia)){
                $ruta=$evidencia['ruta']??$evidencia['path']??null;
                if(!$ruta){
                    continue;
                }
                $nombre=$evidencia['nombre']??basename($ruta);
                $extension=strtolower($evidencia['extension']??pathinfo($ruta,PATHINFO_EXTENSION));
                $tipo=$evidencia['tipo']??($tipos[$extension]??'application/octet-stream');
                $url=$evidencia['url']??asset('storage/'.ltrim($ruta,'/'));
                $evidenciasRespuesta[]=[
                    'nombre'=>$nombre,
                    'ruta'=>$ruta,
                    'url'=>$url,
                    'tipo'=>$tipo,
                    'extension'=>$extension,
                    'tamano'=>$evidencia['tamano']??null,
                ];
                continue;
            }
            if(!is_string($evidencia)||trim($evidencia)===''){
                continue;
            }
            $ruta=trim($evidencia);
            $extension=strtolower(pathinfo($ruta,PATHINFO_EXTENSION));
            $url=asset('storage/'.ltrim($ruta,'/'));
            $evidenciasRespuesta[]=[
                'nombre'=>basename($ruta),
                'ruta'=>$ruta,
                'url'=>$url,
                'tipo'=>$tipos[$extension]??'application/octet-stream',
                'extension'=>$extension,
                'tamano'=>Storage::disk('public')->exists($ruta)?Storage::disk('public')->size($ruta):null,
            ];
        }
        $urlFirma=asset('storage/'.ltrim($rutaFirma,'/'));
        $solucionRespuesta=[
            'id'=>$solucion->id,
            'ticket_id'=>$solucion->ticket_id,
            'login'=>$solucion->login,
            'solucionado_por'=>$solucion->solucionado_por,
            'problema_solucionado'=>(bool)$solucion->problema_solucionado,
            'solucion'=>$solucion->solucion,
            'firma'=>$solucion->firma,
            'url_firma'=>$urlFirma,
            'fecha_solucion'=>$solucion->fecha_solucion,
            'nombre_firmante'=>$solucion->nombre_firmante,
            'fecha_firma'=>$solucion->fecha_firma,
            'evidencia'=>$evidenciasRespuesta,
            'evidencias'=>$evidenciasRespuesta,
            'created_at'=>$solucion->created_at,
            'updated_at'=>$solucion->updated_at,
        ];
        Log::info('SOLUCION FINAL',[
            'id'=>$solucion->id,
            'ticket_id'=>$ticket->id,
            'evidencias'=>$evidenciasRespuesta,
            'firma'=>$urlFirma,
        ]);
        return response()->json([
            'success'=>true,
            'type'=>'success',
            'message'=>$problemaSolucionado
                ?'El ticket fue solucionado correctamente.'
                :'El ticket fue marcado como cancelado.',
            'estado'=>$estado,
            'solucion'=>$solucionRespuesta,
            'evidencias'=>$evidenciasRespuesta,
            'firma'=>$urlFirma,
            'ticket'=>[
                'id'=>$ticket->id,
                'folio'=>$ticket->folio,
                'estado'=>$estado,
                'solucion_id'=>$ticket->solucion_id,
            ],
        ]);
    }
}