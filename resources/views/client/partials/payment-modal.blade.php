<x-modal name="payment-modal" focusable>
    <form method="POST" x-bind:action="`/client/booking/${selectedUser.id}/status`" class="p-6">
        @csrf
        @method('PATCH')

        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Payment for ') }} <span x-text="selectedUser.name" class="font-bold"></span>
        </h2>

        <div class="mt-4 space-y-4">
            <div>
                <x-input-label value="TR-{{ time() }}" />
                <input type="hidden" name="status" value="pending">
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <button type="button" x-on:click="$dispatch('close')"
                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                {{ __('Cancel') }}
            </button>

            <x-primary-button>
                {{ __('Pay Booking') }}
            </x-primary-button>
        </div>
    </form>
</x-modal>