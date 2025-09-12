@extends('layouts.app')

@section('title', 'Patients - Nurses')

@section('content')
    <div class="pageHeader"><h2>Patients</h2></div>
    <div class="fullscreenWidget patientsPage">
        @foreach($patients as $patient)
        <div class="patientCard">
            <div class="patientActionCenter">
                <div class="patientProfile">
                    <img src="{{ asset('assets/patients/corgiIcon.svg') }}" alt="Corgi Icon">
                    <div class="patientInfo">
                        <h2 class="patientName">{{ $patient->name }}</h2>
                        <p class="patientRoom">Room {{ 3900 + $patient->id }}</p>
                    </div>
                </div>
                <div class="patientTaskFilters">
                    <button class="filterButton activeFilter">All Tasks</button>
                    <button class="filterButton">Pending Verification</button>
                    <button class="filterButton">Overdue</button>
                </div>
                <button class="newTaskButton">+ New Task</button>
            </div>

            <div class="inboxList">
                <div class="inboxRow">
                    <p class="dueDate">9:00 am</p>
                    <p class="taskDescription">Cafe: Breakfast</p>
                    <div class="inboxStatus">
                        <img src="{{ asset('assets/tasks/checkmark.svg') }}" alt="Status: Complete">
                        <span class="statusText">Complete</span>
                    </div>
                    <button class="inboxVerify">
                        <img src="{{ asset('assets/tasks/checkmark.svg') }}" alt="Mark Complete">
                        <span class="verifyText">Verify</span>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endsection