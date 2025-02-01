<?php

namespace Database\Seeders;

use App\Enums\AccessLevel;
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
    private static function generateUserImobiliarias(Collection $imobiliarias): array
    {
        $levels = AccessLevel::cases();
        $attachments = [];

        foreach ($levels as $level) {
            $imobiliaria = $imobiliarias->random();
            $level_id = $level->value;
            $attachments[$imobiliaria->id] = ['level' => $level_id];
        }

        return $attachments;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $imobiliarias = Imobiliaria::factory()->count(15)->recycle($users)->create();

        // Add access for user from imobiliaria (each user may have one or more imobiliaria)
        $users
            ->shift() // remove first user (admin)
            ->each(
                function (User $user) use ($imobiliarias) {
                    $attachments = static::generateUserImobiliarias($imobiliarias);
                    // attach the imobiliarias to the user
                    // attach object format:
                    // [imobiliaria_id => ['level' => level_id]]
                    $user->imobiliarias()->attach($attachments);
                }
            );
    }
}
