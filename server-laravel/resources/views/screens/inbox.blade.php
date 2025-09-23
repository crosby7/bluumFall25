@extends('layouts.app')

@section('title', 'Inbox - Bluum')

@section('content')
    <div class="pageHeader">
        <h2>Inbox</h2>
    </div>

    <div class="fullscreenWidget inboxPage">
        <div class="inboxList">
            {{-- Loop through inbox items --}}
            @foreach ($inboxItems as $item)
                <div class="inboxRow">
                    <div class="inboxRowLeft">
                        <img
                            class="inboxProfileIcon"
                            src="{{ asset($item->profile_icon ?? 'assets/patients/corgiIcon.svg') }}"
                            alt="Patient Icon"
                        />
                        <p class="patientDetails">
                            {{ $item->patient_name }} | Room: {{ $item->room }}
                        </p>
                    </div>

                    <p class="taskDescription">{{ $item->task_description }}</p>

                    <div class="inboxStatus">
                        <img
                            src="{{ asset($item->status_icon ?? 'assets/tasks/checkmark.svg') }}"
                            alt="Status: {{ $item->status_text }}"
                        />
                        <span class="statusText">{{ $item->status_text }}</span>
                    </div>

                    <div class="inboxRowRight">
                        <p class="dueDate">{{ $item->due_time }}</p>
                        <button class="inboxVerify">
                            <img src="{{ asset('assets/tasks/checkmark.svg') }}" alt="Mark Complete" />
                            <span class="verifyText">Verify</span>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
