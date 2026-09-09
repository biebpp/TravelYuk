<x-modal name="view-bundle-modal" focusable>
    <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900 border-b pb-3">
            {{ __('Tour Bundle Details') }}
        </h2>

        <div class="mt-4 space-y-4 text-sm">
            <div>
                <span class="font-semibold text-gray-700 block">{{ __('Name:') }}</span>
                <p class="text-gray-900 text-base" x-text="selectedBundle.name"></p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="font-semibold text-gray-700 block">{{ __('Price:') }}</span>
                    <p class="text-gray-900">Rp. <span x-text="selectedBundle.price"></span></p>
                </div>
                <div>
                    <span class="font-semibold text-gray-700 block">{{ __('Available Slots:') }}</span>
                    <p class="text-gray-900" x-text="selectedBundle.slot ?? 'Unlimited'"></p>
                </div>
            </div>

            <div>
                <span class="font-semibold text-gray-700 block mb-1">{{ __('Included Destinations:') }}</span>
                <div class="flex flex-wrap gap-1.5 border p-3 rounded-md bg-gray-50">
                    <template x-if="selectedBundle.destinations && selectedBundle.destinations.length">
                        <template x-for="destination in selectedBundle.destinations" :key="destination.id">
                            <span class="bg-ocean-1 text-md px-2.5 py-1 rounded-md font-medium"
                                x-text="destination.name"></span>
                        </template>
                    </template>
                    <template x-if="!selectedBundle.destinations || !selectedBundle.destinations.length">
                        <span class="text-gray-500 italic">{{ __('No destinations attached.') }}</span>
                    </template>
                </div>
            </div>

            <div>
                <span class="font-semibold text-gray-700 block">{{ __('Description:') }}</span>
                <p class="text-gray-900 whitespace-pre-line mt-1 bg-gray-50 p-3 rounded-md border" x-text="selectedBundle.description"></p>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md">
                {{ __('Close') }}
            </button>
        </div>
    </div>
</x-modal>