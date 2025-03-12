<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Booking::with(['room', 'payment']);
        
        // Only show user's own bookings unless they're an admin
        if (Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        } else {
            $query->with('user'); // Load user info for admin view
        }
        
        $bookings = $query->orderBy('created_at', 'desc')->get();
        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!$request->has('room')) {
            return redirect()->route('rooms.index')
                ->with('error', 'Please select a room to book.');
        }

        $room = Room::findOrFail($request->room);
        
        if (!$room->isAvailable()) {
            return redirect()->route('rooms.index')
                ->with('error', 'Sorry, this room is no longer available.');
        }

        return view('bookings.create', compact('room'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
        ]);

        $room = Room::findOrFail($request->room_id);
        
        if (!$room->isAvailable()) {
            return redirect()->route('rooms.index')
                ->with('error', 'Sorry, this room is no longer available.');
        }

        // Calculate nights and total amount
        $checkIn = Carbon::parse($validated['check_in']);
        $checkOut = Carbon::parse($validated['check_out']);
        $nights = $checkIn->diffInDays($checkOut);
        $totalAmount = $room->price_per_night * $nights;

        // Create booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'room_id' => $validated['room_id'],
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'nights' => $nights,
            'total_amount' => $totalAmount,
            'status' => 'pending'
        ]);

        // Update room status
        $room->update(['status' => 'occupied']);

        return redirect()->route('payments.create', ['booking' => $booking->id])
            ->with('success', 'Room booked successfully. Please complete your payment.');
    }

    /**
     * Show the specified resource.
     */
    public function show(Booking $booking)
    {
        if (Auth::user()->role !== 'admin' && $booking->user_id !== Auth::id()) {
            return redirect()->route('bookings.index')
                ->with('error', 'You are not authorized to view this booking.');
        }

        return view('bookings.show', compact('booking'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        if (Auth::user()->role !== 'admin' && $booking->user_id !== Auth::id()) {
            return redirect()->route('bookings.index')
                ->with('error', 'You are not authorized to cancel this booking.');
        }
        
        if ($booking->status === 'confirmed') {
            return back()->with('error', 'Cannot cancel a confirmed booking.');
        }

        $room = $booking->room;
        $room->update(['status' => 'available']);
        
        $booking->delete();

        return redirect()->route('bookings.index')
            ->with('success', 'Booking cancelled successfully.');
    }
}
