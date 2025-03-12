<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use App\Models\Room;
use Carbon\Carbon;

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
            'check_out' => '2025-07-15',
            'total_amount' => $room102->price_per_semester,
            'status' => 'confirmed',
        ]);

        Booking::create([
            'user_id' => $jane->id,
            'room_id' => $room201->id,
            'check_in' => '2025-08-15',
            'check_out' => '2025-12-15',
            'total_amount' => $room201->price_per_semester,
            'status' => 'confirmed',
        ]);
    }
}
