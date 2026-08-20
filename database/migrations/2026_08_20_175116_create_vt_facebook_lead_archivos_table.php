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
        Schema::create('vt_facebook_lead_archivos', function (Blueprint $table) {

            $table->id();                                      // ID interno del archivo
            $table->unsignedBigInteger('lead_id');             // Relación con vt_facebook_leads
            $table->string('nombre_archivo')->nullable();      // Nombre original del archivo
            $table->string('ruta')->nullable();                // Ruta donde se guarda el archivo
            $table->string('tipo')->nullable();                // Tipo MIME (pdf, jpg, png, etc.)
            $table->integer('tamano')->nullable();             // Tamaño en KB o bytes
            $table->timestamps();                              // Fecha de subida del archivo


            // Relación con la tabla vt_facebook_leads

            $table->foreign('lead_id')              //Declaracion del campo
                ->references('id')                  //Declaracion 
                ->on('vt_facebook_leads')           //de la
                ->onDelete('cascade');              //relacion

        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vt_facebook_lead_archivos');
    }
};
