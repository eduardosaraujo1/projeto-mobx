<?php

namespace Database\Seeders;

use App\Enums\AccessLevel;
use App\Models\Client;
use App\Models\Imobiliaria;
use App\Models\Imovel;
use App\Models\ImovelDocument;
use App\Models\ImovelLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Creates the Collection to be attached to the BelongsToMany relationship between User and Imobiliaria
     * First it chooses some imobiliarias from the parameter `imobiliarias`
     * Then it lists all the IDs of the imobiliarias
     * Then it turns the id array into an array of associative arrays with the `level` property for the pivot table
     * @param \Illuminate\Database\Eloquent\Collection<int, Imobiliaria> $imobiliarias
     * @return \Illuminate\Support\Collection<int|string, array{level: int|string>}
     */
    private static function generateUserImobiliarias(Collection $imobiliarias)
    {
        // Get random imobiliaria list with random length
        $userImobiliarias = $imobiliarias->random(rand(min: 1, max: 3));

        // BelongsToMany->Attach only needs the IDs, so remove the unnecessary info with pluck
        $ids = $userImobiliarias->pluck('id');

        // BelongsToMany->Attach accepts associative array with the other properties of the pivot table
        // read https://laravel.com/docs/11.x/eloquent-relationships#updating-many-to-many-relationships
        $attachments = $ids->mapWithKeys(fn($id) => [
            $id => ['level' => AccessLevel::randomId()]
        ]);

        return $attachments;
    }

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
        $users = User::factory()->count(15)->create();

        // Imobiliarias
        $imobiliarias = Imobiliaria::factory()->count(15)->recycle($users)->create();

        // Add access for user from imobiliaria (each user may have one or more imobiliaria)
        $users
            ->shift() // remove first user (admin)
            ->each(
                function (User $user) use ($imobiliarias) {
                    $attachments = static::generateUserImobiliarias($imobiliarias);
                    // attach the imobiliarias to the user
                    $user->imobiliarias()->attach($attachments);
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
