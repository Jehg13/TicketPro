<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>TicketPro - Backups</title>
<link rel="icon" type="image/png" href="{{ asset('storage/images/logo.png') }}">
@vite(['resources/css/app.css','resources/js/app.js'])
<script src="https://unpkg.com/lucide@latest"></script>
<script>
window.backupPage=function(){
return{
menuMovil:false,
frecuencia:@js($configuracion->frecuencia??'diario'),
automatico:@js((bool)($configuracion->activo??false)),
estadoPendiente:false,
mostrarModalPassword:false,
mostrarPassword:false,
passwordValido:false,
enviandoPassword:false,
tipoAccion:'',
accionForm:null,
tituloAccion:'',
mensajeAccion:'',
textoAccion:'',
colorAccion:'blue',
solicitarCambioBackup(){
const form=this.$refs.configuracionForm;
if(!form)return;
const activoInput=form.querySelector('input[name="activo"]');
if(!activoInput)return;
this.estadoPendiente=!this.automatico;
this.tipoAccion='toggle';
this.accionForm=form;
if(this.estadoPendiente){
this.tituloAccion='Activar backups automáticos';
this.mensajeAccion='activar los backups automáticos';
this.textoAccion='Activar backups';
this.colorAccion='emerald';
}else{
this.tituloAccion='Desactivar backups automáticos';
this.mensajeAccion='desactivar los backups automáticos';
this.textoAccion='Desactivar backups';
this.colorAccion='amber';
}
this.abrirModalPassword();
},
solicitarGuardarConfiguracion(){
const form=this.$refs.configuracionForm;
if(!form)return;
let indicador=form.querySelector('input[name="es_configuracion_guardar"]');
if(!indicador){
indicador=document.createElement('input');
indicador.type='hidden';
indicador.name='es_configuracion_guardar';
form.appendChild(indicador);
}
indicador.value='1';
this.tipoAccion='configuracion';
this.accionForm=form;
this.tituloAccion='Guardar configuración';
this.mensajeAccion='guardar la configuración de backups';
this.textoAccion='Guardar configuración';
this.colorAccion='blue';
this.abrirModalPassword();
},
solicitarDescarga(form){
if(!form)return;
this.tipoAccion='descarga';
this.accionForm=form;
this.tituloAccion='Descargar backup';
this.mensajeAccion='descargar este backup';
this.textoAccion='Descargar backup';
this.colorAccion='blue';
this.abrirModalPassword();
},
solicitarEliminacion(form){
if(!form)return;
this.tipoAccion='eliminacion';
this.accionForm=form;
this.tituloAccion='Eliminar backup';
this.mensajeAccion='eliminar este backup';
this.textoAccion='Eliminar backup';
this.colorAccion='red';
this.abrirModalPassword();
},
abrirModalPassword(){
this.mostrarModalPassword=true;
this.mostrarPassword=false;
this.passwordValido=false;
this.enviandoPassword=false;
this.$nextTick(()=>{
const input=this.$refs.passwordInput;
if(input){
input.value='';
input.focus();
}
});
},
validarPassword(){
const input=this.$refs.passwordInput;
this.passwordValido=!!(input&&input.value.trim().length>0);
},
cerrarModalPassword(){
if(this.enviandoPassword)return;
this.mostrarModalPassword=false;
this.mostrarPassword=false;
this.passwordValido=false;
this.tipoAccion='';
this.accionForm=null;
this.estadoPendiente=false;
this.enviandoPassword=false;
const input=this.$refs.passwordInput;
if(input)input.value='';
},
resetearModal(){
this.mostrarModalPassword=false;
this.mostrarPassword=false;
this.passwordValido=false;
this.enviandoPassword=false;
this.tipoAccion='';
this.accionForm=null;
this.estadoPendiente=false;
const input=this.$refs.passwordInput;
if(input)input.value='';
},
async confirmarDescarga(form,password){
if(!form||!password)return;
this.enviandoPassword=true;
try{
const url=new URL(form.action,window.location.origin);
url.searchParams.set('pswd',password);
const response=await fetch(url.toString(),{
method:'GET',
headers:{
'Accept':'application/octet-stream,application/zip,application/sql,application/x-sql,text/csv,*/*',
'X-Requested-With':'XMLHttpRequest'
},
credentials:'same-origin'
});
if(!response.ok){
let mensaje='No se pudo descargar el backup.';
try{
const data=await response.json();
if(data.message)mensaje=data.message;
}catch(e){}
this.resetearModal();
alert(mensaje);
return;
}
const contentType=(response.headers.get('content-type')||'').toLowerCase();
if(contentType.includes('text/html')||contentType.includes('application/json')){
let mensaje='La contraseña no es válida o no se pudo descargar el backup.';
try{
const data=await response.clone().json();
if(data.message)mensaje=data.message;
}catch(e){}
this.resetearModal();
alert(mensaje);
return;
}
const blob=await response.blob();
const disposition=response.headers.get('content-disposition')||'';
let filename='backup.sql';
const match=disposition.match(/filename\*=UTF-8''([^;]+)|filename="?([^"]+)"?/i);
if(match)filename=decodeURIComponent(match[1]||match[2]);
const blobUrl=window.URL.createObjectURL(blob);
const link=document.createElement('a');
link.href=blobUrl;
link.download=filename;
link.style.display='none';
document.body.appendChild(link);
link.click();
link.remove();
setTimeout(()=>{
window.URL.revokeObjectURL(blobUrl);
this.resetearModal();
},300);
}catch(error){
console.error(error);
this.resetearModal();
alert('Ocurrió un error al descargar el backup.');
}
},
confirmarAccion(){
if(this.enviandoPassword)return;
const input=this.$refs.passwordInput;
if(!input)return;
const password=input.value.trim();
if(!password){
this.passwordValido=false;
input.focus();
return;
}
const form=this.accionForm;
if(!form)return;
let pswdInput=form.querySelector('input[name="pswd"]');
if(!pswdInput){
pswdInput=document.createElement('input');
pswdInput.type='hidden';
pswdInput.name='pswd';
form.appendChild(pswdInput);
}
pswdInput.disabled=false;
pswdInput.value=password;
if(this.tipoAccion==='descarga'){
this.confirmarDescarga(form,password);
return;
}
if(this.tipoAccion==='toggle'){
const indicador=form.querySelector('input[name="es_configuracion_guardar"]');
if(indicador)indicador.remove();
let activoInput=form.querySelector('input[name="activo"]');
if(!activoInput){
activoInput=document.createElement('input');
activoInput.type='hidden';
activoInput.name='activo';
form.appendChild(activoInput);
}
activoInput.disabled=false;
activoInput.value=this.estadoPendiente?'1':'0';
this.automatico=this.estadoPendiente;
this.habilitarControles(form);
this.enviandoPassword=true;
HTMLFormElement.prototype.submit.call(form);
return;
}
if(this.tipoAccion==='configuracion'){
const activoInput=form.querySelector('input[name="activo"]');
if(!activoInput)return;
activoInput.disabled=false;
activoInput.value=this.automatico?'1':'0';
this.habilitarControles(form);
this.enviandoPassword=true;
HTMLFormElement.prototype.submit.call(form);
return;
}
if(this.tipoAccion==='eliminacion'){
this.enviandoPassword=true;
this.habilitarControles(form);
HTMLFormElement.prototype.submit.call(form);
return;
}
},
habilitarControles(form){
form.querySelectorAll('input,select,textarea,button').forEach(control=>{
control.disabled=false;
});
}
};
};
</script>
</head>
<body x-data="backupPage()" class="min-h-screen bg-[#070b19] text-white font-sans antialiased" @keydown.escape.window="cerrarModalPassword()">
<aside :class="menuMovil?'translate-x-0':'-translate-x-full md:translate-x-0'" class="fixed inset-y-0 left-0 z-[99999] w-[280px] max-w-[85vw] border-r border-slate-800/60 bg-[#0a0f24] p-5 sm:p-6 flex flex-col justify-between transition-transform duration-300">
<div>
<div class="flex items-center justify-between mb-8 sm:mb-10">
<span class="text-2xl sm:text-3xl font-extrabold tracking-wide text-white">Ticket<span class="text-blue-500">Pro</span></span>
<button type="button" @click="menuMovil=false" class="flex md:hidden h-9 w-9 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition">
<i data-lucide="x" class="w-5 h-5"></i>
</button>
</div>
<div class="flex items-center gap-3 mb-8 p-2 rounded-xl bg-slate-900/40 border border-slate-800/50">
<img src="{{ auth()->user()->picture ? asset('storage/'.auth()->user()->picture) : asset('storage/profile-photos/user.png') }}" alt="{{ auth()->user()->name }}" class="h-11 w-11 sm:h-12 sm:w-12 shrink-0 rounded-full border-2 border-gray-500 object-cover">
<div class="overflow-hidden min-w-0">
<h4 class="text-sm font-semibold text-slate-200 truncate">{{ auth()->user()->name??'Desconocido' }}</h4>
<p class="text-xs text-slate-400 truncate">{{ auth()->user()->role??'Desconocido' }}</p>
</div>
</div>
<nav class="space-y-2">
<a href="{{ route('tecnologias') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">
<i data-lucide="layout-dashboard" class="w-5 h-5 shrink-0"></i>
<span class="font-medium text-sm">Inicio</span>
</a>
<a href="{{ route('tickettecnologias') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">
<i data-lucide="ticket-check" class="w-5 h-5 shrink-0"></i>
<span class="font-medium text-sm">Tickets</span>
</a>
@if(auth()->check()&&auth()->user()->role==='Gerente Ti'&&auth()->user()->priv_admin==='Y')
<a href="{{ route('cambiostecnologias') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">
<i data-lucide="git-compare-arrows" class="w-5 h-5 shrink-0"></i>
<span class="text-sm">Cambios</span>
</a>
<a href="{{ route('usuarios.tecnologias') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">
<i data-lucide="users" class="w-5 h-5 shrink-0"></i>
<span class="text-sm">Usuarios</span>
</a>
@endif
<a href="{{ route('dispositivos') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">
<i data-lucide="monitor-smartphone" class="w-5 h-5 shrink-0"></i>
<span class="font-medium text-sm">Dispositivos</span>
</a>
<a href="{{ route('avisostecnologias') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">
<i data-lucide="megaphone" class="w-5 h-5 shrink-0"></i>
<span class="font-medium text-sm">Avisos</span>
</a>
<a href="{{ route('perfiltecnologias') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">
<i data-lucide="circle-user-round" class="w-5 h-5 shrink-0"></i>
<span class="font-medium text-sm">Mi perfil</span>
</a>
</nav>
</div>
<form method="POST" action="{{ route('logout') }}">
@csrf
<button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-red-500/10 hover:text-red-400 transition">
<i data-lucide="log-out" class="w-5 h-5"></i>
<span class="font-medium text-sm">Cerrar sesión</span>
</button>
</form>
</aside>
<div x-show="menuMovil" x-cloak x-transition.opacity @click="menuMovil=false" class="fixed inset-0 z-[99998] bg-black/70 backdrop-blur-sm md:hidden"></div>
<main class="md:ml-[280px] min-h-screen px-4 py-5 sm:px-6 sm:py-6 lg:px-8 lg:py-8 pt-20 md:pt-8">
<div class="max-w-[1500px] mx-auto">
<button type="button" @click="menuMovil=true" class="md:hidden fixed top-4 left-4 z-[99997] flex h-10 w-10 items-center justify-center rounded-xl bg-[#0f1535] border border-slate-800 text-slate-300 hover:bg-slate-800 hover:text-white transition">
<i data-lucide="menu" class="w-5 h-5"></i>
</button>
<header class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between mb-7 sm:mb-8">
<div>
<div class="flex items-center gap-3">
<div class="w-11 h-11 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center">
<i data-lucide="database-backup" class="w-6 h-6 text-emerald-400"></i>
</div>
<div>
<h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">Backups</h1>
<p class="text-xs sm:text-sm text-slate-400 mt-1">Administra y programa las copias de seguridad del sistema.</p>
</div>
</div>
</div>
<div class="flex items-center gap-3">
<div class="flex items-center gap-2 px-3 py-2 rounded-xl bg-emerald-500/10 border border-emerald-500/20">
<span class="w-2 h-2 rounded-full" :class="automatico?'bg-emerald-400':'bg-slate-500'"></span>
<span class="text-xs font-medium" :class="automatico?'text-emerald-300':'text-slate-400'" x-text="automatico?'Sistema protegido':'Backups automáticos desactivados'"></span>
</div>
</div>
</header>
<section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
<div class="rounded-2xl border border-slate-800/80 bg-[#0f1535] p-5">
<div class="flex items-center justify-between">
<div class="w-10 h-10 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center">
<i data-lucide="database" class="w-5 h-5 text-blue-400"></i>
</div>
<span class="text-[10px] text-slate-500">ALMACENAMIENTO</span>
</div>
<p class="mt-4 text-2xl font-bold text-white">{{ $espacioUsado??'0 GB' }}</p>
<p class="text-xs text-slate-500 mt-1">Espacio utilizado por backups</p>
</div>
<div class="rounded-2xl border border-slate-800/80 bg-[#0f1535] p-5">
<div class="flex items-center justify-between">
<div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center">
<i data-lucide="shield-check" class="w-5 h-5 text-emerald-400"></i>
</div>
<span class="text-[10px] text-slate-500">ÚLTIMO BACKUP</span>
</div>
<p class="mt-4 text-lg font-bold text-white">{{ $ultimoBackup??'Nunca' }}</p>
<p class="text-xs text-slate-500 mt-1">Última copia completada</p>
</div>
<div class="rounded-2xl border border-slate-800/80 bg-[#0f1535] p-5">
<div class="flex items-center justify-between">
<div class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center">
<i data-lucide="calendar-clock" class="w-5 h-5 text-indigo-400"></i>
</div>
<span class="text-[10px] text-slate-500">PRÓXIMO BACKUP</span>
</div>
<p class="mt-4 text-lg font-bold text-white">{{ $proximoBackup??'No programado' }}</p>
<p class="text-xs text-slate-500 mt-1">Próxima ejecución automática</p>
</div>
<div class="rounded-2xl border border-slate-800/80 bg-[#0f1535] p-5">
<div class="flex items-center justify-between">
<div class="w-10 h-10 rounded-xl bg-violet-500/10 border border-violet-500/20 flex items-center justify-center">
<i data-lucide="archive" class="w-5 h-5 text-violet-400"></i>
</div>
<span class="text-[10px] text-slate-500">COPIAS</span>
</div>
<p class="mt-4 text-2xl font-bold text-white">{{ $totalBackups??0 }}</p>
<p class="text-xs text-slate-500 mt-1">Backups almacenados</p>
</div>
</section>
<div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
<section class="xl:col-span-5">
<div class="rounded-2xl border border-slate-800/80 bg-[#0f1535] overflow-hidden">
<div class="px-5 sm:px-6 py-5 border-b border-slate-800/80">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center">
<i data-lucide="settings-2" class="w-5 h-5 text-blue-400"></i>
</div>
<div>
<h2 class="text-sm font-semibold text-white">Configuración automática</h2>
<p class="text-[11px] text-slate-500 mt-1">Define cuándo se ejecutarán los backups.</p>
</div>
</div>
</div>
<form x-ref="configuracionForm" method="POST" action="{{ route('backups.configurar') }}" class="p-5 sm:p-6 space-y-5">
@csrf
<input type="hidden" name="pswd" value="">
<input type="hidden" name="activo" value="{{ $configuracion?->activo?'1':'0' }}">
<div class="flex items-center justify-between gap-4 rounded-xl border border-slate-800 bg-[#0b1026] p-4">
<div class="flex items-center gap-3">
<div class="w-9 h-9 rounded-lg bg-emerald-500/10 flex items-center justify-center">
<i data-lucide="power" class="w-4 h-4" :class="automatico?'text-emerald-400':'text-slate-500'"></i>
</div>
<div>
<p class="text-sm font-medium text-white">Backups automáticos</p>
<p class="text-[10px]" :class="automatico?'text-emerald-400':'text-slate-500'" x-text="automatico?'Actualmente activados':'Actualmente desactivados'"></p>
</div>
</div>
<button type="button" @click="solicitarCambioBackup()" :class="automatico?'bg-blue-600':'bg-slate-700'" class="relative w-11 h-6 rounded-full transition">
<span :class="automatico?'translate-x-5':'translate-x-1'" class="absolute top-1 left-0 w-4 h-4 rounded-full bg-white transition-transform"></span>
</button>
</div>
<div>
<label class="block text-xs font-semibold text-slate-300 mb-2">Frecuencia</label>
<select name="frecuencia" x-model="frecuencia" :disabled="!automatico" class="w-full bg-[#060818] border border-[#1e295d] rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-blue-500 disabled:opacity-40 transition">
<option value="diario">Diario</option>
<option value="semanal">Semanal</option>
<option value="mensual">Mensual</option>
</select>
</div>
<div x-show="frecuencia==='semanal'" x-cloak>
<label class="block text-xs font-semibold text-slate-300 mb-2">Día de la semana</label>
<select name="dia_semana" :disabled="!automatico" class="w-full bg-[#060818] border border-[#1e295d] rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-blue-500 disabled:opacity-40 transition">
<option value="1" {{ ($configuracion->dia_semana??1)==1?'selected':'' }}>Lunes</option>
<option value="2" {{ ($configuracion->dia_semana??1)==2?'selected':'' }}>Martes</option>
<option value="3" {{ ($configuracion->dia_semana??1)==3?'selected':'' }}>Miércoles</option>
<option value="4" {{ ($configuracion->dia_semana??1)==4?'selected':'' }}>Jueves</option>
<option value="5" {{ ($configuracion->dia_semana??1)==5?'selected':'' }}>Viernes</option>
<option value="6" {{ ($configuracion->dia_semana??1)==6?'selected':'' }}>Sábado</option>
<option value="7" {{ ($configuracion->dia_semana??1)==7?'selected':'' }}>Domingo</option>
</select>
</div>
<div x-show="frecuencia==='mensual'" x-cloak>
<label class="block text-xs font-semibold text-slate-300 mb-2">Día del mes</label>
<select name="dia_mes" :disabled="!automatico" class="w-full bg-[#060818] border border-[#1e295d] rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-blue-500 disabled:opacity-40 transition">
@for($i=1;$i<=31;$i++)
<option value="{{ $i }}" {{ ($configuracion->dia_mes??1)==$i?'selected':'' }}>Día {{ $i }}</option>
@endfor
</select>
</div>
<div>
<label class="block text-xs font-semibold text-slate-300 mb-2">Hora de ejecución</label>
<div class="relative">
<i data-lucide="clock" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500"></i>
<input type="time" name="hora" value="{{ $configuracion->hora??'02:00' }}" :disabled="!automatico" class="w-full bg-[#060818] border border-[#1e295d] rounded-xl pl-11 pr-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-blue-500 disabled:opacity-40 transition">
</div>
<p class="text-[10px] text-slate-500 mt-2">La hora corresponde al horario del servidor.</p>
</div>
<div class="rounded-xl border border-blue-500/10 bg-blue-500/[0.04] p-4">
<div class="flex gap-3">
<i data-lucide="info" class="w-4 h-4 text-blue-400 shrink-0 mt-0.5"></i>
<p class="text-[11px] leading-relaxed text-slate-400">Los backups automáticos almacenarán una copia de la información del sistema según la frecuencia configurada.</p>
</div>
</div>
<button type="button" @click="solicitarGuardarConfiguracion()" class="w-full flex items-center justify-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-500 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/20 transition">
<i data-lucide="save" class="w-4 h-4"></i>
Guardar configuración
</button>
</form>
</div>
</section>
<section class="xl:col-span-7 space-y-6">
<div class="rounded-2xl border border-emerald-500/20 bg-gradient-to-br from-[#10183b] to-[#0d132e] p-5 sm:p-6">
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
<div class="flex items-center gap-4">
<div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center">
<i data-lucide="database-zap" class="w-6 h-6 text-emerald-400"></i>
</div>
<div>
<h2 class="text-base font-semibold text-white">Backup manual</h2>
<p class="text-xs text-slate-400 mt-1">Crea una copia de seguridad inmediatamente.</p>
</div>
</div>
<form method="POST" action="{{ route('backups.crear') }}" class="w-full sm:w-auto">
@csrf
<button type="submit" class="w-full sm:w-auto flex items-center justify-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-600/20 transition">
<i data-lucide="play" class="w-4 h-4"></i>
Crear backup ahora
</button>
</form>
</div>
</div>
<div class="rounded-2xl border border-slate-800/80 bg-[#0f1535] overflow-hidden">
<div class="px-5 sm:px-6 py-5 border-b border-slate-800/80">
<div class="flex items-center justify-between gap-4">
<div>
<h2 class="text-sm font-semibold text-white">Historial de backups</h2>
<p class="text-[11px] text-slate-500 mt-1">Copias de seguridad generadas por el sistema.</p>
</div>
<div class="hidden sm:flex items-center gap-2 text-[10px] text-slate-500">
<i data-lucide="history" class="w-4 h-4"></i>
Historial
</div>
</div>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left text-xs">
<thead>
<tr class="text-slate-500 border-b border-slate-800/80">
<th class="px-5 sm:px-6 py-4 font-semibold">Backup</th>
<th class="px-5 py-4 font-semibold">Tipo</th>
<th class="px-5 py-4 font-semibold">Tamaño</th>
<th class="px-5 py-4 font-semibold">Estado</th>
<th class="px-5 py-4 font-semibold">Fecha</th>
<th class="px-5 sm:px-6 py-4 font-semibold text-right">Acción</th>
</tr>
</thead>
<tbody class="divide-y divide-slate-800/60">
@forelse($backups??[] as $backup)
<tr class="hover:bg-slate-800/20 transition">
<td class="px-5 sm:px-6 py-4">
<div class="flex items-center gap-3">
<div class="w-9 h-9 rounded-lg bg-blue-500/10 border border-blue-500/20 flex items-center justify-center shrink-0">
<i data-lucide="file-archive" class="w-4 h-4 text-blue-400"></i>
</div>
<div class="min-w-0">
<p class="font-medium text-slate-200 truncate max-w-[180px]">{{ $backup->nombre??'backup.sql' }}</p>
<p class="text-[10px] text-slate-500 mt-0.5">#{{ $backup->id??'—' }}</p>
</div>
</div>
</td>
<td class="px-5 py-4">
@if(($backup->tipo??'')==='manual')
<span class="inline-flex items-center gap-1.5 rounded-lg bg-blue-500/10 border border-blue-500/20 px-2.5 py-1 text-[10px] font-medium text-blue-300"><span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span>Manual</span>
@else
<span class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1 text-[10px] font-medium text-emerald-300"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>Automático</span>
@endif
</td>
<td class="px-5 py-4 text-slate-400 whitespace-nowrap">{{ $backup->tamaño??'—' }}</td>
<td class="px-5 py-4">
@if(($backup->estado??'')==='completado')
<span class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1 text-[10px] font-medium text-emerald-300"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>Completado</span>
@elseif(($backup->estado??'')==='error')
<span class="inline-flex items-center gap-1.5 rounded-lg bg-red-500/10 border border-red-500/20 px-2.5 py-1 text-[10px] font-medium text-red-300"><span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>Error</span>
@else
<span class="inline-flex items-center gap-1.5 rounded-lg bg-amber-500/10 border border-amber-500/20 px-2.5 py-1 text-[10px] font-medium text-amber-300"><span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>{{ ucfirst($backup->estado??'Pendiente') }}</span>
@endif
</td>
<td class="px-5 py-4 text-slate-400 whitespace-nowrap">{{ $backup->created_at??'—' }}</td>
<td class="px-5 sm:px-6 py-4">
<div class="flex items-center justify-end gap-2">
@if(!empty($backup->id)&&($backup->estado??'')==='completado')
<form method="GET" action="{{ route('backups.descargar',['id'=>$backup->id]) }}">
<input type="hidden" name="pswd" value="">
<button type="button" @click="solicitarDescarga($el.closest('form'))" title="Descargar backup" class="group w-9 h-9 rounded-lg bg-blue-600/10 border border-blue-500/20 hover:bg-blue-600 hover:border-blue-500 text-blue-400 hover:text-white flex items-center justify-center transition">
<i data-lucide="download" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
</button>
</form>
@endif
@if(!empty($backup->id))
<form method="POST" action="{{ route('backups.eliminar',$backup->id) }}">
@csrf
@method('DELETE')
<input type="hidden" name="pswd" value="">
<button type="button" @click="solicitarEliminacion($el.closest('form'))" title="Eliminar backup" class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-red-500/10 text-slate-400 hover:text-red-400 flex items-center justify-center transition">
<i data-lucide="trash-2" class="w-4 h-4"></i>
</button>
</form>
@endif
</div>
</td>
</tr>
@empty
<tr>
<td colspan="6" class="px-6 py-12">
<div class="text-center">
<div class="mx-auto mb-4 w-14 h-14 rounded-2xl bg-slate-800/50 border border-slate-800 flex items-center justify-center">
<i data-lucide="database-backup" class="w-6 h-6 text-slate-500"></i>
</div>
<p class="text-sm font-medium text-slate-400">No hay backups registrados</p>
<p class="text-xs text-slate-600 mt-1">Los backups generados aparecerán aquí.</p>
</div>
</td>
</tr>
@endforelse
</tbody>
</table>
</div>
@if(isset($backups)&&method_exists($backups,'links'))
<div class="mt-6 px-5 pb-5 pt-5 border-t border-slate-800/80 flex flex-col sm:flex-row justify-between items-center gap-4">
    <span class="text-xs text-slate-400">
        Mostrando <span class="text-white font-medium">{{ $backups->firstItem() ?? 0 }}</span>
        a <span class="text-white font-medium">{{ $backups->lastItem() ?? 0 }}</span>
        de <span class="text-white font-medium">{{ $backups->total() }}</span> backups
    </span>
    <div class="flex items-center gap-1">
        @if($backups->onFirstPage())
            <button type="button" disabled class="w-8 h-8 bg-slate-900 text-slate-600 rounded-lg flex items-center justify-center">
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
            </button>
        @else
            <a href="{{ $backups->previousPageUrl() }}" class="w-8 h-8 bg-slate-800 text-slate-400 rounded-lg hover:bg-slate-700 flex items-center justify-center">
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
            </a>
        @endif
        @foreach($backups->getUrlRange(max(1,$backups->currentPage()-2),min($backups->lastPage(),$backups->currentPage()+2)) as $page=>$url)
            <a href="{{ $url }}" class="w-8 h-8 rounded-lg text-xs flex items-center justify-center {{ $page==$backups->currentPage()?'bg-blue-600 text-white font-bold':'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                {{ $page }}
            </a>
        @endforeach
        @if($backups->hasMorePages())
            <a href="{{ $backups->nextPageUrl() }}" class="w-8 h-8 bg-slate-800 text-slate-400 rounded-lg hover:bg-slate-700 flex items-center justify-center">
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </a>
        @else
            <button type="button" disabled class="w-8 h-8 bg-slate-900 text-slate-600 rounded-lg flex items-center justify-center">
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </button>
        @endif
    </div>
