<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Nurses App')</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    {{-- Top Bar --}}
    <div class="topBar">
        <div class="topBarLeft">
            <div class="hamburger" id="hamburger">
                <img src="{{ asset('assets/common/hamburger.svg') }}" alt="Open Menu">
            </div>
            <div class="logo">
                <img src="{{ asset('assets/common/bluumLogoMain.svg') }}" alt="Bluum">
            </div>
        </div>
        <div class="topBarRight">
            <div class="searchArea">
                <div class="searchIcon">
                    <img src="{{ asset('assets/common/search.svg') }}" alt="Search">
                </div>
                <input type="text" placeholder="Search...">
            </div>
            <div class="profile"><a href="{{ route('login') }}">JD</a></div>
        </div>
    </div>

    <div class="pageWrapper">
        {{-- Sidebar --}}
        <div class="sidebar" id="sidebar">
            <a href="{{ route('home') }}" class="createButton">
                <img src="{{ asset('assets/common/createButton.svg') }}" alt="Create Icon">
                <span>Create</span>
            </a>
            <a href="{{ route('home') }}" class="homeButton">
                <img src="{{ asset('assets/common/homeIcon.svg') }}" alt="Home Icon">
                <span>Home</span>
            </a>
            <a href="{{ route('patients') }}" class="patientsButton">
                <img src="{{ asset('assets/common/calendarIcon.svg') }}" alt="Patients Icon">
                <span>Patients</span>
            </a>
            <a href="#" class="inboxButton">
                <img src="{{ asset('assets/common/inboxIcon.svg') }}" alt="Inbox Icon">
                <span>Inbox</span>
            </a>
        </div>

        {{-- Page Content --}}
        <div class="pageContent" id="pageContent">
            @yield('content')
        </div>
    </div>

    <script src="{{ asset('scripts/nurses.js') }}"></script>
</body>
</html>