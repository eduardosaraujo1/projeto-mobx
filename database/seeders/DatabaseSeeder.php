<?php

namespace Database\Seeders;

use App\Enums\AccessLevel;
use App\Models\Client;
use App\Models\Imobiliaria;
use App\Models\Imovel;
use App\Models\ImovelDocument;
use App\Models\ImovelLog;
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
            'password' => bcrypt('12345678'),
            'is_admin' => true,
        ]);

        // Regular users
        /**
         * @var \Illuminate\Database\Eloquent\Collection<int, User>
         */
        $users = User::factory()->count(15)->create();

        // Imobiliarias
        $imobiliarias = Imobiliaria::factory()->count(15)->recycle($users)->create();

        // Add access for user from imobiliaria (each user may have one or more imobiliaria)
        $users
            ->shift() // remove first user (admin)
            ->each(
                function (User $user) use ($imobiliarias): void {
                    // Get random imobiliaria(s), count between 1 and 3, for each user
                    $userImobiliarias = $imobiliarias->random(rand(min: 1, max: 3));

                    // attach only needs the IDs, so remove the unnecessary info with pluck
                    $ids = $userImobiliarias->pluck('id');

                    // To pass the 'level' parameter, each value from array must be associated with the array of column values
                    // read https://laravel.com/docs/11.x/eloquent-relationships#updating-many-to-many-relationships (For convenience, attach and detach also accept arrays of IDs as input)
                    $attach = $ids->mapWithKeys(fn($id) => [
                        $id => ['level' => AccessLevel::randomId()]
                    ]);

                    // attach the imobiliarias to the user
                    $user->imobiliarias()->attach($attach);
                }
            );

        // Clients
        $clients = Client::factory()
            ->count(15)
            ->recycle($imobiliarias)
            ->create();

        // Imoveis
        $imoveis = Imovel::factory()
            ->count(15)
            ->recycle($clients)
            ->create();

        // Fake documents
        ImovelDocument::factory()
            ->count(5)
            ->recycle($imoveis)
            ->create();

        // Fake logs
        ImovelLog::factory()
            ->count(30)
            ->recycle($imoveis)
            ->recycle($users)
            ->create();
    }
}
