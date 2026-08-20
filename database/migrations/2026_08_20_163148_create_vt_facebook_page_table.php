
//Primera conexion con meta 

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
        Schema::create('vt_facebook_page', function (Blueprint $table) {

            $table->id();                                // ID interno
            $table->string('nombre')->nullable();        // Nombre de la página
            $table->string('facebook_id')->nullable();   // ID de la página en Meta
            $table->text('token')->nullable();           // Token de acceso
            $table->timestamps();                        // created_at / updated_at

        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vt_facebook_page');
    }
};


