<?php

namespace Database\Factories;

use App\Models\Imovel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ImovelLog>
 */
class ImovelLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => implode(' ', fake()->words(2)),
            'description' => fake()->sentence(),
            'imovel_id' => Imovel::factory(),
            'user_id' => User::factory(),
        ];
    }
}
