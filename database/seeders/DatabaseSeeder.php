<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\RoomSeeder;
use Database\Seeders\BookingSeeder;
use Database\Seeders\PaymentSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            RoomSeeder::class,
            BookingSeeder::class,
            PaymentSeeder::class,
        ]);
    }
}
