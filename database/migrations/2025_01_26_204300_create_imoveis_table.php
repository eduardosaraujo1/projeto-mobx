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
            $table->unsignedTinyInteger('location_reference')->nullable(); // 0 is praia, 1 is morro see ImovelLocation enum
            $table->decimal('value', 15, 2)->nullable();
            $table->decimal('iptu', 11, 2)->nullable();
            $table->unsignedTinyInteger('status')->default(0); // livre, alugado ou vendido see ImovelStatus enum
            $table->text('photo_path')->nullable();
            $table->timestamps();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('imobiliaria_id')->constrained()->cascadeOnDelete();
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