</div>
@endif
</div>
</section>
</div>
<section class="mt-6 rounded-2xl border border-amber-500/10 bg-amber-500/[0.025] p-5 sm:p-6">
<div class="flex gap-4">
<div class="w-10 h-10 shrink-0 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center">
<i data-lucide="shield-alert" class="w-5 h-5 text-amber-400"></i>
</div>
<div>
<h3 class="text-sm font-semibold text-slate-200">Seguridad de los backups</h3>
<p class="text-xs leading-relaxed text-slate-500 mt-1 max-w-4xl">Las copias de seguridad contienen información importante del sistema. Se recomienda conservarlas en una ubicación protegida y limitar el acceso únicamente al personal autorizado.</p>
</div>
</div>
</section>
</div>
</main>
<div x-show="mostrarModalPassword" x-cloak x-transition.opacity class="fixed inset-0 z-[100000] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4" @click.self="cerrarModalPassword()">
<div x-show="mostrarModalPassword" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="w-full max-w-md rounded-2xl border border-slate-800 bg-[#0f1535] shadow-2xl overflow-hidden">
<div class="px-5 sm:px-6 py-5 border-b border-slate-800/80">
<div class="flex items-start justify-between gap-4">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-xl flex items-center justify-center border" :class="{'bg-emerald-500/10 border-emerald-500/20':colorAccion==='emerald','bg-amber-500/10 border-amber-500/20':colorAccion==='amber','bg-blue-500/10 border-blue-500/20':colorAccion==='blue','bg-red-500/10 border-red-500/20':colorAccion==='red'}">
<i data-lucide="shield-check" class="w-5 h-5" :class="{'text-emerald-400':colorAccion==='emerald','text-amber-400':colorAccion==='amber','text-blue-400':colorAccion==='blue','text-red-400':colorAccion==='red'}"></i>
</div>
<div>
<h2 class="text-base font-semibold text-white" x-text="tituloAccion"></h2>
<p class="text-[11px] mt-1" :class="{'text-emerald-400':colorAccion==='emerald','text-amber-400':colorAccion==='amber','text-blue-400':colorAccion==='blue','text-red-400':colorAccion==='red'}">Acción protegida</p>
</div>
</div>
<button type="button" @click="cerrarModalPassword()" :disabled="enviandoPassword" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-500 hover:bg-slate-800 hover:text-white transition">
<i data-lucide="x" class="w-4 h-4"></i>
</button>
</div>
</div>
<div class="p-5 sm:p-6">
<div class="rounded-xl border p-4 mb-5" :class="{'border-emerald-500/10 bg-emerald-500/[0.04]':colorAccion==='emerald','border-amber-500/10 bg-amber-500/[0.04]':colorAccion==='amber','border-blue-500/10 bg-blue-500/[0.04]':colorAccion==='blue','border-red-500/10 bg-red-500/[0.04]':colorAccion==='red'}">
<div class="flex gap-3">
<i data-lucide="lock" class="w-4 h-4 shrink-0 mt-0.5" :class="{'text-emerald-400':colorAccion==='emerald','text-amber-400':colorAccion==='amber','text-blue-400':colorAccion==='blue','text-red-400':colorAccion==='red'}"></i>
<p class="text-xs leading-relaxed text-slate-400">Por seguridad, introduce tu contraseña actual para <span class="font-semibold" :class="{'text-emerald-400':colorAccion==='emerald','text-amber-400':colorAccion==='amber','text-blue-400':colorAccion==='blue','text-red-400':colorAccion==='red'}" x-text="mensajeAccion"></span>.</p>
</div>
</div>
<div>
<label class="block text-xs font-semibold text-slate-300 mb-2">Contraseña actual</label>
<div class="relative">
<i data-lucide="key-round" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500"></i>
<input x-ref="passwordInput" :type="mostrarPassword?'text':'password'" autocomplete="current-password" placeholder="Introduce tu contraseña" @input="validarPassword()" @keydown.enter.prevent="confirmarAccion()" class="w-full bg-[#060818] border border-[#1e295d] rounded-xl pl-11 pr-12 py-3 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-blue-500 transition">
<button type="button" @click="mostrarPassword=!mostrarPassword" class="absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg text-slate-500 hover:text-white hover:bg-slate-800 transition">
<i data-lucide="eye" x-show="!mostrarPassword" class="w-4 h-4"></i>
<i data-lucide="eye-off" x-show="mostrarPassword" class="w-4 h-4"></i>
</button>
</div>
</div>
<div class="flex flex-col-reverse sm:flex-row gap-3 mt-6">
<button type="button" @click="cerrarModalPassword()" :disabled="enviandoPassword" class="flex-1 rounded-xl border border-slate-700 bg-slate-800/50 hover:bg-slate-800 px-4 py-3 text-sm font-semibold text-slate-300 hover:text-white transition">Cancelar</button>
<button type="button" @click="confirmarAccion()" :disabled="!passwordValido||enviandoPassword" class="flex-1 flex items-center justify-center gap-2 rounded-xl px-4 py-3 text-sm font-semibold text-white transition disabled:opacity-40 disabled:cursor-not-allowed" :class="{'bg-emerald-600 hover:bg-emerald-500':colorAccion==='emerald','bg-amber-600 hover:bg-amber-500':colorAccion==='amber','bg-blue-600 hover:bg-blue-500':colorAccion==='blue','bg-red-600 hover:bg-red-500':colorAccion==='red'}">
<i data-lucide="loader-2" x-show="enviandoPassword" class="w-4 h-4 animate-spin"></i>
<i data-lucide="shield-check" x-show="!enviandoPassword" class="w-4 h-4"></i>
<span x-text="enviandoPassword?'Verificando...':textoAccion"></span>
</button>
</div>
</div>
</div>
</div>
@if(session('success'))
<div id="successMessage" class="fixed left-4 right-4 sm:left-auto sm:right-5 top-5 z-[9999] w-auto sm:w-full sm:max-w-sm rounded-2xl border border-green-500/30 bg-[#0f1535] p-4 shadow-[0_0_30px_rgba(34,197,94,0.20)]">
<div class="flex items-start gap-3">
<div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-green-500/15 text-green-400">
<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
</svg>
</div>
<div class="flex-1 min-w-0">
<p class="font-bold text-white">¡Éxito!</p>
<p class="mt-1 text-sm text-slate-400 break-words">{{ session('success') }}</p>
</div>
<button type="button" onclick="document.getElementById('successMessage')?.remove()" class="text-slate-500 hover:text-white shrink-0">✕</button>
</div>
</div>
@endif
@if(session('error'))
<div id="errorMessage" class="fixed left-4 right-4 sm:left-auto sm:right-5 top-5 z-[9999] w-auto sm:w-full sm:max-w-sm rounded-2xl border border-red-500/30 bg-[#0f1535] p-4 shadow-[0_0_30px_rgba(239,68,68,0.20)]">
<div class="flex items-start gap-3">
<div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-500/15 text-red-400">
<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
</svg>
</div>
<div class="flex-1 min-w-0">
<p class="font-bold text-white">¡Error!</p>
<p class="mt-1 text-sm text-slate-400 break-words">{{ session('error') }}</p>
</div>
<button type="button" onclick="document.getElementById('errorMessage')?.remove()" class="text-slate-500 hover:text-white transition shrink-0">✕</button>
</div>
</div>
@endif
<script>
document.addEventListener('DOMContentLoaded',function(){
if(typeof lucide!=='undefined')lucide.createIcons();
});
document.addEventListener('alpine:initialized',function(){
if(typeof lucide!=='undefined')lucide.createIcons();
});
document.addEventListener('alpine:updated',function(){
if(typeof lucide!=='undefined')lucide.createIcons();
});
</script>
</body>
</html>