<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Booking Dashboard') }}
        </h2>
    </x-slot>

    <x-table-container>
        @forelse ($bookings as $booking)
            @if (!($booking->status == "payment"))
                <x-row-card>
                    <div class="w-full flex flex-row items-center gap-4">
                        <div class="flex w-full gap-4">
                            <div class="flex flex-col">
                                <p class="text-lg font-semibold text-black">{{ $booking->name }}</p>
                                @if ($booking->bundle)
                                    <p class="font-semibold text-gray-600"> Paket yang di pilih : {{ $booking->bundle->name }}</p>
                                @endif
                                <div class="flex flex-row"> 
                                    <p class="font-semibold text-gray-600">Status : {{ $booking->status }}</p>
                                </div>
                            </div>
                        </div>
                        <div>
                            @if ($booking->status == "pending")
                            <form method="POST" action="{{ route('admin.booking.status', $booking->id) }}"
                                class="flex gap-2 md:flex-row flex-col">
                                @csrf
                                @method('PATCH')
                                <x-edit-button type="submit" name="status" value="accepted">
                                    {{ __('Accept') }}
                                </x-edit-button>
                                <x-delete-button type="submit" name="status" value="declined">
                                    {{ __('Decline') }}
                                </x-delete-button>
                            </form>
                            @endif
                        </div>
                    </div>
                </x-row-card>
            @endif
        @empty
            <p>No bookings found.</p>
        @endforelse
    </x-table-container>
</x-app-layout>