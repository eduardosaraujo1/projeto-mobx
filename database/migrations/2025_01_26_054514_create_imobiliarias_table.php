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
        Schema::create('imobiliarias', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('logo_path')->nullable();
            $table->string('contact');
            $table->timestamps();
        });

        // https://laravel.com/docs/11.x/eloquent-relationships#many-to-many
        // https://laravel.com/docs/11.x/eloquent-factories#pivot-table-attributes
        // Schema::create('acesso_imobiliarias', function (Blueprint $table) {
        //     $table->foreignId('user_id')->constrained();
        //     $table->foreignId('imobiliaria_id')->constrained();
        //     $table->unsignedSmallInteger('nivel_acesso'); // 0 is colaborador, 1 is gerente
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imobiliarias');
    }
};
