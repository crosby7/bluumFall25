@extends('layouts.app')

@section('title', 'Patients - Nurses')

@section('content')
    <div class="pageHeader"><h2>Patients</h2></div>
    <div class="fullscreenWidget patientsPage">
        @foreach($patients as $patient)
        <div class="patientCard" id="patient-{{ $patient->id }}" data-patient-id="{{ $patient->id }}">
            <div class="patientActionCenter">
                <div class="patientProfile">
                    <img src="{{ asset('assets/patients/corgiIcon.svg') }}" alt="Corgi Icon">
                    <div class="patientInfo">
                        <h2 class="patientName">{{ $patient->username }}</h2>
                        <p class="patientRoom">Room {{ 3900 + $patient->id }}</p>
                    </div>
                </div>
                <div class="patientTaskFilters">
                    <button class="filterButton activeFilter">All Tasks</button>
                    <button class="filterButton">Pending Verification</button>
                    <button class="filterButton">Overdue</button>
                </div>
                <button class="newTaskButton addNewTask">+ New Task</button>
            </div>

            <div class="inboxList">
                @foreach($tasks->where('patient_id', $patient->id) as $task)
                <div class="inboxRow">
                    <p class="dueDate">{{ $task->due_time }}</p>
                    <p class="taskDescription">{{ $task->description }}</p>
                    <div class="statusContainer">
                        <div class="inboxStatus {{ $task->status }}Status">
                            @if ($task->status === 'complete')
                            <img src="{{ asset('assets/common/complete.svg') }}" alt="">
                            @elseif ($task->status === 'overdue')
                            <img src="{{ asset('assets/common/overdue.svg') }}" alt="">
                            @elseif ($task->status === 'incomplete')
                            <img src="{{ asset('assets/common/incomplete.svg') }}" alt="">
                            @else
                            <img src="{{ asset('assets/common/new.svg') }}" alt="">
                            @endif
                            <span class="statusText">{{ ucfirst($task->status) }}</span>
                        </div>
                    </div>
                    @if($task->status === 'pending')
                    <button class="inboxVerify">
                        <img src="{{ asset('assets/tasks/complete.svg') }}" alt="Mark Complete">
                        <span class="verifyText">Verify</span>
                    </button>
                    @else
                    <div class="emptyCol"></div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
@endsection

{{-- Auto-scrolling when coming from search --}}
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.location.hash) {
            const id = window.location.hash.substring(1);
            const target = document.querySelector(`[data-patient-id="${id}"]`);
            if (target) {
                // Allow DOM render
                setTimeout(() => {
                    target.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    target.classList.add('highlight');
                    setTimeout(() => {
                        target.classList.remove('highlight');
                    }, 2000);
                }, 500);
            }
        }
    });
</script>
@endsection