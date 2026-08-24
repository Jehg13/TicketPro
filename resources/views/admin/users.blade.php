<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>TicketPro - Usuarios</title>
<link rel="icon" type="image/png" href="{{ asset('storage/images/logo.png') }}">
@vite(['resources/css/app.css','resources/js/app.js'])
<script src="https://unpkg.com/lucide@latest"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>
[x-cloak]{display:none!important}
</style>
</head>
<body class="bg-[#070b19] text-white font-sans min-h-screen antialiased">

<aside class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0a0f24] border-r border-slate-800/60 p-6 hidden md:flex flex-col justify-between">
<div>
<div class="flex items-center gap-2 mb-10">
<span class="text-3xl font-extrabold tracking-wide text-white">Ticket<span class="text-blue-500">Pro</span></span>
</div>

<div class="flex items-center gap-3 mb-8 p-2 rounded-xl bg-slate-900/40 border border-slate-800/50">
<img src="{{ auth()->user()->picture ? asset('storage/'.auth()->user()->picture) : asset('storage/profile-photos/user.png') }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-blue-500/30">
<div class="overflow-hidden">
<h4 class="text-sm font-semibold text-slate-200 truncate">{{ auth()->user()->name ?? 'Desconocido' }}</h4>
<p class="text-xs text-slate-400 truncate">{{ optional(auth()->user()->departamento)->nombre ?? 'Sin departamento' }}</p>
</div>
</div>

<nav class="space-y-2">
<a href="{{ route('tecnologias') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">
<i data-lucide="layout-dashboard" class="w-5 h-5"></i>
<span class="font-medium text-sm">Inicio</span>
</a>

<a href="{{ route('tickettecnologias') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">
<i data-lucide="ticket-check" class="w-5 h-5"></i>
<span class="font-medium text-sm">Tickets</span>
</a>

<a href="{{ route('cambiostecnologias') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">
<i data-lucide="git-compare-arrows" class="w-5 h-5"></i>
<span class="font-medium text-sm">Cambios</span>
</a>

<a href="{{ route('usuarios.tecnologias') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold shadow-lg shadow-blue-600/30">
<i data-lucide="users" class="w-5 h-5"></i>
<span class="text-sm">Usuarios</span>
</a>

<a href="{{ route('avisostecnologias') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">
<i data-lucide="megaphone" class="w-5 h-5"></i>
<span class="font-medium text-sm">Avisos</span>
</a>

<a href="{{ route('perfiltecnologias') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/50 hover:text-white transition">
<i data-lucide="circle-user-round" class="w-5 h-5"></i>
<span class="font-medium text-sm">Mi perfil</span>
</a>
</nav>
</div>

<form method="POST" action="{{ route('logout') }}">
@csrf
<button type="submit" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-red-500/10 hover:text-red-400 transition">
<i data-lucide="log-out" class="w-5 h-5"></i>
<span class="font-medium text-sm">Cerrar sesión</span>
</button>
</form>
</aside>

<main class="md:ml-64 min-h-screen p-6 md:p-8">
<div class="max-w-[1400px] mx-auto">

<header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
<div>
<h1 class="text-2xl md:text-3xl font-bold tracking-tight text-white">Usuarios</h1>
<p class="text-sm text-slate-400 mt-1">Consulta y administra la información de los usuarios del sistema</p>
</div>

<div class="relative" x-data="{perfilAbierto:false}">
<button type="button" @click="perfilAbierto=!perfilAbierto" class="relative flex items-center gap-3 bg-slate-900/80 border border-slate-800 rounded-full p-1.5 pr-4 hover:bg-slate-800 transition-all duration-200">
<img src="{{ auth()->user()->picture ? asset('storage/'.auth()->user()->picture) : asset('storage/profile-photos/user.png') }}" class="w-8 h-8 rounded-full object-cover">
<div class="text-left leading-tight hidden sm:block">
<p class="text-xs font-semibold text-white">{{ auth()->user()->name ?? 'Desconocido' }}</p>
<p class="text-[10px] text-blue-400 font-medium">{{ auth()->user()->role ?? 'Sin rol' }}</p>
</div>
<i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform" :class="{'rotate-180':perfilAbierto}"></i>
</button>

<div x-cloak x-show="perfilAbierto" @click.outside="perfilAbierto=false" x-transition class="absolute right-0 top-full mt-3 w-56 bg-[#0f1535] border border-[#1e295d] rounded-xl shadow-2xl overflow-hidden z-[99999]">
<a href="{{ route('perfiltecnologias') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-300 hover:bg-[#151b3b] hover:text-white transition">
<i data-lucide="circle-user-round" class="w-5 h-5"></i>
Perfil
</a>
<div class="border-t border-[#1e295d]"></div>
<form method="POST" action="{{ route('logout') }}">
@csrf
<button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-slate-300 hover:bg-red-500/10 hover:text-red-400 transition text-left">
<i data-lucide="log-out" class="w-5 h-5"></i>
Cerrar sesión
</button>
</form>
</div>
</div>
</header>

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

<div class="bg-[#0b1026]/90 border border-blue-900/40 rounded-2xl p-5 flex items-center justify-between shadow-xl">
<div>
<p class="text-xs text-slate-400 font-medium mb-1">Total de usuarios</p>
<h3 class="text-2xl font-bold text-white">{{ $totalUsuarios }}</h3>
</div>
<div class="w-11 h-11 bg-blue-500/10 text-blue-400 rounded-xl flex items-center justify-center border border-blue-500/20">
<i data-lucide="users" class="w-5 h-5"></i>
</div>
</div>

<div class="bg-[#0b1026]/90 border border-blue-900/40 rounded-2xl p-5 flex items-center justify-between shadow-xl">
<div>
<p class="text-xs text-slate-400 font-medium mb-1">Cuentas activas</p>
<h3 class="text-2xl font-bold text-white">{{ $usuariosActivos }}</h3>
</div>
<div class="w-11 h-11 bg-emerald-500/10 text-emerald-400 rounded-xl flex items-center justify-center border border-emerald-500/20">
<i data-lucide="user-check" class="w-5 h-5"></i>
</div>
</div>

<div class="bg-[#0b1026]/90 border border-blue-900/40 rounded-2xl p-5 flex items-center justify-between shadow-xl">
<div>
<p class="text-xs text-slate-400 font-medium mb-1">Cuentas inactivas</p>
<h3 class="text-2xl font-bold text-white">{{ $usuariosInactivos }}</h3>
</div>
<div class="w-11 h-11 bg-rose-500/10 text-rose-400 rounded-xl flex items-center justify-center border border-rose-500/20">
<i data-lucide="user-x" class="w-5 h-5"></i>
</div>
</div>

