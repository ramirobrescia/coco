<?php

namespace Database\Seeders;

use App\Models\Provider;
use App\Models\Purchase;
use App\Models\Task;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $users = User::factory(5)->create();
        $providers = Provider::factory(5)->create();

        Task::factory()->createMany([
            ['name' => 'Recibir pedido'],
            ['name' => 'Realizar pedido'],
            ['name' => 'Juntar dinero'],
            ['name' => 'Juntar dinero'],
        ]);

        $elements = collect($users);
        $elements = $elements->merge($providers);
        
        $purchases = Purchase::factory(10)
            ->recycle($elements)
            ->create();

    }
}
