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
                id="close-sidenav-button"
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

<nav
    id="sidenav-2"
    class="absolute left-0 top-0 z-[1035] h-full w-60 -translate-x-full overflow-hidden bg-white shadow-[0_4px_12px_0_rgba(0,0,0,0.07),_0_2px_4px_rgba(0,0,0,0.05)] data-[te-sidenav-hidden='false']:translate-x-0"
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
                id="close-sidenav-button"
                class="absolute right-10 mt-[24px] p-0 inline-block rounded text-xs font-medium uppercase leading-tight text-white shadow-md transition duration-150 ease-in-out hover:shadow-lg focus:shadow-lg focus:outline-none focus:ring-0 active:shadow-lg"
                data-te-sidenav-toggle-ref
                data-te-target="#sidenav-2"
                aria-controls="#sidenav-2"
                aria-haspopup="true">
                <img src="{{ asset('Images/settings.png') }}" alt="qr">
            </button>
        </div>

        <div class="flex flex-col mx-auto px-5 mt-10">
            <div class="mb-4">
                <button onclick="toggleSound()" id="sound-on" @if((isset($_COOKIE['sound']) && $_COOKIE['sound'] === "false")) style="display: none" @endif>
                    <svg xmlns="http://www.w3.org/2000/svg" height="2em" viewBox="0 0 640 512">
                        <!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                        <path
                            d="M533.6 32.5C598.5 85.3 640 165.8 640 256s-41.5 170.8-106.4 223.5c-10.3 8.4-25.4 6.8-33.8-3.5s-6.8-25.4 3.5-33.8C557.5 398.2 592 331.2 592 256s-34.5-142.2-88.7-186.3c-10.3-8.4-11.8-23.5-3.5-33.8s23.5-11.8 33.8-3.5zM473.1 107c43.2 35.2 70.9 88.9 70.9 149s-27.7 113.8-70.9 149c-10.3 8.4-25.4 6.8-33.8-3.5s-6.8-25.4 3.5-33.8C475.3 341.3 496 301.1 496 256s-20.7-85.3-53.2-111.8c-10.3-8.4-11.8-23.5-3.5-33.8s23.5-11.8 33.8-3.5zm-60.5 74.5C434.1 199.1 448 225.9 448 256s-13.9 56.9-35.4 74.5c-10.3 8.4-25.4 6.8-33.8-3.5s-6.8-25.4 3.5-33.8C393.1 284.4 400 271 400 256s-6.9-28.4-17.7-37.3c-10.3-8.4-11.8-23.5-3.5-33.8s23.5-11.8 33.8-3.5zM301.1 34.8C312.6 40 320 51.4 320 64V448c0 12.6-7.4 24-18.9 29.2s-25 3.1-34.4-5.3L131.8 352H64c-35.3 0-64-28.7-64-64V224c0-35.3 28.7-64 64-64h67.8L266.7 40.1c9.4-8.4 22.9-10.4 34.4-5.3z"/>
                    </svg>
                    Aan
                </button>
                <button onclick="toggleSound()" id="sound-off" @if(!isset($_COOKIE['sound']) || (isset($_COOKIE['sound']) && $_COOKIE['sound'] === "true")) style="display: none" @endif>
                    <svg xmlns="http://www.w3.org/2000/svg" height="2em" viewBox="0 0 576 512">
                        <!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                        <path
                            d="M301.1 34.8C312.6 40 320 51.4 320 64V448c0 12.6-7.4 24-18.9 29.2s-25 3.1-34.4-5.3L131.8 352H64c-35.3 0-64-28.7-64-64V224c0-35.3 28.7-64 64-64h67.8L266.7 40.1c9.4-8.4 22.9-10.4 34.4-5.3zM425 167l55 55 55-55c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-55 55 55 55c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-55-55-55 55c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l55-55-55-55c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0z"/>
                    </svg>
                    Uit
                </button>
            </div>

            <h1 class="mb-4 font-extrabold">Instellingen</h1>
            <hr class="mb-4">
            <h1 class="mb-4">Voer hier uw naam in (zichtbaar voor de familie/verzorgers)</h1>
            <form action="{{ route('account.update') }}" method="POST">
                @csrf
                @method('PUT')
                <label class="mb-4" for="name">Naam</label>
                <input class="mb-4" type="text" name="name" id="name" value="{{ \App\Classes\Account::$accountEntity->name }}">
                <button type="submit" class="button-name bg-transparent hover:bg-green-500 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">Opslaan</button>
                @error('name')
                    <p class="text-red-400">Vul een naam in die maximaal 255 tekens bevat.</p>
                    <script>
                        console.log(document.getElementById('open-settings-sidebar'));
                        document.getElementById('open-settings-sidebar').click();
                    </script>
                @enderror
            </form>
            <h1 class="mt-4">Beheer gekoppelde gebruikers</h1>
            <table>
            @foreach($connectedUsers as $connectedUser)
                <tr class="border border-black border-l-0 border-r-0">
                    <td class="py-3 pl-2">{{ $connectedUser->user->name }}</td>
                    <td class="py-3">
                        <form action="{{ route('account.destroy', ['id' => $connectedUser->user->id]) }}" method="POST" id="form-delete-{{ $connectedUser->user->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                    class="button-delete"
                                    onclick="document.dispatchEvent(new CustomEvent('delete-button-pressed', { detail: { 'formId': 'form-delete-{{ $connectedUser->user->id }}' } }))"
                            >
                                Verwijder
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </table>
        </div>
    </div>
</nav>
