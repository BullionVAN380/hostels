<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    public function run()
    {
        $rooms = [
            [
                'room_number' => '101',
                'type' => 'Single',
                'capacity' => 1,
                'price_per_semester' => 30000,
                'status' => 'available'
            ],
            [
                'room_number' => '102',
                'type' => 'Double',
                'capacity' => 2,
                'price_per_semester' => 25000,
                'status' => 'available'
            ],
            [
                'room_number' => '103',
                'type' => 'Triple',
                'capacity' => 3,
                'price_per_semester' => 20000,
                'status' => 'available'
            ],
            [
                'room_number' => '201',
                'type' => 'Single',
                'capacity' => 1,
                'price_per_semester' => 32000,
                'status' => 'available'
            ],
            [
                'room_number' => '202',
                'type' => 'Double',
                'capacity' => 2,
                'price_per_semester' => 27000,
                'status' => 'available'
            ]
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}
