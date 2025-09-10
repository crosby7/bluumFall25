@extends('layouts.auth')

@section('title', 'Login - Nurses')

@section('content')
    <div class="centralHeader">
        <div class="centralLogo">
            <img class="logoFlower" src="{{ asset('assets/common/bluumFlower.svg') }}" alt="">
            <h3 class="logoText">Bluum</h3>
        </div>
        <div class="loginButtons">
            <button class="loginButton">Login</button>
            <button class="registerButton">Register</button>
        </div>
    </div>

    {{-- Login Card --}}
    <div class="largeCard login close">
        <h2>Welcome Back!</h2>
        <form class="loginForm" method="POST" action="{{ route('login') }}">
            @csrf
            <button class="closeButton">X</button>
            <div class="inputGroup">
                <label for="loginEmail">Email</label>
                <input type="email" id="loginEmail" name="email" value="dev@example.com" required>
                {{-- DEVELOPMENT: Pre-filled with dev credentials --}}
            </div>
            <div class="inputGroup">
                <label for="loginPassword">Password</label>
                <input type="password" id="loginPassword" name="password" value="devpass" required>
                {{-- DEVELOPMENT: Pre-filled with dev credentials --}}
            </div>
            <button type="submit" class="submitButton">Login</button>
        </form>
    </div>

    {{-- Register Card --}}
    <div class="largeCard register close">
        <h2>Let's Get Started!</h2>
        <form class="registerForm">
            <button class="closeButton">X</button>
            <div class="inputGroup">
                <label for="registerEmail">Email</label>
                <input type="email" id="registerEmail" name="registerEmail" required>
            </div>
            <div class="inputGroup">
                <label for="registerPassword">Password</label>
                <input type="password" id="registerPassword" name="registerPassword" required>
            </div>
            <button type="submit" class="submitButton">Register</button>
        </form>
    </div>
@endsection
