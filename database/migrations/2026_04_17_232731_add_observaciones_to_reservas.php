<?php
// database/migrations/2024_xxxx_xxxxxx_add_observaciones_to_reservas.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->text('Observaciones')->nullable()->after('TotalReserva');
        });
    }
    
    public function down(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropColumn('Observaciones');
        });
    }
};