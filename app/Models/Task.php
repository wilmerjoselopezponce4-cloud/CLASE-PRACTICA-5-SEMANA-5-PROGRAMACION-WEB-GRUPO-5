<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Task extends Model{
    protected $table='tareas';public $timestamps=false;
    protected $fillable=['titulo','descripcion','estado','prioridad','fecha_vencimiento','usuario_id','proyecto_id','creado_por'];
    public function project(){return $this->belongsTo(Project::class,'proyecto_id');}
    public function assignee(){return $this->belongsTo(User::class,'usuario_id');}
}
