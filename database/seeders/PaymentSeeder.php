<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        // Get the first two bookings
        $bookings = Booking::take(2)->get();
        
        foreach ($bookings as $index => $booking) {
            Payment::create([
                'booking_id' => $booking->id,
                'amount' => $booking->total_amount,
                'payment_method' => 'stripe',
                'status' => 'completed',
                'transaction_id' => 'txn_' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
            ]);
        }
    }
}
