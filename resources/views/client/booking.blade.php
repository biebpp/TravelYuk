<div x-data="{ 
    selectedUser: { id: null, name: '', status: 'pending' },
    clearErrors() {
        document.querySelectorAll('.validation-error-container').forEach(el => el.innerHTML = '');
    },
    openCreateModal() {
        this.clearErrors();
        this.selectedUser = { id: null, name: '', status: 'pending' };
        $dispatch('open-modal', 'create-modal');
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

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @forelse ($bookings as $booking)
                    <x-row-card>
                        <div class="w-full">
                            <p class="font-semibold">{{ $booking->name }}</p>
                            <p class="text-sm text-gray-600">{{ $booking->status }}</p>
                        </div>
                    </x-row-card>
                @empty
                    <p>There is no Booking.</p>
                @endforelse
            </div>
        </div>
    </x-app-layout>
    @include('client.partials.create-modal');
</div>