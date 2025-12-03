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
        Schema::table('campo.solicitud_produccion', function (Blueprint $table) {
            $table->string('codigo_solicitud', 50)->unique()->after('solicitud_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campo.solicitud_produccion', function (Blueprint $table) {
            $table->dropColumn('codigo_solicitud');
        });
    }
};
