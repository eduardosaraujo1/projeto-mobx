<?php

namespace Database\Seeders;

use App\Models\Imobiliaria;
use App\Models\Imovel;
use Illuminate\Database\Seeder;

class ImovelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Imoveis
        Imobiliaria::all()->each(function (Imobiliaria $imobiliaria) use (&$imoveis) {
            Imovel::factory()
                ->count(5)
                ->recycle($imobiliaria)
                ->create();
        });
    }
}
