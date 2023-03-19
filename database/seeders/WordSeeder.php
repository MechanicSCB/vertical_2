<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement("
                                INSERT INTO words (word)
                                SELECT word
                                FROM ts_stat('SELECT to_tsvector(''simple'', name) FROM products');
                            ");
    }
}
