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

        // Create booking
        $booking = new Booking();
        $booking->user_id = Auth::id();
        $booking->room_id = $validated['room_id'];
        $booking->check_in = Carbon::parse($validated['check_in']);
        $booking->check_out = Carbon::parse($validated['check_out']);
        $booking->total_amount = $room->price_per_semester;
        $booking->status = 'pending';
        $booking->save();

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
     * Cancel a booking.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function cancel(Booking $booking)
    {
        // Check if user is authorized to cancel this booking
        if (Auth::user()->role !== 'admin' && $booking->user_id !== Auth::id()) {
            return redirect()->route('bookings.index')
                ->with('error', 'You are not authorized to cancel this booking.');
        }

        // Only pending bookings can be cancelled
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Only pending bookings can be cancelled.');
        }

        // Update booking status
        $booking->status = 'cancelled';
        $booking->save();

        // Make room available again
        $booking->room->status = 'available';
        $booking->room->save();

        return redirect()->route('bookings.index')
            ->with('success', 'Booking cancelled successfully.');
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
