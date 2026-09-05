<x-modal name="delete-modal" focusable>
    <form 
        method="post" 
        x-bind:action="`/admin/destination/${selectedUser.id}`"
        class="p-6"
    >
        @csrf
        @method('delete')

        <h2 class="text-lg font-medium text-gray-900">
            Are you sure you want to delete <span x-text="selectedUser.name" class="font-bold"></span>?
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Once this destination is deleted, all of its resources and data will be permanently deleted.
        </p>

        <div class="mt-6 flex justify-end gap-3">
            <button 
                type="button" 
                x-on:click="$dispatch('close')" 
                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md"
            >
                Cancel
            </button>

            <button 
                type="submit" 
                class="px-4 py-2 bg-red-600 text-white rounded-md"
            >
                Delete Destination
            </button>
        </div>
    </form>
</x-modal>