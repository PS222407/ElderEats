@extends('layouts.app')

@section('content')
    <div class="flex flex-col mx-auto text-2xl">
        <button onclick="showCode()" class="bg-blue-500 hover:bg-blue-600 active:bg-blue-700 p-2 rounded text-white font-bold">Show code</button>
        <div id="display-code" class="text-center"></div>
        <div id="display-qrcode" class="text-center p-2"></div>
    </div>

    <script>
        let timeoutArray = [];

        async function showCode() {
            document.getElementById('display-code').innerHTML = await getCode();
            document.getElementById('display-qrcode').innerHTML = await getQrCode();
            waitForIncomingToken();
        }

        function hideCode() {
            document.getElementById('display-code').innerHTML = '';
        }

        async function fetchCodes() {
            const response = await fetch("{{ route('account.get-temporary-token') }}");
            return await response.json();
        }

        async function getCode() {
            const jsonData = await fetchCodes();

            return jsonData.tempToken;
        }

        async function getQrCode() {
            const jsonData = await fetchCodes();

            return jsonData.tempTokenQR;
        }

        function waitForIncomingToken() {
            const minutes = 10;
            const intervalSeconds = 5;

            for (let i = 0; i < minutes * 10 / intervalSeconds; i++) {
                let timeout = setTimeout(function () {
                    checkIncomingCodeInDatabase();
                }, intervalSeconds * 1000 * i);

                timeoutArray.push(timeout);
            }
        }

        async function checkIncomingCodeInDatabase() {
            const response = await fetch("{{ route('account.has-coming-request') }}");
            const jsonData = await response.json();

            if (jsonData.status === 'success') {
                requestIncomingFromUser(jsonData.userName, jsonData.userId);
            }
        }

        function requestIncomingFromUser(userName, userId) {
            stopTimeouts();
            let isConfirmed = confirm(userName + ' wil verbinding maken. Klik op OK om toe te staan');
            acceptOrDenyUser(isConfirmed, userId);
        }

        function stopTimeouts() {
            for (let i = 0; i < timeoutArray.length; i++) {
                clearTimeout(timeoutArray[i]);
            }
        }

        async function acceptOrDenyUser(isConfirmed, userId) {
            console.log(isConfirmed, userId)
            axios.post("{{ route('account.accept-or-deny-user') }}", {
                isConfirmed: isConfirmed,
                userId: userId
            }).then(function (response) {
                let jsonData = response.data;

                if (jsonData.status === 'success') {
                    hideCode();
                    alert(jsonData.message);
                }
            }).catch(function (error) {
                console.error(error);
            });
        }
    </script>

    <div id="modalEl" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 lg:text-2xl dark:text-white">
                        Producten
                    </h3>
                    <button id="close-delete-products-button" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-6">
                    <div id="deleted-products-list" class="flex flex-col gap-y-2">

                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button id="close-delete-products-button2" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600">
                        Sluiten
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection
