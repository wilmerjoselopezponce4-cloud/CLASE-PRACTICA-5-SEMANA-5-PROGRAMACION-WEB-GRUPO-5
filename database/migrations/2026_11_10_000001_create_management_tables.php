<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('roles', function(Blueprint $t){$t->id('rol_id');$t->string('rol_nombre',30)->unique();});
        Schema::create('usuarios',function(Blueprint $t){
            $t->id(); $t->string('nombre',90);$t->string('email',120)->unique();
            $t->string('contrasena');$t->foreignId('rol_id')->constrained('roles','rol_id');
            $t->boolean('activo')->default(true);$t->timestamp('creado_en')->useCurrent();
        });
        Schema::create('proyectos',function(Blueprint $t){
            $t->id(); $t->string('nombre',120);$t->text('descripcion')->nullable();
            $t->foreignId('gerente_id')->nullable()->constrained('usuarios')->nullOnDelete();
            $t->date('fecha_inicio')->nullable();$t->date('fecha_fin')->nullable();
            $t->enum('estado',['Activo','Pausado','Finalizado'])->default('Activo');
            $t->timestamp('creado_en')->useCurrent();
        });
        Schema::create('proyecto_miembros',function(Blueprint $t){
            $t->foreignId('proyecto_id')->constrained('proyectos')->cascadeOnDelete();
            $t->foreignId('usuario_id')->constrained('usuarios')->cascadeOnDelete();
            $t->primary(['proyecto_id','usuario_id']);
        });
        Schema::create('tareas',function(Blueprint $t){
            $t->id();$t->string('titulo',120);$t->text('descripcion')->nullable();
            $t->enum('estado',['Pendiente','En proceso','Completado'])->default('Pendiente');
            $t->enum('prioridad',['Baja','Media','Alta'])->default('Media');
            $t->date('fecha_vencimiento')->nullable();
            $t->foreignId('usuario_id')->nullable()->constrained('usuarios')->nullOnDelete();
            $t->foreignId('proyecto_id')->constrained('proyectos')->cascadeOnDelete();
            $t->foreignId('creado_por')->nullable()->constrained('usuarios')->nullOnDelete();
            $t->timestamp('creado_en')->useCurrent();$t->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();
            $t->index(['proyecto_id','estado']);
        });
        Schema::create('actividad',function(Blueprint $t){
            $t->id();$t->foreignId('proyecto_id')->nullable()->constrained('proyectos')->cascadeOnDelete();
            $t->foreignId('tarea_id')->nullable()->constrained('tareas')->nullOnDelete();
            $t->foreignId('usuario_id')->nullable()->constrained('usuarios')->nullOnDelete();
            $t->string('descripcion',220);$t->timestamp('creado_en')->useCurrent();
        });
    }
    public function down():void{
        Schema::dropIfExists('actividad');Schema::dropIfExists('tareas');
        Schema::dropIfExists('proyecto_miembros');Schema::dropIfExists('proyectos');
        Schema::dropIfExists('usuarios');Schema::dropIfExists('roles');
    }
};
