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
        <button onclick="showCode()" class="bg-blue-500 hover:bg-blue-600 active:bg-blue-700 p-2 rounded text-white font-bold">Show code</button>
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
        </script>
    </body>
</html>
