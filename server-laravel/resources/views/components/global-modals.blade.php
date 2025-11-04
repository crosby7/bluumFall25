<div class="popUpRegion close">
    {{-- New Patient Modal --}}
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
                {{-- <div class="inputGroup">
                    <label for="patientContact">Patient Contact</label>
                    <input type="text" id="patientContact" name="patientContact" placeholder="Enter email..." required>
                </div> --}}
                <div class="twoInputs">
                    <div class="inputGroup">
                        <label for="usernamePart1">Username Prefix</label>
                        <select name="usernamePart1" id="usernamePart1">
                            <option value="Blue">Blue</option>
                            <option value="Fluffy">Fluffy</option>
                            <option value="Happy">Happy</option>
                            <option value="Brave">Brave</option>
                            <option value="Funky">Funky</option>
                            <option value="Good">Good</option>
                            <option value="Turbo">Turbo</option>
                            <option value="Rocky">Rocky</option>
                            <option value="Gentle">Gentle</option>
                        </select>
                    </div>
                    <div class="inputGroup">
                        <label for="usernamePart2">Username Suffix</label>
                        <select name="usernamePart2" id="usernamePart2">
                            <option value="Tiger">Tiger</option>
                            <option value="Hero">Hero</option>
                            <option value="Falcon">Falcon</option>
                            <option value="Wizard">Wizard</option>
                            <option value="Fox">Fox</option>
                            <option value="Dragon">Dragon</option>
                            <option value="Nurse">Nurse</option>
                            <option value="Duckling">Duckling</option>
                            <option value="Kiddo">Kiddo</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="submitButtons">
                <button type="submit" class="submitButton createPatient">Create Patient</button>
                <button type="button" class="cancelButton closeButton">Cancel</button>
            </div>
        </form>
    </div>

    {{-- New Patient Confirmation Modal --}}
    <div class="popUp largeCard confirmPatient close">
        <button class="closeButton xButton" type="button">X</button>
        <div class="confirmation">
            <img src="{{ asset('assets/tasks/statusComplete.svg') }}" alt="">
            <div class="confirmationText">
                <h3>New Patient Registered!</h3>
                <p>You've successfully registered a new patient.</p>
                <p>Pairing Code: <strong id="confirmPairingCode"></strong></p>
            </div>
            <button type="button" class="addNewPatient">New Patient</button>
            <button type="button" class="closeButton maybeLater">Maybe Later</button>
        </div>
    </div>

    {{-- New Task Modal --}}
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

    {{-- New Task Confirmation Modal --}}
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