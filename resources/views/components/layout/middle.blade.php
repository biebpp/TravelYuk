<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<x-head>
    <body class=" text-gray-900 antialiased">
        <div>
            <div class="fixed w-screen h-screen -z-20">
                <img class="w-full h-full object-fill opacity-65" src="{{ asset('images/1.png') }}"
                    alt="Background Image">
            </div>
            <div class="flex min-h-screen flex-col items-center pt-6 sm:justify-center sm:pt-0">
                <div class="w-full overflow-hidden bg-blue-200 px-8 py-6 shadow-md sm:max-w-md sm:rounded-lg">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</x-head>

</html>