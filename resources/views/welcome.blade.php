<!doctype html>
<html>

<x-head>
  <body class="bg-gray-150 text-black">
    <div class="text-xl w-screen flex items-center">
      <img src="{{ asset('images/BLogo_nocut.svg') }}" alt="Logo" class="h-12 w-auto">
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
  </body>
</x-head>

</html>