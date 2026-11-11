@extends('layouts.app')
@section('title',$project->nombre)
@section('subtitle','Detalles, integrantes y actividades del proyecto.')
@section('content')
<a class="breadcrumb-back" href="{{ route('projects.index') }}">← Todos los proyectos</a>
<div class="project-hero surface-card"><div><span class="section-kicker">DETALLES DEL PROYECTO</span><h2>{{ $project->nombre }}</h2><p>{{ $project->descripcion }}</p><div class="project-meta">Gerente: {{ $project->manager?->nombre ?? 'Sin asignar' }} · {{ $project->estado }} · {{ $project->tasks->count() }} tareas</div></div></div>
@if(auth()->user()->isAdmin())
<details class="surface-card form-disclosure"><summary>⚙ Configurar proyecto</summary><form class="form-grid" method="post" action="{{ route('projects.update',$project) }}">@csrf @method('PUT')
<label>Nombre<input class="input-control" name="nombre" value="{{ $project->nombre }}" required></label>
<label>Gerente<select class="input-control" name="gerente_id"><option value="">Sin asignar</option>@foreach($managers as $m)<option value="{{ $m->id }}" @selected((int)$project->gerente_id===(int)$m->id)>{{ $m->nombre }}</option>@endforeach</select></label>
<label>Fecha de inicio<input class="input-control" type="date" name="fecha_inicio" value="{{ $project->fecha_inicio }}"></label><label>Fecha de cierre<input class="input-control" type="date" name="fecha_fin" value="{{ $project->fecha_fin }}"></label>
<label>Estado<select class="input-control" name="estado">@foreach(['Activo','Pausado','Finalizado'] as $s)<option @selected($project->estado===$s)>{{ $s }}</option>@endforeach</select></label>
<label class="wide">Descripción<textarea class="input-control" name="descripcion" rows="2">{{ $project->descripcion }}</textarea></label>
<button class="btn-primary-custom">Guardar proyecto</button></form>
<form method="post" action="{{ route('projects.destroy',$project) }}" data-confirm="¿Eliminar el proyecto y todas sus tareas?"><input type="hidden" name="_method" value="DELETE">@csrf<button class="danger-link">Eliminar proyecto</button></form></details>
@endif
<div class="project-detail-columns"><div class="project-tasks-section"><div class="section-row"><div><span class="section-kicker">ACTIVIDADES</span><h2>Tablero de tareas <span class="count-pill">{{ $project->tasks->count() }}</span></h2></div></div>
@if(auth()->user()->isManager() && (int)$project->gerente_id===auth()->id() && $project->estado!=='Finalizado')
<details class="surface-card form-disclosure"><summary>＋ Nueva tarea</summary><form method="post" action="{{ route('tasks.store',$project) }}" class="form-grid">@csrf
<label class="wide">Título<input class="input-control" name="titulo" required maxlength="120"></label>
<label>Estado<select name="estado" class="input-control"><option>Pendiente</option><option>En proceso</option><option>Completado</option></select></label><label>Prioridad<select name="prioridad" class="input-control"><option>Media</option><option>Baja</option><option>Alta</option></select></label>
<label>Vencimiento<input type="date" name="fecha_vencimiento" class="input-control"></label><label>Responsable<select name="usuario_id" class="input-control"><option value="">Sin asignar</option>@foreach($project->members as $m)<option value="{{ $m->id }}">{{ $m->nombre }}</option>@endforeach</select></label>
<label class="wide">Descripción<textarea name="descripcion" class="input-control" rows="3"></textarea></label><button type="submit" class="btn-primary-custom">Crear tarea</button></form></details>
@endif
<div class="kanban-columns">@foreach(['Pendiente','En proceso','Completado'] as $state)<section class="kanban-column"><div class="kanban-column-header"><strong>{{ $state }}</strong><span>{{ $project->tasks->where('estado',$state)->count() }}</span></div>
@forelse($project->tasks->where('estado',$state)->filter(fn($t)=>!auth()->user()->isMember() || (int)$t->usuario_id===auth()->id()) as $task)
@include('partials.task', ['task'=>$task])
@empty<p class="empty-mini">Sin tareas.</p>@endforelse</section>@endforeach</div></div>
<aside class="project-aside"><section class="surface-card aside-card"><div class="panel-head"><div><span class="section-kicker">ORGANIZACIÓN</span><h2>Integrantes</h2></div></div>
<div class="member-list">@forelse($project->members as $m)<div class="member-row"><span class="avatar">{{ mb_substr($m->nombre,0,1) }}</span><div><strong>{{ $m->nombre }}</strong><small>{{ $m->email }}</small></div>
@if($canManage)<form method="post" action="{{ route('members.destroy',[$project,$m]) }}" data-confirm="¿Retirar a este integrante?">@csrf @method('DELETE')<button class="danger-link">×</button></form>@endif</div>@empty<p>No hay integrantes asignados.</p>@endforelse</div>
@if($canManage)<form method="post" action="{{ route('members.store',$project) }}" class="mini-form">@csrf
<select class="input-control" name="usuario_id" required><option value="">Seleccionar integrante</option>@foreach($available as $m)<option value="{{ $m->id }}">{{ $m->nombre }}</option>@endforeach</select><button class="btn-small" type="submit">Añadir integrante</button></form>@endif
</section></aside></div>
@endsection
