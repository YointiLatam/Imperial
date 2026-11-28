<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Los modelos generan su slug en eventos, así que aquí no se usa WithoutModelEvents.
     */
    public function run(): void
    {
        $this->call([
            SistemaSeeder::class,
            SuperadminSeeder::class,
        ]);

        if (app()->environment('local')) {
            $this->call(DemoSeeder::class);
        }
    }
}
