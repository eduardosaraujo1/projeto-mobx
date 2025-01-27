<?php

namespace Database\Seeders;

use App\Models\Access;
use App\Models\Imobiliaria;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@mobx.com',
            'password' => bcrypt('12345678'),
            'is_admin' => true,
        ]);

        $count = 5;
        Imobiliaria::factory()
            ->count($count)
            ->hasAttached(User::factory()->count($count), [
                'level' => rand(
                    Access::ACCESS_LEVEL_COLABORADOR,
                    Access::ACCESS_LEVEL_GERENTE,
                ),
            ])->create();
    }
}
