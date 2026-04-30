<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post; 
use App\Models\Postinertia;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name'  => 'Admin User',
            'email' => 'admin@example.com',
            'role'  => 'admin',
        ]);

        User::factory()->create([
            'name'  => 'Regular User',
            'email' => 'user@example.com',
            'role'  => 'user',
        ]);

        User::factory(98)->create();

        Post::factory(50)->create();

        Postinertia::factory(100)->create();
    }
}
