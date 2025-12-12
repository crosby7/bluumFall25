@extends('layouts.app')

@section('title', 'Patients - Nurses')

@section('content')
    <div class="pageHeader"><h2>Patients</h2></div>
    <div class="fullscreenWidget patientsPage">
        @if ($patients->isEmpty())
        <div class="emptyFullscreenWidget">
            <h3>No Bluum patients to see here!</h3>
        </div>
        @else
        @foreach($patients as $patient)
        <div class="patientCard" id="patient-{{ $patient->id }}" data-patient-id="{{ $patient->id }}">
            <div class="patientActionCenter">
                <div class="patientProfile">
                    <img src="{{ asset('assets/patients/corgiIcon.svg') }}" alt="Corgi Icon">
                    <div class="patientInfo">
                        <h2 class="patientName">{{ $patient->username }}</h2>
                        <button class="patientCode" data-pairing-code="{{ $patient->pairing_code }}">Display Pairing Code</button>
                    </div>
                </div>
                <div class="patientTaskFilters">
                    <button class="filterButton activeFilter" data-filter="All Tasks">All Tasks</button>
                    <button class="filterButton" data-filter="Pending">Pending Verification</button>
                    <button class="filterButton" data-filter="Overdue">Overdue</button>
                </div>
                <button class="newTaskButton addNewTask">+ New Task</button>
            </div>

            <div class="inboxList">
                @foreach($tasks->where('patient_id', $patient->id) as $task)
                <div class="inboxRow" data-completion-id="{{ $task->id }}">
                    <p class="dueDatePatients">{{ $task->scheduled_time }}</p>
                    <p class="taskDescription">{{ $task->description }}</p>
                    <div class="statusContainer">
                        <div class="inboxStatus {{ $task->status }}Status">
                            @if ($task->status === 'completed' || $task->status === 'pending')
                            <img src="{{ asset('assets/common/complete.svg') }}" alt="">
                            @elseif ($task->status === 'overdue')
                            <img src="{{ asset('assets/common/overdue.svg') }}" alt="">
                            @elseif ($task->status === 'incomplete' || $task->status === 'skipped' || $task->status === 'failed')
                            <img src="{{ asset('assets/common/incomplete.svg') }}" alt="">
                            @else
                            <img src="{{ asset('assets/common/new.svg') }}" alt="">
                            @endif
                            <span class="statusText">{{ ucfirst($task->status) }}</span>
                        </div>
                    </div>
                    @if($task->status === 'pending')
                    <div class='inboxVerifyContainer'><button class="inboxVerify" onclick="verifyTask(this, {{ $task->completion_id }})">
                        <img src="{{ asset('assets/common/complete.svg') }}" alt="Mark Complete">
                        <span class="verifyText">Verify</span>
                    </button></div>
                    @else
                    <div class="emptyCol"></div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
        @endif
    </div>
@endsection

