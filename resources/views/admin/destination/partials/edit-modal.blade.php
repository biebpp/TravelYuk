<x-modal name="edit-modal" focusable>
    <form method="POST" :action="`/admin/destination/${selectedUser.id}`" class="p-6">
        @csrf
        @method('PATCH')

        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Edit Destination:') }} <span x-text="selectedUser.name"></span>
        </h2>

        <div class="mt-4 space-y-4">
            <div>
                <x-input-label for="edit_name" :value="__('Name')" />
                <x-text-input id="edit_name" name="name" type="text" class="mt-1 block w-full"
                    x-model="selectedUser.name" />
                <div class="validation-error-container">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
            </div>

            <div>
                <x-input-label for="edit_address" :value="__('Address')" />
                <x-text-input id="edit_address" name="address" type="text" class="mt-1 block w-full"
                    x-model="selectedUser.address" />
                <div class="validation-error-container">
                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                </div>
            </div>

            <div>
                <x-input-label for="edit_description" :value="__('Description')" />
                <x-textarea id="edit_description" name="description" class="mt-1 block w-full"
                    x-model="selectedUser.description"></x-textarea>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md">
                    {{ __('Cancel') }}
                </button>

                <x-primary-button>
                    {{ __('Update Destination') }}
                </x-primary-button>
            </div>
        </div>
    </form>
</x-modal>