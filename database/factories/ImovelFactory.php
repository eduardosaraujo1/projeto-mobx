<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Imovel>
 */
class ImovelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'value' => rand(1000, 100000),
            'iptu' => rand(1000, 100000),
            'status' => rand(0, 2),
            'address_name' => fake()->address(),
            'address_number' => rand(1, 600),
            'bairro' => fake()->word(),
            'lado_praia' => rand(0, 1),
            'client_id' => Client::factory(),
        ];
    }
}
