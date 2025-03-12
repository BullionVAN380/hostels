<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rooms = Room::all();
        return view('rooms.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('rooms.index')
                ->with('error', 'Unauthorized access. Admin privileges required.');
        }
        return view('rooms.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('rooms.index')
                ->with('error', 'Unauthorized access. Admin privileges required.');
        }

        $validated = $request->validate([
            'room_number' => 'required|string|unique:rooms',
            'type' => 'required|in:Single,Double,Dorm',
            'capacity' => 'required|integer|min:1|max:10',
            'price_per_night' => 'required|numeric|min:0',
            'status' => 'required|in:available,occupied',
        ]);

        Room::create($validated);

        return redirect()->route('rooms.index')
            ->with('success', 'Room created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Room $room)
    {
        return view('rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Room $room)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('rooms.index')
                ->with('error', 'Unauthorized access. Admin privileges required.');
        }
        return view('rooms.edit', compact('room'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Room $room)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('rooms.index')
                ->with('error', 'Unauthorized access. Admin privileges required.');
        }

        $validated = $request->validate([
            'room_number' => 'required|string|unique:rooms,room_number,' . $room->id,
            'type' => 'required|in:Single,Double,Dorm',
            'capacity' => 'required|integer|min:1|max:10',
            'price_per_night' => 'required|numeric|min:0',
            'status' => 'required|in:available,occupied',
        ]);

        $room->update($validated);

        return redirect()->route('rooms.index')
            ->with('success', 'Room updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Room $room)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('rooms.index')
                ->with('error', 'Unauthorized access. Admin privileges required.');
        }

        if ($room->bookings()->exists()) {
            return back()->with('error', 'Cannot delete room with existing bookings.');
        }

        $room->delete();

        return redirect()->route('rooms.index')
            ->with('success', 'Room deleted successfully.');
    }
}
