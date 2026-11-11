<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
class UserController {
 public function index(Request $r){$users=User::orderBy('rol_id')->orderBy('nombre')->get();return view('users.index',compact('users'));}
 private function input(Request $r,?User $target=null):array{return $r->validate([
   'nombre'=>'required|string|min:3|max:90',
   'email'=>['required','email','max:120',Rule::unique('usuarios','email')->ignore($target?->id)],
   'rol_id'=>['required','integer',Rule::in([1,2,3])],
   'password'=>$target?'nullable|string|min:10|max:72':'required|string|min:10|max:72'
 ]);}
 public function store(Request $r){$d=$this->input($r);$d['contrasena']=Hash::make($d['password']);unset($d['password']);User::create($d);return back()->with('ok','Usuario creado.');}
 public function update(Request $r,User $user){$d=$this->input($r,$user);
    if($r->user()->id===$user->id && (int)$d['rol_id']!==1) return back()->withErrors(['rol_id'=>'No puedes quitarte el rol administrador.']);
    if($user->isAdmin() && (int)$d['rol_id']!==1 && User::where('rol_id',1)->count()<=1) return back()->withErrors(['rol_id'=>'Debe quedar un administrador.']);
    DB::transaction(function()use($user,$d){
      $user->update(['nombre'=>$d['nombre'],'email'=>$d['email'],'rol_id'=>$d['rol_id'],...(!empty($d['password'])?['contrasena'=>Hash::make($d['password'])]:[])]);
      if((int)$d['rol_id']!==2)DB::table('proyectos')->where('gerente_id',$user->id)->update(['gerente_id'=>null]);
      if((int)$d['rol_id']!==3){DB::table('tareas')->where('usuario_id',$user->id)->update(['usuario_id'=>null]);DB::table('proyecto_miembros')->where('usuario_id',$user->id)->delete();}
    });return back()->with('ok','Usuario actualizado.');}
 public function destroy(Request $r,User $user){
    if($user->id===$r->user()->id) return back()->withErrors(['user'=>'No puedes eliminar tu cuenta.']);
    if($user->isAdmin() && User::where('rol_id',1)->count()<=1)return back()->withErrors(['user'=>'Debe quedar un administrador.']);
    // Las referencias se conservan en proyectos y tareas mediante claves externas.
    $user->delete();return back()->with('ok','Usuario eliminado.');
 }
}