<div class="bg-[#0b1026]/90 border border-blue-900/40 rounded-2xl p-5 flex items-center justify-between shadow-xl">
<div>
<p class="text-xs text-slate-400 font-medium mb-1">Administradores</p>
<h3 class="text-2xl font-bold text-white">{{ $administradores }}</h3>
</div>
<div class="w-11 h-11 bg-indigo-500/10 text-indigo-400 rounded-xl flex items-center justify-center border border-indigo-500/20">
<i data-lucide="shield-check" class="w-5 h-5"></i>
</div>
</div>

</div>

<div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4 mb-6">

<div class="flex flex-wrap items-center gap-2">

<div class="flex flex-wrap items-center gap-1 bg-[#0b1026] p-1.5 rounded-xl border border-slate-800">

<a href="{{ route('usuarios.tecnologias', array_filter(['buscar'=>request('buscar')])) }}" class="px-4 py-2 rounded-lg text-xs font-semibold {{ !request('estado') && !request('departamento') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }} transition-all">
Todos
</a>

<a href="{{ route('usuarios.tecnologias', array_filter(['buscar'=>request('buscar'),'estado'=>'activos','departamento'=>request('departamento')])) }}" class="px-4 py-2 rounded-lg text-xs font-semibold {{ request('estado')==='activos' ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }} transition-all">
Activos
</a>

<a href="{{ route('usuarios.tecnologias', array_filter(['buscar'=>request('buscar'),'estado'=>'inactivos','departamento'=>request('departamento')])) }}" class="px-4 py-2 rounded-lg text-xs font-semibold {{ request('estado')==='inactivos' ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }} transition-all">
Inactivos
</a>

</div>

<div class="relative">
<select onchange="window.location.href=this.value" class="appearance-none bg-[#0b1026] border border-slate-800 text-slate-300 text-xs rounded-xl pl-4 pr-10 py-3 focus:outline-none focus:border-blue-500 min-w-[220px] cursor-pointer">

<option value="{{ route('usuarios.tecnologias', array_filter(['buscar'=>request('buscar'),'estado'=>request('estado')])) }}" {{ !request('departamento') ? 'selected' : '' }}>
Todos los departamentos
</option>

@foreach($departamentos as $departamento)
<option value="{{ route('usuarios.tecnologias', array_filter(['buscar'=>request('buscar'),'estado'=>request('estado'),'departamento'=>$departamento])) }}" {{ request('departamento') === $departamento ? 'selected' : '' }}>
{{ $departamento }}
</option>
@endforeach

</select>

<i data-lucide="chevron-down" class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500"></i>
</div>

</div>

<form method="GET" action="{{ route('usuarios.tecnologias') }}" class="relative w-full xl:w-72" x-data="{buscar:@js(request('buscar',''))}" x-ref="form">

@if(request('estado'))
<input type="hidden" name="estado" value="{{ request('estado') }}">
@endif

@if(request('departamento'))
<input type="hidden" name="departamento" value="{{ request('departamento') }}">
@endif

<i data-lucide="search" class="absolute left-3.5 top-3 w-4 h-4 text-slate-500"></i>

<input type="text" name="buscar" x-model="buscar" x-ref="input" value="{{ request('buscar') }}" placeholder="Buscar usuario..." class="w-full bg-[#0b1026] border border-slate-800 text-slate-200 text-xs rounded-xl pl-10 pr-10 py-2.5 focus:outline-none focus:border-blue-500">

<button type="button" x-show="buscar.length > 0" x-cloak @click="buscar='';$nextTick(()=>{$refs.form.submit()})" class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 flex items-center justify-center rounded-full text-slate-500 hover:text-white hover:bg-slate-700 transition">
<i data-lucide="x" class="w-3.5 h-3.5"></i>
</button>

</form>

</div>

<div x-data="usuariosPanel()" class="grid grid-cols-1 lg:grid-cols-3 gap-6">

<div class="lg:col-span-2 bg-[#070e27] border border-slate-800/80 rounded-2xl p-5 flex flex-col">

<div class="overflow-x-auto">
<table class="w-full text-left text-xs">
<thead>
<tr class="text-slate-400 border-b border-slate-800/80">
<th class="pb-3 font-semibold">Usuario</th>
<th class="pb-3 font-semibold">Rol</th>
<th class="pb-3 font-semibold text-center">Estado</th>
<th class="pb-3 font-semibold">Departamento</th>
<th class="pb-3 text-center">Acción</th>
</tr>
</thead>

<tbody class="divide-y divide-slate-800/50 text-slate-300">

@forelse($usuarios as $usuario)

<tr class="hover:bg-slate-800/20 transition" :class="usuarioSeleccionado&&usuarioSeleccionado.login===@js($usuario->login)?'bg-blue-500/5':''">

<td class="py-4">
<div class="flex items-center gap-3">
<img src="{{ $usuario->picture ? asset('storage/'.$usuario->picture) : asset('storage/profile-photos/user.png') }}" class="w-9 h-9 rounded-full object-cover ring-2 ring-blue-500/20">
<div class="min-w-0">
<p class="text-white font-medium truncate">{{ $usuario->name }}</p>
<p class="text-[10px] text-slate-500 truncate">{{ $usuario->email }}</p>
</div>
</div>
</td>

<td class="py-4">
@php $rol=strtolower(trim($usuario->role??'')); @endphp

@if($rol==='gerente ti')
<span class="px-2.5 py-1 rounded-full text-[10px] font-medium bg-purple-500/10 text-purple-400 border border-purple-500/20">Gerente TI</span>
@elseif(in_array($rol,['tecnico','técnico']))
<span class="px-2.5 py-1 rounded-full text-[10px] font-medium bg-blue-500/10 text-blue-400 border border-blue-500/20">Técnico</span>
@else
<span class="px-2.5 py-1 rounded-full text-[10px] font-medium bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">{{ $usuario->role ?: 'Usuario' }}</span>
@endif
</td>

<td class="py-4 text-center">
@if(strtoupper(trim((string)$usuario->active))==='Y')
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
<span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>Activa
</span>
@else
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-medium bg-rose-500/10 text-rose-400 border border-rose-500/20">
<span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>Inactiva
</span>
@endif
</td>

<td class="py-4 text-slate-400">{{ $usuario->departamento ?: 'Sin departamento' }}</td>

