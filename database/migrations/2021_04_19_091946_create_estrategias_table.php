<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\TipoObjeto;
use App\Enums\TipoEstrategia;

class CreateEstrategiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estrategias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mapa_proceso_id')->constrained('mapa_procesos')->onDelete('cascade');
            $table->enum('objeto_tipo', TipoObjeto::getValues());
            $table->unsignedBigInteger('objeto_id');
            $table->enum('tipo_estrategia', TipoEstrategia::getValues());
            $table->string('nombre');
            $table->boolean('activo')->default(true);
            $table->boolean('ligado');
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
        Schema::dropIfExists('estrategias');
    }
}
