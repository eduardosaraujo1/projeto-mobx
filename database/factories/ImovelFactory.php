<?php

namespace Database\Factories;

use App\Enums\ImovelLocation;
use App\Enums\ImovelStatus;
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
            'status' => ImovelStatus::randomId(),
            'address_name' => fake()->streetName(),
            'address_number' => rand(1, 600),
            'bairro' => fake()->city(),
            'location_reference' => ImovelLocation::randomId(),
            'client_id' => Client::factory(),
        ];
    }
}
