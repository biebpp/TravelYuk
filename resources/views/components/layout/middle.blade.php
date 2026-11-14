<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<x-head>

    <body class="text-gray-900 antialiased">
        <div class="auth-page-background">
            <img src="{{ asset('images/3.png') }}" alt="Background Image">
        </div>
        <div class="auth-page-content w-screen h-screen flex flex-col justify-center items-center">
            <div class="bg-ocean-2 p-4 rounded-lg max-w-4xl w-full shadow-xl">
                <div class="flex flex-row items-stretch overflow-hidden rounded-lg h-auto min-h-[420px]">
                    <div class="hidden sm:block sm:w-1/2 md:w-96 relative overflow-hidden">
                        <div class="relative w-full h-full">
                            <img src="{{ asset('images/WLogo_nocut.svg') }}" alt="Logo"
                                class="absolute bottom-4 left-1/2 -translate-x-1/2 h-18 w-auto z-20">
                            <div class="absolute inset-0 bg-gradient-to-b from-black/0 to-black/40 pointer-events-none">
                            </div>

                            <img class="flex w-full h-full object-cover" src="{{ asset('images/1.png') }}"
                                alt="Background Image">
                        </div>
                    </div>

                    <div class="w-full sm:w-1/2 flex-1 bg-ocean-4/10 px-8 py-6 sm:px-16">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</x-head>

</html>