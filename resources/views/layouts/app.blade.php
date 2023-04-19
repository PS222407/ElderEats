<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="account" content="{{ \App\Classes\Account::$accountModel->id }}">

    <title>ElderEats</title>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="antialiased">
    @include('layouts.nav')
    @yield('content')
    @livewireScripts

<script>
    setInterval(function(){ window.location.reload(); }, 60 * 60 * 1000);
</script>
</body>
</html>
