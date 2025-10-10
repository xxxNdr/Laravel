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
        Schema::table('prenotazioni', function (Blueprint $table) {
            $table->foreign(['id_cliente'], 'prenotazioni_ibfk_1')->references(['id'])->on('clienti')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['id_trattamento'], 'prenotazioni_ibfk_2')->references(['id'])->on('trattamenti')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prenotazioni', function (Blueprint $table) {
            $table->dropForeign('prenotazioni_ibfk_1');
            $table->dropForeign('prenotazioni_ibfk_2');
        });
    }
};
