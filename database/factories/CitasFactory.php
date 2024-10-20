<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\citas>
 */
class CitasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'paciente_id' => $this->faker->numberBetween(1,20), 
            'medico_id' => $this->faker->numberBetween(1,10), 
            'fecha_cita' => $this->faker->dateTimeBetween('+1 days', '+1 month'), // Citas futuras
            'motivo' => $this->faker->sentence(6), // Motivo aleatorio
        ];
    }
}
