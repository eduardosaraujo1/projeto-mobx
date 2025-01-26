<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('imoveis', function (Blueprint $table) {
            $table->id();
            $table->decimal('value', 15, 2)->nullable();
            $table->decimal('iptu', 11, 2)->nullable();
            $table->unsignedSmallInteger('status'); // 0 is livre, 1 is alugado, 2 is vendido
            $table->string('address_name');
            $table->unsignedSmallInteger('address_number');
            $table->string('bairro');
            $table->boolean('lado_praia');
            $table->timestamps();
            $table->foreignId('client_id')->constrained()->noActionOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imoveis');
    }
};
