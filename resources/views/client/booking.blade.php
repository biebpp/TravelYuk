<div x-data="{ 
    selectedUser: { id: null, name: '', bundle_id: '', status: 'pending' },
    clearErrors() {
        document.querySelectorAll('.validation-error-container').forEach(el => el.innerHTML = '');
    },
    openPaymentModal(booking) {
        const bundle = booking.bundle || {};
        this.selectedUser = { 
            id: booking.id,
            name: booking.name,
            price: bundle.price || '0',
            slot: bundle.slot ?? 'Unlimited',
            bundle_name: bundle.name || '',
            description: bundle.description || '',
            destinations: bundle.destinations || [],
            destination_ids: (bundle.destinations || []).map(d => d.id.toString()) 
        };
        $dispatch('open-modal', 'payment-modal');
    },
    openDeleteModal(id, name) {
        this.selectedUser = { id, name };
        $dispatch('open-modal', 'delete-modal');
    }
}">
    <x-app-layout>
        <x-slot name="header">
            <div class="flex w-full justify-between items-center lg:flex-row">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Your Booking') }}
                </h2>
            </div>
        </x-slot>

        <x-table-container>
            @forelse ($bookings as $booking)
                <x-row-card>
                    <div class="w-full">
                        <div>
                            <div class="flex flex-col">
                                <p class="text-xl font-semibold">{{ $booking->bundle->name }}</p>
                                <div class="flex flex-row font-semibold text-gray-600">
                                    <div class="flex flex-col w-full text-gray-600 text-md mb-2">
                                        <span>
                                            Status :
                                            @if ($booking->status == "payment")
                                                under-{{ $booking->status }}
                                            @else
                                                {{ $booking->status }}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex justify-end gap-1">
                                        @if ($booking->status == "payment")
                                            <x-edit-button class="h-fit"
                                                x-on:click="openPaymentModal({{ json_encode($booking) }})">
                                                {{ __('Pay') }}
                                            </x-edit-button>
                                        @endif
                                        <x-delete-button class="h-fit"
                                            x-on:click="openDeleteModal({{ $booking->id }}, '{{ addslashes($booking->name) }}')">
                                            Cancel
                                        </x-delete-button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-row-card>
            @empty
                <p>There is no Booking.</p>
            @endforelse
        </x-table-container>
    </x-app-layout>

    @include('client.partials.delete-modal')
    @include('client.partials.payment-modal')
</div>