
//Tabla guarda los formularios de meta

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('vt_facebook_forms', function (Blueprint $table) {

        $table->id();                                      // ID interno
        $table->unsignedBigInteger('page_id');             // Relación con vt_facebook_page
        $table->string('form_id')->nullable();             // ID del formulario en Meta
        $table->string('nombre')->nullable();              // Nombre del formulario
        $table->enum('estatus', ['activo', 'inactivo'])     // Estado del formulario
              ->default('activo');
        $table->timestamps();


        // Relación con la tabla vt_facebook_page
        $table->foreign('page_id')                  //Declaracion del campo
              ->references('id')                    //Declaracion
              ->on('vt_facebook_page')              //de la
              ->onDelete('cascade');                //relacion

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vt_facebook_forms');
    }
};
