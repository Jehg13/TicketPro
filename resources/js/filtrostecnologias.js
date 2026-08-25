document.addEventListener('alpine:init',()=>{Alpine.data('ticketModal',()=>({
openModal:false,
selectedTicket:{},
usuarioActualId:null,
usuarioActualLogin:window.usuarioActualLogin||'',
comentarios:[],
evidencias:[],
evidenciasSolucion:[],
archivoAdjunto:null,
archivoSolucion:null,
rutaComentario:'',
tomandoTicket:false,
enviandoComentario:false,
notificacionVisible:false,
notificacionTipo:'',
notificacionMensaje:'',
notificacionTimeout:null,
filtro:new URLSearchParams(window.location.search).get('filtro')||'todos',
busqueda:new URLSearchParams(window.location.search).get('buscar')||'',
ticketsActualizados:{},
_comentariosAbortController:null,
_comentariosRequestId:0,
_comentariosTicketId:null,
_ultimoComentarioEnviadoId:null,
openModalSolucion:false,
ticketSolucion:{},
guardandoSolucion:false,
modalSolucionSoloLectura:false,
firmaExistente:'',
_firmaAbortController:null,
_firmaCanvas:null,
_firmaContext:null,
_previewSolucionUrl:null,
solucionForm:{
solucion:'',
solucionado:null,
fecha_solucion:'',
nombre_firmante:'',
fecha_firma:''
},
init(){
if(window.usuarioActualId!==undefined&&window.usuarioActualId!==null){this.usuarioActualId=Number(window.usuarioActualId);}
this.usuarioActualLogin=String(window.usuarioActualLogin||'').trim();
this.filtro=String(this.filtro||'todos').trim().toLowerCase();
this.busqueda=String(this.busqueda||'').trim();
this.$nextTick(()=>this.actualizarIconos());
},
abrirTicket(ticket,comentariosIniciales=[]){
if(!ticket){return;}
this.cancelarCargaComentarios();
this.selectedTicket=this.obtenerDatosTicket(ticket);
this.comentarios=Array.isArray(comentariosIniciales)?this.ordenarComentarios(this.eliminarComentariosDuplicados(comentariosIniciales.map(comentario=>this.normalizarComentario(comentario)).filter(Boolean))):[];
this._comentariosTicketId=Number(this.selectedTicket.id);
this._ultimoComentarioEnviadoId=null;
this.evidencias=this.obtenerEvidencias(this.selectedTicket);
this.rutaComentario=this.generarRutaComentario(this.selectedTicket.id);
this.archivoAdjunto=null;
this.enviandoComentario=false;
this.openModal=true;
this.cargarComentarios(this.selectedTicket.id);
this.$nextTick(()=>{
const fileInput=this.$refs.fileInputModal;
if(fileInput){fileInput.value='';}
this.actualizarIconos();
this.scrollComentariosAlFinal();
});
},
cerrarModal(){
this.cancelarCargaComentarios();
this.openModal=false;
this.selectedTicket={};
this.comentarios=[];
this.evidencias=[];
this.rutaComentario='';
this.archivoAdjunto=null;
this.tomandoTicket=false;
this.enviandoComentario=false;
this._comentariosTicketId=null;
this._ultimoComentarioEnviadoId=null;
this.$nextTick(()=>{
const fileInput=this.$refs.fileInputModal;
if(fileInput){fileInput.value='';}
});
},
mostrarTicket(estado,ticket){
if(!ticket){return false;}
const filtroActual=String(this.filtro||'todos').trim().toLowerCase();
if(filtroActual==='mis tickets'){return this.esMiTicket(ticket);}
return true;
},
esMiTicket(ticket){
if(!ticket){return false;}
const datos=this.obtenerDatosTicket(ticket);
const loginActual=String(this.usuarioActualLogin||window.usuarioActualLogin||'').trim().toLowerCase();
if(!loginActual){return false;}
const tomadoPor=datos?.tomado_por;
if(!tomadoPor){return false;}
let loginTomador='';
if(typeof tomadoPor==='object'){
loginTomador=String(tomadoPor.login||tomadoPor.usuario||tomadoPor.username||tomadoPor.name_login||'').trim().toLowerCase();
}else{
loginTomador=String(tomadoPor).trim().toLowerCase();
}
return loginTomador!==''&&loginTomador===loginActual;
},
tomarTicket(ticket=null){
if(ticket){this.selectedTicket=this.obtenerDatosTicket(ticket);}
if(!this.selectedTicket?.id){this.mostrarError('No se encontró el ID del ticket.');return;}
if(this.tomandoTicket){return;}
const datosTicket=this.obtenerDatosTicket(this.selectedTicket);
const estadoActual=this.obtenerEstado(datosTicket);
const tecnicoActual=this.obtenerTecnico(datosTicket);
if(['solucionado','cancelado'].includes(estadoActual)){return;}
if(tecnicoActual){return;}
this.tomandoTicket=true;
const csrfToken=document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if(!csrfToken){
this.tomandoTicket=false;
this.mostrarError('No se encontró el token de seguridad.');
return;
}
const ticketId=Number(this.selectedTicket.id);
fetch(`/tickets/${ticketId}/tomar`,{
method:'POST',
headers:{
'Content-Type':'application/json',
'X-CSRF-TOKEN':csrfToken,
'Accept':'application/json',
'X-Requested-With':'XMLHttpRequest'
},
body:JSON.stringify({})
}).then(async response=>{
let data={};
const texto=await response.text();
if(texto){
try{data=JSON.parse(texto);}catch(error){console.error('Respuesta JSON inválida:',texto);}
}
if(!response.ok){throw new Error(data.message||`Error HTTP ${response.status}`);}
return data;
}).then(data=>{
if(!data?.success){throw new Error(data?.message||'No se pudo tomar el ticket.');}
const tecnico=data.tomado_por||data.usuario||null;
const nuevoEstado=data.estado||'en proceso';
const fechaTomado=data.fecha_tomado||null;
const tecnicoNormalizado=this.normalizarTecnico(tecnico);
this.selectedTicket={
...this.selectedTicket,
estado:nuevoEstado,
tomado_por:tecnicoNormalizado,
fecha_tomado:fechaTomado
};
this.ticketsActualizados[ticketId]={
...(this.ticketsActualizados[ticketId]||{}),
estado:nuevoEstado,
tomado_por:tecnicoNormalizado,
fecha_tomado:fechaTomado
};
this.tomandoTicket=false;
this.actualizarIconos();
}).catch(error=>{
console.error('Error al tomar ticket:',error);
this.tomandoTicket=false;
this.mostrarError(error.message||'No se pudo tomar el ticket.');
});
},
normalizarTecnico(tecnico){
if(!tecnico){return null;}
if(typeof tecnico!=='object'){
const login=String(tecnico).trim();
return{login:login,name:login||'Técnico'};
}
const resultado={...tecnico};
if(resultado.login){resultado.login=String(resultado.login).trim();}
if(resultado.foto){
resultado.foto=String(resultado.foto)
.replace(`${window.location.origin}/storage/`,'')
.replace('/storage/','')
.replace(/^\/?storage[\/\\]+/,'');
}
return resultado;
},
ticketActualizado(id){
if(id===null||id===undefined){return null;}
return this.ticketsActualizados[id]||this.ticketsActualizados[String(id)]||null;
},
obtenerDatosTicket(ticket){
if(!ticket){return {};}
const actualizado=this.ticketActualizado(ticket.id);
let datos={...ticket};
if(actualizado){
let tomadoPor=actualizado.tomado_por!==undefined?actualizado.tomado_por:ticket.tomado_por;
if(tomadoPor){tomadoPor=this.normalizarTecnico(tomadoPor);}
datos={
...ticket,
estado:actualizado.estado??ticket.estado,
tomado_por:tomadoPor,
fecha_tomado:actualizado.fecha_tomado??ticket.fecha_tomado,
solucion:actualizado.solucion??ticket.solucion
};
}
return datos;
},
obtenerEstado(ticket){
const datos=this.obtenerDatosTicket(ticket);
const estado=String(datos?.estado??'').trim().toLowerCase();
if(!estado&&datos?.tomado_por){return'en proceso';}
return estado;
},
obtenerTecnico(ticket){
const datos=this.obtenerDatosTicket(ticket);
if(!datos?.tomado_por){return null;}
if(typeof datos.tomado_por==='object'){return datos.tomado_por;}
const login=String(datos.tomado_por).trim();
return{login:login,name:login||'Técnico'};
},
nombreTecnico(ticket){
const tecnico=this.obtenerTecnico(ticket);
if(!tecnico){return'Sin asignar';}
return tecnico.name||tecnico.nombre||tecnico.login||'Técnico';
},
nombreTomadoPor(ticket){
return this.nombreTecnico(ticket);
},
tieneTomado(ticket){
return Boolean(this.obtenerTecnico(ticket));
},
puedeTomarTicket(ticket){
if(!ticket){return false;}
const datos=this.obtenerDatosTicket(ticket);
const estado=this.obtenerEstado(datos);
if(['solucionado','cancelado'].includes(estado)){return false;}
if(this.tieneTomado(datos)){return false;}
if(this.tomandoTicket){return false;}
return true;
},
cancelarCargaComentarios(){
if(this._comentariosAbortController){
try{this._comentariosAbortController.abort();}catch(error){}
}
this._comentariosAbortController=null;
this._comentariosRequestId++;
},
eliminarComentariosDuplicados(comentarios){
if(!Array.isArray(comentarios)){return[];}
const mapa=new Map();
comentarios.forEach(comentario=>{
if(!comentario||typeof comentario!=='object'){return;}
if(comentario.id!==null&&comentario.id!==undefined){
mapa.set(`id-${comentario.id}`,comentario);
return;
}
const claveTemporal=[comentario.mensaje||'',comentario.archivo||'',comentario.fecha||'',comentario.created_at||''].join('|');
if(!mapa.has(claveTemporal)){mapa.set(claveTemporal,comentario);}
});
return Array.from(mapa.values());
},
obtenerFechaComentario(comentario){
if(!comentario){return 0;}
const posiblesFechas=[comentario.created_at,comentario.updated_at,comentario.fecha,comentario.fecha_creacion,comentario.createdAt];
for(const fecha of posiblesFechas){
if(!fecha){continue;}
const tiempo=new Date(fecha).getTime();
if(Number.isFinite(tiempo)){return tiempo;}
}
return 0;
},
ordenarComentarios(comentarios){
if(!Array.isArray(comentarios)){return[];}
return[...comentarios].sort((a,b)=>{
const fechaA=this.obtenerFechaComentario(a);
const fechaB=this.obtenerFechaComentario(b);
if(fechaA&&fechaB&&fechaA!==fechaB){return fechaA-fechaB;}
const idA=Number(a?.id);
const idB=Number(b?.id);
if(Number.isFinite(idA)&&Number.isFinite(idB)){return idA-idB;}
return 0;
});
},
normalizarComentario(comentario){
if(!comentario||typeof comentario!=='object'){return null;}
return{
...comentario,
id:comentario.id!==undefined&&comentario.id!==null?Number(comentario.id):comentario.id,
mensaje:comentario.mensaje??comentario.message??'',
archivo:comentario.archivo??comentario.archivo_url??null,
created_at:comentario.created_at??comentario.fecha??null
};
},
agregarComentarioNuevo(comentario){
const normalizado=this.normalizarComentario(comentario);
if(!normalizado){return;}
if(normalizado.id!==null&&normalizado.id!==undefined){this._ultimoComentarioEnviadoId=Number(normalizado.id);}
this.comentarios=this.ordenarComentarios(this.eliminarComentariosDuplicados([...this.comentarios,normalizado]));
this.$nextTick(()=>{
this.scrollComentariosAlFinal();
this.actualizarIconos();
});
},
scrollComentariosAlFinal(){
this.$nextTick(()=>{
const lista=document.getElementById('listaComentarios');
if(lista){lista.scrollTop=lista.scrollHeight;}
});
},
enviarComentario(form=null){
if(this.enviandoComentario){return;}
if(!this.selectedTicket?.id){
this.mostrarError('No se encontró el ticket seleccionado.');
return;
}
if(!form){form=document.getElementById('formComentario');}
if(!form){return;}
const mensajeInput=form.querySelector('input[name="mensaje"],textarea[name="mensaje"]');
const archivoInput=form.querySelector('input[name="archivo"]');
const mensaje=mensajeInput?.value?.trim()||'';
const archivo=archivoInput?.files?.[0]||null;
if(!mensaje&&!archivo){
if(mensajeInput){mensajeInput.focus();}
return;
}
const csrfToken=document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if(!csrfToken){
this.mostrarError('No se encontró el token de seguridad.');
return;
}
const ticketId=Number(this.selectedTicket.id);
const url=form.getAttribute('action')||this.rutaComentario||this.generarRutaComentario(ticketId);
if(!url){
this.mostrarError('No se pudo determinar la ruta del comentario.');
return;
}
this.enviandoComentario=true;
const formData=new FormData(form);
formData.set('mensaje',mensaje);
if(archivo){formData.set('archivo',archivo);}else{formData.delete('archivo');}
this.cancelarCargaComentarios();
const requestIdEnvio=this._comentariosRequestId;
fetch(url,{
method:'POST',
headers:{
'X-CSRF-TOKEN':csrfToken,
'Accept':'application/json',
'X-Requested-With':'XMLHttpRequest'
},
body:formData
}).then(async response=>{
const texto=await response.text();
let data={};
if(texto){
try{data=JSON.parse(texto);}catch(error){console.error('Respuesta no JSON:',texto);}
}
if(!response.ok){
if(data.errors&&typeof data.errors==='object'){
const errores=Object.values(data.errors).flat().join('\n');
throw new Error(errores||data.message||`Error HTTP ${response.status}`);
}
throw new Error(data.message||`Error HTTP ${response.status}`);
}
return data;
}).then(data=>{
if(!data||data.success===false){throw new Error(data?.message||'No se pudo guardar el comentario.');}
let comentarioNuevo=data.comentario||data.comment||data.data?.comentario||data.data?.comment||null;
if(!comentarioNuevo&&data.id&&(data.mensaje!==undefined||data.archivo!==undefined)){comentarioNuevo=data;}
this.limpiarFormularioComentario(mensajeInput,archivoInput);
this.enviandoComentario=false;
if(comentarioNuevo){
this.agregarComentarioNuevo(comentarioNuevo);
this.sincronizarComentariosDespuesDeEnviar(ticketId,requestIdEnvio);
return;
}
this.forzarRecargaComentarios(ticketId);
}).catch(error=>{
console.error('Error al enviar comentario:',error);
this.enviandoComentario=false;
this.mostrarError(error.message||'No se pudo enviar el comentario.');
});
},
sincronizarComentariosDespuesDeEnviar(ticketId){
setTimeout(()=>{
if(!this.openModal){return;}
if(Number(this.selectedTicket?.id)!==Number(ticketId)){return;}
this.cargarComentarios(ticketId,true);
},300);
},
forzarRecargaComentarios(ticketId){
if(!ticketId){return;}
this.cancelarCargaComentarios();
this.cargarComentarios(ticketId,false);
},
limpiarFormularioComentario(mensajeInput,archivoInput){
if(mensajeInput){mensajeInput.value='';}
if(archivoInput){archivoInput.value='';}
this.archivoAdjunto=null;
},
cargarComentarios(ticketId,preservarComentarioNuevo=false){
if(!ticketId){
this.comentarios=[];
return;
}
const idTicket=Number(ticketId);
this.cancelarCargaComentarios();
const controller=new AbortController();
this._comentariosAbortController=controller;
const requestId=this._comentariosRequestId;
this._comentariosTicketId=idTicket;
const cacheBuster=`${Date.now()}-${Math.random().toString(36).substring(2)}`;
const url=`/tickets/${idTicket}/comentarios?_=${cacheBuster}`;
fetch(url,{
method:'GET',
cache:'no-store',
headers:{
'Accept':'application/json',
'Cache-Control':'no-cache,no-store,must-revalidate,max-age=0',
'Pragma':'no-cache',
'Expires':'0',
'X-Requested-With':'XMLHttpRequest'
},
signal:controller.signal
}).then(async response=>{
const texto=await response.text();
let data={};
if(texto){
try{data=JSON.parse(texto);}catch(error){console.error('La respuesta de comentarios no es JSON:',texto);}
}
if(!response.ok){throw new Error(data.message||`Error HTTP ${response.status}`);}
return data;
}).then(data=>{
if(requestId!==this._comentariosRequestId||this._comentariosTicketId!==idTicket||!this.openModal){return;}
if(!data.success){throw new Error(data.message||'No se pudieron cargar los comentarios.');}
let comentarios=Array.isArray(data.comentarios)?data.comentarios:[];
comentarios=this.ordenarComentarios(this.eliminarComentariosDuplicados(comentarios.map(comentario=>this.normalizarComentario(comentario)).filter(Boolean)));
if(preservarComentarioNuevo&&this._ultimoComentarioEnviadoId!==null){
const existe=comentarios.some(comentario=>Number(comentario.id)===Number(this._ultimoComentarioEnviadoId));
if(!existe){
const comentarioActual=this.comentarios.find(comentario=>Number(comentario.id)===Number(this._ultimoComentarioEnviadoId));
if(comentarioActual){
comentarios.push(comentarioActual);
comentarios=this.ordenarComentarios(this.eliminarComentariosDuplicados(comentarios));
}
}
}
this.comentarios=comentarios;
this.scrollComentariosAlFinal();
this.actualizarIconos();
}).catch(error=>{
if(error?.name==='AbortError'){return;}
console.error('Error al cargar comentarios:',error);
}).finally(()=>{
if(this._comentariosAbortController===controller){this._comentariosAbortController=null;}
});
},
seleccionarArchivo(evento){
const archivo=evento?.target?.files?.[0]||null;
this.archivoAdjunto=archivo;
},
quitarArchivo(){
this.archivoAdjunto=null;
const input=this.$refs.fileInputModal;
if(input){input.value='';}
},
nombreArchivoComentario(){
return this.archivoAdjunto?.name||'';
},
tamañoArchivoComentario(){
return this.archivoAdjunto?this.formatearTamanoArchivo(this.archivoAdjunto.size):'';
},
formatearTamanoArchivo(bytes){
if(!bytes||Number(bytes)<=0){return'0 KB';}
const unidades=['Bytes','KB','MB','GB'];
const indice=Math.max(0,Math.min(Math.floor(Math.log(bytes)/Math.log(1024)),unidades.length-1));
return parseFloat((bytes/Math.pow(1024,indice)).toFixed(2))+' '+unidades[indice];
},
formatearTamano(bytes){
return this.formatearTamanoArchivo(bytes);
},
seleccionarArchivoSolucion(evento){
if(this.modalSolucionSoloLectura){return;}
const archivos=Array.from(evento?.target?.files||[]);
if(!archivos.length){return;}
const extensionesPermitidas=['jpg','jpeg','png','gif','webp','pdf','mp4','webm','mov','avi','mkv','doc','docx','xls','xlsx','ppt','pptx','txt','zip','rar'];
const archivosValidos=archivos.filter(archivo=>{
if(!archivo){return false;}
if(archivo.size>10*1024*1024){return false;}
const extension=this.extensionArchivo(archivo.name).toLowerCase();
return extensionesPermitidas.includes(extension);
});
const rechazados=archivos.length-archivosValidos.length;
if(rechazados>0){
this.mostrarError('Uno o más archivos no son válidos. Cada archivo debe pesar máximo 10 MB y tener una extensión permitida.');
}
const existentes=new Set(this.evidenciasSolucion.filter(archivo=>typeof File!=='undefined'&&archivo instanceof File).map(archivo=>`${archivo.name}-${archivo.size}-${archivo.lastModified}`));
const nuevos=archivosValidos.filter(archivo=>{
const clave=`${archivo.name}-${archivo.size}-${archivo.lastModified}`;
return!existentes.has(clave);
});
if(this.evidenciasSolucion.length+nuevos.length>10){
this.mostrarError('Solo puedes adjuntar un máximo de 10 evidencias.');
return;
}
this.evidenciasSolucion=[...this.evidenciasSolucion,...nuevos];
this.archivoSolucion=null;
this.actualizarInputEvidencias();
this.$nextTick(()=>this.actualizarIconos());
},
actualizarInputEvidencias(){
const input=this.$refs.evidenciaInput;
if(!input||typeof DataTransfer==='undefined'){return;}
const dataTransfer=new DataTransfer();
this.evidenciasSolucion.filter(archivo=>typeof File!=='undefined'&&archivo instanceof File).forEach(archivo=>dataTransfer.items.add(archivo));
input.files=dataTransfer.files;
},
eliminarEvidencia(index){
if(index<0||index>=this.evidenciasSolucion.length){return;}
this.evidenciasSolucion=this.evidenciasSolucion.filter((_,i)=>i!==index);
this.actualizarInputEvidencias();
},
seleccionarArchivoUnicoSolucion(evento){
this.seleccionarArchivoSolucion(evento);
},
quitarArchivoSolucion(){
this.archivoSolucion=null;
const input=this.$refs.archivoSolucion;
if(input){input.value='';}
},
nombreArchivoSolucion(){
return this.archivoSolucion?.name||'';
},
tamañoArchivoSolucion(){
return this.archivoSolucion?this.formatearTamanoArchivo(this.archivoSolucion.size):'';
},
tipoArchivoSolucion(){
if(!this.archivoSolucion){return'Archivo';}
return this.archivoSolucion.type||this.extensionArchivo(this.archivoSolucion)||'Archivo';
},
mostrarPreviewSolucion(){
return;
},
limpiarPreviewSolucion(){
if(this._previewSolucionUrl){
try{URL.revokeObjectURL(this._previewSolucionUrl);}catch(error){}
this._previewSolucionUrl=null;
}
if(this.$refs?.previewImagenSolucion){this.$refs.previewImagenSolucion.removeAttribute('src');}
if(this.$refs?.previewPdfSolucion){this.$refs.previewPdfSolucion.removeAttribute('src');}
},
fechaActual(){
return new Date().toLocaleString('es-MX',{day:'2-digit',month:'short',year:'numeric',hour:'2-digit',minute:'2-digit'});
},
limpiarBusqueda(){
const url=new URL(window.location.href);
url.searchParams.delete('buscar');
url.searchParams.delete('page');
window.location.href=url.toString();
},
hayBusquedaYNoHayResultados(){
const botones=document.querySelectorAll('tbody button[title="Ver ticket"]');
if(!botones.length){return false;}
let resultados=0;
botones.forEach(boton=>{
const fila=boton.closest('tr');
if(!fila){return;}
if(window.getComputedStyle(fila).display!=='none'){resultados++;}
});
return resultados===0&&(String(this.busqueda||'').trim().length>0||String(this.filtro||'todos').trim().toLowerCase()!=='todos');
},
limpiarFiltros(){
const url=new URL(window.location.href);
url.searchParams.delete('buscar');
url.searchParams.set('filtro','todos');
url.searchParams.delete('page');
this.filtro='todos';
this.busqueda='';
window.location.href=url.toString();
},
capitalizar(valor){
if(valor===null||valor===undefined||valor===''){return'—';}
return String(valor).replace(/[_-]/g,' ').replace(/\b\w/g,letra=>letra.toUpperCase());
},
formatearFecha(fecha){
if(!fecha){return'—';}
const date=new Date(fecha);
if(Number.isNaN(date.getTime())){return fecha;}
return date.toLocaleString('es-MX',{day:'2-digit',month:'short',year:'numeric',hour:'2-digit',minute:'2-digit'});
},
avatarUsuario(nombre){
const usuario=encodeURIComponent(nombre||'Usuario');
return `https://ui-avatars.com/api/?name=${usuario}&background=0D8ABC&color=fff`;
},
normalizarEvidencia(evidencia){
if(!evidencia){return null;}
if(typeof evidencia==='string'){
const valor=evidencia.trim();
if(!valor){return null;}
if((valor.startsWith('{')&&valor.endsWith('}'))||(valor.startsWith('[')&&valor.endsWith(']'))){
try{
const parsed=JSON.parse(valor);
if(Array.isArray(parsed)){return parsed.map(item=>this.normalizarEvidencia(item)).filter(Boolean);}
if(parsed&&typeof parsed==='object'){return this.normalizarEvidencia(parsed);}
}catch(error){}
}
return{
url:this.archivoUrl(valor),
ruta:valor,
nombre:this.nombreArchivo(valor),
tipo:this.tipoArchivo(valor),
extension:this.extensionArchivo(valor)
};
}
if(typeof File!=='undefined'&&evidencia instanceof File){return evidencia;}
if(typeof evidencia==='object'){
const ruta=evidencia.ruta||evidencia.path||evidencia.archivo||'';
let url=evidencia.url||evidencia.url_archivo||evidencia.ruta_url||'';
if(!url&&ruta){url=this.archivoUrl(ruta);}
return{
...evidencia,
url,
ruta,
nombre:evidencia.nombre||evidencia.nombre_archivo||evidencia.name||evidencia.original_name||(ruta?this.nombreArchivo(ruta):'Archivo'),
tipo:evidencia.tipo||evidencia.mime_type||evidencia.type||(ruta?this.tipoArchivo(ruta):'application/octet-stream'),
extension:evidencia.extension||this.extensionArchivo(evidencia.nombre||evidencia.nombre_archivo||evidencia.name||evidencia.original_name||ruta)
};
}
return null;
},
normalizarListaEvidencias(evidencias){
if(!evidencias){return[];}
if(typeof evidencias==='string'){
try{return this.normalizarListaEvidencias(JSON.parse(evidencias));}
catch(error){
const evidencia=this.normalizarEvidencia(evidencias);
return evidencia?[evidencia]:[];
}
}
if(Array.isArray(evidencias)){
const resultado=[];
evidencias.forEach(evidencia=>{
const normalizada=this.normalizarEvidencia(evidencia);
if(Array.isArray(normalizada)){resultado.push(...normalizada);}
else if(normalizada){resultado.push(normalizada);}
});
return resultado;
}
if(typeof evidencias==='object'){
return this.normalizarListaEvidencias(Object.values(evidencias));
}
return[];
},
obtenerEvidencias(ticket){
if(!ticket||typeof ticket!=='object'){return[];}
if(ticket.evidencia!==undefined&&ticket.evidencia!==null){return this.normalizarListaEvidencias(ticket.evidencia);}
if(ticket.evidencias!==undefined&&ticket.evidencias!==null){return this.normalizarListaEvidencias(ticket.evidencias);}
return[];
},
obtenerEvidenciasSolucion(solucion){
if(!solucion){return[];}
if(typeof solucion==='string'){
try{return this.obtenerEvidenciasSolucion(JSON.parse(solucion));}
catch(error){return[];}
}
if(Array.isArray(solucion)){return this.normalizarListaEvidencias(solucion);}
if(typeof solucion!=='object'){return[];}
if(solucion.evidencias!==undefined&&solucion.evidencias!==null){return this.normalizarListaEvidencias(solucion.evidencias);}
if(solucion.evidencia!==undefined&&solucion.evidencia!==null){return this.normalizarListaEvidencias(solucion.evidencia);}
if(solucion.archivos!==undefined&&solucion.archivos!==null){return this.normalizarListaEvidencias(solucion.archivos);}
if(solucion.archivos_evidencia!==undefined&&solucion.archivos_evidencia!==null){return this.normalizarListaEvidencias(solucion.archivos_evidencia);}
return[];
},
archivoUrl(archivo){
if(!archivo){return'#';}
if(typeof File!=='undefined'&&archivo instanceof File){
return URL.createObjectURL(archivo);
}
if(typeof archivo==='object'){
if(archivo.url){return this.archivoUrl(archivo.url);}
if(archivo.url_archivo){return this.archivoUrl(archivo.url_archivo);}
if(archivo.ruta_url){return this.archivoUrl(archivo.ruta_url);}
if(archivo.ruta){return this.archivoUrl(archivo.ruta);}
if(archivo.path){return this.archivoUrl(archivo.path);}
if(typeof archivo.archivo==='string'){return this.archivoUrl(archivo.archivo);}
return'#';
}
if(typeof archivo!=='string'){return'#';}
let valor=archivo.trim();
if(!valor){return'#';}
if(valor.startsWith('http://')||valor.startsWith('https://')||valor.startsWith('data:')){return valor;}
if(valor.startsWith('/public/storage/')){return valor.replace('/public','');}
if(valor.startsWith('public/storage/')){return`/${valor.replace('public/','')}`;}
if(valor.startsWith('/storage/')){return valor;}
if(valor.startsWith('storage/')){return`/${valor}`;}
if(valor.startsWith('/')){return valor;}
return`/storage/${valor}`;
},
nombreArchivo(archivo){
if(!archivo){return'Archivo';}
if(typeof File!=='undefined'&&archivo instanceof File){return archivo.name||'Archivo';}
if(typeof archivo==='object'){
if(archivo.nombre){return archivo.nombre;}
if(archivo.nombre_archivo){return archivo.nombre_archivo;}
if(archivo.name){return archivo.name;}
if(archivo.original_name){return archivo.original_name;}
if(typeof archivo.archivo==='string'){return this.nombreArchivo(archivo.archivo);}
if(archivo.url){return this.nombreArchivo(archivo.url);}
if(archivo.ruta){return this.nombreArchivo(archivo.ruta);}
}
const url=this.archivoUrl(archivo);
if(!url||url==='#'){return'Archivo';}
try{
return decodeURIComponent(url.split('/').pop().split('?')[0]);
}catch(error){
return url.split('/').pop().split('?')[0]||'Archivo';
}
},
extensionArchivo(archivo){
const nombre=this.nombreArchivo(archivo);
if(!nombre||nombre==='Archivo'){return'FILE';}
const partes=nombre.split('.');
if(partes.length<2){return'FILE';}
return partes.pop().toUpperCase();
},
tipoArchivo(archivo){
if(archivo&&typeof archivo==='object'){
if(archivo.tipo){return archivo.tipo;}
if(archivo.mime_type){return archivo.mime_type;}
if(archivo.type){return archivo.type;}
}
const extension=this.extensionArchivo(archivo).toLowerCase();
const tipos={
jpg:'image/jpeg',
jpeg:'image/jpeg',
png:'image/png',
gif:'image/gif',
webp:'image/webp',
bmp:'image/bmp',
svg:'image/svg+xml',
pdf:'application/pdf',
mp4:'video/mp4',
mov:'video/quicktime',
avi:'video/x-msvideo',
webm:'video/webm',
mkv:'video/x-matroska',
doc:'application/msword',
docx:'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
xls:'application/vnd.ms-excel',
xlsx:'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
ppt:'application/vnd.ms-powerpoint',
pptx:'application/vnd.openxmlformats-officedocument.presentationml.presentation',
txt:'text/plain',
zip:'application/zip',
rar:'application/vnd.rar'
};
return tipos[extension]||'application/octet-stream';
},
esImagen(archivo){
const tipo=this.tipoArchivo(archivo).toLowerCase();
if(tipo.startsWith('image/')){return true;}
return['jpg','jpeg','png','gif','webp','bmp','svg'].includes(this.extensionArchivo(archivo).toLowerCase());
},
esPDF(archivo){
return this.tipoArchivo(archivo).toLowerCase()==='application/pdf';
},
esVideo(archivo){
return this.tipoArchivo(archivo).toLowerCase().startsWith('video/');
},
generarRutaComentario(ticketId){
if(ticketId===null||ticketId===undefined){return'';}
return`/tickets/${ticketId}/comentarios`;
},
abrirModalSolucion(ticket,soloLectura=false){
if(!ticket){return;}
this.limpiarPreviewSolucion();
this.archivoSolucion=null;
this.evidenciasSolucion=[];
this.ticketSolucion=this.obtenerDatosTicket(ticket);
const estado=this.obtenerEstado(this.ticketSolucion);
let solucion=this.ticketSolucion?.solucion||{};
if(typeof solucion==='string'){
try{solucion=JSON.parse(solucion);}
catch(error){solucion={solucion:solucion};}
}
const tieneSolucion=Boolean(solucion&&typeof solucion==='object'&&(solucion.solucion||solucion.evidencia||solucion.evidencias));
const esSolucionado=estado==='solucionado';
const esCancelado=estado==='cancelado';
this.modalSolucionSoloLectura=Boolean(soloLectura||tieneSolucion||esSolucionado||esCancelado);
this.openModalSolucion=true;
this.firmaExistente='';
if(this.modalSolucionSoloLectura){
this.solucionForm={
solucion:solucion?.solucion??'',
solucionado:solucion?.problema_solucionado??(esSolucionado?true:esCancelado?false:null),
fecha_solucion:this.formatearFechaInput(solucion?.fecha_solucion),
nombre_firmante:solucion?.nombre_firmante??this.ticketSolucion?.user?.name??'',
fecha_firma:this.formatearFechaInput(solucion?.fecha_firma)
};
this.evidenciasSolucion=this.obtenerEvidenciasSolucion(solucion);
this.firmaExistente=this.obtenerFirma(solucion);
}else{
this.solucionForm={
solucion:'',
solucionado:null,
fecha_solucion:this.obtenerFechaActualInput(),
nombre_firmante:this.ticketSolucion?.user?.name??'',
fecha_firma:''
};
this.evidenciasSolucion=[];
}
this.$nextTick(()=>{
this.actualizarIconos();
if(!this.modalSolucionSoloLectura){this.inicializarFirma();}
});
},
cerrarModalSolucion(){
this.limpiarPreviewSolucion();
this.archivoSolucion=null;
if(this._firmaAbortController){
try{this._firmaAbortController.abort();}catch(error){}
this._firmaAbortController=null;
}
this.openModalSolucion=false;
this.ticketSolucion={};
this.modalSolucionSoloLectura=false;
this.guardandoSolucion=false;
this.evidenciasSolucion=[];
this.firmaExistente='';
this._firmaCanvas=null;
this._firmaContext=null;
this.solucionForm={
solucion:'',
solucionado:null,
fecha_solucion:'',
nombre_firmante:'',
fecha_firma:''
};
this.$nextTick(()=>{
const input=this.$refs.evidenciaInput;
if(input){input.value='';}
const inputSolucion=this.$refs.archivoSolucion;
if(inputSolucion){inputSolucion.value='';}
});
},
inicializarFirma(){
this.$nextTick(()=>{
const canvas=this.$refs.canvasFirma;
if(!canvas){return;}
if(this._firmaAbortController){
try{this._firmaAbortController.abort();}catch(error){}
}
const controller=new AbortController();
this._firmaAbortController=controller;
const ctx=canvas.getContext('2d');
if(!ctx){return;}
ctx.fillStyle='#ffffff';
ctx.fillRect(0,0,canvas.width,canvas.height);
ctx.strokeStyle='#000000';
ctx.lineWidth=3;
ctx.lineCap='round';
ctx.lineJoin='round';
let dibujando=false;
const obtenerPosicion=evento=>{
const rect=canvas.getBoundingClientRect();
if(!rect.width||!rect.height){return{x:0,y:0};}
const escalaX=canvas.width/rect.width;
const escalaY=canvas.height/rect.height;
let clientX=0;
let clientY=0;
if(evento.touches&&evento.touches.length){
clientX=evento.touches[0].clientX;
clientY=evento.touches[0].clientY;
}else{
clientX=evento.clientX;
clientY=evento.clientY;
}
return{x:(clientX-rect.left)*escalaX,y:(clientY-rect.top)*escalaY};
};
const comenzar=evento=>{
evento.preventDefault();
dibujando=true;
const posicion=obtenerPosicion(evento);
ctx.beginPath();
ctx.moveTo(posicion.x,posicion.y);
};
const dibujar=evento=>{
if(!dibujando){return;}
evento.preventDefault();
const posicion=obtenerPosicion(evento);
ctx.lineTo(posicion.x,posicion.y);
ctx.stroke();
};
const terminar=()=>{
if(!dibujando){return;}
dibujando=false;
ctx.closePath();
};
canvas.addEventListener('mousedown',comenzar,{signal:controller.signal});
canvas.addEventListener('mousemove',dibujar,{signal:controller.signal});
canvas.addEventListener('mouseup',terminar,{signal:controller.signal});
canvas.addEventListener('mouseleave',terminar,{signal:controller.signal});
canvas.addEventListener('touchstart',comenzar,{passive:false,signal:controller.signal});
canvas.addEventListener('touchmove',dibujar,{passive:false,signal:controller.signal});
canvas.addEventListener('touchend',terminar,{signal:controller.signal});
this._firmaCanvas=canvas;
this._firmaContext=ctx;
});
},
limpiarFirma(){
const canvas=this.$refs.canvasFirma;
if(!canvas){return;}
const ctx=canvas.getContext('2d');
if(!ctx){return;}
ctx.clearRect(0,0,canvas.width,canvas.height);
ctx.fillStyle='#ffffff';
ctx.fillRect(0,0,canvas.width,canvas.height);
},
canvasTieneFirma(canvas){
if(!canvas){return false;}
const ctx=canvas.getContext('2d');
if(!ctx){return false;}
const imagen=ctx.getImageData(0,0,canvas.width,canvas.height);
for(let i=0;i<imagen.data.length;i+=4){
if(imagen.data[i]<245||imagen.data[i+1]<245||imagen.data[i+2]<245){return true;}
}
return false;
},
obtenerFirma(solucion){
if(!solucion||typeof solucion!=='object'){return'';}
if(solucion.url_firma){return this.normalizarUrlFirma(solucion.url_firma);}
if(solucion.firma){return this.normalizarUrlFirma(solucion.firma);}
return'';
},
normalizarUrlFirma(firma){
if(!firma){return'';}
const valor=String(firma).trim();
if(!valor){return'';}
if(valor.startsWith('data:image/')){return valor;}
if(valor.startsWith('http://')||valor.startsWith('https://')){return valor;}
if(valor.startsWith('/public/storage/')){return valor.replace('/public','');}
if(valor.startsWith('public/storage/')){return`/${valor.replace('public/','')}`;}
if(valor.startsWith('/storage/')){return valor;}
if(valor.startsWith('storage/')){return`/${valor}`;}
if(valor.startsWith('/')){return valor;}
return`/storage/${valor}`;
},
seleccionarEvidencias(evento){
if(this.modalSolucionSoloLectura){return;}
const archivos=Array.from(evento?.target?.files||[]);
if(!archivos.length){return;}
const extensionesPermitidas=['jpg','jpeg','png','gif','webp','pdf','mp4','webm','mov','avi','mkv','doc','docx','xls','xlsx','ppt','pptx','txt','zip','rar'];
const archivosInvalidos=[];
const archivosValidos=archivos.filter(archivo=>{
if(!archivo){return false;}
const extension=this.extensionArchivo(archivo.name).toLowerCase();
const validoExtension=extensionesPermitidas.includes(extension);
const validoTamano=Number(archivo.size)<=10*1024*1024;
if(!validoExtension||!validoTamano){
archivosInvalidos.push(archivo);
return false;
}
return true;
});
if(archivosInvalidos.length){
this.mostrarError('Algunos archivos no son válidos. Cada archivo debe pesar máximo 10 MB y tener una extensión permitida.');
}
const existentes=new Set(this.evidenciasSolucion.filter(archivo=>typeof File!=='undefined'&&archivo instanceof File).map(archivo=>`${archivo.name}-${archivo.size}-${archivo.lastModified}`));
const nuevos=archivosValidos.filter(archivo=>{
const clave=`${archivo.name}-${archivo.size}-${archivo.lastModified}`;
return!existentes.has(clave);
});
if(this.evidenciasSolucion.length+nuevos.length>10){
this.mostrarError('Solo puedes adjuntar un máximo de 10 evidencias.');
return;
}
this.evidenciasSolucion=[...this.evidenciasSolucion,...nuevos];
this.actualizarInputEvidencias();
this.$nextTick(()=>this.actualizarIconos());
},
actualizarInputEvidencias(){
const input=this.$refs.evidenciaInput;
if(!input||typeof DataTransfer==='undefined'){return;}
const dataTransfer=new DataTransfer();
this.evidenciasSolucion.filter(archivo=>typeof File!=='undefined'&&archivo instanceof File).forEach(archivo=>dataTransfer.items.add(archivo));
input.files=dataTransfer.files;
},
eliminarEvidencia(index){
if(index<0||index>=this.evidenciasSolucion.length){return;}
this.evidenciasSolucion=this.evidenciasSolucion.filter((_,i)=>i!==index);
this.actualizarInputEvidencias();
},
guardarSolucion(){
if(this.modalSolucionSoloLectura||this.guardandoSolucion){return;}
if(!this.ticketSolucion?.id){
this.mostrarError('No se encontró el ticket.');
return;
}
const estadoActual=this.obtenerEstado(this.ticketSolucion);
if(estadoActual==='cancelado'){
this.modalSolucionSoloLectura=true;
this.mostrarError('Este ticket está cancelado y no puede recibir una solución.');
return;
}
if(estadoActual==='solucionado'||this.ticketSolucion?.solucion){
this.modalSolucionSoloLectura=true;
this.mostrarError('Este ticket ya tiene una solución registrada.');
return;
}
if(!this.solucionForm.solucion?.trim()){
this.mostrarError('Debes escribir la solución aplicada.');
return;
}
if(this.solucionForm.solucionado===null||this.solucionForm.solucionado===undefined){
this.mostrarError('Indica si el problema fue solucionado.');
return;
}
const canvas=this.$refs.canvasFirma;
if(!canvas){
this.mostrarError('No se encontró la firma.');
return;
}
if(!this.canvasTieneFirma(canvas)){
this.mostrarError('La persona debe firmar antes de guardar.');
return;
}
const csrfToken=document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if(!csrfToken){
this.mostrarError('No se encontró el token de seguridad.');
return;
}
this.guardandoSolucion=true;
let firma='';
try{
firma=canvas.toDataURL('image/png');
}catch(error){
this.guardandoSolucion=false;
this.mostrarError('No se pudo generar la firma.');
return;
}
const archivos=this.evidenciasSolucion.filter(archivo=>typeof File!=='undefined'&&archivo instanceof File);
if(archivos.length>10){
this.guardandoSolucion=false;
this.mostrarError('Solo puedes adjuntar un máximo de 10 evidencias.');
return;
}
const formData=new FormData();
formData.append('ticket_id',String(this.ticketSolucion.id));
formData.append('folio',this.ticketSolucion.folio||'');
formData.append('solucion',this.solucionForm.solucion.trim());
formData.append('problema_solucionado',this.solucionForm.solucionado?'1':'0');
formData.append('fecha_solucion',this.solucionForm.fecha_solucion||'');
formData.append('nombre_firmante',this.solucionForm.nombre_firmante||this.ticketSolucion?.user?.name||'');
formData.append('fecha_firma',this.solucionForm.fecha_firma||this.obtenerFechaActualInput());
formData.append('firma',firma);
archivos.forEach(archivo=>{
formData.append('evidencias[]',archivo,archivo.name);
});
const ticketId=Number(this.ticketSolucion.id);
fetch(`/tecnologias/tickets/${ticketId}/solucion`,{
method:'POST',
headers:{
'X-CSRF-TOKEN':csrfToken,
'Accept':'application/json',
'X-Requested-With':'XMLHttpRequest'
},
body:formData
}).then(async response=>{
const texto=await response.text();
let data={};
if(texto){
try{data=JSON.parse(texto);}
catch(error){console.error('Respuesta JSON inválida:',texto);}
}
if(!response.ok){
if(data.errors&&typeof data.errors==='object'){
const errores=Object.values(data.errors).flat().join('\n');
throw new Error(errores||data.message||`Error HTTP ${response.status}`);
}
throw new Error(data.message||`Error HTTP ${response.status}`);
}
return data;
}).then(data=>{
if(!data?.success){throw new Error(data?.message||'No se pudo guardar la solución.');}
let solucionGuardada=data.solucion||data.data||{};
if(typeof solucionGuardada==='string'){
try{solucionGuardada=JSON.parse(solucionGuardada);}
catch(error){solucionGuardada={solucion:this.solucionForm.solucion.trim()};}
}
if(!solucionGuardada||typeof solucionGuardada!=='object'){solucionGuardada={};}
if(data.evidencias!==undefined&&data.evidencias!==null){solucionGuardada.evidencias=data.evidencias;}
if(solucionGuardada.evidencia!==undefined&&solucionGuardada.evidencia!==null){solucionGuardada.evidencias=solucionGuardada.evidencia;}
if(data.firma&&!solucionGuardada.firma&&!solucionGuardada.url_firma){solucionGuardada.firma=data.firma;}
if(data.url_firma&&!solucionGuardada.url_firma){solucionGuardada.url_firma=data.url_firma;}
solucionGuardada.solucion=solucionGuardada.solucion??this.solucionForm.solucion.trim();
solucionGuardada.problema_solucionado=solucionGuardada.problema_solucionado??Boolean(this.solucionForm.solucionado);
solucionGuardada.fecha_solucion=solucionGuardada.fecha_solucion??this.solucionForm.fecha_solucion;
solucionGuardada.nombre_firmante=solucionGuardada.nombre_firmante??this.solucionForm.nombre_firmante??this.ticketSolucion?.user?.name??'';
solucionGuardada.fecha_firma=solucionGuardada.fecha_firma??this.solucionForm.fecha_firma;
solucionGuardada.evidencias=this.obtenerEvidenciasSolucion(solucionGuardada);
const nuevoEstado=data.estado||(this.solucionForm.solucionado?'solucionado':'cancelado');
this.ticketSolucion={
...this.ticketSolucion,
estado:nuevoEstado,
solucion:solucionGuardada
};
this.ticketsActualizados[ticketId]={
...(this.ticketsActualizados[ticketId]||{}),
estado:nuevoEstado,
tomado_por:this.ticketSolucion.tomado_por??null,
fecha_tomado:this.ticketSolucion.fecha_tomado??null,
solucion:solucionGuardada
};
if(this.selectedTicket?.id&&Number(this.selectedTicket.id)===ticketId){
this.selectedTicket={
...this.selectedTicket,
estado:nuevoEstado,
solucion:solucionGuardada
};
}
this.evidenciasSolucion=this.obtenerEvidenciasSolucion(solucionGuardada);
this.firmaExistente=this.obtenerFirma(solucionGuardada);
this.archivoSolucion=null;
this.limpiarPreviewSolucion();
this.modalSolucionSoloLectura=true;
this.guardandoSolucion=false;
const input=this.$refs.evidenciaInput;
if(input){input.value='';}
const inputSolucion=this.$refs.archivoSolucion;
if(inputSolucion){inputSolucion.value='';}
if(this._firmaAbortController){
try{this._firmaAbortController.abort();}catch(error){}
this._firmaAbortController=null;
}
this.mostrarSuccess(data.message||'La solución se guardó correctamente.');
this.actualizarIconos();
}).catch(error=>{
console.error('Error al guardar solución:',error);
this.guardandoSolucion=false;
this.mostrarError(error.message||'Ocurrió un error al guardar la solución.');
});
},
formatearFechaInput(fecha){
if(!fecha){return'';}
const date=new Date(fecha);
if(Number.isNaN(date.getTime())){return'';}
const offset=date.getTimezoneOffset();
const fechaLocal=new Date(date.getTime()-offset*60000);
return fechaLocal.toISOString().slice(0,16);
},
obtenerFechaActualInput(){
const ahora=new Date();
const offset=ahora.getTimezoneOffset();
const fecha=new Date(ahora.getTime()-offset*60000);
return fecha.toISOString().slice(0,16);
},
buscarTickets(){
const url=new URL(window.location.href);
const texto=String(this.busqueda||'').trim();
if(texto){url.searchParams.set('buscar',texto);}
else{url.searchParams.delete('buscar');}
url.searchParams.delete('page');
url.searchParams.set('filtro',this.filtro||'todos');
window.location.href=url.toString();
},
cambiarFiltro(nuevoFiltro){
const filtroNormalizado=String(nuevoFiltro||'todos').trim().toLowerCase();
this.filtro=filtroNormalizado;
const url=new URL(window.location.href);
url.searchParams.set('filtro',filtroNormalizado);
url.searchParams.delete('page');
const texto=String(this.busqueda||'').trim();
if(texto){url.searchParams.set('buscar',texto);}
else{url.searchParams.delete('buscar');}
window.location.href=url.toString();
},
actualizarIconos(){
this.$nextTick(()=>{
if(typeof lucide!=='undefined'&&typeof lucide.createIcons==='function'){lucide.createIcons();}
});
},
mostrarSuccess(mensaje){
this.notificacionTipo='success';
this.notificacionMensaje=mensaje;
this.notificacionVisible=true;
if(this.notificacionTimeout){clearTimeout(this.notificacionTimeout);}
this.notificacionTimeout=setTimeout(()=>{
this.notificacionVisible=false;
this.notificacionTimeout=null;
},4000);
this.$nextTick(()=>this.actualizarIconos());
},
mostrarError(mensaje){
this.notificacionTipo='error';
this.notificacionMensaje=mensaje;
this.notificacionVisible=true;
if(this.notificacionTimeout){clearTimeout(this.notificacionTimeout);}
this.notificacionTimeout=setTimeout(()=>{
this.notificacionVisible=false;
this.notificacionTimeout=null;
},5000);
this.$nextTick(()=>this.actualizarIconos());
},
cerrarNotificacion(){
this.notificacionVisible=false;
if(this.notificacionTimeout){
clearTimeout(this.notificacionTimeout);
this.notificacionTimeout=null;
}
}
}));});