<td class="py-4">
<div class="flex items-center justify-center gap-2">

<button type="button" title="Ver usuario" @click.prevent="seleccionarUsuario(@js($usuario->login))" class="w-8 h-8 rounded-lg flex items-center justify-center bg-slate-800/70 text-slate-400 hover:bg-blue-500/10 hover:text-blue-400 border border-slate-700/50 transition-all">
<i data-lucide="eye" class="w-4 h-4 pointer-events-none"></i>
</button>

<button type="button" title="Editar usuario" @click.prevent="abrirModalEditar(@js($usuario->login))" class="w-8 h-8 rounded-lg flex items-center justify-center bg-slate-800/70 text-slate-400 hover:bg-amber-500/10 hover:text-amber-400 border border-slate-700/50 transition-all">
<i data-lucide="pencil" class="w-4 h-4 pointer-events-none"></i>
</button>

<button type="button" title="Eliminar usuario" @click.prevent="abrirModalEliminar(@js($usuario->login),@js($usuario->name))" class="w-8 h-8 rounded-lg flex items-center justify-center bg-slate-800/70 text-slate-400 hover:bg-rose-500/10 hover:text-rose-400 border border-slate-700/50 transition-all">
<i data-lucide="trash-2" class="w-4 h-4 pointer-events-none"></i>
</button>

</div>
</td>
</tr>

@empty

<tr>
<td colspan="5" class="py-12 text-center text-slate-500">No se encontraron usuarios.</td>
</tr>

@endforelse

</tbody>
</table>
</div>

<div class="mt-6 pt-5 border-t border-slate-800/80 flex flex-col sm:flex-row justify-between items-center gap-4">

<span class="text-xs text-slate-400">
Mostrando <span class="text-white font-medium">{{ $usuarios->firstItem() ?? 0 }}</span>
a <span class="text-white font-medium">{{ $usuarios->lastItem() ?? 0 }}</span>
de <span class="text-white font-medium">{{ $usuarios->total() }}</span> usuarios
</span>

<div class="flex items-center gap-1">

@if($usuarios->onFirstPage())
<button type="button" disabled class="w-8 h-8 bg-slate-900 text-slate-600 rounded-lg flex items-center justify-center">
<i data-lucide="chevron-left" class="w-4 h-4"></i>
</button>
@else
<a href="{{ $usuarios->previousPageUrl() }}" class="w-8 h-8 bg-slate-800 text-slate-400 rounded-lg hover:bg-slate-700 flex items-center justify-center">
<i data-lucide="chevron-left" class="w-4 h-4"></i>
</a>
@endif

@foreach($usuarios->getUrlRange(max(1,$usuarios->currentPage()-2),min($usuarios->lastPage(),$usuarios->currentPage()+2)) as $page=>$url)
<a href="{{ $url }}" class="w-8 h-8 rounded-lg text-xs flex items-center justify-center {{ $page==$usuarios->currentPage()?'bg-blue-600 text-white font-bold':'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
{{ $page }}
</a>
@endforeach

@if($usuarios->hasMorePages())
<a href="{{ $usuarios->nextPageUrl() }}" class="w-8 h-8 bg-slate-800 text-slate-400 rounded-lg hover:bg-slate-700 flex items-center justify-center">
<i data-lucide="chevron-right" class="w-4 h-4"></i>
</a>
@else
<button type="button" disabled class="w-8 h-8 bg-slate-900 text-slate-600 rounded-lg flex items-center justify-center">
<i data-lucide="chevron-right" class="w-4 h-4"></i>
</button>
@endif

</div>
</div>
</div>

<div class="bg-[#0b1026]/90 border border-blue-900/40 rounded-2xl p-5 shadow-xl backdrop-blur-md">

<template x-if="cargando">
<div class="h-full min-h-[500px] flex flex-col items-center justify-center text-center">
<div class="w-12 h-12 rounded-full border-4 border-slate-700 border-t-blue-500 animate-spin mb-4"></div>
<h3 class="text-sm font-semibold text-white">Cargando usuario...</h3>
<p class="text-xs text-slate-500 mt-2">Obteniendo información.</p>
</div>
</template>

<template x-if="!usuarioSeleccionado&&!cargando">
<div class="h-full min-h-[500px] flex flex-col items-center justify-center text-center">
<div class="w-16 h-16 rounded-2xl bg-slate-800/50 flex items-center justify-center mb-4">
<i data-lucide="user-search" class="w-8 h-8 text-slate-500"></i>
</div>
<h3 class="text-sm font-semibold text-white">Selecciona un usuario</h3>
<p class="text-xs text-slate-500 mt-2 max-w-xs">Selecciona un usuario de la lista para consultar su información.</p>
</div>
</template>

<template x-if="usuarioSeleccionado&&!cargando">
<div class="space-y-6">

<div class="flex items-start justify-between pb-4 border-b border-slate-800">
<div>
<h2 class="text-sm font-semibold text-white">Información del usuario</h2>
<p class="text-[10px] text-slate-500 mt-1">Detalle completo de la cuenta</p>
</div>

<span x-show="usuarioSeleccionado.active" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
<span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>Activa
</span>

<span x-show="!usuarioSeleccionado.active" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-medium bg-rose-500/10 text-rose-400 border border-rose-500/20">
<span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>Inactiva
</span>
</div>

<div class="flex flex-col items-center text-center">
<div class="relative">
<img :src="usuarioSeleccionado.picture" class="w-24 h-24 rounded-full object-cover ring-4 ring-blue-500/10 border border-slate-700">
<div class="absolute bottom-1 right-1 w-6 h-6 rounded-full border-4 border-[#0b1026]" :class="usuarioSeleccionado.active?'bg-emerald-500':'bg-rose-500'"></div>
</div>

<h3 class="mt-4 text-lg font-bold text-white" x-text="usuarioSeleccionado.name"></h3>
<p class="text-xs text-slate-400" x-text="usuarioSeleccionado.email"></p>
<span class="mt-2 px-3 py-1 rounded-full text-[10px] font-medium bg-indigo-500/10 text-indigo-400 border border-indigo-500/20" x-text="usuarioSeleccionado.role"></span>
</div>

<div>
<p class="text-slate-400 text-[10px] mb-3">Información general</p>

<div class="space-y-2">

<div class="flex items-center gap-3 bg-slate-800/40 rounded-xl p-3">
<div class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-400 flex items-center justify-center">
<i data-lucide="badge" class="w-4 h-4"></i>
</div>
<div>
<p class="text-[10px] text-slate-500">Login</p>
<p class="text-xs text-white font-medium" x-text="usuarioSeleccionado.login"></p>
</div>
</div>

