<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Imobiliaria>
 */
class ImobiliariaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'cnpj' => fake()->bothify(str_repeat('#', 14)),
            'address' => fake()->address(),
            'email' => fake()->email(),
            'contact' => fake()->phoneNumber(),
        ];
    }
}
