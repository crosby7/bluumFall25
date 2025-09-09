<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Nurses Auth')</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    {{-- Simple Top Bar --}}
    <div class="topBar">
        <div class="topBarLeft">
            <div class="logo">
                <img src="{{ asset('assets/common/bluumLogoMain.svg') }}" alt="Bluum">
            </div>
        </div>
        <div class="topBarRight">
            <div class="profile"><a href="{{ route('home') }}">JD</a></div>
        </div>
    </div>

    <div class="pageWrapper">
        <div class="pageContent" id="pageContent">
            @yield('content')
        </div>
    </div>

    <script src="{{ asset('scripts/nurses.js') }}"></script>
</body>
</html>