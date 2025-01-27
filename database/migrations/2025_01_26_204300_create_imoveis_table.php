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
            $table->string('address_name');
            $table->string('address_number', 4);
            $table->string('bairro');
            $table->boolean('is_lado_praia');
            $table->decimal('value', 15, 2)->nullable();
            $table->decimal('iptu', 11, 2)->nullable();
            $table->unsignedTinyInteger('status'); // livre, alugado ou vendido
            $table->timestamps();
            $table->foreignId('client_id')->constrained()->restrictOnDelete();
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
