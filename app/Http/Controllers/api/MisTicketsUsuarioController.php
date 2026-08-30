<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\TicketU;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class MisTicketsUsuarioController extends Controller
{
    public function index(Request $request)
    {
        $usuario=Auth::user();
        if(!$usuario)return response()->json(['success'=>false,'message'=>'Sesión no válida.'],401);
        $login=trim((string)$usuario->login);
        $buscar=trim((string)$request->input('buscar',''));
        $estado=strtolower(trim((string)$request->input('estado','todos')));
        $estados=['todos','pendiente','en proceso','solucionado','cancelado'];
        if(!in_array($estado,$estados,true))$estado='todos';
        $perPage=min(max((int)$request->input('per_page',10),1),50);
        $query=TicketU::with(['user','tomadoPor','historialComentarios.usuario','solucion'])->where('login',$login);
        if($estado!=='todos')$query->where('estado',$estado);
        if($buscar!==''){
            $query->where(function($q)use($buscar){
                $like="%{$buscar}%";
                $q->whereRaw('CAST(folio AS CHAR) LIKE ?',[$like])->orWhere('titulo','LIKE',$like)->orWhere('tipo_falla','LIKE',$like)->orWhere('prioridad','LIKE',$like)->orWhere('descripcion','LIKE',$like);
                foreach(['d/m/Y','Y-m-d'] as $formato){
                    try{$fecha=Carbon::createFromFormat($formato,$buscar)->format('Y-m-d');$q->orWhereDate('created_at',$fecha);break;}catch(\Exception $e){}
                }
            });
        }
        $tickets=$query->latest('created_at')->paginate($perPage);
        return response()->json([
            'success'=>true,
            'usuario'=>[
                'login'=>$usuario->login,
                'name'=>$usuario->name,
                'email'=>$usuario->email
            ],
            'filtro'=>$estado,
            'buscar'=>$buscar,
            'tickets'=>$tickets->items(),
            'pagination'=>[
                'current_page'=>$tickets->currentPage(),
                'last_page'=>$tickets->lastPage(),
                'per_page'=>$tickets->perPage(),
                'total'=>$tickets->total(),
                'from'=>$tickets->firstItem(),
                'to'=>$tickets->lastItem()
            ]
        ]);
    }
    public function show($id)
    {
        $usuario=Auth::user();
        if(!$usuario)return response()->json(['success'=>false,'message'=>'Sesión no válida.'],401);
        $login=trim((string)$usuario->login);
        $ticket=TicketU::with(['user','tomadoPor','historialComentarios.usuario','solucion'])->where('id',$id)->where('login',$login)->first();
        if(!$ticket)return response()->json(['success'=>false,'message'=>'Ticket no encontrado o no tienes permiso para consultarlo.'],404);
        return response()->json(['success'=>true,'ticket'=>$ticket]);
    }
    public function notificaciones(Request $request)
    {
        $usuario=Auth::user();
        if(!$usuario)return response()->json(['success'=>false,'message'=>'Sesión no válida.'],401);
        $login=trim((string)$usuario->login);
        $perPage=min(max((int)$request->input('per_page',10),1),50);
        $notificaciones=Notificacion::where('login',$login)->latest('created_at')->paginate($perPage);
        $noLeidas=Notificacion::where('login',$login)->where('leida',false)->count();
        return response()->json([
            'success'=>true,
            'notificaciones'=>$notificaciones->items(),
            'no_leidas'=>$noLeidas,
            'pagination'=>[
                'current_page'=>$notificaciones->currentPage(),
                'last_page'=>$notificaciones->lastPage(),
                'per_page'=>$notificaciones->perPage(),
                'total'=>$notificaciones->total(),
                'from'=>$notificaciones->firstItem(),
                'to'=>$notificaciones->lastItem()
            ]
        ]);
    }
    public function marcarNotificacionLeida($id)
    {
        $usuario=Auth::user();
        if(!$usuario)return response()->json(['success'=>false,'message'=>'Sesión no válida.'],401);
        $login=trim((string)$usuario->login);
        $notificacion=Notificacion::where('id',$id)->where('login',$login)->first();
        if(!$notificacion)return response()->json(['success'=>false,'message'=>'Notificación no encontrada.'],404);
        $notificacion->update(['leida'=>true]);
        return response()->json(['success'=>true,'message'=>'Notificación marcada como leída.']);
    }

    public function agregarComentario(Request $request, $id)
{
    $usuario = Auth::user();

    if (!$usuario) {
        return response()->json([
            'success' => false,
            'message' => 'Sesión no válida.'
        ], 401);
    }

    $request->validate([
        'mensaje' => 'required|string|max:2000',
        'archivo' => 'nullable|file|max:10240'
    ]);

    $login = trim((string) $usuario->login);

    $ticket = TicketU::where('id', $id)
        ->where('login', $login)
        ->first();

    if (!$ticket) {
        return response()->json([
            'success' => false,
            'message' => 'Ticket no encontrado o no tienes permiso para comentar.'
        ], 404);
    }

    $archivo = null;

    if ($request->hasFile('archivo')) {
        $archivo = $request->file('archivo')->store(
            'ticket_comentarios',
            'public'
        );
    }

    $comentario = $ticket->historialComentarios()->create([
        'login' => $login,
        'mensaje' => trim($request->input('mensaje')),
        'archivo' => $archivo
    ]);

    $comentario->load('usuario');

    return response()->json([
        'success' => true,
        'message' => 'Comentario agregado correctamente.',
        'comentario' => $comentario
    ], 201);
}
    public function marcarTodasNotificacionesLeidas()
    {
        $usuario=Auth::user();
        if(!$usuario)return response()->json(['success'=>false,'message'=>'Sesión no válida.'],401);
        $login=trim((string)$usuario->login);
        $actualizadas=Notificacion::where('login',$login)->where('leida',false)->update(['leida'=>true]);
        return response()->json([
            'success'=>true,
            'message'=>'Todas las notificaciones fueron marcadas como leídas.',
            'actualizadas'=>$actualizadas
        ]);
    }
    public function resumen()
    {
        $usuario=Auth::user();
        if(!$usuario)return response()->json(['success'=>false,'message'=>'Sesión no válida.'],401);
        $login=trim((string)$usuario->login);
        $query=TicketU::where('login',$login);
        $total=(clone $query)->count();
        $pendientes=(clone $query)->where('estado','pendiente')->count();
        $enProceso=(clone $query)->where('estado','en proceso')->count();
        $solucionados=(clone $query)->where('estado','solucionado')->count();
        $cancelados=(clone $query)->where('estado','cancelado')->count();
        $noLeidas=Notificacion::where('login',$login)->where('leida',false)->count();
        return response()->json([
            'success'=>true,
            'usuario'=>[
                'login'=>$usuario->login,
                'name'=>$usuario->name,
                'email'=>$usuario->email
            ],
            'tickets'=>[
                'total'=>$total,
                'pendientes'=>$pendientes,
                'en_proceso'=>$enProceso,
                'solucionados'=>$solucionados,
                'cancelados'=>$cancelados
            ],
            'notificaciones'=>[
                'no_leidas'=>$noLeidas
            ]
        ]);
    }
}