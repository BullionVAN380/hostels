<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Available Rooms') }}
            </h2>
            @can('admin')
                <a href="{{ route('rooms.create') }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Add New Room
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2">Room</th>
                                    <th class="px-4 py-2">Type</th>
                                    <th class="px-4 py-2">Capacity</th>
                                    <th class="px-4 py-2">Price/Night</th>
                                    <th class="px-4 py-2">Status</th>
                                    <th class="px-4 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rooms as $room)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-2">#{{ $room->room_number }}</td>
                                        <td class="px-4 py-2">{{ $room->type }}</td>
                                        <td class="px-4 py-2">{{ $room->capacity }} Person(s)</td>
                                        <td class="px-4 py-2">${{ number_format($room->price_per_night, 2) }}</td>
                                        <td class="px-4 py-2">
                                            <span class="px-2 py-1 rounded text-sm {{ $room->status === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ ucfirst($room->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2">
                                            @if(auth()->user()->role === 'student')
                                                @if($room->status === 'available')
                                                    <a href="{{ route('bookings.create', ['room' => $room->id]) }}" 
                                                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                                        Book Now
                                                    </a>
                                                @else
                                                    <span class="text-gray-500">Not Available</span>
                                                @endif
                                            @else
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('rooms.edit', $room) }}" 
                                                       class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('rooms.destroy', $room) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                                                onclick="return confirm('Are you sure you want to delete this room?')">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
