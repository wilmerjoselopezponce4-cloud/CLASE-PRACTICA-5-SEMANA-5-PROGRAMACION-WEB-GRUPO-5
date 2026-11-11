<?php
namespace App\Http\Controllers;
use App\Models\{Project,Task,Activity};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class TaskController{
 private function manager(Request $r, Project $p):void{
  abort_unless($r->user()->isManager() && (int)$p->gerente_id===(int)$r->user()->id,403);
  abort_if($p->estado==='Finalizado',403,'El proyecto está finalizado.');
 }
 private function input(Request $r,Project $p):array{
  $d=$r->validate([
    'titulo'=>'required|string|min:3|max:120','descripcion'=>'nullable|string|max:4000',
    'estado'=>['required',Rule::in(['Pendiente','En proceso','Completado'])],
    'prioridad'=>['required',Rule::in(['Baja','Media','Alta'])],
    'fecha_vencimiento'=>'nullable|date',
    'usuario_id'=>['nullable','integer', Rule::exists('proyecto_miembros','usuario_id')->where('proyecto_id',$p->id)]
  ]);
  return $d;
 }
 public function index(Request $r){$u=$r->user();$tasks=Task::with(['project','assignee'])->orderBy('fecha_vencimiento')->get()->filter(function($t)use($u){
    if($u->isAdmin())return true;
    if($u->isManager())return $t->project && (int)$t->project->gerente_id===(int)$u->id;
    return (int)$t->usuario_id===$u->id;
  });return view('tasks.index',compact('tasks'));}
 public function store(Request $r,Project $project){$this->manager($r,$project);
    $d=$this->input($r,$project);$d['proyecto_id']=$project->id;$d['creado_por']=$r->user()->id;
    $task=Task::create($d);Activity::create(['proyecto_id'=>$project->id,'tarea_id'=>$task->id,'usuario_id'=>$r->user()->id,'descripcion'=>'Tarea creada: '.$task->titulo]);
    return back()->with('ok','Tarea creada.');}
 public function update(Request $r,Task $task){$this->manager($r,$task->project);
    $task->update($this->input($r,$task->project));return back()->with('ok','Tarea actualizada.');}
 public function destroy(Request $r,Task $task){$this->manager($r,$task->project);$task->delete();return back()->with('ok','Tarea eliminada.');}
 public function status(Request $r,Task $task){$u=$r->user();$p=$task->project;
    abort_unless(($u->isManager() && (int)$p->gerente_id===$u->id) || ($u->isMember() && (int)$task->usuario_id===$u->id),403);
    abort_if($p->estado==='Finalizado',403);
    $data=$r->validate(['estado'=>['required',Rule::in(['Pendiente','En proceso','Completado'])]]);
    $task->update($data);Activity::create(['proyecto_id'=>$p->id,'tarea_id'=>$task->id,'usuario_id'=>$u->id,'descripcion'=>'Estado actualizado a '.$data['estado']]);
    return back()->with('ok','Estado actualizado.');}
}