<div class="flex items-center gap-3 bg-slate-800/40 rounded-xl p-3">
<div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-400 flex items-center justify-center">
<i data-lucide="contact" class="w-4 h-4"></i>
</div>
<div>
<p class="text-[10px] text-slate-500">Número de empleado</p>
<p class="text-xs text-white font-medium" x-text="usuarioSeleccionado.numero_empleado||'Sin número de empleado'"></p>
</div>
</div>

<div class="flex items-center gap-3 bg-slate-800/40 rounded-xl p-3">
<div class="w-8 h-8 rounded-lg bg-cyan-500/10 text-cyan-400 flex items-center justify-center">
<i data-lucide="building" class="w-4 h-4"></i>
</div>
<div>
<p class="text-[10px] text-slate-500">Empresa</p>
<p class="text-xs text-white font-medium" x-text="usuarioSeleccionado.empresa||'Sin empresa'"></p>
</div>
</div>

<div class="flex items-center gap-3 bg-slate-800/40 rounded-xl p-3">
<div class="w-8 h-8 rounded-lg bg-purple-500/10 text-purple-400 flex items-center justify-center">
<i data-lucide="map-pin" class="w-4 h-4"></i>
</div>
<div>
<p class="text-[10px] text-slate-500">Oficina</p>
<p class="text-xs text-white font-medium" x-text="usuarioSeleccionado.oficina||'Sin oficina'"></p>
</div>
</div>

<div class="flex items-center gap-3 bg-slate-800/40 rounded-xl p-3">
<div class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-400 flex items-center justify-center">
<i data-lucide="building-2" class="w-4 h-4"></i>
</div>
<div>
<p class="text-[10px] text-slate-500">Departamento</p>
<p class="text-xs text-white font-medium" x-text="usuarioSeleccionado.departamento||'Sin departamento'"></p>
</div>
</div>

<div class="flex items-center gap-3 bg-slate-800/40 rounded-xl p-3">
<div class="w-8 h-8 rounded-lg bg-purple-500/10 text-purple-400 flex items-center justify-center">
<i data-lucide="shield" class="w-4 h-4"></i>
</div>
<div>
<p class="text-[10px] text-slate-500">Rol</p>
<p class="text-xs text-white font-medium" x-text="usuarioSeleccionado.role||'Usuario'"></p>
</div>
</div>

</div>
</div>

<div>
<p class="text-slate-400 text-[10px] mb-3">Información de contacto</p>

<div class="bg-slate-900/60 border border-slate-800 rounded-xl p-3 space-y-3">

<div class="flex items-center gap-3">
<i data-lucide="mail" class="w-4 h-4 text-slate-500"></i>
<div>
<p class="text-[9px] text-slate-500">Correo electrónico</p>
<p class="text-xs text-slate-300" x-text="usuarioSeleccionado.email||'Sin correo'"></p>
</div>
</div>

<div class="flex items-center gap-3">
<i data-lucide="phone" class="w-4 h-4 text-slate-500"></i>
<div>
<p class="text-[9px] text-slate-500">Teléfono</p>
<p class="text-xs text-slate-300" x-text="usuarioSeleccionado.phone||'Sin teléfono'"></p>
</div>
</div>

</div>
</div>

<div>
<p class="text-slate-400 text-[10px] mb-3">Permisos</p>

<div class="flex flex-wrap gap-2">

<template x-if="usuarioSeleccionado.priv_admin">
<span class="px-2.5 py-1 rounded-lg bg-blue-500/10 border border-blue-500/20 text-[10px] text-blue-400">Administrador</span>
</template>

<span class="px-2.5 py-1 rounded-lg bg-blue-500/10 border border-blue-500/20 text-[10px] text-blue-400">Tickets</span>
<span class="px-2.5 py-1 rounded-lg bg-blue-500/10 border border-blue-500/20 text-[10px] text-blue-400">Comentarios</span>

</div>
</div>

</div>
</template>

</div>

<template x-teleport="body">
<div x-cloak x-show="modalEditar" @keydown.escape.window="modalEditar&&cerrarModalEditar()" x-transition.opacity class="fixed inset-0 z-[999999] flex items-center justify-center p-4">

<div class="absolute inset-0 bg-black/70 backdrop-blur-sm" @click="cerrarModalEditar()"></div>

