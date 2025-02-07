<?php

namespace Database\Seeders;

use App\Models\Imovel;
use App\Models\ImovelDocument;
use Illuminate\Database\Seeder;

class ImovelDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Imovel::all()->each(function (Imovel $imovel) {
            ImovelDocument::factory()
                ->count(3)
                ->recycle($imovel)
                ->create();
        });
    }
}
