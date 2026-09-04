<x-modal name="view-modal" focusable>
    <div x-bind:action="`/users/${selectedUser.id}`" class="p-6">
        <h2 class="text-lg font-medium text-gray-900">
            User Details: <span x-text="selectedUser.name" class="font-bold"></span>
        </h2>

        <div class="mt-4 space-y-2 text-sm text-gray-600">
            <p><bold>Name:</bold> <span x-text="selectedUser.name"></span></p>
            <p><bold>Email:</bold> <span x-text="selectedUser.email"></span></p>
            <p><bold>Role:</bold> <span x-text="selectedUser.role"></span></p>
        </div>

        <div class="mt-6 flex justify-end">
            <button 
                type="button" 
                x-on:click="$dispatch('close')" 
                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md"
            >
                Close
            </button>
        </div>
    </div>
</x-modal>