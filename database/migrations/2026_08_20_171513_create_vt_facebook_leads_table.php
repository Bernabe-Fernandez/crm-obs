
//Tabla de leads que recibiremos de meta

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
        Schema::create('vt_facebook_leads', function (Blueprint $table) {

            $table->id();                                      // ID interno del lead
            $table->unsignedBigInteger('form_id');             // Relación con vt_facebook_forms
            $table->string('facebook_lead_id')->nullable();    // ID del lead en Meta
            $table->string('nombre')->nullable();              // Nombre del prospecto
            $table->string('correo')->nullable();              // Correo del prospecto
            $table->string('telefono')->nullable();            // Teléfono del prospecto
            $table->string('ciudad')->nullable();              // Ciudad del prospecto
            $table->string('plataforma')->nullable();          // Facebook o Instagram
            $table->string('vendedor')->nullable();            // Asignado desde el CRM
            $table->enum('estatus', ['nuevo', 'en_proceso', 'cerrado'])
                ->default('nuevo');                          // Estado del seguimiento
            $table->timestamp('fecha_lead')->nullable();       // Fecha y hora del lead
            $table->timestamps();


            // Relación con la tabla vt_facebook_forms

            $table->foreign('form_id')                 //Declaracion del campo 
                ->references('id')                     //Declaraion 
                ->on('vt_facebook_forms')              //de la 
                ->onDelete('cascade');                 //relacion
                
        });
    }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('vt_facebook_leads');
        }
    };
