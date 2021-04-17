<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\TipoProceso;

class CreateProcesosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procesos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->string('nombre');
            $table->enum('tipo', TipoProceso::getValues());
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->dateTime('deleted_at')->nullable();
            $table->dateTime('activated_at')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('activated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('procesos');
    }
}
