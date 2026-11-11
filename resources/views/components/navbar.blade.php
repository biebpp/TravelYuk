<div
    x-data="{ scrolled: {{ request()->routeIs('index') ? 'false' : 'true' }}, mobileOpen: false }"
    @scroll.window="scrolled = window.scrollY > 24"
    @keydown.escape.window="mobileOpen = false"
    class="relative z-50"
>
    <header
        :class="scrolled ? 'bg-white/95 text-slate-900 shadow-sm backdrop-blur-md' : 'bg-transparent text-white'"
        class="fixed inset-x-0 top-0 transition-colors duration-300"
    >
        <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="{{ route('index') }}" class="shrink-0" aria-label="Travel Yuk beranda">
                <img
                    :src="scrolled ? '{{ asset('images/BLogo_nocut.svg') }}' : '{{ asset('images/WLogo_nocut.svg') }}'"
                    src="{{ request()->routeIs('index') ? asset('images/WLogo_nocut.svg') : asset('images/BLogo_nocut.svg') }}"
                    alt="Travel Yuk"
                    class="h-12 w-auto"
                >
            </a>

            <nav aria-label="Navigasi utama" class="hidden items-center gap-8 text-sm font-medium md:flex">
                <a href="{{ route('index') }}" class="transition hover:opacity-70">Home</a>
                <a href="{{ route('packages') }}" class="transition hover:opacity-70">Packages</a>
                <a href="{{ route('index') }}#destinations" class="transition hover:opacity-70">Tours</a>
                <a href="{{ route('index') }}#contact" class="transition hover:opacity-70">Contact</a>
            </nav>

            <div class="hidden items-center gap-3 md:flex">
                @auth
                    <a href="{{ route('dashboard') }}" class="rounded-full bg-teal-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-teal-800">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold transition hover:opacity-70">Masuk</a>
                    <a href="{{ route('register') }}" class="rounded-full bg-teal-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-teal-800">Daftar</a>
                @endauth
            </div>

            <button
                type="button"
                @click="mobileOpen = !mobileOpen"
                :aria-expanded="mobileOpen.toString()"
                aria-controls="mobile-navigation"
                aria-label="Buka menu navigasi"
                class="rounded-lg p-2 transition hover:bg-black/10 focus:outline-none focus:ring-2 focus:ring-teal-600 md:hidden"
            >
                <svg x-show="!mobileOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="mobileOpen" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m6 6 12 12M18 6 6 18" />
                </svg>
            </button>
        </div>

        <nav
            id="mobile-navigation"
            x-show="mobileOpen"
            x-cloak
            x-transition.opacity
            @click.outside="mobileOpen = false"
            class="border-t border-slate-200 bg-white px-4 py-4 text-slate-900 shadow-lg md:hidden"
            aria-label="Navigasi mobile"
        >
            <div class="mx-auto flex max-w-7xl flex-col gap-1">
                <a @click="mobileOpen = false" href="{{ route('index') }}" class="rounded-lg px-3 py-3 hover:bg-slate-100">Home</a>
                <a @click="mobileOpen = false" href="{{ route('packages') }}" class="rounded-lg px-3 py-3 hover:bg-slate-100">Packages</a>
                <a @click="mobileOpen = false" href="{{ route('index') }}#destinations" class="rounded-lg px-3 py-3 hover:bg-slate-100">Tours</a>
                <a @click="mobileOpen = false" href="{{ route('index') }}#contact" class="rounded-lg px-3 py-3 hover:bg-slate-100">Contact</a>
                <div class="mt-2 border-t border-slate-100 pt-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="block rounded-lg bg-teal-700 px-4 py-3 text-center font-semibold text-white">Dashboard</a>
                    @else
                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('login') }}" class="rounded-lg border border-slate-300 px-4 py-3 text-center font-semibold">Masuk</a>
                            <a href="{{ route('register') }}" class="rounded-lg bg-teal-700 px-4 py-3 text-center font-semibold text-white">Daftar</a>
                        </div>
                    @endauth
                </div>
            </div>
        </nav>
    </header>
</div>
