@extends('layouts.auth')

@section('title', 'Verify Email - Nurses')

@section('content')
    <div class="centralHeader">
        <div class="loginBanner">
            <div class="centralLogo">
                <img class="logoFlower" src="{{ asset('assets/common/bluumFlower.svg') }}" alt="">
                <h3 class="logoText">Bluum</h3>
            </div>
            <p class="centralTagline">Inpatient Companion for Kids</p>
        </div>

        {{-- Decorative Shapes --}}
        <div class="decorativeShapes">
            <img src="{{ asset('assets/common/yellowShape.svg') }}" alt="" class="yellowShape rotatingShape clockwise">
            <img src="{{ asset('assets/common/pinkShape.svg') }}" alt="" class="pinkShape rotatingShape counterClockwise">
            <img src="{{ asset('assets/common/purpleShape.svg') }}" alt="" class="purpleShape rotatingShape clockwise">
            <img src="{{ asset('assets/common/greenShape.svg') }}" alt="" class="greenShape rotatingShape counterClockwise">
        </div>
    </div>

    {{-- Verification Notice Card --}}
    <div class="loginPageCard">
        <h2>Verify Your Email</h2>

        <p style="margin-bottom: 1.5rem; color: #666;">
            Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just emailed to you.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div style="margin-bottom: 1rem; padding: 10px; background-color: #d4edda; color: #155724; border-radius: 4px;">
                <p>A new verification link has been sent to your email address.</p>
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="submitButton">Resend Verification Email</button>
        </form>
    </div>
@endsection
