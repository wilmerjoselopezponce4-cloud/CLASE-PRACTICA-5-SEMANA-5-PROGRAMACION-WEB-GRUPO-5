@extends('layouts.app')
@section('title','Proyectos')
@section('subtitle','Gestiona y revisa los proyectos del equipo.')
@section('content')
@if(auth()->user()->isAdmin())
<details class="surface-card form-disclosure" id="newproject"><summary>＋ Crear proyecto</summary>
<form method="post" action="{{ route('projects.store') }}" class="form-grid">@csrf
<label>Nombre<input name="nombre" class="input-control" maxlength="120" minlength="3" required></label>
<label>Gerente<select name="gerente_id" class="input-control"><option value="">Sin asignar</option>@foreach($managers as $m)<option value="{{ $m->id }}">{{ $m->nombre }}</option>@endforeach</select></label>
<label>Inicio<input type="date" name="fecha_inicio" class="input-control"></label><label>Finalización<input type="date" name="fecha_fin" class="input-control"></label>
<label>Estado<select name="estado" class="input-control"><option>Activo</option><option>Pausado</option><option>Finalizado</option></select></label>
<label class="wide">Descripción<textarea name="descripcion" class="input-control" rows="3"></textarea></label>
<button class="btn-primary-custom" type="submit">Guardar proyecto</button></form></details>
@endif
<div class="project-cards">@forelse($projects as $project)<a class="project-card" href="{{ route('projects.show',$project) }}"><span class="section-kicker">PROYECTO · {{ $project->estado }}</span><h2>{{ $project->nombre }}</h2><p>{{ Str::limit($project->descripcion,130) }}</p><div class="project-card-footer"><span>Gerente: {{ $project->manager?->nombre ?? 'Sin asignar' }}</span><strong>{{ $project->tasks_count }} tareas →</strong></div></a>@empty<div class="surface-card" style="grid-column:1/-1"><div class="empty-state">Todavía no hay proyectos disponibles.</div></div>@endforelse</div>
@endsection
