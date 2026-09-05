<div x-data="{ 
    selectedUser: { id: null, name: '', description: '', address: '' },
    clearErrors() {
        document.querySelectorAll('.validation-error-container').forEach(el => el.innerHTML = '');
    },
    openEditModal(destination) {
        this.clearErrors();
        this.selectedUser = { ...destination };
        $dispatch('open-modal', 'edit-modal');
    },
    openCreateModal() {
        this.clearErrors();
        this.selectedUser = { id: null, name: '', description: '', address: '' };
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
                    {{ __('Destination Dashboard') }}
                </h2>
                <div class="flex justify-end px-12">
                    <x-edit-button x-on:click="openCreateModal()">
                        {{ __('Add Destination') }}
                    </x-edit-button>
                </div>
            </div>
        </x-slot>

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-full">
                @forelse ($destinations as $destination)
                    <x-row-card>
                        <div class="w-full flex flex-row items-center gap-4">
                            {{ $destination->name }}
                            {{ $destination->address }}
                            {{ $destination->description }}
                        </div>
                        <div>
                            <x-edit-button x-on:click="openEditModal({ id: {{ $destination->id }}, name: '{{ addslashes($destination->name) }}', address: '{{ addslashes($destination->address) }}', description: '{{ addslashes($destination->description) }}' })">
                                Edit
                            </x-edit-button>
                            <x-delete-button x-on:click="openDeleteModal({{ $destination->id }}, '{{ addslashes($destination->name) }}')">
                                Delete
                            </x-delete-button>
                        </div>
                    </x-row-card>
                @empty
                    <p>No destinations found.</p>
                @endforelse
            </div>
        </div>
    </x-app-layout>
    @include('admin.destination.partials.delete-modal')
    @include('admin.destination.partials.edit-modal')
    @include('admin.destination.partials.create-modal')
</div>