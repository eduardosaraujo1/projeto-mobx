<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Imobiliaria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $imobiliarias = Imobiliaria::all();
        Client::factory()
            ->count(15)
            ->recycle($imobiliarias)
            ->create();
    }
}
