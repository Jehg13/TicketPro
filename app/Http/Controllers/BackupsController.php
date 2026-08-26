<?php
namespace App\Http\Controllers;
use App\Models\Backups;
use App\Models\User;
use App\Models\Notificacion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
class BackupsController extends Controller
{
public function index()
{
$usuario=Auth::user();
$this->autorizar($usuario);
$configuracion=Backups::where('login',$usuario->login)->where('es_configuracion',true)->first();
if($configuracion&&$configuracion->activo&&!$configuracion->proxima_ejecucion){
$configuracion->update(['proxima_ejecucion'=>$this->calcularProximaEjecucion($configuracion)]);
$configuracion->refresh();
}
$backups=Backups::where('login',$usuario->login)->where('es_configuracion',false)->orderByDesc('created_at')->paginate(10)->withQueryString();
$backupsTodos=Backups::where('login',$usuario->login)->where('es_configuracion',false)->get();
$totalBackups=$backupsTodos->count();
$bytesTotales=0;
$disk=Storage::disk('local');
foreach($backupsTodos as $backup){
if(!empty($backup->archivo)&&$disk->exists($backup->archivo)){
$bytesTotales+=$disk->size($backup->archivo);
}
}
$espacioUsado=$this->formatearBytes($bytesTotales);
$ultimoBackupRegistro=Backups::where('login',$usuario->login)->where('es_configuracion',false)->where('estado','completado')->whereNotNull('fecha_finalizacion')->orderByDesc('fecha_finalizacion')->first();
$ultimoBackup=$ultimoBackupRegistro?Carbon::parse($ultimoBackupRegistro->fecha_finalizacion)->format('d/m/Y H:i'):'Nunca';
$proximoBackup='No programado';
if($configuracion&&$configuracion->activo&&$configuracion->proxima_ejecucion){
$proximoBackup=Carbon::parse($configuracion->proxima_ejecucion)->format('d/m/Y H:i');
}
return view('admin.backups',compact('configuracion','backups','espacioUsado','ultimoBackup','proximoBackup','totalBackups'));
}
public function guardarConfiguracion(Request $request)
{
$usuario=Auth::user();
$this->autorizar($usuario);
$esToggle=!$request->boolean('es_configuracion_guardar');
$configuracion=Backups::where('login',$usuario->login)->where('es_configuracion',true)->first();
if($esToggle){
$request->validate([
'pswd'=>['required','string'],
'activo'=>['required','boolean'],
]);
if(!$this->verificarPassword($request->input('pswd'),$usuario)){
return redirect()->route('backups')->with('error','La contraseña actual es incorrecta.');
}
if(!$configuracion){
$configuracion=Backups::create([
'login'=>$usuario->login,
'nombre'=>'Configuración de backups',
'archivo'=>null,
'tipo'=>'automatico',
'frecuencia'=>'diario',
'es_configuracion'=>true,
'activo'=>$request->boolean('activo'),
'hora'=>'02:00:00',
'dia_semana'=>null,
'dia_mes'=>null,
'estado'=>'pendiente',
'tamaño'=>null,
'fecha_inicio'=>null,
'fecha_finalizacion'=>null,
'ultima_ejecucion'=>null,
'proxima_ejecucion'=>null,
'mensaje'=>$request->boolean('activo')?'Backups automáticos activados.':'Backups automáticos desactivados.',
]);
if($request->boolean('activo')){
$configuracion->refresh();
$configuracion->update([
'proxima_ejecucion'=>$this->calcularProximaEjecucion($configuracion),
]);
}
}else{
if($request->boolean('activo')){
$configuracion->update([
'activo'=>true,
'estado'=>'pendiente',
'proxima_ejecucion'=>null,
'mensaje'=>'Backups automáticos activados.',
]);
$configuracion->refresh();
$configuracion->update([
'proxima_ejecucion'=>$this->calcularProximaEjecucion($configuracion),
]);
}else{
$configuracion->update([
'activo'=>false,
'proxima_ejecucion'=>null,
'estado'=>'pendiente',
'mensaje'=>'Backups automáticos desactivados.',
]);
}
}
return redirect()->route('backups')->with('success',$request->boolean('activo')?'Backups automáticos activados correctamente.':'Backups automáticos desactivados correctamente.');
}
$validated=$request->validate([
'pswd'=>['required','string'],
'activo'=>['required','boolean'],
'frecuencia'=>['required','in:diario,semanal,mensual'],
'hora'=>['required','date_format:H:i'],
'dia_semana'=>['nullable','integer','between:1,7'],
'dia_mes'=>['nullable','integer','between:1,31'],
]);
if(!$this->verificarPassword($validated['pswd'],$usuario)){
return redirect()->route('backups')->with('error','La contraseña actual es incorrecta.');
}
$activo=$request->boolean('activo');
$frecuencia=$validated['frecuencia'];
$hora=$validated['hora'].':00';
$diaSemana=$validated['dia_semana']??null;
$diaMes=$validated['dia_mes']??null;
if($frecuencia==='diario'){
$diaSemana=null;
$diaMes=null;
}elseif($frecuencia==='semanal'){
$diaSemana=!empty($diaSemana)?(int)$diaSemana:1;
$diaMes=null;
}elseif($frecuencia==='mensual'){
$diaSemana=null;
$diaMes=!empty($diaMes)?(int)$diaMes:1;
}
if(!$configuracion){
$configuracion=Backups::create([
'login'=>$usuario->login,
'nombre'=>'Configuración de backups',
'archivo'=>null,
'tipo'=>'automatico',
'frecuencia'=>$frecuencia,
'es_configuracion'=>true,
'activo'=>$activo,
'hora'=>$hora,
'dia_semana'=>$diaSemana,
'dia_mes'=>$diaMes,
'estado'=>'pendiente',
'tamaño'=>null,
'fecha_inicio'=>null,
'fecha_finalizacion'=>null,
'ultima_ejecucion'=>null,
'proxima_ejecucion'=>null,
'mensaje'=>$activo?'Backups automáticos activados.':'Backups automáticos desactivados.',
]);
}else{
$configuracion->update([
'frecuencia'=>$frecuencia,
'activo'=>$activo,
'hora'=>$hora,
'dia_semana'=>$diaSemana,
'dia_mes'=>$diaMes,
'estado'=>'pendiente',
'proxima_ejecucion'=>null,
'mensaje'=>$activo?'Backups automáticos activados.':'Backups automáticos desactivados.',
]);
}
$configuracion->refresh();
if($activo){
$configuracion->update([
'activo'=>true,
'proxima_ejecucion'=>$this->calcularProximaEjecucion($configuracion),
'estado'=>'pendiente',
'mensaje'=>'Backups automáticos activados.',
]);
}else{
$configuracion->update([
'activo'=>false,
'proxima_ejecucion'=>null,
'estado'=>'pendiente',
'mensaje'=>'Backups automáticos desactivados.',
]);
}
return redirect()->route('backups')->with('success','La configuración de backups se guardó correctamente.');
}
public function activar(Request $request)
{
$request->merge(['activo'=>true]);
return $this->guardarConfiguracion($request);
}
public function desactivar(Request $request)
{
$request->merge(['activo'=>false]);
return $this->guardarConfiguracion($request);
}
public function crearManual()
{
$usuario=Auth::user();
$this->autorizar($usuario);
return $this->generarBackup($usuario,'manual');
}
public function ejecutarAutomatico()
{
$configuraciones=Backups::where('es_configuracion',true)->where('activo',true)->whereNotNull('proxima_ejecucion')->where('proxima_ejecucion','<=',now())->get();
$ejecutados=0;
$errores=0;
foreach($configuraciones as $configuracion){
$usuario=User::where('login',$configuracion->login)->first();
if(!$usuario){
$configuracion->update([
'estado'=>'error',
'mensaje'=>'No se encontró el usuario asociado a la configuración.',
'fecha_finalizacion'=>now(),
]);
$errores++;
continue;
}
if(trim((string)$usuario->role)!=='Gerente Ti'||strtoupper(trim((string)$usuario->priv_admin))!=='Y'){
$configuracion->update([
'estado'=>'error',
'mensaje'=>'El usuario asociado no tiene permisos para ejecutar backups.',
'fecha_finalizacion'=>now(),
'proxima_ejecucion'=>null,
'activo'=>false,
]);
$errores++;
continue;
}
try{
$this->generarBackup($usuario,'automatico',$configuracion);
$ejecutados++;
}catch(\Throwable $e){
$configuracion->update([
'estado'=>'error',
'mensaje'=>$e->getMessage(),
'fecha_finalizacion'=>now(),
'proxima_ejecucion'=>$this->calcularProximaEjecucion($configuracion,now()),
]);
$this->notificarErrorBackup($usuario,$e);
$errores++;
}
}
return response()->json([
'success'=>true,
'ejecutados'=>$ejecutados,
'errores'=>$errores,
'message'=>'Proceso de backups automáticos ejecutado correctamente.',
]);
}
private function generarBackup($usuario,string $tipo='manual',?Backups $configuracion=null)
{
$this->autorizar($usuario);
$ahora=now();
$nombreArchivo='backup_'.$ahora->format('Y-m-d_H-i-s').'.sql';
$backup=Backups::create([
'login'=>$usuario->login,
'nombre'=>$nombreArchivo,
'archivo'=>null,
'tipo'=>$tipo,
'frecuencia'=>$tipo==='automatico'?$configuracion?->frecuencia:null,
'es_configuracion'=>false,
'activo'=>false,
'hora'=>null,
'dia_semana'=>null,
'dia_mes'=>null,
'estado'=>'pendiente',
'tamaño'=>null,
'fecha_inicio'=>$ahora,
'fecha_finalizacion'=>null,
'ultima_ejecucion'=>null,
'proxima_ejecucion'=>null,
'mensaje'=>'Generando backup...',
]);
$disk=Storage::disk('local');
$rutaRelativa='backups/'.$nombreArchivo;
try{
$disk->makeDirectory('backups');
$rutaCompleta=$disk->path($rutaRelativa);
$database=config('database.connections.mysql');
$username=$database['username']??'root';
$password=$database['password']??'';
$databaseName=$database['database']??null;
$host=$database['host']??'127.0.0.1';
$port=$database['port']??3306;
if(!$databaseName){
throw new \RuntimeException('No se encontró el nombre de la base de datos.');
}
$mysqldump=env('MYSQLDUMP_PATH','C:/Program Files/MySQL/MySQL Server 8.0/bin/mysqldump.exe');
if(!file_exists($mysqldump)){
throw new \RuntimeException('No se encontró mysqldump.exe en: '.$mysqldump);
}
$environment=getenv();
if(!is_array($environment)){
$environment=[];
}
$environment['MYSQL_PWD']=$password;
$process=new Process([
$mysqldump,
'--protocol=TCP',
'--host='.$host,
'--port='.$port,
'--user='.$username,
'--single-transaction',
'--routines',
'--triggers',
'--result-file='.$rutaCompleta,
$databaseName,
]);
$process->setEnv($environment);
$process->setTimeout(300);
$process->run();
$errorOutput=trim($process->getErrorOutput());
if(!$process->isSuccessful()){
if($disk->exists($rutaRelativa)){
$disk->delete($rutaRelativa);
}
throw new \RuntimeException($errorOutput?:'mysqldump no pudo generar el backup.');
}
if(!$disk->exists($rutaRelativa)||$disk->size($rutaRelativa)<=0){
throw new \RuntimeException('mysqldump terminó correctamente, pero el archivo generado está vacío o no existe.');
}
$bytes=$disk->size($rutaRelativa);
$tamano=$this->formatearBytes($bytes);
$fechaFinalizacion=now();
$backup->update([
'archivo'=>$rutaRelativa,
'estado'=>'completado',
'tamaño'=>$tamano,
'fecha_finalizacion'=>$fechaFinalizacion,
'ultima_ejecucion'=>$fechaFinalizacion,
'mensaje'=>$tipo==='automatico'?'Backup automático generado correctamente.':'Backup generado correctamente.',
]);
if($tipo==='automatico'&&$configuracion){
$configuracion->refresh();
if($configuracion->activo&&trim((string)$usuario->role)==='Gerente Ti'&&strtoupper(trim((string)$usuario->priv_admin))==='Y'){
$configuracion->update([
'estado'=>'pendiente',
'ultima_ejecucion'=>$fechaFinalizacion,
'proxima_ejecucion'=>$this->calcularProximaEjecucion($configuracion,$fechaFinalizacion),
'mensaje'=>'Backup automático generado correctamente.',
]);
}
}
if($tipo==='manual'){
return redirect()->route('backups')->with('success','Backup creado correctamente.');
}
return $backup;
}catch(\Throwable $e){
if($disk->exists($rutaRelativa)){
$disk->delete($rutaRelativa);
}
$fechaError=now();
$backup->update([
'estado'=>'error',
'fecha_finalizacion'=>$fechaError,
'mensaje'=>$e->getMessage(),
]);
if($tipo==='automatico'&&$configuracion){
$configuracion->refresh();
$configuracion->update([
'estado'=>'error',
'mensaje'=>$e->getMessage(),
'proxima_ejecucion'=>$this->calcularProximaEjecucion($configuracion,$fechaError),
]);
$this->notificarErrorBackup($usuario,$e);
}
if($tipo==='manual'){
return redirect()->route('backups')->with('error','No se pudo crear el backup: '.$e->getMessage());
}
throw $e;
}
}
public function descargar($id)
{
$usuario=Auth::user();
$this->autorizar($usuario);
$backup=Backups::where('login',$usuario->login)->where('es_configuracion',false)->findOrFail($id);
if(empty($backup->archivo)||!Storage::disk('local')->exists($backup->archivo)){
return redirect()->route('backups')->with('error','El archivo del backup no está disponible.');
}
return Storage::disk('local')->download($backup->archivo,$backup->nombre);
}
public function destroy($id)
{
$usuario=Auth::user();
$this->autorizar($usuario);
$backup=Backups::where('login',$usuario->login)->where('es_configuracion',false)->findOrFail($id);
if(!empty($backup->archivo)&&Storage::disk('local')->exists($backup->archivo)){
Storage::disk('local')->delete($backup->archivo);
}
$backup->delete();
return redirect()->route('backups')->with('success','Backup eliminado correctamente.');
}
private function calcularProximaEjecucion(Backups $configuracion,?Carbon $base=null)
{
if(!$configuracion->activo||!$configuracion->hora){
return null;
}
$ahora=$base?$base->copy():now();
$hora=substr((string)$configuracion->hora,0,5);
if(!preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/',$hora)){
return null;
}
if($configuracion->frecuencia==='diario'){
$fecha=$ahora->copy()->setTimeFromTimeString($hora);
if($fecha->lessThanOrEqualTo($ahora)){
$fecha->addDay();
}
return $fecha;
}
if($configuracion->frecuencia==='semanal'){
$diaSemana=(int)($configuracion->dia_semana?:1);
$diaSemana=max(1,min($diaSemana,7));
$fecha=$ahora->copy()->startOfDay();
$dias=($diaSemana-$fecha->dayOfWeekIso+7)%7;
$fecha->addDays($dias)->setTimeFromTimeString($hora);
if($fecha->lessThanOrEqualTo($ahora)){
$fecha->addDays(7);
}
return $fecha;
}
if($configuracion->frecuencia==='mensual'){
$diaMes=(int)($configuracion->dia_mes?:1);
$diaMes=max(1,min($diaMes,31));
$fecha=$ahora->copy()->startOfMonth();
$ultimoDia=$fecha->copy()->endOfMonth()->day;
$diaReal=min($diaMes,$ultimoDia);
$fecha->day($diaReal)->setTimeFromTimeString($hora);
if($fecha->lessThanOrEqualTo($ahora)){
$fecha=$ahora->copy()->addMonthNoOverflow()->startOfMonth();
$ultimoDia=$fecha->copy()->endOfMonth()->day;
$diaReal=min($diaMes,$ultimoDia);
$fecha->day($diaReal)->setTimeFromTimeString($hora);
}
return $fecha;
}
return null;
}
private function formatearBytes($bytes)
{
$bytes=(float)$bytes;
if($bytes<=0){
return '0 B';
}
$unidades=['B','KB','MB','GB','TB'];
$i=(int)floor(log($bytes,1024));
$i=min($i,count($unidades)-1);
return round($bytes/pow(1024,$i),2).' '.$unidades[$i];
}
private function verificarPassword(string $password,$usuario): bool
{
$passwordGuardada=(string)$usuario->pswd;
if($passwordGuardada===''){
return false;
}
try{
return Hash::check($password,$passwordGuardada);
}catch(\Throwable $e){
return false;
}
}
private function notificarErrorBackup($usuario,\Throwable $e): void
{
try{
Notificacion::create([
'login'=>$usuario->login,
'tipo'=>'backup',
'titulo'=>'Error en backup automático',
'mensaje'=>'No se pudo realizar el backup automático. Motivo: '.$e->getMessage(),
'url'=>route('backups'),
'leida'=>false,
'icono'=>'alert-triangle',
'color'=>'red',
]);
}catch(\Throwable $notificacionError){
}
}
private function autorizar($usuario)
{
abort_unless($usuario&&trim((string)$usuario->role)==='Gerente Ti'&&strtoupper(trim((string)$usuario->priv_admin))==='Y',403);
}
}