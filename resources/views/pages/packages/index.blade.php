<!doctype html>
<html>

<x-head>
    <div x-data="{ 
    selectedBundle: { 
        id: null, 
        name: '', 
        description: '', 
        price: '', 
        slot: '', 
        transportation_id: '', 
        destination_ids: [] 
    },
    clearErrors() {
        document.querySelectorAll('.validation-error-container').forEach(el => el.innerHTML = '');
    },
    openViewModal(bundle) {
        this.selectedBundle = { 
            ...bundle, 
            destination_ids: bundle.destinations.map(d => d.id.toString()) 
        };
        $dispatch('open-modal', 'view-bundle-modal');
    }
}">
        <div class="text-white">
            <div class="bg-black fixed w-full h-full -z-50">
                <img class="w-full h-full object-fill opacity-75" src="{{ asset('images/1.png') }}"
                    alt="Background Image">
            </div>

            <div class="flex flex-col h-screen">
                <x-navbar></x-navbar>

                <div class="h-full flex flex-col px-8 justify-evenly">
                    <div class="flex justify-center">
                        <div class="w-fit flex flex-col justify-center ephesis-regular">
                            <span class="w-full flex justify-start items-start text-8xl">
                                Explore the World
                            </span>
                            <span class="w-full flex justify-end items-end px-12 text-6xl">
                                With Us
                            </span>
                        </div>
                    </div>

                    <div class="flex justify-center text-black">
                        <div class="grid grid-cols-4 gap-6">

                            @foreach ($bundles as $bundle)
                                <div class="bg-white pb-4 w-min h-fit">
                                    <img class="mb-2 w-64 max-w-none" src="https://placehold.co/250x250" />
                                    <div
                                        class="w-full flex flex-col px-4 text-xl justify-center items-center font-semibold gap-2">
                                        <span class="">{{ $bundle->name }}</span>
                                        <x-primary-button class="w-fit" x-on:click="openViewModal({{ json_encode($bundle) }})">
                                            {{ __('View Detail') }}
                                        </x-primary-button>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>

            <div class="h-screen"></div>
        </div>
        @include('pages.packages.partials.view-modal')
    </div>
</x-head>

</html>