<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Admins;
use App\Models\Clients;
use App\Models\Products;
use Illuminate\Database\Seeder;
use Illuminate\Support\Testing\Fakes;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Admins::create(['name' => 'master', 'email' => 'admin@email.com.br', 'password' => 'adm!n@123']);
        $name = fake()->name();
        $email = str_replace(" ", "", strtolower($name));
        Clients::create(['name' => $name, 'email' => "$email@email.com.br", 'type_document' => 'cpf', 'document' => '000.000.000-00']);
        Products::create(['name' => 'Camisa Polo M', 'stock_quantity' => rand(1, 50), 'unity_price' => rand(200, 900) / 10]);
    }
}
