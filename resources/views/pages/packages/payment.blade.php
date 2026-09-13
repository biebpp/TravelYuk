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
                        Payment
                    </span>
                </div>
                <div class="w-full px-96 mt-12 text-black">
                    <div class="bg-ocean-2 p-4 rounded-md">
                        <form method="POST" action="{{ route('client.booking.status', $booking) }}" class="">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="pending">
                            <div class="flex w-full justify-end items-end gap-1">
                                <a href="{{ route('client.booking') }}">
                                    <x-primary-button type="button">
                                        {{ __('Pay Later') }}
                                    </x-primary-button>
                                </a>
                                <x-edit-button type="submit">
                                    {{ __('Confirm Payment') }}
                                </x-edit-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-head>

</html>