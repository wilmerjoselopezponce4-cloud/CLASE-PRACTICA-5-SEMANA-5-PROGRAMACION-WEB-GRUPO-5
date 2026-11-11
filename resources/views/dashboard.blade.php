@extends('layouts.app')
@section('title','Panel general')
@section('subtitle','Una vista actualizada del trabajo y los pendientes del equipo.')
@section('content')
@php($done=$tasks->where('estado','Completado')->count())
@php($running=$tasks->where('estado','En proceso')->count())
@php($pending=$tasks->where('estado','Pendiente')->count())
@php($pct=$tasks->count()?round(100*$done/$tasks->count()):0)
<div class="welcome-banner"><div class="welcome-copy"><span class="banner-kicker">✳ RESUMEN DE ACTIVIDAD</span><h2>Hola, {{ explode(' ',auth()->user()->nombre)[0] }} 👋</h2><p>Consulta las tareas pendientes y conoce el avance de tus proyectos.</p></div></div>
<div class="metrics-grid"><div class="metric-card"><div class="metric-head"><span class="metric-icon sky">▤</span><span class="metric-trend">Proyectos</span></div><div class="metric-number">{{ $projects->count() }}</div><div class="metric-caption">Disponibles</div></div>
<div class="metric-card"><div class="metric-head"><span class="metric-icon blue">☷</span><span class="metric-trend">Tareas</span></div><div class="metric-number">{{ $tasks->count() }}</div><div class="metric-caption">En seguimiento</div></div>
<div class="metric-card"><div class="metric-head"><span class="metric-icon lime">◷</span><span class="metric-trend">En proceso</span></div><div class="metric-number">{{ $running }}</div><div class="metric-caption">Actividades activas</div></div>
<div class="metric-card"><div class="metric-head"><span class="metric-icon mint">✓</span><span class="metric-trend">Completadas</span></div><div class="metric-number">{{ $done }}</div><div class="metric-caption">Avance del equipo</div></div></div>
<div class="dashboard-columns"><section class="surface-card"><div class="panel-head"><div><div class="section-kicker">SEGUIMIENTO</div><h2>Estado de las tareas</h2><p>Distribución por etapa</p></div></div><div class="progress-summary"><div class="donut" style="--progress:{{ $pct }}"><div><strong>{{ $pct }}%</strong><small>Completado</small></div></div><div class="legend-list"><div>● Pendientes: <strong>{{ $pending }}</strong></div><div>● En proceso: <strong>{{ $running }}</strong></div><div>● Completadas: <strong>{{ $done }}</strong></div></div></div></section>
<section class="surface-card"><div class="panel-head"><div><div class="section-kicker">PRÓXIMOS PASOS</div><h2>Tareas prioritarias</h2><p>Actividades por atender</p></div><a class="text-link" href="{{ route('tasks.index') }}">Ver todas →</a></div>
<div class="quick-tasks">@forelse($tasks->where('estado','!=','Completado')->take(5) as $task)<a class="quick-task" href="{{ route('projects.show', $task->proyecto_id) }}"><span class="quick-check">☷</span><div><strong>{{ $task->titulo }}</strong><small>{{ $task->project?->nombre }}</small></div><span class="badge-status">{{ $task->prioridad }}</span></a>@empty<div class="empty-state">No hay tareas pendientes.</div>@endforelse</div></section></div>
<section class="surface-card"><div class="panel-head"><div><div class="section-kicker">PORTAFOLIO</div><h2>Proyectos recientes</h2></div><a class="text-link" href="{{ route('projects.index') }}">Ir a proyectos →</a></div><div class="project-overview-grid">@forelse($projects->take(3) as $project)<a class="project-overview-item" href="{{ route('projects.show',$project) }}"><h3>{{ $project->nombre }}</h3><p>{{ Str::limit($project->descripcion,90) }}</p><div class="project-meta">{{ $project->estado }} · {{ $project->tasks_count }} tareas</div></a>@empty<p>Aún no hay proyectos.</p>@endforelse</div></section>
@endsection
