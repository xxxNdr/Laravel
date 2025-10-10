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
        Schema::create('prenotazioni', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_cliente')->index('id_cliente');
            $table->integer('id_trattamento')->index('id_trattamento');
            $table->date('data');
            $table->time('ora_inizio');
            $table->time('ora_fine')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
            $table->foreign('id_cliente')->references('id')->on('clienti')->onDelete('cascade');
            $table->foreign('id_trattamento')->references('id')->on('trattamenti')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prenotazioni');
    }
};
