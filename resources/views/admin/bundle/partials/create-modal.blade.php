<x-modal name="create-bundle-modal" focusable>
    <form method="POST" action="{{ route('admin.bundles.store') }}" class="p-6">
        @csrf

        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Create New Tour Bundle') }}
        </h2>

        <div class="mt-4 space-y-4">
            <!-- Name -->
            <div>
                <x-input-label for="create_name" :value="__('Bundle Name')" />
                <x-text-input id="create_name" name="name" type="text" class="mt-1 block w-full"
                    x-model="selectedBundle.name" required />
                <div class="validation-error-container">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
            </div>

            <!-- Price & Slot -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="create_price" :value="__('Price ($)')" />
                    <x-text-input id="create_price" name="price" type="number" step="0.01" class="mt-1 block w-full"
                        x-model="selectedBundle.price" required />
                    <div class="validation-error-container">
                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                    </div>
                </div>
                <div>
                    <x-input-label for="create_slot" :value="__('Available Slots')" />
                    <x-text-input id="create_slot" name="slot" type="number" class="mt-1 block w-full"
                        x-model="selectedBundle.slot" />
                    <div class="validation-error-container">
                        <x-input-error :messages="$errors->get('slot')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Destinations Multiple Select -->
            <div x-data="{ 
                selectedDestinations: [''],
                addDestination() {
                    this.selectedDestinations.push('');
                },
                removeDestination(index) {
                    if (this.selectedDestinations.length > 1) {
                        this.selectedDestinations.splice(index, 1);
                    }
                }
            }">
                <div class="flex items-center justify-between mb-2">
                    <x-input-label :value="__('Destinations')" />
                    <button type="button" x-on:click="addDestination()" 
                        class="inline-flex items-center text-md font-semibold text-indigo-600 hover:text-indigo-800">
                        + {{ __('Add Destination') }}
                    </button>
                </div>

                <div class="space-y-2">
                    <template x-for="(destination, index) in selectedDestinations" :key="index">
                        <div class="flex items-center gap-2">
                            <select :name="`destination_ids[${index}]`" x-model="selectedDestinations[index]"
                                class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="" disabled selected>{{ __('Select a Destination') }}</option>
                                @foreach($destinations as $dest)
                                    <option value="{{ $dest->id }}">{{ $dest->name }}</option>
                                @endforeach
                            </select>

                            <button type="button" x-on:click="removeDestination(index)" 
                                x-show="selectedDestinations.length > 1"
                                class="p-2 text-red-600 hover:text-red-800 rounded-md hover:bg-red-50"
                                title="Remove destination">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>

                <div class="validation-error-container">
                    <x-input-error :messages="$errors->get('destination_ids')" class="mt-2" />
                    <x-input-error :messages="$errors->get('destination_ids.*')" class="mt-2" />
                </div>
            </div>

            <!-- Description -->
            <div>
                <x-input-label for="create_description" :value="__('Description')" />
                <x-textarea id="create_description" name="description" class="mt-1 block w-full"
                    x-model="selectedBundle.description" required></x-textarea>
                <div class="validation-error-container">
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md">
                {{ __('Cancel') }}
            </button>
            <x-primary-button>
                {{ __('Create Bundle') }}
            </x-primary-button>
        </div>
    </form>
</x-modal>