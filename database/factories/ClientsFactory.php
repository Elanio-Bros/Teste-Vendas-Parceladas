<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Clients>
 */
class ClientsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->name();
        $email = str_replace(" ", "_", strtolower($name));
        $code = rand(10, 99);

        return [
            'name' => $name,
            'email' => $email,
            'type_document' => 'cpf',
            'document' => "000.000.000-{$code}"
        ];
    }
}
