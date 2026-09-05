<x-head>
    <body class="font-sans antialiased overflow-hidden">
        <!-- Fixed Viewport Shell: locks the screen from scrolling as a whole -->
        <div x-data="{ open: false }" class="h-screen w-screen flex bg-gray-100 overflow-hidden">

            <!-- Sidebar Navigation -->
            @include('layouts.navigation')

            <!-- Right Content Panel: handles main page vertical scrolling independently -->
            <div class="flex-1 flex flex-col min-w-0 h-full overflow-y-auto">

                <!-- Mobile Header Bar -->
                <div
                    class="flex items-center justify-between h-16 px-4 bg-white border-b border-gray-100 shrink-0 lg:hidden">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                    <button @click="open = !open"
                        class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Page Heading -->
                @if (isset($header))
                    <header class="bg-white shadow shrink-0">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <main class="flex-1 p-6">
                    {{ $slot }}
                </main>

            </div>
        </div>
    </body>
</x-head>