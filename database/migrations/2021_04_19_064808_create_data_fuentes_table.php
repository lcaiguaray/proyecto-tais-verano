<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataFuentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_fuentes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indicador_id')->constrained('indicadors')->onDelete('cascade');
            $table->date('fecha');
            $table->unsignedDecimal('valor', 7, 2);
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
        Schema::dropIfExists('data_fuentes');
    }
}
