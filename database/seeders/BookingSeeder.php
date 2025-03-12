<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use App\Models\Room;

class BookingSeeder extends Seeder
{
    public function run()
    {
        $john = User::where('email', 'john@hostel.com')->first();
        $jane = User::where('email', 'jane@hostel.com')->first();

        $room102 = Room::where('room_number', '102')->first();
        $room201 = Room::where('room_number', '201')->first();

        Booking::create([
            'user_id' => $john->id,
            'room_id' => $room102->id,
            'check_in' => '2025-03-15',
            'check_out' => '2025-03-20',
            'nights' => 5,
            'total_amount' => 5 * $room102->price_per_night,
            'status' => 'confirmed',
        ]);

        Booking::create([
            'user_id' => $jane->id,
            'room_id' => $room201->id,
            'check_in' => '2025-03-18',
            'check_out' => '2025-03-25',
            'nights' => 7,
            'total_amount' => 7 * $room201->price_per_night,
            'status' => 'confirmed',
        ]);
    }
}
