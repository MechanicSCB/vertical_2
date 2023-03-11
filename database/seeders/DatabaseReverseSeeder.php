<?php

namespace Database\Seeders;


class DatabaseReverseSeeder
{
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
