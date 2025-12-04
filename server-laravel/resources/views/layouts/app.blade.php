<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Nurses App')</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/common/bluumFlower.svg') }}">
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />
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
                <input type="text" placeholder="Search..." id="globalSearch"/>
                <div class="searchResults close" id="searchResults"></div>
            </div>
            <div class="profile"><a href="{{ route('login') }}"><i class="fa-solid fa-user"></i></a></div>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0;">Logout</button>
            </form>
        </div>
    </div>

    <div class="pageWrapper">
        {{-- Sidebar --}}
        <div class="sidebar" id="sidebar">
            <a class="createButton">
                <img src="{{ asset('assets/common/createButton.svg') }}" alt="Create Icon">
                <span>Create</span>
            </a>
            <div class="createMenu">
                <a class="createPatientButton addNewPatient">New Patient</a>
                <a class="createTaskButton addNewTask">New Task</a>
            </div>
            <a href="{{ route('home') }}" class="homeButton">
                <img src="{{ asset('assets/common/homeIcon.svg') }}" alt="Home Icon">
                <span>Home</span>
            </a>
            <a href="{{ route('patients') }}" class="patientsButton">
                <img src="{{ asset('assets/common/calendarIcon.svg') }}" alt="Patients Icon">
                <span>Patients</span>
            </a>
            <a href="{{ route('inbox') }}" class="inboxButton">
                <img src="{{ asset('assets/common/inboxIcon.svg') }}" alt="Inbox Icon">
                <span>Inbox</span>
            </a>
        </div>

        {{-- Create Menu Popover --}}
        <div class="createMenuPopover" id="createMenuPopover">
                <a class="createPatientButton addNewPatient">New Patient</a>
                <a class="createTaskButton addNewTask">New Task</a>
        </div>

        {{-- Page Content --}}
        <div class="pageContent" id="pageContent">
            @yield('content')
            @include('components.global-modals')
        </div>
    </div>

    <script src="{{ asset('scripts/nurses.js') }}"></script>
    <script src="{{ asset('scripts/context.js') }}"></script>
    @yield('scripts')
</body>
</html>