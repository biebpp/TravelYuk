<!doctype html>
<html>

<x-head>

  <body class="bg-gray-150 text-black">
    <div class="fixed w-full h-full -z-50">
      <img class="w-full opacity-35" src="{{ asset('images/3.png') }}" alt="Background Image">
    </div>

    <div class="text-white">
      <div class="bg-black absolute w-full h-screen -z-40">
        <img class="w-full h-full object-fill opacity-85" src="{{ asset('images/1.png') }}" alt="Background Image">
      </div>

      <div id="hero" class="flex flex-col h-screen">
        <div class="p-8 pr-12 text-xl w-full flex items-center">
          <img src="{{ asset('images/WLogo_nocut.svg') }}" alt="Logo" class="h-12 w-auto">
          <div class="w-full flex gap-4 justify-center">
            <a>Home</a>
            <a>Packages</a>
            <a>Tours</a>
            <a>Contact</a>
          </div>
          <div class="flex justify-end">
            @auth
              <a href="{{ route('dashboard') }}" class="">
                Dashboard
              </a>
            @else
              <a href="{{ route('login') }}" class="">
                Log in
              </a>
              <a href="{{ route('register') }}" class="">
                Register
              </a>
            @endauth
          </div>
        </div>

        <div class="flex flex-col h-full justify-evenly">
          <div class="flex flex-col">
            <div class="flex flex-col justify-center items-center">
              <div class="text-6xl">
                The World is Waiting for You
              </div>
              <div class="text-4xl">
                Explore the World with us.
              </div>
            </div>
          </div>

          <div class="flex-col">
            <div class="flex justify-center items-center">
              <div class="flex p-2 px-12 bg-blue-500 w-fit text-2xl gap-8">
                <a class="hover:underline">Flights</a>
                <a class="hover:underline">Bundles</a>
                <a class="hover:underline">Tours</a>
              </div>
            </div>

            <div class="flex justify-center items-center text-black">
              <div class="flex p-4 px-4 bg-white w-fit text-2xl gap-8">
                <div class="w-32 flex justify-between">
                  <a class="hover:text-black/80 hover:cursor-pointer">From</a>
                  <img src="{{ asset('assets/chevron-down.svg') }}" />
                </div>

                <div class="w-32 flex justify-between">
                  <a class="hover:text-black/80 hover:cursor-pointer">To</a>
                  <img src="{{ asset('assets/chevron-down.svg') }}" />
                </div>

                <div class="w-32 flex justify-between">
                  <a class="hover:text-black/80 hover:cursor-pointer">Tour</a>
                  <img src="{{ asset('assets/chevron-down.svg') }}" />
                </div>

                <div class="w-32 flex justify-between">
                  <a class="hover:text-black/80 hover:cursor-pointer">Bundle</a>
                  <img src="{{ asset('assets/chevron-down.svg') }}" />
                </div>

                <div class="w-32 flex justify-between">
                  <a class="hover:text-black/80 hover:cursor-pointer">Date</a>
                  <img src="{{ asset('assets/chevron-down.svg') }}" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="h-screen flex justify-center items-center text-black">
        <div class="flex flex-col justify-center items-center">
          <div class="text-7xl mb-8">Popular Destination</div>
          <div class="flex flex-row gap-8">

            <div class="bg-white pb-2 w-min">
              <img class="mb-2 w-64 max-w-none" src="https://placehold.co/250x300" />
              <div class="flex flex-col">
                <span class="px-4 text-xl font-semibold">Nusantara</span>
                <span class="px-4 text-sm">Lorem ipsum dolor sit amet consectetur adipisicing elit. Eius, nostrum vero
                  molestiae pariatur laudantium dolorem eum atque esse nam!</span>
              </div>
            </div>

            <div class="bg-white pb-2 w-min">
              <img class="mb-2 w-64 max-w-none" src="https://placehold.co/250x300" />
              <div class="flex flex-col">
                <span class="px-4 text-xl font-semibold">Nusantara</span>
                <span class="px-4 text-sm">Lorem ipsum dolor sit amet consectetur adipisicing elit. Eius, nostrum vero
                  molestiae pariatur laudantium dolorem eum atque esse nam!</span>
              </div>
            </div>

            <div class="bg-white pb-2 w-min">
              <img class="mb-2 w-64 max-w-none" src="https://placehold.co/250x300" />
              <div class="flex flex-col">
                <span class="px-4 text-xl font-semibold">Nusantara</span>
                <span class="px-4 text-sm">Lorem ipsum dolor sit amet consectetur adipisicing elit. Eius, nostrum vero
                  molestiae pariatur laudantium dolorem eum atque esse nam!</span>
              </div>
            </div>

            <div class="bg-white pb-2 w-min">
              <img class="mb-2 w-64 max-w-none" src="https://placehold.co/250x300" />
              <div class="flex flex-col">
                <span class="px-4 text-xl font-semibold">Nusantara</span>
                <span class="px-4 text-sm">Lorem ipsum dolor sit amet consectetur adipisicing elit. Eius, nostrum vero
                  molestiae pariatur laudantium dolorem eum atque esse nam!</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</x-head>

</html>