@extends('layouts.app')

@section('title', 'Inbox - Bluum')

@section('content')
    <div class="pageHeader">
        <h2>Inbox</h2>
    </div>

    <div class="fullscreenWidget inboxPage">
        @if ($criticalTasks->isEmpty())
        <div class="emptyFullscreenWidget">
            <h3>No new items for you!</h3>
        </div>
        @else
        <div class="inboxList">
            {{-- Loop through inbox items -> criticalTasks or new action items (new patient) --}}
            @foreach ($criticalTasks as $task)
            <div class="inboxRow">
                <div class="inboxRowLeft">
                    <img
                        class="inboxProfileIcon"
                        src="{{ asset('assets/patients/corgiIcon.svg') }}"
                        alt="Patient Icon"
                    />
                    <p class="patientDetails">
                        {{ $patients->firstWhere('id', $task->patient_id)->username }}
                    </p>
                </div>

                <p class="taskDescription">{{ $task->description }}</p>

                <div class="inboxStatus {{ $task->status }}Status">
                    <img
                        src="{{ asset('assets/common/' . $task->status . '.svg') }}"
                        alt="Status: {{ ucfirst($task->status) }}"
                    />
                    <span class="statusText">{{ ucfirst($task->status) }}</span>
                </div>

                <div class="inboxRowRight">
                    <p class="dueDate">{{ $task->scheduled_time }}</p>
                    @if ($task->status === 'pending')
                    <button class="inboxVerify">
                        <img src="{{ asset('assets/common/complete.svg') }}" alt="Mark Complete" />
                        <span class="verifyText">Verify</span>
                    </button>
                    @else
                    <div class="emptyCol"></div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
@endsection
