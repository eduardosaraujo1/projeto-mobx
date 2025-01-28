<?php

namespace Database\Factories;

use App\Enums\ClientType;
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
            'type' => ClientType::randomId(),
            'imobiliaria_id' => Imobiliaria::factory()
        ];
    }
}
