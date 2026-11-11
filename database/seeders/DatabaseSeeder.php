<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\{Role,User,Project,Task};
class DatabaseSeeder extends Seeder{
 public function run():void{
    foreach([1=>'Administrador',2=>'Gerente de proyecto',3=>'Miembro del equipo'] as $id=>$label){Role::updateOrCreate(['rol_id'=>$id],['rol_nombre'=>$label]);}
    $accounts=[
      ['Jorge Toapanta','jorge@grupo5.test',1],
      ['Wilmer Adriano','wilmer.adriano@grupo5.test',2],
      ['Jhoely Dávila','jhoely@grupo5.test',3],
      ['Jhonatan Chávez','jhonatan@grupo5.test',3],
      ['Wilmer López','wilmer.lopez@grupo5.test',3],
    ];
    foreach($accounts as [$name,$email,$role]){
       User::firstOrCreate(['email'=>$email],[ 'nombre'=>$name,'rol_id'=>$role,'contrasena'=>Hash::make('12345678910'),'activo'=>1]);
    }
    $manager=User::where('email','wilmer.adriano@grupo5.test')->firstOrFail();
    $members=User::where('rol_id',3)->orderBy('id')->get();
    $p1=Project::firstOrCreate(['nombre'=>'Portal de operaciones'],[
      'descripcion'=>'Organización y seguimiento de entregables del equipo.',
      'gerente_id'=>$manager->id,'fecha_inicio'=>today()->toDateString(),
      'fecha_fin'=>today()->addDays(30)->toDateString(),'estado'=>'Activo']);
    $p2=Project::firstOrCreate(['nombre'=>'Optimización de procesos'],[
      'descripcion'=>'Planificación de mejoras y actividades internas.',
      'gerente_id'=>$manager->id,'fecha_inicio'=>today()->toDateString(),
      'fecha_fin'=>today()->addDays(45)->toDateString(),'estado'=>'Activo']);
    foreach([$p1,$p2] as $p){$p->members()->syncWithoutDetaching($members->pluck('id')->all());}
    $samples=[
      ['Definir requerimientos','Revisar las necesidades y el alcance funcional.','Completado','Alta',5,0,$p1],
      ['Diseñar interfaz','Preparar pantallas y componentes principales.','En proceso','Alta',9,1,$p1],
      ['Integrar base de datos','Conectar formularios y consultas seguras.','Pendiente','Media',14,2,$p1],
      ['Verificar accesos','Comprobar permisos de administrador, gerente y miembro.','Pendiente','Alta',18,0,$p2],
      ['Preparar pruebas','Registrar escenarios y resultados de validación.','En proceso','Media',20,1,$p2],
    ];
    foreach($samples as [$title,$desc,$state,$priority,$days,$n,$p]){
       Task::firstOrCreate(['titulo'=>$title,'proyecto_id'=>$p->id],[
          'descripcion'=>$desc,'estado'=>$state,'prioridad'=>$priority,
          'fecha_vencimiento'=>today()->addDays($days)->toDateString(),
          'usuario_id'=>$members[$n]->id ?? null,'creado_por'=>$manager->id]);
    }
 }
}
