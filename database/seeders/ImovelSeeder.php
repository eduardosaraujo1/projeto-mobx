<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Imovel;
use App\Models\ImovelDocument;
use App\Models\ImovelLog;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImovelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $clients = Client::all();

        // Imoveis
        $imoveis = Imovel::factory()
            ->count(150)
            ->recycle($clients)
            ->create();

        // Fake documents
        $imoveis->each(function ($imovel) use ($users) {
            ImovelDocument::factory()
                ->recycle($imovel)
                ->create();
            ImovelLog::factory()
                ->count(5)
                ->recycle($imovel)
                ->recycle($users)
                ->create();
        });
    }
}
