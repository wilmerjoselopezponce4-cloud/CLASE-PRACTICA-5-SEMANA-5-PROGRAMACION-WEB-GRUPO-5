<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Project extends Model {
    protected $table='proyectos';public $timestamps=false;
    protected $fillable=['nombre','descripcion','gerente_id','fecha_inicio','fecha_fin','estado'];
    public function manager(){return $this->belongsTo(User::class,'gerente_id');}
    public function members(){return $this->belongsToMany(User::class,'proyecto_miembros','proyecto_id','usuario_id');}
    public function tasks(){return $this->hasMany(Task::class,'proyecto_id');}
    public function allowedFor(User $u):bool{
        if($u->isAdmin())return true;
        if($u->isManager())return (int)$this->gerente_id===(int)$u->id;
        return $this->members()->where('usuarios.id',$u->id)->exists();
    }
}
