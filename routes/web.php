<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
Route::get('/',fn()=>redirect()->route('dashboard'));
Route::middleware('guest')->group(function(){
 Route::get('/login',[AuthController::class,'showLogin'])->name('login');
 Route::post('/login',[AuthController::class,'login'])->middleware('throttle:8,10')->name('login.submit');
});
Route::middleware('auth')->group(function(){
 Route::post('/logout',[AuthController::class,'logout'])->name('logout');
 Route::get('/perfil',[AuthController::class,'profile'])->name('profile');
 Route::put('/perfil',[AuthController::class,'updateProfile'])->name('profile.update');
 Route::get('/panel',[DashboardController::class,'index'])->name('dashboard');
 Route::get('/proyectos',[ProjectController::class,'index'])->name('projects.index');
 Route::get('/proyectos/{project}',[ProjectController::class,'show'])->name('projects.show');
 Route::post('/proyectos',[ProjectController::class,'store'])->middleware('role:Administrador')->name('projects.store');
 Route::put('/proyectos/{project}',[ProjectController::class,'update'])->middleware('role:Administrador')->name('projects.update');
 Route::delete('/proyectos/{project}',[ProjectController::class,'destroy'])->middleware('role:Administrador')->name('projects.destroy');
 Route::post('/proyectos/{project}/miembros',[ProjectController::class,'addMember'])->name('members.store');
 Route::delete('/proyectos/{project}/miembros/{user}',[ProjectController::class,'removeMember'])->name('members.destroy');
 Route::get('/tareas',[TaskController::class,'index'])->name('tasks.index');
 Route::post('/proyectos/{project}/tareas',[TaskController::class,'store'])->middleware('role:Gerente de proyecto')->name('tasks.store');
 Route::put('/tareas/{task}',[TaskController::class,'update'])->middleware('role:Gerente de proyecto')->name('tasks.update');
 Route::delete('/tareas/{task}',[TaskController::class,'destroy'])->middleware('role:Gerente de proyecto')->name('tasks.destroy');
 Route::patch('/tareas/{task}/estado',[TaskController::class,'status'])->middleware('role:Gerente de proyecto,Miembro del equipo')->name('tasks.status');
 Route::get('/equipo',[UserController::class,'index'])->name('users.index');
 Route::post('/equipo',[UserController::class,'store'])->middleware('role:Administrador')->name('users.store');
 Route::put('/equipo/{user}',[UserController::class,'update'])->middleware('role:Administrador')->name('users.update');
 Route::delete('/equipo/{user}',[UserController::class,'destroy'])->middleware('role:Administrador')->name('users.destroy');
});
