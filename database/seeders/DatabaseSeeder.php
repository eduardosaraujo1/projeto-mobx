<?php

namespace Database\Seeders;

use App\Models\Access;
use App\Models\Client;
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

        $users = User::factory()->count(5)->create();

        $imobiliarias = Imobiliaria::factory()
            ->count(5)
            ->hasAttached($users, fn() => [
                'level' => rand(0, 1),
            ])->create();


        $imobiliarias->each(function ($imobiliaria) {
            Client::factory()->for($imobiliaria)->create();
        });
    }
}
