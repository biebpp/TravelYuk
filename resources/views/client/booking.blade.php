<div x-data="{ 
    selectedUser: { id: null, name: '', bundle_id: '', status: 'pending' },
    clearErrors() {
        document.querySelectorAll('.validation-error-container').forEach(el => el.innerHTML = '');
    },
    openPaymentModal(id, name) {
        this.selectedUser = { id, name };
        $dispatch('open-modal', 'payment-modal');
    },
    openCreateModal() {
        this.clearErrors();
        this.selectedUser = { id: null, name: '', bundle_id: '', status: 'pending' };
        $dispatch('open-modal', 'create-modal');
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
                    {{ __('Booking Dashboard') }}
                </h2>
                <div class="flex justify-end px-12">
                    <x-edit-button x-on:click="openCreateModal()">
                        {{ __('Add Booking') }}
                    </x-edit-button>
                </div>
            </div>
        </x-slot>

        <x-table-container>
            @forelse ($bookings as $booking)
                <x-row-card>
                    <div class="w-full">
                        <div>
                            <div class="flex flex-col">
                                <p class="text-xl font-semibold">{{ $booking->name }}</p>
                                <div class="flex flex-row">
                                    <div class="flex flex-col w-full text-gray-600 text-sm mb-2">
                                        @if ($booking->bundle)
                                            <span>
                                                Paket yang Dipilih : {{ $booking->bundle->name }}
                                            </span>
                                        @endif
                                        <span>
                                            Status : {{ $booking->status }}
                                        </span>
                                    </div>
                                    <div class="flex justify-end gap-1">
                                        @if ($booking->status == "payment")
                                            <x-edit-button class="h-fit" x-on:click="openPaymentModal({{ $booking->id }}, '{{ addslashes($booking->name) }}')">
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
    
    @include('client.partials.create-modal')
    @include('client.partials.delete-modal')
    @include('client.partials.payment-modal')
</div>