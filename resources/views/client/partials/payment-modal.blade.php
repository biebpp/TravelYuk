<x-modal name="payment-modal" focusable>
    <form method="POST" x-bind:action="`/client/booking/${selectedUser.id}/status`" class="p-6">
        @csrf
        @method('PATCH')

        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Payment') }}
        </h2>

        <div class="mt-2 space-y-4">
            <div>
                <x-input-label value="" />
                <div class="mb-4">
                    <span class="text-3xl font-semibold text-gray-700 block" x-text="'Bundle : ' + selectedUser.bundle_name"></span>
                </div>

                <div>
                    <span class="font-semibold text-gray-700 block">{{ __('Price:') }}</span>
                    <p class="text-gray-900 whitespace-pre-line mt-1 bg-gray-50 p-3 rounded-md border"
                        x-text="'Rp. ' + selectedUser.price"></p>
                </div>

                <div>
                    <span class="font-semibold text-gray-700 block">{{ __('Transaction ID:') }}</span>
                    <p class="text-gray-900 font-medium whitespace-pre-line mt-1 bg-gray-50 p-3 rounded-md border">TR-{{ time() }}
                    </p>
                </div>

                <div>
                    <span class="font-semibold text-gray-700 block">{{ __('Description:') }}</span>
                    <p class="text-gray-900 whitespace-pre-line mt-1 bg-gray-50 p-3 rounded-md border"
                        x-text="selectedUser.description"></p>
                </div>

                <div>
                    <span class="font-semibold text-gray-700 block mb-1">{{ __('Included Destinations:') }}</span>
                    <div class="flex flex-wrap gap-1.5 border p-3 rounded-md bg-gray-50">
                        <template x-if="selectedUser.destinations && selectedUser.destinations.length">
                            <template x-for="destination in selectedUser.destinations" :key="destination.id">
                                <span class="bg-ocean-1 text-md px-2.5 py-1 rounded-md font-medium"
                                    x-text="destination.name"></span>
                            </template>
                        </template>
                        <template x-if="!selectedUser.destinations || !selectedUser.destinations.length">
                            <span class="text-gray-500 italic">{{ __('No destinations attached.') }}</span>
                        </template>
                    </div>
                </div>

            </div>
        </div>

        <input type="hidden" name="status" value="pending">

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