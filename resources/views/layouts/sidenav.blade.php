<nav
    id="sidenav-1"
    class="absolute left-0 top-0 z-[1035] h-full w-60 -translate-x-full overflow-hidden bg-banner shadow-[0_4px_12px_0_rgba(0,0,0,0.07),_0_2px_4px_rgba(0,0,0,0.05)] data-[te-sidenav-hidden='false']:translate-x-0"
    data-te-sidenav-init
    data-te-sidenav-hidden="true"
    data-te-sidenav-position="absolute"
    data-te-sidenav-width="600"
>
    <div class="relative m-0 list-none" data-te-sidenav-menu-ref>

        <div class="flex justify-center bg-banner border-b border-hamburger">
            <a href="{{ route('welcome') }}">
                <img src="{{ asset('Images/logo_schaduw.png') }}" alt="logo" class="w-28 p-2 aspect-square">
            </a>
            <button
                class="absolute right-10 mt-[24px] p-0 inline-block rounded text-xs font-medium uppercase leading-tight text-white shadow-md transition duration-150 ease-in-out hover:shadow-lg focus:shadow-lg focus:outline-none focus:ring-0 active:shadow-lg"
                data-te-sidenav-toggle-ref
                data-te-target="#sidenav-1"
                aria-controls="#sidenav-1"
                aria-haspopup="true">
                <img src="{{ asset('Images/groene_qr.png') }}" alt="qr">
            </button>
        </div>

        <div class="flex flex-col mx-auto text-2xl mt-10">
            <div id="display-code" class="text-center text-4xl text-white"></div>
            <div id="display-qrcode" class="my-5 text-center w-min mx-auto rounded p-3 border bg-white"></div>
            <p class="capitalize w-64 text-white italic mx-auto">
                Scan deze qr code via je telefoon vanaf onze website.
            </p>
        </div>
    </div>
</nav>
