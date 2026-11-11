<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Activity extends Model{
    protected $table='actividad';public $timestamps=false;
    protected $fillable=['proyecto_id','tarea_id','usuario_id','descripcion'];
}
