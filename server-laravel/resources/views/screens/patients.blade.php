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

    {{-- Pop Up for New Patient Initialization --}}
    <div class="largeCard">
        <button class="closeButton">X</button>
        <form class="newPatientForm">
            <div class="formInputs">
                <div class="patientIDRow">
                    <input type="text" id="patientID" name="patientID" value="#3768" readonly>
                    <label for="patientID">
                        <div class="patientIDLabel">
                            <h4>Patient ID</h4>
                            <p>Auto Generated</p>
                        </div>
                    </label>
                </div>
                <div class="inputGroup">
                    <label for="patientContact">Patient Contact</label>
                    <input type="text" id="patientContact" name="patientContact" placeholder="Enter email..." required>
                </div>
                <div class="inputGroup">
                    <label for="roomNumber">Room Number</label>
                    <select name="roomNumber" id="roomNumber">
                        <option value="" disabled selected>Select room...</option>
                        <option value="3901">Room 3901</option>
                        <option value="3902">Room 3902</option>
                        <option value="3903">Room 3903</option>
                        <option value="3904">Room 3904</option>
                        <option value="3905">Room 3905</option>
                        <option value="3906">Room 3906</option>
                        <option value="3907">Room 3907</option>
                        <option value="3908">Room 3908</option>
                        <option value="3909">Room 3909</option>
                    </select>
                </div>
                <div class="inputGroup">
                    <label for="patientNotes">Notes</label>
                    <textarea id="patientNotes" name="patientNotes" placeholder="Anything to add about this patient..." required></textarea>
                </div>
            </div>
            <div class="submitButtons">
                <button type="submit" class="submitButton">Create Patient</button>
                <button type="button" class="cancelButton">Cancel</button>
            </div>
        </form>
    </div>
@endsection