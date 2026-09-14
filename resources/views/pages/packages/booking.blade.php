<!doctype html>
<html>

<x-head>
    <div class="text-white">
        <div class="bg-black fixed w-full h-full -z-50">
            <img class="w-full h-full object-fill opacity-75" src="{{ asset('images/1.png') }}" alt="Background Image">
        </div>

        <div class="flex flex-col h-screen">
            <x-navbar></x-navbar>

            <div class="flex flex-col w-screen py-4 justify-center items-center">
                <div class="w-fit flex flex-row justify-center ephesis-regular">
                    <span class="w-full flex justify-start items-start text-8xl">
                        Booking
                    </span>
                </div>
                <div class="w-full px-96 mt-12 text-black">
                    <div class="bg-ocean-1/75 backdrop-blur-sm p-4 rounded-md">
                        @if ($bundle->slot > 0)
                            <div class="w-fit bg-white/50 font-semibold p-2 rounded-md mb-2">
                                <span class="text-2xl">{{ $bundle->name }}</span>
                            </div>
                            <div class="flex flex-col px-2 text-lg">
                                <span>Rp. {{ $bundle->price }}</span>
                                <div class="flex">
                                    <span>Destination: </span>
                                    @foreach ($bundle->destinations as $destination)
                                        <span class="bg-ocean-1/50 px-1 rounded-md ml-2">
                                            {{ $destination->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            
                            <form method="POST" action="{{ route('client.booking.store') }}" class="">
                                @csrf
                                <input type="hidden" name="bundle_name" value="{{ $bundle->name }}">
                                <input type="hidden" name="bundle_id" value="{{ $bundle->id }}">
                                <div class="flex w-full justify-end items-end">
                                    <x-edit-button type="submit">
                                        {{ __('Confirm Booking') }}
                                    </x-edit-button>
                                </div>
                            </form>
                        @else
                            Paket ini Sudah Sold Out
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-head>

</html>