{{-- Auto-scrolling when coming from search --}}
{{-- Also listen for filterButtons --}}
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

        // .patientCode listeners attach
        function patientCodeListener() {
            const patientCodeButtons = document.querySelectorAll('.patientCode');
            const defaultText = 'Display Pairing Code';
            patientCodeButtons.forEach(button => {
                let isToggled = false;
                button.addEventListener('click', () => {
                    if (isToggled) {
                        button.textContent = defaultText;
                    } else {
                        const pairingCode = button.getAttribute('data-pairing-code') || 'N/A';
                        button.textContent = `${pairingCode}`;
                    }
                    isToggled = !isToggled;
                });
            });
        }
        

        // filter button functionality
        function setupFilterButtons() {
            document.querySelectorAll('.patientCard').forEach(card => {
                const filterButtons = card.querySelectorAll('.filterButton');
                const inboxRows = card.querySelectorAll('.inboxRow');

                filterButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        // Update active state
                        filterButtons.forEach(btn => btn.classList.remove('activeFilter'));
                        button.classList.add('activeFilter');

                        const filter = button.dataset.filter;
                        console.log('filtering by: ', filter);
                        inboxRows.forEach(row => {
                            const statusText = row.querySelector('.statusText').textContent.trim();
                            console.log('row status: ', statusText);
                            if (filter === 'All Tasks') {
                                row.style.display = '';
                                console.log('show all: filter: ', filter, " status: ", statusText);
                            }
                            else if (filter === 'Pending') {
                                row.style.display = (statusText === 'Pending') ? '' : 'none';
                                console.log('Pending: filter: ', filter, " status: ", statusText);
                            }
                            else if (filter === 'Overdue') {
                                row.style.display = (statusText === 'Overdue') ? '' : 'none';
                                console.log('Overdue: filter: ', filter, " status: ", statusText);
                            }
                            else {
                                row.style.display = '';
                                console.log('default show all: filter: ', filter, " status: ", statusText);
                            }
                        });
                    });
                });
            });
        }

        // Update UI on any data changes or passive refresh
        window.updatePatientUI = function (context) {
            console.log('Updating Patient UI with context:', context);
            const container = document.querySelector('.patientsPage');
            if (!container) return;

            container.innerHTML = context.patients.map(p => `
            <div class="patientCard" id="patient-${p.id}" data-patient-id="${p.id}">
                <div class="patientActionCenter">
                    <div class="patientProfile">
                        <img src="/assets/patients/corgiIcon.svg" alt="Corgi Icon">
                        <div class="patientInfo">
                            <h2 class="patientName">${p.username}</h2>
                            <button class="patientCode" data-pairing-code="${p.pairing_code}">Display Pairing Code</button>
                        </div>
                    </div>
                    <div class="patientTaskFilters">
                        <button class="filterButton activeFilter" data-filter="All Tasks">All Tasks</button>
                        <button class="filterButton" data-filter="Pending">Pending Verification</button>
                        <button class="filterButton" data-filter="Overdue">Overdue</button>
                    </div>
                    <button class="newTaskButton addNewTask">+ New Task</button>
                </div>
                <div class="inboxList">
                    ${context.tasks.filter(t => t.patient_id === p.id).map(t => `
                    <div class="inboxRow" data-completion-id="${t.id}">
                        <p class="dueDatePatients">${t.scheduled_time}</p>
                        <p class="taskDescription">${t.description}</p>
                        <div class="statusContainer">
                            <div class="inboxStatus ${t.status}Status">
                                ${t.status === 'completed' || t.status === 'pending' ? 
                                `<img src="/assets/common/complete.svg" alt="">` : t.status === 'overdue' ? 
                                `<img src="/assets/common/overdue.svg" alt="">` : t.status === 'incomplete' || t.status === 'skipped' || t.status === 'failed' ? 
                                `<img src="/assets/common/incomplete.svg" alt="">` : 
                                `<img src="/assets/common/new.svg" alt="">`}
                               <span class="statusText">${t.status ? t.status.charAt(0).toUpperCase() + t.status.slice(1) : 'Unknown'}</span>
                            </div>
                        </div>
                        ${t.status === 'pending' ? `
                        <div class='inboxVerifyContainer'><button class="inboxVerify" onclick="verifyTask(this, ${t.completion_id})">
                            <img src="/assets/common/complete.svg" alt="Mark Complete">
                            <span class="verifyText">Verify</span>
                        </button></div>` : `<div class="emptyCol"></div>`}
                    </div>
                    `).join('')}
                </div>
            </div>
            `).join('');

            // Reattach event listeners for new elements
            patientCodeListener();
            attachNewTaskListeners();

            // Reattach filter button functionality
            setupFilterButtons();
        }

        // Start passive refresh
        startPassiveRefresh(updatePatientUI);

        // Assign updatePatientUI as common refresh function
        window.pageRefreshFunction = updatePatientUI;
    });
</script>
@endsection