<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    public function run()
    {
        Room::create([
            'room_number' => '101',
            'type' => 'Single',
            'capacity' => 1,
            'price_per_night' => 30.00,
            'status' => 'available',
        ]);

        Room::create([
            'room_number' => '102',
            'type' => 'Double',
            'capacity' => 2,
            'price_per_night' => 50.00,
            'status' => 'available',
        ]);

        Room::create([
            'room_number' => '201',
            'type' => 'Dorm',
            'capacity' => 4,
            'price_per_night' => 20.00,
            'status' => 'available',
        ]);

        Room::create([
            'room_number' => '202',
            'type' => 'Dorm',
            'capacity' => 6,
            'price_per_night' => 25.00,
            'status' => 'available',
        ]);

        Room::create([
            'room_number' => '301',
            'type' => 'Single',
            'capacity' => 1,
            'price_per_night' => 35.00,
            'status' => 'available',
        ]);
    }
}
