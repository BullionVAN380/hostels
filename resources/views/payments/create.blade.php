<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Complete Payment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Booking Summary -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Booking Summary</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Room Number</p>
                                    <p class="text-base font-medium text-gray-900 dark:text-gray-100">
                                        #{{ $booking->room->room_number }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Room Type</p>
                                    <p class="text-base font-medium text-gray-900 dark:text-gray-100">
                                        {{ $booking->room->type }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Check-in Date</p>
                                    <p class="text-base font-medium text-gray-900 dark:text-gray-100">
                                        {{ $booking->check_in->format('M d, Y') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Check-out Date</p>
                                    <p class="text-base font-medium text-gray-900 dark:text-gray-100">
                                        {{ $booking->check_out->format('M d, Y') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Duration</p>
                                    <p class="text-base font-medium text-gray-900 dark:text-gray-100">
                                        One Semester
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Price per Semester</p>
                                    <p class="text-base font-medium text-gray-900 dark:text-gray-100">
                                        KSh {{ number_format($booking->room->price_per_semester, 2) }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                                <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    Total Amount: KSh {{ number_format($booking->total_amount, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Form -->
                    <form method="POST" action="{{ route('payments.store', $booking) }}" class="space-y-6">
                        @csrf
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Payment Method</h3>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="text-gray-600 dark:text-gray-400 mb-4">
                                    For demonstration purposes, this is a simplified payment process. 
                                    In a real application, this would integrate with M-PESA or other payment methods.
                                </p>
                                <button type="submit"
                                        class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                    Pay KSh {{ number_format($booking->total_amount, 2) }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
