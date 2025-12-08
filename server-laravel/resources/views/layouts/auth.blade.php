<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Auth')</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/common/bluumFlower.svg') }}">
</head>
<body>
    <div class="fullPage loginPage">
        <div class="pageContent" id="pageContent">
            @yield('content')
        </div>
    </div>

    <script src="{{ asset('scripts/nurses.js') }}"></script>
</body>
</html>