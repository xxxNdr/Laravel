<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class ClienteFactory extends Factory
{
    protected $model = \App\Models\Cliente::class;
    public function definition(): array
    {
        return [
            'nome' => fake()->firstName(),
            'cognome' => fake()->lastName(),
            'telefono' => fake()->unique()->phoneNumber(),
        ];
    }
}
