<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('All Bookings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($bookings->isEmpty())
                        <div class="text-center py-8">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">No Bookings in the System</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">There are no bookings in the system yet.</p>
                            <a href="{{ route('rooms.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                View Rooms
                            </a>
                        </div>
                    @else
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Guest
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Room Details
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Dates
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Amount
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($bookings as $booking)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $booking->user->name }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $booking->user->email }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                Room #{{ $booking->room->room_number }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $booking->room->type }} - {{ $booking->room->capacity }} Person(s)
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 dark:text-gray-100">
                                                Check-in: {{ \Carbon\Carbon::parse($booking->check_in)->format('M d, Y') }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                Check-out: {{ \Carbon\Carbon::parse($booking->check_out)->format('M d, Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                ({{ $booking->nights }} nights)
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                ${{ number_format($booking->total_amount, 2) }}
                                            </div>
                                            @if($booking->payment)
                                                <div class="text-xs text-green-600 dark:text-green-400">
                                                    Paid
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                                   ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                   'bg-red-100 text-red-800') }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium">
                                            <div class="space-y-2">
                                                @if($booking->status === 'pending')
                                                    <a href="{{ route('payments.create', ['booking' => $booking->id]) }}" 
                                                       class="block text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                        Complete Payment
                                                    </a>
                                                @elseif($booking->status === 'confirmed')
                                                    <span class="text-green-600 dark:text-green-400">
                                                        Booking Confirmed
                                                    </span>
                                                @endif

                                                <a href="{{ route('bookings.show', $booking) }}" 
                                                   class="block text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                    View Details
                                                </a>

                                                @if($booking->status === 'pending')
                                                    <form method="POST" action="{{ route('bookings.destroy', $booking) }}" 
                                                          onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                            Cancel Booking
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
