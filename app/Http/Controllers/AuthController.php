<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
class AuthController {
 public function showLogin(){return view('auth.login');}
 public function login(Request $request){
   $data=$request->validate(['email'=>'required|email','password'=>'required|string']);
   if(!Auth::attempt(['email'=>strtolower($data['email']),'password'=>$data['password'],'activo'=>1])){
       return back()->withErrors(['email'=>'Correo o contraseña incorrectos.'])->onlyInput('email');
   }
   $request->session()->regenerate();return redirect()->intended(route('dashboard'));
 }
 public function logout(Request $r){Auth::logout();$r->session()->invalidate();$r->session()->regenerateToken();return redirect()->route('login');}
 public function profile(){return view('profile');}
 public function updateProfile(Request $r){
    $u=$r->user();$d=$r->validate([
      'nombre'=>'required|string|min:3|max:90',
      'email'=>['required','email','max:120',Rule::unique('usuarios','email')->ignore($u->id)],
      'actual'=>'nullable|string', 'nueva'=>'nullable|string|min:10|max:72'
    ]);
    if(!empty($d['nueva'])){
       if(!Hash::check($d['actual']??'', $u->contrasena))return back()->withErrors(['actual'=>'La contraseña actual es incorrecta.']);
       $u->contrasena=Hash::make($d['nueva']);
    }
    $u->nombre=$d['nombre'];$u->email=strtolower($d['email']);$u->save();
    return back()->with('ok','Perfil actualizado.');
 }
}
