@extends('layouts.auth')

@section('title', 'Login - Nurses')

@section('content')
    <div class="centralHeader">
        <div class="loginBanner">
            <div class="centralLogo">
            <img class="logoFlower" src="{{ asset('assets/common/bluumFlower.svg') }}" alt="">
            <h3 class="logoText">Bluum</h3>
        </div>
        <p class="centralTagline">Inpatient Companion for Kids</p>
        </div>
        <div class="loginButtons">
            <button class="loginButton">Login</button>
            <button class="registerButton">Register</button>
        </div>

        {{-- Decorative Shapes --}}
        <div class="decorativeShapes">
            <img src="{{ asset('assets/common/yellowShape.svg') }}" alt="" class="yellowShape rotatingShape clockwise">
            <img src="{{ asset('assets/common/pinkShape.svg') }}" alt="" class="pinkShape rotatingShape counterClockwise">
            <img src="{{ asset('assets/common/purpleShape.svg') }}" alt="" class="purpleShape rotatingShape clockwise">
            <img src="{{ asset('assets/common/greenShape.svg') }}" alt="" class="greenShape rotatingShape counterClockwise">
        </div>
    </div>

    {{-- Login Card --}}
    <div class="loginPageCard popUp login close">
        <form class="loginForm" method="POST" action="{{ route('login') }}">
            @csrf
            <button class="closeButton xButton" type="button">X</button>
            <h2>Sign into Bluum</h2>
            <div class="inputGroup">
                <input type="email" id="loginEmail" name="email" placeholder="Email" value="dev@example.com" required>
                <input type="password" id="loginPassword" name="password" value="password" placeholder="Password" required>
            </div>
            <button type="submit" class="submitButton">Log In</button>
        </form>
    </div>

    {{-- Register Card --}}
    <div class="loginPageCard popUp register close">
        <form class="registerForm">
            <button class="closeButton xButton" type="button">X</button>
            <h2>Register with Bluum</h2>
            <div class="inputGroup">
                <input type="email" id="registerEmail" name="registerEmail" placeholder="Email" required>
                <input type="password" id="registerPassword" name="registerPassword" placeholder="Password" required>
            </div>
            <button type="submit" class="submitButton">Register</button>
        </form>
    </div>
@endsection
