<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Booking Dashboard') }}
        </h2>
    </x-slot>

    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <div class="max-w-full">
            @forelse ($bookings as $booking)
                <x-row-card>
                    <div class="w-full flex flex-row items-center gap-4">
                        <div class="flex w-full gap-4">
                            <p class="font-semibold text-gray-900">{{ $booking->name }}</p>
                            <p class="font-semibold text-gray-600">{{ $booking->user_name }}</p>
                            <p class="font-semibold text-gray-600">{{ $booking->status }}</p>
                        </div>
                        <div>
                            <form method="POST" action="{{ route('admin.booking.status', $booking->id) }}" class="flex gap-2 md:flex-row flex-col">
                                @csrf
                                @method('PATCH')
                                <x-edit-button type="submit" name="status" value="accepted">
                                    {{ __('Accept') }}
                                </x-edit-button>
                                <x-delete-button type="submit" name="status" value="declined">
                                    {{ __('Decline') }}
                                </x-delete-button>
                            </form>
                        </div>
                    </div>
                </x-row-card>
            @empty
                <p>No bookings found.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>