@extends('layouts.app')

@section('content')
    <h1 class="text-4xl mt-10 font-bold text-center">Inventaris</h1>

    @include('layouts.sidenav')

    @livewire('product-list-homepage')

    @if (session('popup'))
        <div id="show-add-to-shopping-list" ean="{{ session('ean') }}"></div>
    @endif
    @if (session('error-popup'))
        <div id="show-error-alert"></div>
    @endif
    @if(session('type'))
        <div id="show-success-alert"></div>
    @endif

    <script>
        let timeoutArray = [];

        async function showCode() {
            const response = await fetch("{{ route('account.get-temporary-token') }}");
            const jsonData = await response.json();

            document.getElementById('display-code').innerHTML = jsonData.tempToken;
            document.getElementById('display-qrcode').innerHTML = jsonData.tempTokenQR;

            setTimeout(hideCode, 10 * 60 * 1000);
        }
    </script>

    <div id="modalEl" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-5 bg-banner border-b rounded-t">
                    <h3 class="text-xl font-semibold text-white lg:text-2xl">
                        Welk product wilt u verwijderen?
                    </h3>
                    <button id="close-delete-products-button" type="button" class="text-gray-400 bg-transparent hover:shadow-lg rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                        <svg class="w-10 h-10" fill="white" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-6" style="overflow-y: scroll; max-height: 487px">
                    <div id="deleted-products-list" class="flex flex-col gap-y-2">

                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
                    <button id="close-delete-products-button2" type="button" class="text-gray-500 font-bold hover:bg-gray-100 rounded-lg border border-gray-200 text-sm px-5 py-2.5 hover:text-gray-900">
                        Sluiten
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="btn hidden"></div>
    @include('modals.product-added-modal')
@endsection
