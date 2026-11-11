@extends('layouts.app')
@section('title','Mi perfil')
@section('subtitle','Actualiza tu información y contraseña de acceso.')
@section('content')
<div class="profile-grid"><section class="surface-card profile-aside"><div class="profile-cover"><span class="profile-avatar">{{ mb_substr(auth()->user()->nombre,0,1) }}</span></div><h2>{{ auth()->user()->nombre }}</h2><p>{{ auth()->user()->roleName() }}</p><p>{{ auth()->user()->email }}</p></section>
<section class="surface-card profile-section"><div class="panel-head"><div><div class="section-kicker">CONFIGURACIÓN PERSONAL</div><h2>Datos y seguridad</h2></div></div>
<form method="post" action="{{ route('profile.update') }}" class="profile-form">@csrf @method('PUT')
<label>Nombre completo<input class="input-control" name="nombre" value="{{ old('nombre',auth()->user()->nombre) }}" required></label>
<label>Correo electrónico<input class="input-control" type="email" name="email" value="{{ old('email',auth()->user()->email) }}" required></label>
<hr><p>Para cambiar la contraseña, completa los siguientes campos. Si no deseas modificarla, déjalos vacíos.</p>
<label>Contraseña actual<input class="input-control" type="password" name="actual"></label>
<label>Nueva contraseña<input class="input-control" type="password" name="nueva" minlength="10"></label>
<button class="btn-primary-custom" type="submit">Guardar información</button></form></section></div>
@endsection
