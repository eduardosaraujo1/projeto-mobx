<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Imobiliaria;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class ImobiliariaSeeder extends Seeder
{    /**
     * Creates the Collection to be attached to the BelongsToMany relationship between User and Imobiliaria
     * First it chooses some imobiliarias from the parameter `imobiliarias`
     * Then it lists all the IDs of the imobiliarias
     * Then it turns the id array into an array of associative arrays with the `level` property for the pivot table
     * @param \Illuminate\Database\Eloquent\Collection<int, Imobiliaria> $imobiliarias
     * @return \Illuminate\Support\Collection<int|string, array{level: int|string>}
     */
    private static function generateallImobiliarias(): array
    {
        // $imobiliarias = Imobiliaria::factory()->count($count)->create();
        $levels = UserRole::cases();

        $attachments = [];

        // make each user be in every level of a random imobiliaria
        foreach ($levels as $level) {
            $imobiliaria = Imobiliaria::factory()->create();
            $level_id = $level->value;
            $attachments[$imobiliaria->id] = ['role' => $level_id];
        }

        return $attachments;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        // For each user, create imobiliarias and attach access level
        $users->skip(1)->each(
            function (User $user) {
                $attachments = static::generateallImobiliarias();
                // attach imobiliarias to the user's imobiliaria list (aquired through user->imobiliarias())
                // object format:
                // [imobiliaria_id => ['level' => level_id], ...]
                $user->imobiliarias()->attach($attachments);
            }
        );
    }
}
