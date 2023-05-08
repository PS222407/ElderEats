<nav class="bg-banner w-full z-20 top-0 left-0">
    <button
        onclick="showCode()"
        class="absolute left-10 mt-[24px] p-0 inline-block rounded text-xs font-medium uppercase leading-tight text-white shadow-md transition duration-150 ease-in-out hover:shadow-lg focus:shadow-lg focus:outline-none focus:ring-0 active:shadow-lg"
        data-te-sidenav-toggle-ref
        data-te-target="#sidenav-1"
        aria-controls="#sidenav-1"
        aria-haspopup="true">
        <img src="{{ asset('Images/groene_qr.png') }}" alt="qr">
    </button>

    <div class="flex justify-center">
        <a href="{{ route('welcome') }}">
            <img src="{{ asset('Images/logo_schaduw.png') }}" alt="logo" class="w-28 p-2 aspect-square">
        </a>
    </div>

    <button
        class="absolute top-0 right-10 mt-[24px] p-0 inline-block rounded text-xs font-medium uppercase leading-tight text-white shadow-md transition duration-150 ease-in-out hover:shadow-lg focus:shadow-lg focus:outline-none focus:ring-0 active:shadow-lg"
        data-te-sidenav-toggle-ref
        data-te-target="#sidenav-2"
        aria-controls="#sidenav-2"
        aria-haspopup="true">
        <img src="{{ asset('Images/settings.png') }}" alt="qr">
    </button>
</nav>

