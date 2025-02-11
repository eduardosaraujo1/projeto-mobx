<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@mobx.com',
            'password' => bcrypt('admin'),
            'is_admin' => true,
        ]);

        // Generic user
        User::factory()->create([
            'name' => 'Generic',
            'email' => 'generic@mobx.com',
            'password' => bcrypt('generic'),
        ]);

        $this->call([
            UserSeeder::class,
            ImobiliariaSeeder::class,
            ClientSeeder::class,
            ImovelSeeder::class,
        ]);

    }
}
