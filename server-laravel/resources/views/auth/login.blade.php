@extends('layouts.auth')

@section('title', 'Login - Nurses')

@section('content')
    <div class="centralHeader">
        <div class="centralLogo">
            <img class="logoFlower" src="{{ asset('assets/common/bluumFlower.svg') }}" alt="">
            <h3 class="logoText">Bluum</h3>
        </div>
        <p class="centralTagline">Inpatient Companion for Kids</p>
        <div class="loginButtons">
            <button class="loginButton">Login</button>
            <button class="registerButton">Register</button>
        </div>

        {{-- Decorative Shapes --}}
        <div class="decorativeShapes">
            <img src="{{ asset('assets/common/yellowFlower.svg') }}" alt="" class="yellowFlower rotatingShape clockwise">
            <img src="{{ asset('assets/common/pinkFlower.svg') }}" alt="" class="pinkFlower rotatingShape clockwise">
            <img src="{{ asset('assets/common/blueTriangle.svg') }}" alt="" class="blueTriangle rotatingShape counterClockwise">
            <img src="{{ asset('assets/common/greenAsterisk.svg') }}" alt="" class="greenAsterisk rotatingShape counterClockwise">
        </div>
    </div>

    {{-- Login Card --}}
    <div class="largeCard login close">
        <h2>Sign into Bluum</h2>
        <form class="loginForm" method="POST" action="{{ route('login') }}">
            @csrf
            <button class="closeButton">X</button>
            <div class="inputGroup">
                <input type="email" id="loginEmail" name="email" placeholder="Email" value="dev@example.com" required>
                {{-- DEVELOPMENT: Pre-filled with dev credentials --}}
            </div>
            <div class="inputGroup">
                <input type="password" id="loginPassword" name="password" placeholder="Password" value="devpass" required>
                {{-- DEVELOPMENT: Pre-filled with dev credentials --}}
            </div>
            <button type="submit" class="submitButton">Login</button>
        </form>
    </div>

    {{-- Register Card --}}
    <div class="largeCard register close">
        <h2>Register with Bluum</h2>
        <form class="registerForm">
            <button class="closeButton">X</button>
            <div class="inputGroup">
                <input type="email" id="registerEmail" name="registerEmail" placeholder="Email" required>
            </div>
            <div class="inputGroup">
                <input type="password" id="registerPassword" name="registerPassword" placeholder="Password" required>
            </div>
            <button type="submit" class="submitButton">Register</button>
        </form>
    </div>
@endsection
