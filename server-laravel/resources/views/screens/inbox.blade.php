@extends('layouts.app')

@section('title', 'Inbox - Bluum')

@section('content')
    <div class="pageHeader">
        <h2>Inbox</h2>
    </div>

    <div class="fullscreenWidget inboxPage">
        @php
            // Merge critical tasks and events for inbox display
            $inboxItems = $criticalTasks->concat($events)->sortByDesc('created_at')->values();
        @endphp
        @if ($inboxItems->isEmpty())
        <div class="emptyFullscreenWidget">
            <h3>No new items for you!</h3>
        </div>
        @else
        <div class="inboxList">
            {{-- Loop through inbox items -> criticalTasks or new action items (new patient) --}}
            @foreach ($inboxItems as $task)
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

                <div class="statusContainer">
                @if ($task->type === 'event')
                    {{-- Event items show event name --}}
                    <div class="inboxStatus eventStatus">
                        <img src="{{ asset('assets/common/new.svg') }}" alt="">
                        <span class="statusText">{{ $task->name }}</span>
                    </div>
                @else
                    {{-- Task items show status with icon --}}
                        <div class="inboxStatus {{ $task->status }}Status">
                        @if ($task->status === 'completed' || $task->status === 'pending')
                            <img src="{{ asset('assets/common/complete.svg') }}" alt="">
                        @elseif ($task->status === 'overdue')
                            <img src="{{ asset('assets/common/overdue.svg') }}" alt="">
                        @endif
                        <span class="statusText">{{ ucfirst($task->status) }}</span>
                        </div>
                @endif
                </div>


                <div class="inboxRowRight">
                    <p class="dueDate">{{ $task->scheduled_time }}</p>
                    @if ($task->type !== 'event' && $task->status === 'pending')
                    <div class="inboxVerifyContainer">
                        <button class="inboxVerify" onclick="verifyTask(this, {{ $task->completion_id }})">
                        <img src="{{ asset('assets/common/complete.svg') }}" alt="Mark Complete" />
                        <span class="verifyText">Verify</span>
                    </button>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
                // Update UI on any data changes or passive refresh
        window.updateInboxUI = function (context) {
            console.log('Updating Inbox UI with context:', context);
            const container = document.querySelector('.inboxList');
            if (!container) return;

            const inboxItems = [
                ...(context.criticalTasks || []),
                ...(context.events || [])
            ].sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

            container.innerHTML = inboxItems.map(item => {
                const patient = context.patients.find(p => p.id === item.patient_id) || {};
                const isEvent = item.type === 'event';
                const status = item.status || 'new';

                const statusIcon = isEvent
                    ? '<img src="{{ asset('assets/common/new.svg') }}" alt="">'
                    : status === 'completed' || status === 'pending'
                        ? '<img src="{{ asset('assets/common/complete.svg') }}" alt="">'
                        : status === 'overdue'
                            ? '<img src="{{ asset('assets/common/overdue.svg') }}" alt="">'
                            : '<img src="{{ asset('assets/common/new.svg') }}" alt="">';

                return `
                    <div class="inboxRow">
                        <div class="inboxRowLeft">
                            <img class="inboxProfileIcon" src="{{ asset('assets/patients/corgiIcon.svg') }}" alt="Patient Icon" />
                            <p class="patientDetails">${patient.username || 'Patient'}</p>
                        </div>

                        <p class='taskDescription'>${item.description || ''}</p>
                        <div class="statusContainer">
                        ${isEvent
                            ? `<div class="inboxStatus eventStatus">
                                ${statusIcon}
                                <span class="statusText">${item.name || 'New Event'}</span>
                               </div>`
                            : `<div class="inboxStatus ${status}Status">
                                ${statusIcon}
                                <span class="statusText">${status.charAt(0).toUpperCase() + status.slice(1)}</span>
                               </div>`}
                        </div>
                        <div class="inboxRowRight">
                            <p class="dueDate">${item.scheduled_time || ''}</p>
                            ${!isEvent && status === 'pending' ? `
                            <div class='inboxVerifyContainer'><button class="inboxVerify" onclick="verifyTask(this, ${item.completion_id})">
                                <img src="{{ asset('assets/common/complete.svg') }}" alt="Mark Complete" />
                                <span class="verifyText">Verify</span>
                            </button></div>` : ''}
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Start passive refresh
        startPassiveRefresh(updateInboxUI);

        // Assign updateInboxUI as common refresh function
        window.pageRefreshFunction = updateInboxUI;
    })
</script>
@endsection