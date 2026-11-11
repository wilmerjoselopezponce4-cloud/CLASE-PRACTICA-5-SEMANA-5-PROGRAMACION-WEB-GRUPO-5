<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class User extends Authenticatable {
    protected $table='usuarios';public $timestamps=false;
    protected $fillable=['nombre','email','contrasena','rol_id','activo'];
    protected $hidden=['contrasena'];
    protected function casts():array{return ['activo'=>'boolean'];}
    public function getAuthPassword():string{return $this->contrasena;}
    public function getAuthPasswordName():string{return 'contrasena';}
    public function roleName():string{return match((int)$this->rol_id){1=>'Administrador',2=>'Gerente de proyecto',default=>'Miembro del equipo'};}
    public function isAdmin():bool{return (int)$this->rol_id===1;}
    public function isManager():bool{return (int)$this->rol_id===2;}
    public function isMember():bool{return (int)$this->rol_id===3;}
    public function projects():BelongsToMany{return $this->belongsToMany(Project::class,'proyecto_miembros','usuario_id','proyecto_id');}
    public function role():BelongsTo{return $this->belongsTo(Role::class,'rol_id','rol_id');}
}
