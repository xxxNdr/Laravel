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
        Schema::create('orari_apertura', function (Blueprint $table) {
            $table->integer('id', true);
            $table->tinyInteger('giorno_settimana');
            $table->time('ora_inizio_mattina')->nullable();
            $table->time('ora_fine_mattina')->nullable();
            $table->time('ora_inizio_pomeriggio')->nullable();
            $table->time('ora_fine_pomeriggio')->nullable();
            $table->boolean('aperto')->nullable()->default(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orari_apertura');
    }
};
