<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            KategoriAcaraSeeder::class,
            PaketBundlingSeeder::class,
            AhpRespondenSeeder::class,
            BookingKuisionerSpkSeeder::class,
        ]);
    }
}
