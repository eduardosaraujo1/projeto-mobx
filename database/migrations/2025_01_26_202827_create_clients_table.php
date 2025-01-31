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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('cpf', 11);
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->unsignedTinyInteger('type'); // rents or sells (locador ou vendedor)
            $table->timestamps();
            $table->foreignId('imobiliaria_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
