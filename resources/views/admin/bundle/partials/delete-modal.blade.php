<x-modal name="delete-bundle-modal" focusable>
    <form method="POST" :action="`/admin/bundles/${selectedBundle.id}`" class="p-6">
        @csrf
        @method('DELETE')

        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Are you sure you want to delete this bundle?') }}
        </h2>

        <p class="mt-2 text-sm text-gray-600">
            {{ __('This action cannot be undone. All pivot connections for ') }} 
            <strong x-text="selectedBundle.name"></strong> 
            {{ __('will be permanently removed.') }}
        </p>

        <div class="mt-6 flex justify-end gap-3">
            <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md">
                {{ __('Cancel') }}
            </button>

            <x-danger-button>
                {{ __('Delete Bundle') }}
            </x-danger-button>
        </div>
    </form>
</x-modal>