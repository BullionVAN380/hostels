<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Booking $booking)
    {
        // Check authorization
        if (Auth::user()->role !== 'admin' && $booking->user_id !== Auth::id()) {
            return redirect()->route('bookings.index')
                ->with('error', 'You are not authorized to make payment for this booking.');
        }

        // Check if already paid
        if ($booking->payment) {
            return redirect()->route('bookings.index')
                ->with('error', 'This booking has already been paid for.');
        }

        return view('payments.create', compact('booking'));
    }

    public function store(Request $request, Booking $booking)
    {
        // Check authorization
        if (Auth::user()->role !== 'admin' && $booking->user_id !== Auth::id()) {
            return redirect()->route('bookings.index')
                ->with('error', 'You are not authorized to make payment for this booking.');
        }

        // Check if already paid
        if ($booking->payment) {
            return redirect()->route('bookings.index')
                ->with('error', 'This booking has already been paid for.');
        }

        try {
            // Create payment record
            Payment::create([
                'booking_id' => $booking->id,
                'amount' => $booking->total_amount,
                'payment_method' => 'cash',
                'status' => 'completed',
                'transaction_id' => 'CASH-' . uniqid(),
            ]);

            // Update booking status
            $booking->update(['status' => 'confirmed']);

            return redirect()->route('bookings.index')
                ->with('success', 'Payment successful! Your booking has been confirmed.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Payment failed. Please try again.');
        }
    }
}