<div x-show="modalEditar" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" @click.stop class="relative w-full max-w-4xl max-h-[92vh] overflow-hidden bg-[#0b1026] border border-slate-800 rounded-2xl shadow-2xl flex flex-col">

<div class="p-6 border-b border-slate-800 shrink-0">

<div class="flex items-center justify-between">

<div class="flex items-center gap-4">

<div class="w-11 h-11 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center">
<i data-lucide="pencil" class="w-5 h-5 text-amber-400"></i>
</div>

<div>
<h3 class="text-lg font-bold text-white">Editar usuario</h3>
<p class="text-xs text-slate-500 mt-1">Modifica la información de la cuenta.</p>
</div>

</div>

<button type="button" @click="cerrarModalEditar()" :disabled="editarCargando" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-500 hover:text-white hover:bg-slate-800 transition">
<i data-lucide="x" class="w-5 h-5"></i>
</button>

</div>
</div>

<form @submit.prevent="solicitarPasswordEdicion()" class="flex flex-col min-h-0">

<div class="p-6 space-y-5 overflow-y-auto">

<div x-show="errorEditar" class="flex items-center gap-3 p-3 rounded-xl bg-rose-500/10 border border-rose-500/20">
<i data-lucide="circle-alert" class="w-4 h-4 text-rose-400 shrink-0"></i>
<p class="text-xs text-rose-400" x-text="errorEditar"></p>
</div>

<div>
<p class="text-xs font-semibold text-white mb-3">Información personal</p>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

<div>
<label class="block text-xs font-medium text-slate-400 mb-2">Nombre</label>
<input type="text" x-model="editarForm.name" required class="w-full bg-slate-900/70 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500">
</div>

<div>
<label class="block text-xs font-medium text-slate-400 mb-2">Número de empleado</label>
<input type="text" x-model="editarForm.numero_empleado" placeholder="Número de empleado" class="w-full bg-slate-900/70 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-blue-500">
</div>

<div>
<label class="block text-xs font-medium text-slate-400 mb-2">Login</label>
<input type="text" :value="editarLogin" disabled class="w-full bg-slate-950/70 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-500 cursor-not-allowed">
</div>

<div>
<label class="block text-xs font-medium text-slate-400 mb-2">Correo electrónico</label>
<input type="email" x-model="editarForm.email" required class="w-full bg-slate-900/70 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500">
</div>

<div>
<label class="block text-xs font-medium text-slate-400 mb-2">Teléfono</label>
<input type="text" x-model="editarForm.phone" class="w-full bg-slate-900/70 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500">
</div>

<div>
<label class="block text-xs font-medium text-slate-400 mb-2">Contraseña</label>
<div class="relative">
<i data-lucide="lock" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500"></i>
<input type="password" x-model="editarForm.password" autocomplete="new-password" placeholder="Nueva contraseña" class="w-full bg-slate-900/70 border border-slate-700 rounded-xl pl-10 pr-4 py-3 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-blue-500">
</div>
<p class="text-[10px] text-slate-500 mt-1">Déjala vacía si no deseas cambiarla.</p>
</div>

</div>
</div>

<div>
<p class="text-xs font-semibold text-white mb-3">Ubicación</p>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

<div>
<label class="block text-xs font-medium text-slate-400 mb-2">Ubicación / Oficina</label>

<div class="relative">

<i data-lucide="map-pin" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-purple-500"></i>

<select x-model="editarForm.oficina_id" @change="actualizarEmpresaPorOficina()" required :disabled="cargandoOficinas" class="w-full bg-slate-900/70 border border-slate-700 rounded-xl pl-10 pr-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500 disabled:opacity-50">

<option value="">Selecciona una ubicación</option>

<template x-for="oficina in oficinas" :key="oficina.id">
<option :value="String(oficina.id)" x-text="oficina.nombre"></option>
</template>

</select>
</div>

<p x-show="cargandoOficinas" class="text-[10px] text-slate-500 mt-1">Cargando ubicaciones...</p>
</div>

<div>
<label class="block text-xs font-medium text-slate-400 mb-2">Departamento</label>

<input type="text" x-model="editarForm.departamento" placeholder="Escribe el departamento del usuario" class="w-full bg-slate-900/70 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-blue-500">

<p class="text-[10px] text-slate-500 mt-1">El departamento se guarda directamente como texto.</p>
</div>

</div>
</div>

<div>
<p class="text-xs font-semibold text-white mb-3">Configuración de cuenta</p>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">

<div>
<label class="block text-xs font-medium text-slate-400 mb-2">Role</label>
<input type="text" x-model="editarForm.role" required placeholder="Escribe el rol del usuario" class="w-full bg-slate-900/70 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-blue-500">
</div>

<div>
<label class="block text-xs font-medium text-slate-400 mb-2">Estado</label>
<select x-model="editarForm.active" required class="w-full bg-slate-900/70 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500">
<option value="Y">Activa</option>
<option value="N">Inactiva</option>
</select>
</div>

<div>
<label class="block text-xs font-medium text-slate-400 mb-2">Permiso administrador</label>
<select x-model="editarForm.priv_admin" required class="w-full bg-slate-900/70 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500">
<option value="Y">Sí</option>
<option value="N">No</option>
</select>
</div>

</div>
</div>

</div>

<div class="px-6 py-4 bg-slate-950/50 border-t border-slate-800 flex items-center justify-end gap-3 shrink-0">

<button type="button" @click="cerrarModalEditar()" :disabled="editarCargando" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-300 bg-slate-800 hover:bg-slate-700 disabled:opacity-50 transition">
Cancelar
</button>

<button type="submit" :disabled="editarCargando" class="min-w-[130px] px-4 py-2.5 rounded-xl text-xs font-semibold text-white bg-blue-600 hover:bg-blue-500 disabled:opacity-50 transition flex items-center justify-center gap-2">

<template x-if="!editarCargando">
<span class="flex items-center gap-2">
<i data-lucide="save" class="w-4 h-4"></i>Guardar
</span>
</template>

<template x-if="editarCargando">
<span class="flex items-center gap-2">
<span class="w-4 h-4 rounded-full border-2 border-white/30 border-t-white animate-spin"></span>Guardando...
</span>
</template>

</button>

</div>
</form>
</div>
</div>
</template>

<template x-teleport="body">
<div x-cloak x-show="modalConfirmarEdicion" @keydown.escape.window="modalConfirmarEdicion&&cerrarConfirmacionEdicion()" x-transition.opacity class="fixed inset-0 z-[1000001] flex items-center justify-center p-4">

<div class="absolute inset-0 bg-black/75 backdrop-blur-sm" @click="cerrarConfirmacionEdicion()"></div>

<div x-show="modalConfirmarEdicion" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" @click.stop class="relative w-full max-w-md bg-[#0b1026] border border-slate-800 rounded-2xl shadow-2xl overflow-hidden">

<div class="p-6">

<div class="flex items-start gap-4">

<div class="w-12 h-12 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center shrink-0">
<i data-lucide="shield-check" class="w-6 h-6 text-blue-400"></i>
</div>

<div>
<h3 class="text-lg font-bold text-white">Confirmar cambios</h3>
<p class="text-sm text-slate-400 mt-1">Por seguridad, ingresa tu contraseña para aplicar los cambios al usuario.</p>
</div>

</div>

<div class="mt-5 p-4 rounded-xl bg-slate-900/70 border border-slate-800">
<p class="text-[10px] text-slate-500 uppercase tracking-wider">Usuario que será modificado</p>
<p class="mt-1 text-sm font-semibold text-white truncate" x-text="editarForm.name"></p>
<p class="text-xs text-slate-500 mt-1" x-text="editarLogin"></p>
</div>

<div class="mt-5">

<label class="block text-xs font-medium text-slate-400 mb-2">Tu contraseña</label>

<div class="relative">
<i data-lucide="lock" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500"></i>

<input type="password" x-model="passwordConfirmarEdicion" @keydown.enter.prevent="actualizarUsuario()" placeholder="Ingresa tu contraseña" autocomplete="current-password" class="w-full bg-slate-900/70 border border-slate-700 rounded-xl pl-10 pr-4 py-3 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-blue-500">
</div>

<template x-if="errorConfirmarEdicion">
<div class="mt-3 flex items-center gap-2 text-rose-400">
<i data-lucide="circle-alert" class="w-4 h-4"></i>
<p class="text-xs" x-text="errorConfirmarEdicion"></p>
</div>
</template>

</div>
</div>

<div class="px-6 py-4 bg-slate-950/50 border-t border-slate-800 flex items-center justify-end gap-3">

<button type="button" @click="cerrarConfirmacionEdicion()" :disabled="editarCargando" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-300 bg-slate-800 hover:bg-slate-700 disabled:opacity-50 transition">
Atrás
</button>

<button type="button" @click="actualizarUsuario()" :disabled="editarCargando||!passwordConfirmarEdicion" class="min-w-[125px] px-4 py-2.5 rounded-xl text-xs font-semibold text-white bg-blue-600 hover:bg-blue-500 disabled:opacity-50 transition flex items-center justify-center gap-2">

<template x-if="!editarCargando">
<span class="flex items-center gap-2">
<i data-lucide="check" class="w-4 h-4"></i>Confirmar
</span>
</template>

<template x-if="editarCargando">
<span class="flex items-center gap-2">
<span class="w-4 h-4 rounded-full border-2 border-white/30 border-t-white animate-spin"></span>Verificando...
</span>
</template>

</button>
</div>
</div>
</div>
</template>

<template x-teleport="body">
<div x-cloak x-show="modalEliminar" @keydown.escape.window="modalEliminar&&cerrarModalEliminar()" x-transition.opacity class="fixed inset-0 z-[1000000] flex items-center justify-center p-4">

<div class="absolute inset-0 bg-black/70 backdrop-blur-sm" @click="cerrarModalEliminar()"></div>

<div x-show="modalEliminar" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" @click.stop class="relative w-full max-w-md bg-[#0b1026] border border-slate-800 rounded-2xl shadow-2xl overflow-hidden">

<template x-if="!mostrarPasswordEliminar">

<div>

<div class="p-6">

<div class="flex items-start gap-4">

<div class="w-12 h-12 rounded-xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center shrink-0">
<i data-lucide="triangle-alert" class="w-6 h-6 text-rose-400"></i>
</div>

<div>
<h3 class="text-lg font-bold text-white">Eliminar usuario</h3>
<p class="text-sm text-slate-400 mt-1">¿Estás seguro de que deseas eliminar este usuario?</p>
</div>

</div>

<div class="mt-5 p-4 rounded-xl bg-slate-900/70 border border-slate-800">
<p class="text-[10px] text-slate-500 uppercase tracking-wider">Usuario seleccionado</p>
<p class="mt-1 text-sm font-semibold text-white truncate" x-text="usuarioEliminarNombre"></p>
<p class="text-xs text-slate-500 mt-1" x-text="usuarioEliminarLogin"></p>
</div>

<div class="mt-4 flex gap-3 p-3 rounded-xl bg-amber-500/5 border border-amber-500/10">
<i data-lucide="info" class="w-4 h-4 text-amber-400 shrink-0 mt-0.5"></i>
<p class="text-xs text-slate-400 leading-relaxed">Esta acción eliminará la cuenta del usuario. <span class="text-amber-400 font-medium">Esta acción no se puede deshacer.</span></p>
</div>

</div>

<div class="px-6 py-4 bg-slate-950/50 border-t border-slate-800 flex items-center justify-end gap-3">

<button type="button" @click="cerrarModalEliminar()" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-300 bg-slate-800 hover:bg-slate-700 transition">
Cancelar
</button>

<button type="button" @click="mostrarConfirmacionPassword()" class="min-w-[110px] px-4 py-2.5 rounded-xl text-xs font-semibold text-white bg-rose-600 hover:bg-rose-500 transition flex items-center justify-center gap-2">
<i data-lucide="arrow-right" class="w-4 h-4"></i>Continuar
</button>

</div>
</div>
</template>

<template x-if="mostrarPasswordEliminar">

<div>

<div class="p-6">

<div class="flex items-start gap-4">

<div class="w-12 h-12 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center shrink-0">
<i data-lucide="shield-check" class="w-6 h-6 text-blue-400"></i>
</div>

<div>
<h3 class="text-lg font-bold text-white">Confirmar identidad</h3>
<p class="text-sm text-slate-400 mt-1">Ingresa tu contraseña para confirmar la eliminación.</p>
</div>

</div>

<div class="mt-5 p-4 rounded-xl bg-slate-900/70 border border-slate-800">
<p class="text-[10px] text-slate-500 uppercase tracking-wider">Usuario que será eliminado</p>
<p class="mt-1 text-sm font-semibold text-white truncate" x-text="usuarioEliminarNombre"></p>
<p class="text-xs text-slate-500 mt-1" x-text="usuarioEliminarLogin"></p>
</div>

<div class="mt-5">

<label class="block text-xs font-medium text-slate-400 mb-2">Tu contraseña</label>

<div class="relative">
<i data-lucide="lock" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500"></i>

<input type="password" x-model="passwordEliminar" @keydown.enter.prevent="eliminarUsuario()" placeholder="Ingresa tu contraseña" autocomplete="current-password" class="w-full bg-slate-900/70 border border-slate-700 rounded-xl pl-10 pr-4 py-3 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-blue-500">
</div>

<template x-if="errorPassword">
<div class="mt-3 flex items-center gap-2 text-rose-400">
<i data-lucide="circle-alert" class="w-4 h-4"></i>
<p class="text-xs" x-text="errorPassword"></p>
</div>
</template>

</div>
</div>

<div class="px-6 py-4 bg-slate-950/50 border-t border-slate-800 flex items-center justify-end gap-3">

<button type="button" @click="volverConfirmacionEliminar()" :disabled="eliminarCargando" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-300 bg-slate-800 hover:bg-slate-700 disabled:opacity-50 transition">
Atrás
</button>

<button type="button" @click="eliminarUsuario()" :disabled="eliminarCargando||!passwordEliminar" class="min-w-[125px] px-4 py-2.5 rounded-xl text-xs font-semibold text-white bg-rose-600 hover:bg-rose-500 disabled:opacity-50 transition flex items-center justify-center gap-2">

<template x-if="!eliminarCargando">
<span class="flex items-center gap-2">
<i data-lucide="trash-2" class="w-4 h-4"></i>Eliminar
</span>
</template>

<template x-if="eliminarCargando">
<span class="flex items-center gap-2">
<span class="w-4 h-4 rounded-full border-2 border-white/30 border-t-white animate-spin"></span>Verificando...
</span>
</template>

</button>
</div>

</div>
</template>

</div>
</div>
</template>

</div>
</div>
</main>

<script>
document.addEventListener('alpine:init',()=>{

Alpine.data('usuariosPanel',()=>({

usuarioSeleccionado:null,
cargando:false,

modalEditar:false,
modalConfirmarEdicion:false,
editarLogin:null,
editarCargando:false,
errorEditar:'',
passwordConfirmarEdicion:'',
errorConfirmarEdicion:'',

oficinas:[],
cargandoOficinas:false,

editarForm:{
name:'',
numero_empleado:'',
email:'',
phone:'',
password:'',
role:'Usuario',
active:'Y',
priv_admin:'N',
empresa_id:'',
empresa_nombre:'',
oficina_id:'',
oficina_nombre:'',
departamento:''
},

modalEliminar:false,
usuarioEliminarLogin:null,
usuarioEliminarNombre:'',
eliminarCargando:false,
mostrarPasswordEliminar:false,
passwordEliminar:'',
errorPassword:'',

seleccionarUsuario(login){

if(!login)return;

this.cargando=true;
this.usuarioSeleccionado=null;

fetch('/tecnologias/usuarios/'+encodeURIComponent(login),{
method:'GET',
headers:{
'Accept':'application/json',
'X-Requested-With':'XMLHttpRequest'
}
})
.then(async response=>{

const data=await response.json();

if(!response.ok){
throw new Error(data.message||`Error HTTP ${response.status}`);
}

return data;
})
.then(data=>{

if(!data.success){
throw new Error(data.message||'No se pudo obtener el usuario.');
}

this.usuarioSeleccionado=data.usuario;

})
.catch(error=>{

console.error('Error obteniendo usuario:',error);

this.usuarioSeleccionado=null;

alert(error.message||'Ocurrió un error al obtener la información del usuario.');

})
.finally(()=>{

this.cargando=false;

this.$nextTick(()=>{

if(window.lucide){
lucide.createIcons();
}

});

});

},

abrirModalEditar(login){

if(!login)return;

this.editarCargando=false;
this.errorEditar='';
this.errorConfirmarEdicion='';
this.passwordConfirmarEdicion='';
this.editarLogin=login;
this.modalEditar=true;
this.modalConfirmarEdicion=false;
this.oficinas=[];
this.cargandoOficinas=true;
this.editarForm.password='';

fetch('/tecnologias/usuarios/'+encodeURIComponent(login),{
method:'GET',
headers:{
'Accept':'application/json',
'X-Requested-With':'XMLHttpRequest'
}
})
.then(async response=>{

const data=await response.json();

if(!response.ok){
throw new Error(data.message||`Error HTTP ${response.status}`);
}

return data;

})
.then(data=>{

if(!data.success){
throw new Error(data.message||'No se pudo obtener el usuario.');
}

const usuario=data.usuario;

this.oficinas=Array.isArray(data.oficinas)?data.oficinas:[];

this.editarForm={
name:usuario.name??'',
numero_empleado:usuario.numero_empleado??'',
email:usuario.email??'',
phone:usuario.phone??'',
password:'',
role:usuario.role??'Usuario',
active:String(usuario.active??'').toUpperCase()==='Y'?'Y':'N',
priv_admin:String(usuario.priv_admin??'').toUpperCase()==='Y'?'Y':'N',
empresa_id:usuario.empresa_id?String(usuario.empresa_id):'',
empresa_nombre:usuario.empresa??'',
oficina_id:usuario.oficina_id?String(usuario.oficina_id):'',
oficina_nombre:usuario.oficina??'',
departamento:usuario.departamento??''
};

this.actualizarEmpresaPorOficina();

this.cargandoOficinas=false;

})
.catch(error=>{

console.error('Error cargando usuario:',error);

this.cargandoOficinas=false;

this.errorEditar=error.message||'No se pudo cargar la información del usuario.';

})
.finally(()=>{

this.$nextTick(()=>{

if(window.lucide){
lucide.createIcons();
}

});

});

},

actualizarEmpresaPorOficina(){

const oficinaId=String(this.editarForm.oficina_id||'');

if(!oficinaId){

this.editarForm.empresa_id='';
this.editarForm.empresa_nombre='';
this.editarForm.oficina_nombre='';

return;
}

const oficina=this.oficinas.find(item=>String(item.id)===oficinaId);

if(!oficina){

this.editarForm.empresa_id='';
this.editarForm.empresa_nombre='';
this.editarForm.oficina_nombre='';

return;
}

this.editarForm.oficina_nombre=oficina.nombre??'';
this.editarForm.empresa_id=oficina.empresa_id?String(oficina.empresa_id):'';
this.editarForm.empresa_nombre=oficina.empresa??oficina.empresa_nombre??'';

},

solicitarPasswordEdicion(){

if(!this.editarLogin){

this.errorEditar='No se encontró el login del usuario.';

return;
}

if(!this.editarForm.oficina_id){

this.errorEditar='Debes seleccionar una ubicación.';

return;
}

const oficina=this.oficinas.find(item=>String(item.id)===String(this.editarForm.oficina_id));

if(!oficina){

this.errorEditar='La ubicación seleccionada no es válida.';

return;
}

this.actualizarEmpresaPorOficina();

if(!this.editarForm.empresa_id){

this.errorEditar='La ubicación seleccionada no tiene una empresa asignada.';

return;
}

this.errorEditar='';
this.errorConfirmarEdicion='';
this.passwordConfirmarEdicion='';
this.modalConfirmarEdicion=true;

this.$nextTick(()=>{

if(window.lucide){
lucide.createIcons();
}

const input=document.querySelector('[x-model="passwordConfirmarEdicion"]');

if(input){
input.focus();
}

});

},

cerrarConfirmacionEdicion(){

if(this.editarCargando)return;

this.modalConfirmarEdicion=false;
this.passwordConfirmarEdicion='';
this.errorConfirmarEdicion='';

},

actualizarUsuario(){

if(!this.editarLogin)return;

if(!this.passwordConfirmarEdicion){

this.errorConfirmarEdicion='Debes ingresar tu contraseña.';

return;
}

if(!this.editarForm.oficina_id){

this.modalConfirmarEdicion=false;
this.errorEditar='Debes seleccionar una ubicación.';

return;
}

this.actualizarEmpresaPorOficina();

if(!this.editarForm.empresa_id){

this.modalConfirmarEdicion=false;
this.errorEditar='La ubicación seleccionada no tiene una empresa asignada.';

return;
}

this.editarCargando=true;
this.errorConfirmarEdicion='';
this.errorEditar='';

const login=this.editarLogin;

const csrfToken=document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const payload={
name:this.editarForm.name,
numero_empleado:this.editarForm.numero_empleado,
email:this.editarForm.email,
phone:this.editarForm.phone,
password:this.editarForm.password||null,
password_actual:this.passwordConfirmarEdicion,
role:this.editarForm.role,
active:this.editarForm.active,
priv_admin:this.editarForm.priv_admin,
empresa_id:this.editarForm.empresa_id,
oficina_id:this.editarForm.oficina_id,
departamento:(this.editarForm.departamento||'').trim()
};

fetch('/tecnologias/usuarios/'+encodeURIComponent(login),{
method:'PUT',
headers:{
'Accept':'application/json',
'Content-Type':'application/json',
'X-Requested-With':'XMLHttpRequest',
'X-CSRF-TOKEN':csrfToken
},
body:JSON.stringify(payload)
})
.then(async response=>{

const text=await response.text();

let data={};

try{

data=JSON.parse(text);

}catch(e){

throw new Error(response.status===500?'Error interno del servidor. Revisa el log de Laravel.':`Error HTTP ${response.status}`);

}

if(!response.ok){

if(data.errors){

const primerError=Object.values(data.errors).flat()[0];

throw new Error(primerError||data.message||`Error HTTP ${response.status}`);

}

throw new Error(data.message||`Error HTTP ${response.status}`);

}

return data;

})
.then(data=>{

if(!data.success){

throw new Error(data.message||'No se pudo actualizar el usuario.');

}

const usuarioActualizado=data.usuario||null;

if(usuarioActualizado&&this.usuarioSeleccionado&&this.usuarioSeleccionado.login===login){

this.usuarioSeleccionado=usuarioActualizado;

}

this.modalConfirmarEdicion=false;
this.modalEditar=false;
this.editarLogin=null;
this.passwordConfirmarEdicion='';
this.errorConfirmarEdicion='';
this.editarForm.password='';

window.location.reload();

})
.catch(error=>{

console.error('Error actualizando usuario:',error);

this.errorConfirmarEdicion=error.message||'No se pudo actualizar el usuario.';

})
.finally(()=>{

this.editarCargando=false;

this.$nextTick(()=>{

if(window.lucide){
lucide.createIcons();
}

});

});

},

cargarOficinas(){

this.cargandoOficinas=true;
this.oficinas=[];

fetch('/tecnologias/oficinas',{
method:'GET',
headers:{
'Accept':'application/json',
'X-Requested-With':'XMLHttpRequest'
}
})
.then(async response=>{

const data=await response.json();

if(!response.ok||!data.success){

throw new Error(data.message||'No se pudieron cargar las ubicaciones.');

}

return data;

})
.then(data=>{

this.oficinas=Array.isArray(data.oficinas)?data.oficinas:[];

})
.catch(error=>{

console.error('Error cargando oficinas:',error);

this.errorEditar=error.message||'No se pudieron cargar las ubicaciones.';

})
.finally(()=>{

this.cargandoOficinas=false;

});

},

cerrarModalEditar(){

if(this.editarCargando)return;

this.modalEditar=false;
this.modalConfirmarEdicion=false;
this.editarLogin=null;
this.errorEditar='';
this.errorConfirmarEdicion='';
this.passwordConfirmarEdicion='';
this.oficinas=[];
this.cargandoOficinas=false;

this.editarForm={
name:'',
numero_empleado:'',
email:'',
phone:'',
password:'',
role:'Usuario',
active:'Y',
priv_admin:'N',
empresa_id:'',
empresa_nombre:'',
oficina_id:'',
oficina_nombre:'',
departamento:''
};

},

abrirModalEliminar(login,nombre){

if(!login)return;

this.usuarioEliminarLogin=login;
this.usuarioEliminarNombre=nombre;
this.modalEliminar=true;
this.mostrarPasswordEliminar=false;
this.passwordEliminar='';
this.errorPassword='';

this.$nextTick(()=>{

if(window.lucide){
lucide.createIcons();
}

});

},

cerrarModalEliminar(){

if(this.eliminarCargando)return;

this.modalEliminar=false;
this.usuarioEliminarLogin=null;
this.usuarioEliminarNombre='';
this.passwordEliminar='';
this.errorPassword='';
this.mostrarPasswordEliminar=false;

},

mostrarConfirmacionPassword(){

this.mostrarPasswordEliminar=true;
this.passwordEliminar='';
this.errorPassword='';

this.$nextTick(()=>{

if(window.lucide){
lucide.createIcons();
}

const input=document.querySelector('[x-model="passwordEliminar"]');

if(input){
input.focus();
}

});

},

volverConfirmacionEliminar(){

if(this.eliminarCargando)return;

this.mostrarPasswordEliminar=false;
this.passwordEliminar='';
this.errorPassword='';

},

eliminarUsuario(){

if(!this.usuarioEliminarLogin)return;

if(!this.passwordEliminar){

this.errorPassword='Debes ingresar tu contraseña.';

return;
}

this.eliminarCargando=true;
this.errorPassword='';

const login=this.usuarioEliminarLogin;

const csrfToken=document.querySelector('meta[name="csrf-token"]').getAttribute('content');

fetch('/tecnologias/usuarios/'+encodeURIComponent(login),{
method:'DELETE',
headers:{
'Accept':'application/json',
'Content-Type':'application/json',
'X-Requested-With':'XMLHttpRequest',
'X-CSRF-TOKEN':csrfToken
},
body:JSON.stringify({
password:this.passwordEliminar
})
})
.then(async response=>{

const text=await response.text();

let data={};

try{

data=JSON.parse(text);

}catch(e){

throw new Error(response.status===500?'Error interno del servidor. Revisa el log de Laravel.':`Error HTTP ${response.status}`);

}

if(!response.ok){

throw new Error(data.message||`Error HTTP ${response.status}`);

}

return data;

})
.then(data=>{

if(!data.success){

throw new Error(data.message||'No se pudo eliminar el usuario.');

}

this.modalEliminar=false;
this.mostrarPasswordEliminar=false;

if(this.usuarioSeleccionado&&this.usuarioSeleccionado.login===login){

this.usuarioSeleccionado=null;

}

this.usuarioEliminarLogin=null;
this.usuarioEliminarNombre='';
this.passwordEliminar='';
this.errorPassword='';

window.location.reload();

})
.catch(error=>{

console.error('Error eliminando usuario:',error);

this.errorPassword=error.message||'La contraseña es incorrecta.';

})
.finally(()=>{

this.eliminarCargando=false;

this.$nextTick(()=>{

if(window.lucide){
lucide.createIcons();
}

});

});

}

}));

});

document.addEventListener('DOMContentLoaded',()=>{

if(window.lucide){
lucide.createIcons();
}

});
</script>

</body>
</html>