<?php
namespace App\Http\Controllers;
use App\Models\{Project,Task,User,Activity};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
class ProjectController{
 private function valid(Request $r):array{return $r->validate([
  'nombre'=>'required|string|min:3|max:120','descripcion'=>'nullable|string|max:4000',
  'gerente_id'=>['nullable','integer',Rule::exists('usuarios','id')->where('rol_id',2)->where('activo',1)],
  'fecha_inicio'=>'nullable|date','fecha_fin'=>'nullable|date|after_or_equal:fecha_inicio',
  'estado'=>['required',Rule::in(['Activo','Pausado','Finalizado'])],
 ]);}
 private function canManage(Project $p,User $u):void{
   abort_unless($u->isAdmin() || ($u->isManager() && (int)$p->gerente_id===$u->id),403);
 }
 public function index(Request $r){$u=$r->user();$projects=Project::with('manager')->withCount('tasks')->get()->filter(fn($p)=>$p->allowedFor($u));
  $managers=User::where('rol_id',2)->where('activo',1)->get();return view('projects.index',compact('projects','managers'));}
 public function show(Request $r, Project $project){$u=$r->user();abort_unless($project->allowedFor($u),403);
  $project->load(['manager','members','tasks.assignee','tasks.project.members']);$canManage=$u->isAdmin()||($u->isManager() && (int)$project->gerente_id===(int)$u->id);
  $available=User::where('rol_id',3)->where('activo',1)->whereNotIn('id',$project->members->pluck('id'))->get();
  $managers=User::where('rol_id',2)->where('activo',1)->get();
  return view('projects.show',compact('project','canManage','available','managers'));}
 public function store(Request $r){$project=Project::create($this->valid($r));Activity::create(['proyecto_id'=>$project->id,'usuario_id'=>$r->user()->id,'descripcion'=>'Proyecto creado.']);return redirect()->route('projects.show',$project)->with('ok','Proyecto creado.');}
 public function update(Request $r,Project $project){$project->update($this->valid($r));
    Activity::create(['proyecto_id'=>$project->id,'usuario_id'=>$r->user()->id,'descripcion'=>'Proyecto actualizado.']);
    return back()->with('ok','Proyecto actualizado.');}
 public function destroy(Project $project){$project->delete();return redirect()->route('projects.index')->with('ok','Proyecto eliminado.');}
 public function addMember(Request $r,Project $project){$this->canManage($project,$r->user());
   $data=$r->validate(['usuario_id'=>['required','integer',Rule::exists('usuarios','id')->where('rol_id',3)->where('activo',1)]]);
   $project->members()->syncWithoutDetaching([(int)$data['usuario_id']]);return back()->with('ok','Miembro agregado.');}
 public function removeMember(Request $r,Project $project,User $user){$this->canManage($project,$r->user());
   DB::transaction(function()use($project,$user){Task::where('proyecto_id',$project->id)->where('usuario_id',$user->id)->update(['usuario_id'=>null]);$project->members()->detach($user->id);});
   return back()->with('ok','Miembro retirado.');}
}
