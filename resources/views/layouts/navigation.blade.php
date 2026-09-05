<!-- Mobile Overlay -->
<div x-show="open" x-cloak @click="open = false" x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-black/50 lg:hidden"></div>

<aside :class="{'translate-x-0': open, '-translate-x-full': !open}"
    class="fixed inset-y-0 left-0 z-50 w-64 h-screen bg-white border-r border-gray-100 flex flex-col transition-transform duration-300 ease-in-out lg:translate-x-0 shrink-0">
    <!-- Top Logo -->
    <div class="h-16 flex items-center px-6 border-b border-gray-100 shrink-0">
        <a href="{{ route('dashboard') }}">
            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
        </a>
        <div class="px-2">
            <div class="font-medium text-base text-gray-800 truncate">{{ Auth::user()->name }}</div>
            <div class="font-medium text-sm text-gray-500 truncate">{{ Auth::user()->email }}</div>
        </div>
    </div>

    <!-- Nav Links -->
    <nav class="flex-1 min-h-0 overflow-y-auto px-4 py-4 space-y-1">
        @if (auth()->user()->role === 'admin')
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('admin.users.dashboard')" class="rounded-md">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('booking')" :active="request()->routeIs('admin.booking')" class="rounded-md">
                {{ __('Booking') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.destinations')" :active="request()->routeIs('admin.destinations')" class="rounded-md">
                {{ __('Destination') }}
            </x-responsive-nav-link>
        @else
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('client.dashboard')" class="rounded-md">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('booking')" :active="request()->routeIs('client.booking')" class="rounded-md">
                {{ __('Booking') }}
            </x-responsive-nav-link>
        @endif
    </nav>

    <!-- Profile Footer -->
    <div class="p-4 border-t border-gray-200 shrink-0 bg-white">
        <x-responsive-nav-link :href="route('profile.edit')" class="rounded-md">
            {{ __('Profile') }}
        </x-responsive-nav-link>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-responsive-nav-link :href="route('logout')"
                onclick="event.preventDefault(); this.closest('form').submit();" class="rounded-md">
                {{ __('Log Out') }}
            </x-responsive-nav-link>
        </form>
    </div>
</aside>

<!-- Spacer on desktop -->
<div class="hidden lg:block w-64 shrink-0 h-screen"></div>