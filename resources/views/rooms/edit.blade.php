<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Room') }} #{{ $room->room_number }}
            </h2>
            <a href="{{ route('rooms.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Rooms
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('rooms.update', $room) }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="room_number" class="block text-sm font-medium text-gray-700">Room Number</label>
                                <input type="text" name="room_number" id="room_number" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                    value="{{ old('room_number', $room->room_number) }}" 
                                    placeholder="e.g., 101" required>
                            </div>

                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">Room Type</label>
                                <select name="type" id="type" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                    required>
                                    <option value="">Select a type</option>
                                    <option value="Single" {{ old('type', $room->type) == 'Single' ? 'selected' : '' }}>Single Room</option>
                                    <option value="Double" {{ old('type', $room->type) == 'Double' ? 'selected' : '' }}>Double Room</option>
                                    <option value="Dorm" {{ old('type', $room->type) == 'Dorm' ? 'selected' : '' }}>Dormitory</option>
                                </select>
                            </div>

                            <div>
                                <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity (persons)</label>
                                <input type="number" name="capacity" id="capacity" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                    value="{{ old('capacity', $room->capacity) }}" 
                                    min="1" max="10" required>
                            </div>

                            <div>
                                <label for="price_per_night" class="block text-sm font-medium text-gray-700">Price per Night ($)</label>
                                <input type="number" step="0.01" name="price_per_night" id="price_per_night" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                    value="{{ old('price_per_night', $room->price_per_night) }}" 
                                    min="0" required>
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                    required>
                                    <option value="available" {{ old('status', $room->status) == 'available' ? 'selected' : '' }}>Available</option>
                                    <option value="occupied" {{ old('status', $room->status) == 'occupied' ? 'selected' : '' }}>Occupied</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end pt-6">
                            <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Update Room
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
