<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            NodeSeeder::class,
            ProductSeeder::class,
        ]);
    }

    /**
     * Save json files from db tables
     */
    public function reverse(): void
    {
        (new CategorySeeder())->reverse();
        (new NodeSeeder())->reverse();
        (new ProductSeeder())->reverse();
    }
}
