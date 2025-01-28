<?php

namespace Database\Factories;

use App\Models\Imovel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ImovelDocument>
 */
class ImovelDocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'filepath' => fake()->filePath(),
            'imovel_id' => Imovel::factory()
        ];
    }
}
