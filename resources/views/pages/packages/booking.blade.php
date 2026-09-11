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
                    <div class="bg-ocean-2 p-4 rounded-md">
                        <form method="POST" action="{{ route('client.booking.store') }}" class="">
                            @csrf
                            <input type="hidden" name="bundle_name" value="{{ $bundle->name }}">
                            <input type="hidden" name="bundle_id" value="{{ $bundle->id }}">
                            <div class="flex w-full justify-end items-end">
                                <x-primary-button type="submit">
                                    {{ __('Confirm Booking') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-head>

</html>