<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('vendite', function (Blueprint $table) {
            $table->id();
            $table->string('agente');
            $table->decimal('importo', 6, 2);
            $table->date('data_vendita');
            $table->decimal('provvigione', 6, 2)->nullable();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('vendite');
    }
};
