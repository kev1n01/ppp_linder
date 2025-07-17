<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ser_name' => $this->faker->word() . ' Service',
            'ser_description' => $this->faker->sentence(),
            'ser_price' => $this->faker->randomFloat(2, 10, 500), // entre 10 y 500
            'ser_status' => $this->faker->boolean(90), // 90% activos
        ];
    }
}
