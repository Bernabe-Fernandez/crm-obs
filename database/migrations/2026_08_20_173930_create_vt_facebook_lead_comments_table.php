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
        Schema::create('vt_facebook_lead_comments', function (Blueprint $table) {

            $table->id();                                      // ID interno del comentario
            $table->unsignedBigInteger('lead_id');             // Relación con vt_facebook_leads
            $table->text('comentario')->nullable();            // Texto del comentario
            $table->string('usuario')->nullable();             // Quién hizo el comentario
            $table->timestamps();                              //ya cubre la fecha y hora del comentario


            // Relación con la tabla vt_facebook_leads

            $table->foreign('lead_id')           //Declaracion del campo
                ->references('id')               //Declaracion 
                ->on('vt_facebook_leads')        //de la
                ->onDelete('cascade');           //relacion

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vt_facebook_lead_comments');
    }
};
