<div class="p-8 pr-12 text-xl w-full flex items-center">
    <a href="/">
        <img src="{{ asset('images/WLogo_nocut.svg') }}" alt="Logo" class="h-12 w-auto">
    </a>
    <div class="w-full flex gap-4 justify-center">
        <a href="/" class="cursor-pointer">Home</a>
        <a href="/packages" class="cursor-pointer">Packages</a>
        <a class="cursor-pointer">Tours</a>
        <a class="cursor-pointer">Contact</a>
    </div>
    <div class="flex justify-end">
        @auth
            <a href="{{ route('dashboard') }}" class="">
                Dashboard
            </a>
        @else
            <div class="flex flex-row gap-4">
                <a href="{{ route('login') }}" class="">
                    Login
                </a>
                <a href="{{ route('register') }}" class="">
                    Register
                </a>
            </div>
        @endauth
    </div>
</div>