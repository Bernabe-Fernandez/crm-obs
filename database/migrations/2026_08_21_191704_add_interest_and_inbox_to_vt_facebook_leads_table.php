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
        Schema::table('vt_facebook_leads', function (Blueprint $table) {
            // Agregar los nuevos campos
            $table->string('interest')->nullable()->after('ciudad');
          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vt_facebook_leads', function (Blueprint $table) {
            // Eliminar los campos si se revierte la migración
            $table->dropColumn(['interest']);
        });
    }
};
