@extends('layouts.app')

@section('title', "Nurse's Home Page")

@section('content')
    <div class="pageHeader">
        <h2>Home</h2>
        <p>{{ date('l, F jS') }}</p>
    </div>
    <h2 class="greeting">Good Morning, {{ $user->name ?? "Jane Doe" }}!</h2>

    <div class="widgetArea">
        {{-- Inbox Widget --}}
        <div class="widget inbox">
            <div class="widgetHeader"><h3>Inbox</h3></div>
            @if ($criticalTasks->isEmpty())
            <div class="emptyWidget">
                <h3>No new items for you!</h3>
            </div>
            @else
            <div class="inboxList">
                @foreach($criticalTasks as $task)
                <div class="inboxRow">
                    <img class="inboxProfileIcon" src="{{ asset('assets/patients/corgiIcon.svg') }}" alt="Patient Icon">
                    <p class="patientDetails">
                        {{ $patients->firstWhere('id', $task->patient_id)->username }}
                    </p>
                    <img
                        class="statusIcon {{ $task->status }}Status"
                        src="{{ asset('assets/common/' . $task->status . '.svg') }}"
                        alt="Status: {{ ucfirst($task->status) }}"
                    />
                    @if ($task->status === 'pending')
                    <button class="inboxVerifyButton">
                        <img src="{{ asset('assets/common/complete.svg') }}" alt="Mark Complete">
                    </button>
                    @endif
                </div>
                @endforeach 
            </div>
            @endif
            <div class="widgetFooter" onclick="window.location.href='/inbox'"><p>View All</p></div>
        </div>

        {{-- Patients Widget --}}
        <div class="widget patients">
            <div class="widgetHeader"><h3>Patients</h3></div>
            <div class="patientList">
                @foreach($patients as $patient)
                <div class="patientCard" onclick="window.location.href='/patients#{{ $patient->id }}'">
                    <img src="{{ asset('assets/patients/corgiIcon.svg') }}" alt="Patient Icon">
                    <div class="patientInfo">
                        <h2 class="patientName">{{ $patient->username }}</h2>
                    </div>
                </div>
                @endforeach
                @if ($patients->isEmpty())
                <div class="emptyWidget">
                    <h3>No patients for now!</h3>
                </div>
                @endif
            </div>
            <div class="widgetFooter" onclick="window.location.href='/patients'"><p>View All</p></div>
        </div>
    </div>
@endsection
