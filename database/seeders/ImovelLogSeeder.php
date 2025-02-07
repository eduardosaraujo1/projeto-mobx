<?php

namespace Database\Seeders;

use App\Models\Imovel;
use App\Models\ImovelLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class ImovelLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        Imovel::all()->each(function (Imovel $imovel) use ($users) {
            ImovelLog::factory()
                ->count(3)
                ->recycle($imovel)
                ->recycle($users)
                ->create();
        });
    }
}
