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
    <div class="popUpRegion close">
        <div class="largeCard popUp close newPatient">
            <button class="closeButton xButton" type="button">X</button>
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
                </div>
                <div class="submitButtons">
                    <button type="submit" class="submitButton createPatient">Create Patient</button>
                    <button type="button" class="cancelButton closeButton">Cancel</button>
                </div>
            </form>
        </div>
        <div class="popUp largeCard confirmPatient close">
            <button class="closeButton xButton" type="button">X</button>
            <div class="confirmation">
                <img src="{{ asset('assets/tasks/statusComplete.svg') }}" alt="">
                <div class="confirmationText">
                    <h3>New Patient Registered!</h3>
                    <p>You've successfully registered a new patient.</p>
                </div>
                <button type="button" class="addNewPatient">New Patient</button>
                <button type="button" class="closeButton maybeLater">Maybe Later</button>
            </div>
        </div>
        <div class="newTask largeCard popUp close">
            <button class="closeButton xButton" type="button">X</button>
            <form class="newTaskForm">
                <div class="formInputs">
                    <select name="taskName" id="taskName" class="fullWidthSelect">
                        <option value="" disabled selected>Select task...</option>
                        <option value="medication">Morning Medication</option>
                        <option value="grooming">Grooming</option>
                        <option value="ptTherapy">PT Therapy</option>
                        <option value="breakfast">Breakfast</option>
                        <option value="lunch">Lunch</option>
                        <option value="dinner">Dinner</option>
                        <option value="otTherapy">OT Therapy</option>
                        <option value="recActivity">Recreation Activity</option>
                        <option value="reading">School: Reading</option>
                        <option value="math">School: Math</option>
                        <option value="bandages">Bandage Change</option>
                    </select>
                    <div class="inputGroup">
                        <label for="taskAssignee">Assignee</label>
                        <select name="taskAssignee" id="taskAssignee">
                            <option value="" disabled selected>No assignee</option>
                            <option value="patient1">dev</option>
                            <option value="patient2">Jared Carthalion</option>
                            <option value="patient3">Jace Beleren</option>
                        </select>
                    </div>
                    <div class="inputGroup">
                        <label for="dueDate">Due Date & Time</label>
                        <input type="datetime-local" id="dueDate" name="dueDate" required>
                    </div>
                    <div class="twoInputs">
                        <div class="inputGroup">
                            <label for="repeats">Repeats</label>
                            <select name="repeats" id="repeats">
                                <option value="none" selected>No repeats</option>
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                            </select>
                        </div>
                        <div class="inputGroup">
                            <label for="category">Category</label>
                            <select name="category" id="category">
                                <option value="personal">Self Care</option>
                                <option value="medical">Medical</option>
                                <option value="nutritional">Nutritional</option>
                                <option value="school">School</option>
                                <option value="recreational">Recreational</option>
                            </select>
                        </div>
                    </div>
                </div>
                    <div class="submitButtons">
                        <button type="submit" class="submitButton createTask">Create Task</button>
                        <button type="button" class="cancelButton closeButton">Cancel</button>
                    </div>
        </div>
        <div class="popUp largeCard confirmTask close">
            <button class="closeButton xButton" type="button">X</button>
            <div class="confirmation">
                <img src="{{ asset('assets/tasks/statusComplete.svg') }}" alt="">
                <div class="confirmationText">
                    <h3>Task Created!</h3>
                    <p>You've successfully assigned a new task.</p>
                </div>
                <button type="button" class="addNewTask">New Task</button>
                <button type="button" class="closeButton maybeLater">Maybe Later</button>
            </div>
        </div>
    </div>
@endsection