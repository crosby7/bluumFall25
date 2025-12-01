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
                    @if ($task->type === 'event')
                        <img
                        class="statusIcon eventStatus"
                        src="{{ asset('assets/common/new.svg') }}"
                        alt="Status: {{ ucfirst($task->status) }}"
                    />
                    @elseif ($task->status === 'completed' || $task->status === 'pending')
                        <img
                        class="statusIcon completedStatus"
                        src="{{ asset('assets/common/complete.svg') }}"
                        alt="Status: {{ ucfirst($task->status) }}" />
                    @elseif ($task->status === 'overdue')
                        <img
                        class="statusIcon overdueStatus"
                        src="{{ asset('assets/common/overdue.svg') }}"
                        alt="Status: {{ ucfirst($task->status) }}" />
                    @elseif ($task->status === 'incomplete' || $task->status === 'skipped' || $task->status === 'failed')
                        <img
                        class="statusIcon {{$task->status}}Status"
                        src="{{ asset('assets/common/incomplete.svg') }}"
                        alt="Status: {{ ucfirst($task->status) }}" />
                    @else
                        <img
                        class="statusIcon generalStatus"
                        src="{{ asset('assets/common/new.svg') }}"
                        alt="Status: {{ ucfirst($task->status) }}" />
                    @endif
                    @if ($task->status === 'pending')
                    <button class="inboxVerifyButton">
                        <img src="{{ asset('assets/common/complete.svg') }}" alt="Mark Complete">
                    </button>
                    @else
                    <div class="homeEmptyCol"></div>
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

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Update UI on any data changes or passive refresh
        window.updateHomeUI = function (context) {
            console.log('Updating Home UI with context:', context);

            // there are two widgets to update: inbox and patients

            // update inbox widget
            const inboxContainer = document.querySelector('.inbox');
            if (!inboxContainer) return;

            const inboxItems = [
                ...(context.criticalTasks || []),
                ...(context.events || [])
            ].sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

            // set pageHeader
            inboxContainer.innerHTML = `<div class='widgetHeader'><h3>Inbox</h3></div>`;

            // if inboxItems is empty, show empty state
            if (inboxItems.length === 0) {
                inboxContainer.innerHTML += `
                    <div class='emptyWidget'>
                        <h3>No new items for you!</h3>
                    </div>
                `;
                inboxContainer.innerHTML += `<div class="widgetFooter" onclick="window.location.href='/inbox'"><p>View All</p></div>`;
                return;
            }

            // inbox is not empty. set inbox content
            inboxContainer.innerHTML += `<div class='inboxList'>` + inboxItems.map(item => {
                const patient = context.patients.find(p => p.id === item.patient_id) || {};
                const isEvent = item.type === 'event';
                const status = item.status || 'new';

                const statusIcon = isEvent
                    ? '<img class="statusIcon eventStatus" src="{{ asset('assets/common/new.svg') }}" alt="">'
                    : status === 'completed' || status === 'pending'
                        ? '<img class="statusIcon '+ status +'Status" src="{{ asset('assets/common/complete.svg') }}" alt="">'
                        : status === 'overdue'
                            ? '<img class="statusIcon overdueStatus" src="{{ asset('assets/common/overdue.svg') }}" alt="">'
                            : '<img class="statusIcon newStatus" src="{{ asset('assets/common/new.svg') }}" alt="">';

                return `
                        <div class='inboxRow'>
                            <img class='inboxProfileIcon' src='{{ asset('assets/patients/corgiIcon.svg') }}' alt='Patient Icon' />
                            <p class='patientDetails'>${patient.username || 'Patient'}</p>
                            ${statusIcon}
                                ${!isEvent && status === 'pending' ? `
                                <button class="inboxVerifyButton">
                                    <img src="{{ asset('assets/common/complete.svg') }}" alt="Mark Complete">
                                </button>` : `<div class="homeEmptyCol"></div>`}
                        </div>
                `;
            }).join('');

            // set footer
            inboxContainer.innerHTML += `</div><div class="widgetFooter" onclick="window.location.href='/inbox'"><p>View All</p></div>`;
        }

        // Start passive refresh
        startPassiveRefresh(updateHomeUI);

        // Assign updateHomeUI as common refresh function
        window.pageRefreshFunction = updateHomeUI;
    })
</script>
@endsection