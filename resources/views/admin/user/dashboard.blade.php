<div x-data="{ 
    selectedUser: { id: null, name: '', email: '', role: 'client', password: '' },
    clearErrors() {
        document.querySelectorAll('.validation-error-container').forEach(el => el.innerHTML = '');
    },
    openViewModal(id, name, email, role) {
        this.selectedUser = { id, name, email, role };
        $dispatch('open-modal', 'view-modal');
    },
    openCreateModal() {
        this.clearErrors();
        this.selectedUser = { id: null, name: '', email: '', role: 'client', password: '' };
        $dispatch('open-modal', 'create-modal');
    },
    openEditModal(user) {
        this.clearErrors();
        this.selectedUser = { ...user, password: '' };
        $dispatch('open-modal', 'edit-modal');
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
                    {{ __('User Dashboard') }}
                </h2>
                <div class="flex justify-end px-12">
                    <x-edit-button x-on:click="openCreateModal()">
                        {{ __('Add User') }}
                    </x-edit-button>
                </div>
            </div>
        </x-slot>

        <x-table-container>
            @forelse ($users as $user)
                <x-row-card>
                    <div class="w-full flex flex-row items-center gap-4">
                        <div class="bg-indigo-100 p-2 rounded-full shadow-md">
                            <x-lucide-user-round class="h-8 w-auto" />
                        </div>
                        <div class="w-full">
                            <p class="font-semibold">{{ $user->name }}</p>
                            <p class="text-sm text-gray-600">{{ $user->email }}</p>
                        </div>
                        <div class="flex items-end gap-1">
                            <x-primary-button
                                x-on:click="openViewModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}', '{{ addslashes($user->role) }}')">
                                View
                            </x-primary-button>
                            <x-edit-button
                                x-on:click="openEditModal({ id: {{ $user->id }}, name: '{{ addslashes($user->name) }}', email: '{{ addslashes($user->email) }}', role: '{{ addslashes($user->role) }}' })">
                                Edit
                            </x-edit-button>
                            <x-delete-button x-on:click="openDeleteModal({{ $user->id }}, '{{ addslashes($user->name) }}')">
                                Delete
                            </x-delete-button>
                        </div>
                    </div>
                </x-row-card>
            @empty
                <p>There is no user.</p>
            @endforelse
        </x-table-container>
    </x-app-layout>

    @include('admin.user.partials.create-modal')
    @include('admin.user.partials.edit-modal')
    @include('admin.user.partials.view-modal')
    @include('admin.user.partials.delete-modal')
</div>