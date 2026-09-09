<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<x-head>
    <body class="text-gray-900 antialiased">
        <div>
            <div class="bg-black fixed w-screen h-screen -z-20">
                <img class="w-full h-full object-fill opacity-65" src="{{ asset('images/1.png') }}"
                    alt="Background Image">
            </div>
            <div class="flex min-h-screen flex-col items-center pt-6 sm:justify-center sm:pt-0">
                <div class="w-full overflow-hidden bg-ocean-3/95 px-8 py-6 sm:max-w-md sm:rounded-lg">
                    <div class="w-full flex justify-center py-8">
                        <img src="{{ asset('images/BLogo_nocut.svg') }}" alt="Logo" class="h-20 w-auto">
                    </div>
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</x-head>

</html>