<x-modal name="create-modal" focusable>
    <form method="POST" action="{{ route('client.booking.store') }}" class="p-6">
        @csrf

        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Create Booking') }}
        </h2>

        <div class="mt-4 space-y-4">
            <div>
                <x-input-label for="create_name" :value="__('Name')" />
                <x-text-input id="create_name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required />
                <div class="validation-error-container">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md">
                {{ __('Cancel') }}
            </button>

            <x-primary-button>
                {{ __('Create Booking') }}
            </x-primary-button>
        </div>
    </form>
</x-modal>