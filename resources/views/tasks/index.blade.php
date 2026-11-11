@extends('layouts.app')
@section('title','Tareas')
@section('subtitle','Consulta y actualiza las actividades disponibles para tu rol.')
@section('content')
<div class="surface-card"><div class="panel-head"><div><span class="section-kicker">SEGUIMIENTO</span><h2>Listado de tareas</h2></div><span class="count-pill">{{ $tasks->count() }} actividades</span></div>
<div class="table-scroll"><table class="fresh-table"><thead><tr><th>Tarea</th><th>Proyecto</th><th>Responsable</th><th>Prioridad</th><th>Estado</th><th>Vencimiento</th></tr></thead>
<tbody>@forelse($tasks as $task)<tr><td><a href="{{ route('projects.show', $task->proyecto_id) }}">{{ $task->titulo }}</a></td><td>{{ $task->project?->nombre }}</td><td>{{ $task->assignee?->nombre ?? 'Pendiente' }}</td><td>{{ $task->prioridad }}</td><td><span class="status-chip">{{ $task->estado }}</span></td><td>{{ $task->fecha_vencimiento ?: 'Sin fecha' }}</td></tr>@empty<tr><td colspan="6">No hay tareas asignadas.</td></tr>@endforelse</tbody></table></div>
</div><p class="helper-note">Para actualizar una tarea, entra al proyecto correspondiente.</p>
@endsection
