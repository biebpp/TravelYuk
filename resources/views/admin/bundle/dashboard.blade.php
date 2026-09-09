<div x-data="{ 
    selectedBundle: { 
        id: null, 
        name: '', 
        description: '', 
        price: '', 
        slot: '', 
        transportation_id: '', 
        destination_ids: [] 
    },
    clearErrors() {
        document.querySelectorAll('.validation-error-container').forEach(el => el.innerHTML = '');
    },
    openViewModal(bundle) {
        this.selectedBundle = { 
            ...bundle, 
            destination_ids: bundle.destinations.map(d => d.id.toString()) 
        };
        $dispatch('open-modal', 'view-bundle-modal');
    },
    openCreateModal() {
        this.clearErrors();
        this.selectedBundle = { 
            id: null, 
            name: '', 
            description: '', 
            price: '', 
            slot: '', 
            transportation_id: '', 
            destination_ids: [] 
        };
        $dispatch('open-modal', 'create-bundle-modal');
    },
    openEditModal(bundle) {
        this.clearErrors();
        this.selectedBundle = { 
            ...bundle, 
            destination_ids: bundle.destinations.map(d => d.id.toString()) 
        };
        $dispatch('open-modal', 'edit-bundle-modal');
    },
    openDeleteModal(id, name) {
        this.selectedBundle = { id, name };
        $dispatch('open-modal', 'delete-bundle-modal');
    }
}">
    <x-app-layout>
        <x-slot name="header">
            <div class="flex w-full justify-between items-center lg:flex-row">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Tour Bundle Dashboard') }}
                </h2>
                <div class="flex justify-end px-12">
                    <x-edit-button x-on:click="openCreateModal()">
                        {{ __('Add Tour Bundle') }}
                    </x-edit-button>
                </div>
            </div>
        </x-slot>

        <x-table-container>
            @forelse ($bundles as $bundle)
                <x-row-card>
                    <div class="w-full flex flex-row items-center gap-4">
                        <div class="bg-indigo-100 p-2 rounded-full shadow-md">
                            <x-lucide-package class="h-8 w-auto" />
                        </div>
                        
                        <div class="w-full">
                            <p class="font-semibold text-lg text-gray-900">{{ $bundle->name }}</p>
                            <div class="flex flex-col md:flex-row md:items-center md:gap-4 text-sm text-gray-600 mt-1">
                                <span><strong>Price:</strong> Rp. {{ number_format($bundle->price, 2) }}</span>
                                <span><strong>Slots:</strong> {{ $bundle->slot ?? 'N/A' }}</span>
                                <span><strong>Destinations:</strong> {{ $bundle->destinations->count() }}</span>
                            </div>
                            
                            <div class="flex flex-wrap gap-1 mt-2">
                                @foreach ($bundle->destinations as $destination)
                                    <span class="bg-ocean-1 text-gray-700 text-xs rounded-full shadow-md px-1">
                                        {{ $destination->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row md:items-center gap-1">
                            <x-primary-button x-on:click="openViewModal({{ json_encode($bundle) }})">
                                View
                            </x-primary-button>
                            
                            <x-edit-button x-on:click="openEditModal({{ json_encode($bundle) }})">
                                Edit
                            </x-edit-button>

                            <x-delete-button x-on:click="openDeleteModal({{ $bundle->id }}, '{{ addslashes($bundle->name) }}')">
                                Delete
                            </x-delete-button>
                        </div>
                    </div>
                </x-row-card>
            @empty
                <p class="text-gray-500 py-4 text-center">There are no tour bundles available.</p>
            @endforelse
        </x-table-container>
    </x-app-layout>

    @include('admin.bundle.partials.create-modal')
    @include('admin.bundle.partials.edit-modal')
    @include('admin.bundle.partials.view-modal')
    @include('admin.bundle.partials.delete-modal')
</div>