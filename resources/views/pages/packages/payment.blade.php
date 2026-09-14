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
                    <div class="bg-ocean-1/75 backdrop-blur-sm p-4 rounded-md">
                        <div class="w-fit bg-white/50 font-semibold p-2 rounded-md mb-2">
                            <span class="text-2xl">TR-{{ time() }}</span>
                        </div>
                        <div class="flex flex-col px-2 text-lg">
                            <span>Bundle : {{ $booking->bundle->name }}</span>
                            <span>Price : Rp. {{ $booking->bundle->price }}</span>
                        </div>

                        <form method="POST" action="{{ route('client.booking.status', $booking) }}" class="">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="transaction_id" value="TR-{{ time() }}">
                            <input type="hidden" name="price" value="{{ $booking->bundle->price }}">

                            <select name="payment_method">
                                <option>BRI</option>
                                <option>BCA</option>
                            </select>

                            <input type="hidden" name="status" value="pending">
                            <input type="hidden" name="bundle_id" value="{{ $booking->bundle_id }}">

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