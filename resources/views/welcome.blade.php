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
@endsection
