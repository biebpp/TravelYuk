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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Booking Dashboard') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-end px-12">
                    <x-edit-button x-on:click="openCreateModal()">
                        {{ __('Add User') }}
                    </x-edit-button>
                </div>
            </div>
        </div>
    </x-app-layout>
    @include('client.partials.create-modal');
</div>