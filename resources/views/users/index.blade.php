@extends('layouts.app')
@section('title','Equipo')
@section('subtitle','Integrantes y permisos del espacio de trabajo.')
@section('content')
@if(auth()->user()->isAdmin())
<details class="surface-card form-disclosure"><summary>＋ Registrar usuario</summary><form method="post" action="{{ route('users.store') }}" class="form-grid">@csrf
<label>Nombre<input class="input-control" name="nombre" required maxlength="90"></label>
<label>Correo<input type="email" class="input-control" name="email" required></label>
<label>Rol<select name="rol_id" class="input-control"><option value="1">Administrador</option><option value="2">Gerente de proyecto</option><option value="3">Miembro del equipo</option></select></label>
<label>Contraseña<input type="password" class="input-control" name="password" minlength="10" required></label><button class="btn-primary-custom">Registrar cuenta</button></form></details>
@endif
<div class="surface-card"><div class="panel-head"><div><div class="section-kicker">DIRECTORIO</div><h2>Miembros del sistema</h2></div><span class="count-pill">{{ $users->count() }} registros</span></div>
<div class="team-cards">@foreach($users as $u)<article class="team-card"><span class="avatar">{{ mb_substr($u->nombre,0,1) }}</span><div><h3>{{ $u->nombre }}</h3><p>{{ $u->email }}</p><span class="badge-status">{{ $u->roleName() }}</span></div>
@if(auth()->user()->isAdmin())<details class="edit-task"><summary>Editar</summary><form method="post" action="{{ route('users.update',$u) }}" class="mini-form">@csrf @method('PUT')
<input class="input-control" name="nombre" value="{{ $u->nombre }}" required><input type="email" class="input-control" name="email" value="{{ $u->email }}" required>
<select name="rol_id" class="input-control">@foreach([1=>'Administrador',2=>'Gerente de proyecto',3=>'Miembro del equipo'] as $id=>$label)<option value="{{ $id }}" @selected((int)$u->rol_id===(int)$id)>{{ $label }}</option>@endforeach</select>
<input class="input-control" type="password" name="password" placeholder="Nueva contraseña (opcional)" minlength="10"><button class="btn-small">Guardar</button></form></details>
@if(auth()->id()!==$u->id)<form method="post" action="{{ route('users.destroy',$u) }}" data-confirm="¿Eliminar este usuario?">@csrf @method('DELETE')<button class="danger-link">Eliminar</button></form>@endif
@endif</article>@endforeach</div></div>
@endsection
