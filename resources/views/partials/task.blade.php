<article class="task-tile"><div class="task-tile-head"><span class="status-chip">{{ $task->estado }}</span><span class="priority-label">{{ $task->prioridad }}</span></div>
<h3>{{ $task->titulo }}</h3><p>{{ $task->descripcion ?: 'Sin descripción.' }}</p>
<div class="task-tile-meta"><span>👤 {{ $task->assignee?->nombre ?? 'Sin asignar' }}</span><span>◷ {{ $task->fecha_vencimiento ?: 'Sin fecha' }}</span></div>
@if($task->project->estado!=='Finalizado' && ((auth()->user()->isManager() && (int)$task->project->gerente_id===auth()->id()) || (auth()->user()->isMember() && (int)$task->usuario_id===auth()->id())))
<form method="post" action="{{ route('tasks.status',$task) }}" class="inline-status">@csrf @method('PATCH')<select name="estado" class="input-control" aria-label="Estado">
@foreach(['Pendiente','En proceso','Completado'] as $s)<option value="{{ $s }}" @selected($task->estado===$s)>{{ $s }}</option>@endforeach
</select><button type="submit" class="btn-small">Actualizar</button></form>@endif
@if(auth()->user()->isManager() && (int)$task->project->gerente_id===auth()->id() && $task->project->estado!=='Finalizado')
<details class="edit-task"><summary>Editar tarea</summary><form method="post" action="{{ route('tasks.update',$task) }}" class="mini-form">@csrf @method('PUT')
<input class="input-control" name="titulo" required maxlength="120" value="{{ $task->titulo }}"><textarea name="descripcion" class="input-control" rows="2">{{ $task->descripcion }}</textarea>
<div class="mini-columns"><select name="estado" class="input-control">@foreach(['Pendiente','En proceso','Completado'] as $s)<option @selected($task->estado===$s)>{{ $s }}</option>@endforeach</select>
<select name="prioridad" class="input-control">@foreach(['Baja','Media','Alta'] as $s)<option @selected($task->prioridad===$s)>{{ $s }}</option>@endforeach</select></div>
<input class="input-control" type="date" name="fecha_vencimiento" value="{{ $task->fecha_vencimiento }}">
<select class="input-control" name="usuario_id"><option value="">Sin asignar</option>@foreach($task->project->members as $m)<option value="{{ $m->id }}" @selected((int)$task->usuario_id===(int)$m->id)>{{ $m->nombre }}</option>@endforeach</select>
<button type="submit" class="btn-small">Guardar cambios</button></form></details>
<form method="post" action="{{ route('tasks.destroy',$task) }}" data-confirm="¿Eliminar esta tarea?">@csrf @method('DELETE')<button type="submit" class="danger-link">Eliminar tarea</button></form>
@endif</article>
