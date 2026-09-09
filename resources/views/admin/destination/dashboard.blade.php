<div x-data="{ 
    selectedUser: { id: null, name: '', description: '', address: '' },
    clearErrors() {
        document.querySelectorAll('.validation-error-container').forEach(el => el.innerHTML = '');
    },
    openViewModal(id, name, address, description) {
        this.selectedUser = { id, name, address, description };
        $dispatch('open-modal', 'view-modal');
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

        <x-table-container>
            @forelse ($destinations as $destination)
                <x-row-card>
                    <div class="flex flex-row">
                        <div class="w-full flex flex-col">
                            <span class="text-xl font-semibold">
                                {{ $destination->name }}
                            </span>
                            <span class="text-gray-600 text-sm">
                                {{ $destination->address }}
                            </span>
                        </div>
                        <div class="flex justify-end items-center gap-1">
                            <x-primary-button
                                x-on:click="openViewModal({{ $destination->id }}, '{{ addslashes($destination->name) }}', '{{ addslashes($destination->address) }}', '{{ addslashes($destination->description) }}')">
                                View
                            </x-primary-button>
                            <x-edit-button class="h-fit"
                                x-on:click="openEditModal({ id: {{ $destination->id }}, name: '{{ addslashes($destination->name) }}', address: '{{ addslashes($destination->address) }}', description: '{{ addslashes($destination->description) }}' })">
                                Edit
                            </x-edit-button>
                            <x-delete-button class="h-fit"
                                x-on:click="openDeleteModal({{ $destination->id }}, '{{ addslashes($destination->name) }}')">
                                Delete
                            </x-delete-button>
                        </div>
                    </div>
                </x-row-card>
            @empty
                <p>No destinations found.</p>
            @endforelse
        </x-table-container>
    </x-app-layout>
    @include('admin.destination.partials.view-modal')
    @include('admin.destination.partials.create-modal')
    @include('admin.destination.partials.edit-modal')
    @include('admin.destination.partials.delete-modal')
</div>