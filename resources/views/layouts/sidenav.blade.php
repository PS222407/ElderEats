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
            <h1 class="mb-4 font-extrabold">Instellingen</h1>
            <hr class="mb-4">
            <h1 class="mb-4">Voer hier uw naam in (zichtbaar voor de familie/verzorgers)</h1>
            <form action="{{ route('account.update') }}" method="POST">
                @csrf
                @method('PUT')
                <label class="mb-4" for="name">Naam</label>
                <input class="mb-4" type="text" name="name" id="name" value="{{ \App\Classes\Account::$accountModel->name }}">
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
                    <td class="py-3 pl-2">{{ $connectedUser->name }}</td>
                    <td class="py-3">
                        <form action="{{ route('account.destroy', ['id' => $connectedUser->id]) }}" method="POST" id="form-delete-{{ $connectedUser->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                    class="button-delete"
                                    onclick="document.dispatchEvent(new CustomEvent('delete-button-pressed', { detail: { 'formId': 'form-delete-{{ $connectedUser->id }}' } }))"
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
