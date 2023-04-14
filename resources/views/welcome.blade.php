<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
<div class="flex flex-col mx-auto text-2xl">
    <button onclick="showCode()"
            class="bg-blue-500 hover:bg-blue-600 active:bg-blue-700 p-2 rounded text-white font-bold">Show code
    </button>
    <div id="display-code" class="text-center"></div>
</div>

<script>
    async function showCode() {
        document.getElementById('display-code').innerHTML = await getCode();
    }

    async function getCode() {
        const response = await fetch("{{ route('account.get-temporary-token') }}");
        const jsonData = await response.json();

        return jsonData.tempToken;
    }

    checkForIncomingCode();
    function checkForIncomingCode() {
        const minutes = 10;
        const intervalSeconds = 5;

        for (let i = 0; i < minutes * 10 / intervalSeconds; i++) {
            setTimeout(function () {
                checkIncomingCodeInDatabase();
            }, intervalSeconds * 1000 * i);
        }
    }

    async function checkIncomingCodeInDatabase() {
        console.log('check for incoming');

        const response = await fetch("{{ route('account.has-coming-request') }}");
        const jsonData = await response.json();

        console.log('')
    }
</script>
</body>
</html>
