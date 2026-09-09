<x-modal name="view-modal" focusable>
    <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900 border-b pb-3">
            {{ __('Booking Details') }}
        </h2>

        <div class="mt-4 space-y-4 text-sm">

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="font-semibold text-gray-700 block">{{ __('Name:') }}</span>
                    <p class="text-gray-900 text-base" x-text="selectedUser.name"></p>
                </div>
                <div>
                    <span class="font-semibold text-gray-700 block">{{ __('Address:') }}</span>
                    <p class="text-gray-900"><span x-text="selectedUser.address"></span></p>
                </div>
            </div>

            <div>
                <span class="font-semibold text-gray-700 block">{{ __('Description:') }}</span>
                <p class="text-gray-900 whitespace-pre-line mt-1 bg-gray-50 p-2 rounded-md border"
                    x-text="selectedUser.description"></p>
            </div>

            <div>
                <span class="font-semibold text-gray-700 block">{{ __('Map:') }}</span>
                <div class="whitespace-pre-line bg-gray-50 p-2 rounded-md border">
                    <div id="view_map" style="width: full; height: 300px" class="map rounded-xl w-full"></div>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        fetch('/get-coordinates', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({ address: "SMK PGRI 3 Malang" })
                        })
                            .then(res => res.json())
                            .then(data => {
                                console.log(data);
                                const lat = parseFloat(data.latitude);
                                const lng = parseFloat(data.longitude);

                                var map = L.map('view_map').setView([lat, lng], 13);

                                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                                }).addTo(map);


                            })
                            .catch(err => console.error(err));
                    });
                </script>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="button" x-on:click="$dispatch('close')"
                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md">
                {{ __('Close') }}
            </button>
        </div>
    </div>
</x-modal>