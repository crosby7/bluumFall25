@extends('layouts.app')

@section('title', 'Inbox - Bluum')

@section('content')
    <div class="pageHeader">
        <h2>Inbox</h2>
    </div>

    <div class="fullscreenWidget inboxPage">
        <div class="inboxList">
            {{-- Loop through inbox items --}}
            @foreach ($tasks as $task)
                <div class="inboxRow">
                    <div class="inboxRowLeft">
                        <img
                            class="inboxProfileIcon"
                            src="{{ asset($task->profile_icon ?? 'assets/patients/corgiIcon.svg') }}"
                            alt="Patient Icon"
                        />
                        <p class="patientDetails">
                            {{ $task->patient_name }} | Room: {{ $task->room }}
                        </p>
                    </div>

                    <p class="taskDescription">{{ $task->task_description }}</p>

                    <div class="inboxStatus {{ $task->status }}Status">
                        <img
                            src="{{ asset($task->status_icon ?? 'assets/common/complete.svg') }}"
                            alt="Status: {{ ucfirst($task->status) }}"
                        />
                        <span class="statusText">{{ ucfirst($task->status) }}</span>
                    </div>

                    <div class="inboxRowRight">
                        <p class="dueDate">{{ $task->due_time }}</p>
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
    </div>
@endsection
