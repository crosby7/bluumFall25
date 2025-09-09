@extends('layouts.app')

@section('title', "Nurse's Home Page")

@section('content')
    <div class="pageHeader">
        <h2>Home</h2>
        <p>Monday, January 20th</p>
    </div>
    <h2 class="greeting">Good Morning, Jane Doe!</h2>

    <div class="widgetArea">
        {{-- Inbox Widget --}}
        <div class="widget inbox">
            <div class="widgetHeader"><h3>Inbox</h3></div>
            <div class="inboxList">
                {{-- Example Row --}}
                <div class="inboxRow">
                    <img class="inboxProfileIcon" src="{{ asset('assets/patients/corgiIcon.svg') }}" alt="Patient Icon">
                    <p class="patientDetails">Pixel#3501 | Room: 3909</p>
                    <img class="inboxStatusIcon" src="{{ asset('assets/tasks/statusComplete.svg') }}" alt="Status: Complete">
                    <button class="inboxVerifyButton">
                        <img src="{{ asset('assets/tasks/checkmark.svg') }}" alt="Mark Complete">
                    </button>
                </div>
            </div>
            <div class="widgetFooter"><p>View All</p></div>
        </div>

        {{-- Patients Widget --}}
        <div class="widget patients">
            <div class="widgetHeader"><h3>Patients</h3></div>
            <div class="patientList">
                <div class="patientCard">
                    <img src="{{ asset('assets/patients/corgiIcon.svg') }}" alt="Patient Icon">
                    <div class="patientInfo">
                        <h2 class="patientName">Pixel#3501</h2>
                        <p class="patientRoom">Room 3909</p>
                    </div>
                </div>
            </div>
            <div class="widgetFooter"><p>View All</p></div>
        </div>
    </div>
@endsection
