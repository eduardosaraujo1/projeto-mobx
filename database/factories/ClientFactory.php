<?php

namespace Database\Factories;

use App\Models\Imobiliaria;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cpf' => fake()->bothify('###########'),
            'name' => fake()->name(),
            'contact' => fake()->email(),
            'address' => fake()->address(),
            'type' => fake()->numberBetween(0, 1), // 0 is locator, 1 is vendedor
            'imobiliaria_id' => Imobiliaria::factory()
        ];
    }
}
