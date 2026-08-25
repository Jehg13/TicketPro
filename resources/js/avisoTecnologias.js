document.addEventListener('alpine:init',()=>{
Alpine.data('avisosApp',()=>({
modalVer:false,
modalEditar:false,
modalEliminar:false,
avisoSeleccionado:null,
oficinaSeleccionada:null,
abrirVer(aviso){
this.cerrarModales();
this.avisoSeleccionado=JSON.parse(JSON.stringify(aviso));
this.normalizarAviso();
this.modalVer=true;
},
abrirEditar(aviso){
this.cerrarModales();
this.avisoSeleccionado=JSON.parse(JSON.stringify(aviso));
this.normalizarAviso();
this.modalEditar=true;
},
abrirEliminar(aviso){
this.cerrarModales();
this.avisoSeleccionado=JSON.parse(JSON.stringify(aviso));
this.normalizarAviso();
this.modalEliminar=true;
},
normalizarAviso(){
if(!this.avisoSeleccionado){
return;
}
if(!this.avisoSeleccionado.afecta_a){
this.avisoSeleccionado.afecta_a={};
}
if(typeof this.avisoSeleccionado.afecta_a==='string'){
try{
this.avisoSeleccionado.afecta_a=JSON.parse(this.avisoSeleccionado.afecta_a);
}catch(error){
this.avisoSeleccionado.afecta_a={};
}
}
const tipo=this.avisoSeleccionado.afecta_a?.tipo;
if(tipo==='todos'){
this.avisoSeleccionado.aplica_a='todos';
this.oficinaSeleccionada=null;
}else if(tipo==='oficina'){
this.avisoSeleccionado.aplica_a='oficina';
this.oficinaSeleccionada=this.avisoSeleccionado.afecta_a?.oficina_id??null;
}else if(tipo==='departamentos'){
this.avisoSeleccionado.aplica_a='departamento';
this.oficinaSeleccionada=null;
}else if(tipo==='usuarios'){
this.avisoSeleccionado.aplica_a='usuarios';
this.oficinaSeleccionada=null;
}
this.avisoSeleccionado.mostrar_notificaciones=Boolean(Number(this.avisoSeleccionado.mostrar_notificaciones));
this.avisoSeleccionado.fijado=Boolean(Number(this.avisoSeleccionado.fijado));
},
obtenerDepartamento(id){
const departamentos=window.departamentos||[];
return departamentos.find(departamento=>Number(departamento.id)===Number(id))||null;
},
obtenerOficina(id){
const oficinas=window.oficinas||[];
return oficinas.find(oficina=>Number(oficina.id)===Number(id))||null;
},
obtenerUsuario(id){
const usuarios=window.usuarios||[];
return usuarios.find(usuario=>Number(usuario.id)===Number(id))||null;
},
cerrarModales(){
this.modalVer=false;
this.modalEditar=false;
this.modalEliminar=false;
this.avisoSeleccionado=null;
this.oficinaSeleccionada=null;
},
formatearFecha(fecha){
if(!fecha){
return'No especificada';
}
try{
const fechaNormalizada=String(fecha).replace(' ','T');
const date=new Date(fechaNormalizada);
if(isNaN(date.getTime())){
return fecha;
}
return date.toLocaleString('es-MX',{
day:'2-digit',
month:'2-digit',
year:'numeric',
hour:'2-digit',
minute:'2-digit'
});
}catch(error){
return fecha;
}
},
formatearHora(fecha){
if(!fecha){
return'No especificada';
}
try{
const fechaNormalizada=String(fecha).replace(' ','T');
const date=new Date(fechaNormalizada);
if(isNaN(date.getTime())){
return'';
}
return date.toLocaleTimeString('es-MX',{
hour:'2-digit',
minute:'2-digit',
hour12:true
});
}catch(error){
return'';
}
},
fechaSolo(fecha){
if(!fecha){
return'';
}
return String(fecha).replace(' ','T').substring(0,10);
},
horaSolo(fecha){
if(!fecha){
return'';
}
return String(fecha).replace(' ','T').substring(11,16);
},
afectaSeleccionado(tipo,id){
if(!this.avisoSeleccionado||!this.avisoSeleccionado.afecta_a){
return false;
}
const afecta=this.avisoSeleccionado.afecta_a;
if(tipo==='oficina'){
return afecta.tipo==='oficina'&&Number(afecta.oficina_id)===Number(id);
}
if(afecta.tipo!==tipo){
return false;
}
if(tipo==='usuarios'){
if(!Array.isArray(afecta.logins)){
return false;
}
return afecta.logins.map(String).includes(String(id));
}
if(!Array.isArray(afecta.ids)){
return false;
}
return afecta.ids.map(Number).includes(Number(id));
},
cambiarDestino(){
if(!this.avisoSeleccionado){
return;
}
if(this.avisoSeleccionado.aplica_a==='todos'){
this.oficinaSeleccionada=null;
this.avisoSeleccionado.afecta_a={
tipo:'todos'
};
return;
}
if(this.avisoSeleccionado.aplica_a==='oficina'){
this.oficinaSeleccionada=null;
this.avisoSeleccionado.afecta_a={
tipo:'oficina',
oficina_id:null
};
return;
}
if(this.avisoSeleccionado.aplica_a==='departamento'){
this.oficinaSeleccionada=null;
this.avisoSeleccionado.afecta_a={
tipo:'departamentos',
ids:[]
};
return;
}
if(this.avisoSeleccionado.aplica_a==='usuarios'){
this.oficinaSeleccionada=null;
this.avisoSeleccionado.afecta_a={
tipo:'usuarios',
logins:[]
};
}
},
textoAplicaA(valor){
if(valor==='todos'){
return'Todos los usuarios';
}
if(valor==='oficina'){
return'Ubicaciones';
}
if(valor==='departamento'){
return'Departamentos';
}
if(valor==='usuarios'){
return'Usuarios específicos';
}
return valor||'No especificado';
},
nombreArchivo(archivo){
if(!archivo){
return'Sin archivo';
}
return String(archivo).split('/').pop();
},
esImagen(archivo){
if(!archivo){
return false;
}
return/\.(jpg|jpeg|png)$/i.test(archivo);
},
esPdf(archivo){
if(!archivo){
return false;
}
return/\.pdf$/i.test(archivo);
},
esVideo(archivo){
if(!archivo){
return false;
}
return/\.mp4$/i.test(archivo);
},
urlArchivo(archivo){
if(!archivo){
return'';
}
if(String(archivo).startsWith('http://')||String(archivo).startsWith('https://')||String(archivo).startsWith('/')){
return archivo;
}
return'/storage/'+archivo;
}
}));
});