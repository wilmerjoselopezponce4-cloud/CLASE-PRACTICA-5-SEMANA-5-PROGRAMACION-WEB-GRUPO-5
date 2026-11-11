<?php
namespace App\Http\Controllers;
use App\Models\{Project,Task};
use Illuminate\Http\Request;
class DashboardController{
 public function index(Request $request){
    $u=$request->user();
    $projects=Project::with('manager')->withCount('tasks')->get()->filter(fn($p)=>$p->allowedFor($u))->values();
    $tasks=Task::with(['assignee','project'])->whereIn('proyecto_id',$projects->pluck('id'))
       ->when($u->isMember(),fn($q)=>$q->where('usuario_id',$u->id))
       ->orderBy('fecha_vencimiento')->get();
    return view('dashboard',compact('projects','tasks'));
 }
}
