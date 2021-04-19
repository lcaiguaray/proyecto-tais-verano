<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\TipoObjeto;
use App\Enums\TipoFrecuencia;
use App\Enums\TipoFormula;
use App\Enums\TipoCondicionIndicador;

class CreateIndicadorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indicadors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mapa_proceso_id')->constrained('mapa_procesos')->onDelete('cascade');
            $table->enum('objeto_tipo', TipoObjeto::getValues());
            $table->unsignedBigInteger('objeto_id');
            $table->string('nombre');
            $table->string('responsable');
            $table->text('objetivo');
            $table->text('descripcion');
            $table->text('realizar');
            $table->text('mecanismo');
            $table->text('tolerancia');
            $table->text('resultados');
            $table->unsignedDecimal('meta', 7, 2);
            $table->unsignedDecimal('linea_base', 7, 2)->default(0.00);
            $table->text('iniciativa');
            $table->enum('frecuencia', TipoFrecuencia::getValues());
            $table->enum('formula', TipoFormula::getValues());
            $table->enum('tipo_condicion', TipoCondicionIndicador::getValues());
            $table->text('primer_parametro');
            $table->text('segundo_parametro')->nullable();
            $table->unsignedDecimal('condicion_rojo', 7, 2);
            $table->unsignedDecimal('condicion_verde', 7, 2);
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
        Schema::dropIfExists('indicadors');
    }
}
