<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Imobiliaria;
use App\Models\Imovel;
use App\Models\ImovelDocument;
use App\Models\ImovelLog;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class ImovelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Imoveis
        $imoveis = collect();
        $imobiliarias = Imobiliaria::all();
        $imobiliarias->each(function (Imobiliaria $imobiliaria) use (&$imoveis) {
            $imovel = Imovel::factory()
                ->count(5)
                ->recycle($imobiliaria)
                ->create();

            // insert into imoveis collection with duplicate prevention
            $imoveis->merge($imovel);
        });

        // Fake documents
        $users = User::all();
        $imoveis->each(function ($imovel) use ($users) {
            ImovelDocument::factory()
                ->recycle($imovel)
                ->create();
            ImovelLog::factory()
                ->count(3)
                ->recycle($imovel)
                ->recycle($users)
                ->create();
        });
    }
}
