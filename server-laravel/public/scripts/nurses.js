const sidebar = document.getElementById("sidebar");
const toggleBtn = document.getElementById("hamburger");
const centralHeader = document.querySelector(".centralHeader");
const loginButton = document.querySelector(".loginButton");
const registerButton = document.querySelector(".registerButton");
const loginButtonDiv = document.querySelector(".loginButtons");

const newTaskPopUp = document.querySelector(".newTask");

if (toggleBtn) {
    toggleBtn.addEventListener("click", () => {
        sidebar.classList.toggle("open");
    });
}

// find the common refresh function for the specific page we're on
window.runPageRefresh = function (context) {
    if (typeof window.pageRefreshFunction === "function") {
        window.pageRefreshFunction(context);
    } else {
        console.warn("No pageRefreshFunction() defined for this page.");
    }
};

// Large Cards: close button functionality
const closeButtons = document.querySelectorAll(".closeButton");

if (closeButtons) {
    closeButtons.forEach((button) => {
        button.addEventListener("click", () => {
            button.closest(".popUp").classList.toggle("close");
            if (
                button
                    .closest(".popUp")
                    .parentElement.classList.contains("popUpRegion")
            ) {
                button.closest(".popUpRegion").classList.toggle("close");
            }
            if (loginButtonDiv) {
                loginButtonDiv.classList.remove("close");
            }
        });
    });
}

// // Temp: submit button will just close the large card
// // Skip .createPatient and .createTask buttons as they have their own form handlers
// const submitButtons = document.querySelectorAll(
//     ".submitButton:not(.createPatient):not(.createTask)"
// );

// if (submitButtons) {
//     submitButtons.forEach((button) => {
//         button.addEventListener("click", () => {
//             button.closest(".popUp").classList.toggle("close");
//             if (
//                 button
//                     .closest(".popUp")
//                     .parentElement.classList.contains("popUpRegion")
//             ) {
//                 button.closest(".popUpRegion").classList.toggle("close");
//             }
//             if (loginButtonDiv) {
//                 loginButtonDiv.classList.remove("close");
//             }
//         });
//     });
// }

// login and register buttons will open popup card
if (loginButton) {
    loginButton.addEventListener("click", () => {
        loginButtonDiv.classList.toggle("close");
        document.querySelector(".login").classList.toggle("close");
    });
}
if (registerButton) {
    registerButton.addEventListener("click", () => {
        loginButtonDiv.classList.toggle("close");
        document.querySelector(".register").classList.toggle("close");
    });
}

// New Patient Button will open New Patient Pop Up
const newPatientButtons = document.querySelectorAll(".addNewPatient");
const newPatientPopUp = document.querySelector(".newPatient");
const confirmPatientPopUp = document.querySelector(".confirmPatient");

if (newPatientButtons) {
    newPatientButtons.forEach((button) => {
        button.addEventListener("click", () => {
            showPatientModal();
        });
    });
}

function attachNewTaskListeners() {
    // newTaskButton will opent the new task popup
    const newTaskButtons = document.querySelectorAll(".addNewTask");

    if (newTaskButtons) {
        newTaskButtons.forEach((button) => {
            button.addEventListener("click", () => {
                console.log(
                    "New Task button clicked for patient ID:",
                    button.closest("[data-patient-id]")?.dataset.patientId
                );
                showTaskModal(
                    button.closest("[data-patient-id]")?.dataset.patientId ||
                        null
                );
            });
        });
    }
}
attachNewTaskListeners();

// Create button (sidebar) will open create menu
const createButton = document.querySelector(".createButton");
const createPatientButton = document.querySelector(".createPatientButton");
const createTaskButton = document.querySelector(".createTaskButton");
const createMenu = document.querySelector(".createMenu");
const createMenuPopover = document.getElementById("createMenuPopover");
const sideBar = document.getElementById("sidebar");

if (sideBar && createButton) {
    createButton.addEventListener("click", () => {
        if (sideBar.classList.contains("open")) {
            if (!createMenu.classList.contains("open")) {
                createMenu.style.height = "0px";
                createMenu.classList.toggle("open");
                void createMenu.offsetWidth;
                createMenu.style.height = createMenu.scrollHeight + "px";
            } else {
                createMenu.style.height = createMenu.scrollHeight + "px";
                void createMenu.offsetWidth;
                createMenu.style.height = "0px";
                createMenu.addEventListener(
                    "transitionend",
                    () => {
                        createMenu.classList.remove("open");
                    },
                    { once: true }
                );
            }
        } else {
            // Toggle popover visibility
            createMenuPopover.classList.toggle("open");
        }
    });
}

if (createPatientButton) {
    createPatientButton.addEventListener("click", () => {
        // Close the create menu
        createMenu.classList.remove("open");

        showPatientModal();
    });
}

if (createTaskButton) {
    createTaskButton.addEventListener("click", () => {
        // Close the create menu
        createMenu.classList.remove("open");

        showTaskModal();
    });
}

// Search functionality
document.addEventListener("DOMContentLoaded", () => {
    const input = document.getElementById("globalSearch");
    const results = document.getElementById("searchResults");

    if (!input || !results) return;

    let timeout = null;

    function renderCards(items) {
        if (!items.length) {
            results.innerHTML =
                '<div class="search-card"><h4>No results</h4></div>';
            return;
        }

        results.innerHTML = items
            .map((item) => {
                if (item.type === "action") {
                    return `<div class="search-card" data-action="${item.action}">
                  <h4>${item.label}</h4>
                </div>`;
                }
                if (item.type === "patient") {
                    return `<div class="search-card" data-patient="${item.id}">
                  <div>
                    <h4>${item.label}</h4>
                    <small>Go to Patient Profile</small>
                  </div>
                </div>`;
                }
                if (item.type === "page") {
                    return `<div class="search-card" data-url="${item.route}">
                  <h4>${item.label}</h4>
                </div>`;
                }
                return "";
            })
            .join("");
    }

    function performSearch(query = "") {
        fetch(`/search?q=${encodeURIComponent(query)}`)
            .then((r) => r.json())
            .then(renderCards)
            .then(() => results.classList.remove("close"));
    }

    input.addEventListener("focus", () => performSearch());
    input.addEventListener("input", () => {
        clearTimeout(timeout);
        timeout = setTimeout(() => performSearch(input.value.trim()), 300);
    });

    document.addEventListener("click", (e) => {
        const card = e.target.closest(".search-card");
        if (card) {
            const url = card.dataset.url;
            const patient = card.dataset.patient;
            const action = card.dataset.action;

            if (url) window.location.href = url;
            else if (action === "createPatient") showPatientModal();
            else if (action === "createTask") showTaskModal();
            else if (patient) {
                const currentPath = window.location.pathname;
                const patientPage = "/patients";
                if (currentPath === patientPage) {
                    // If already on patient page, just scroll there
                    const target = document.querySelector(
                        `[data-patient-id="${patient}"]`
                    );
                    if (target) {
                        target.scrollIntoView({
                            behavior: "smooth",
                            block: "center",
                        });
                        target.classList.add("highlight");
                        setTimeout(() => {
                            target.classList.remove("highlight");
                        }, 2000);
                    }
                } else {
                    // Navigate to the patients page with anchor hash
                    console.log(
                        "navigating to patient page: ",
                        patientPage,
                        patient
                    );
                    window.location.href = `${patientPage}#${patient}`;
                }
            }
        } else if (!e.target.closest(".searchArea")) {
            results.classList.add("close");
        }
    });
});

// Show task modal
async function showTaskModal(patientId = null) {
    try {
        // Fetch available tasks
        const tasksResponse = await fetch("/api/nurse/tasks", {
            headers: {
                Accept: "application/json",
            },
            credentials: "same-origin",
        });

        if (!tasksResponse.ok) {
            throw new Error("Failed to fetch tasks");
        }

        const tasksData = await tasksResponse.json();
        const tasks = tasksData.data || tasksData;

        // Populate task dropdown
        const taskSelect = document.getElementById("taskName");
        taskSelect.innerHTML =
            '<option value="" disabled selected>Select task...</option>';
        tasks.forEach((task) => {
            const option = document.createElement("option");
            option.value = task.id;
            option.textContent = task.name;
            option.dataset.category = task.category || "";
            taskSelect.appendChild(option);
        });

        // Fetch patients from the page (they're already loaded)
        const patientCards = document.querySelectorAll("[data-patient-id]");
        const assigneeSelect = document.getElementById("taskAssignee");
        assigneeSelect.innerHTML =
            '<option value="" disabled selected>No assignee</option>';

        patientCards.forEach((card) => {
            const patientIdFromCard = card.dataset.patientId;
            const patientName =
                card.querySelector(".patientName")?.textContent ||
                `Patient ${patientIdFromCard}`;
            const option = document.createElement("option");
            option.value = patientIdFromCard;
            option.textContent = patientName;
            assigneeSelect.appendChild(option);
        });

        // Pre-select patient if patientId was provided
        if (patientId) {
            assigneeSelect.value = patientId;
        }

        // Clear other fields
        document.getElementById("dueDate").value = "";
        document.getElementById("repeats").value = "none";
        document.getElementById("category").value = "";

        // Open the new task pop up
        newTaskPopUp.classList.remove("close");

        // Open the pop up region if it's closed
        if (newTaskPopUp.parentElement.classList.contains("close")) {
            newTaskPopUp.parentElement.classList.remove("close");
        }
    } catch (error) {
        console.error("Error loading task modal:", error);
        alert("Error loading task form: " + error.message);
    }
}

// Show patient modal
function showPatientModal() {
    // Randomize username parts
    initRandomUsername();

    // Open the new patient pop up
    newPatientPopUp.classList.remove("close");

    // Open the pop up region if it's closed
    if (newPatientPopUp.parentElement.classList.contains("close")) {
        newPatientPopUp.parentElement.classList.remove("close");
    }
}

// Update category when task is selected
document.addEventListener("DOMContentLoaded", () => {
    const taskSelect = document.getElementById("taskName");
    const categoryInput = document.getElementById("category");

    if (taskSelect && categoryInput) {
        taskSelect.addEventListener("change", (e) => {
            const selectedOption = e.target.options[e.target.selectedIndex];
            const category = selectedOption.dataset.category || "";
            categoryInput.value = category;
        });
    }
});

// Random Username functionality
function initRandomUsername() {
    const usernamePart1 = document.getElementById("usernamePart1");
    const usernamePart2 = document.getElementById("usernamePart2");

    if (!usernamePart1 || !usernamePart2) return;

    const randomIndex = (n) => Math.floor(Math.random() * n);

    usernamePart1.selectedIndex = randomIndex(usernamePart1.options.length);
    usernamePart2.selectedIndex = randomIndex(usernamePart2.options.length);
}

// Handle patient form submission
document.addEventListener("DOMContentLoaded", () => {
    const patientForm = document.querySelector(".newPatientForm");

    if (!patientForm) return;

    patientForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        // Get the submit button to disable it during submission
        const submitButton = patientForm.querySelector('button[type="submit"]');

        // Prevent double submission
        if (submitButton.disabled) {
            return;
        }

        // Get username from the two selected words
        const usernamePart1 = document.getElementById("usernamePart1")?.value;
        const usernamePart2 = document.getElementById("usernamePart2")?.value;

        if (!usernamePart1 || !usernamePart2) {
            alert("Please select both username parts");
            return;
        }

        const username = usernamePart1 + usernamePart2;
        const csrfToken = document.querySelector(
            'meta[name="csrf-token"]'
        )?.content;

        // Check if "assign default schedule" checkbox is checked
        const assignDefaultSchedule = document.getElementById(
            "assignDefaultSchedulePatient"
        )?.checked;

        // Disable submit button to prevent double submission
        submitButton.disabled = true;
        const originalText = submitButton.textContent;
        submitButton.textContent = "Creating...";

        try {
            const response = await fetch("/api/nurse/patients", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                credentials: "same-origin",
                body: JSON.stringify({
                    username: username,
                    avatar_id: 1,
                }),
            });

            const data = await response.json();

            if (!response.ok) {
                if (data.errors) {
                    alert(
                        "Validation Error:\n" +
                            Object.values(data.errors).flat().join("\n")
                    );
                } else {
                    alert(data.message || "Failed to create patient");
                }
                // Re-enable button on error
                submitButton.disabled = false;
                submitButton.textContent = originalText;
                return;
            }

            // Get pairing code and patient ID from response
            const pairingCode = data.pairing_code || data.data?.pairing_code;
            const patientId = data.id || data.data?.id;

            if (!pairingCode) {
                alert("Patient created but pairing code not found in response");
                // Re-enable button on error
                submitButton.disabled = false;
                submitButton.textContent = originalText;
                return;
            }

            // If default schedule checkbox is checked, assign default schedule
            if (assignDefaultSchedule && patientId) {
                try {
                    const scheduleResponse = await fetch(
                        `/api/nurse/patients/${patientId}/task-subscriptions/default-schedule`,
                        {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                Accept: "application/json",
                                "X-CSRF-TOKEN": csrfToken,
                            },
                            credentials: "same-origin",
                        }
                    );

                    if (!scheduleResponse.ok) {
                        const scheduleData = await scheduleResponse.json();
                        console.error("Failed to assign default schedule:", scheduleData);
                        // Don't block patient creation, just log the error
                    }
                } catch (scheduleError) {
                    console.error("Error assigning default schedule:", scheduleError);
                    // Don't block patient creation, just log the error
                }
            }

            // Update confirmation modal with pairing code
            const confirmCodeElement =
                document.getElementById("confirmPairingCode");
            if (confirmCodeElement) {
                confirmCodeElement.textContent = pairingCode;
            }

            // Show confirmation modal
            if (newPatientPopUp && confirmPatientPopUp) {
                newPatientPopUp.classList.add("close");
                confirmPatientPopUp.classList.remove("close");
                patientForm.reset();
                initRandomUsername();
                // Re-enable button after successful submission
                submitButton.disabled = false;
                submitButton.textContent = originalText;

                // Refresh page content
                refreshBaseContext(window.runPageRefresh);
            } else {
                // Fallback if modal elements not found
                alert(
                    "Patient created successfully! Pairing code: " + pairingCode
                );
                // Re-enable button
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            }
        } catch (error) {
            console.error("Error creating patient:", error);
            alert("Error: " + error.message);
            // Re-enable button on error
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        }
    });
});

// Handle default schedule checkbox toggling
document.addEventListener("DOMContentLoaded", () => {
    const defaultScheduleCheckbox = document.getElementById(
        "assignDefaultSchedule"
    );
    const taskNameSelect = document.getElementById("taskName");
    const dueDateInput = document.getElementById("dueDate");
    const repeatsSelect = document.getElementById("repeats");

    if (
        defaultScheduleCheckbox &&
        taskNameSelect &&
        dueDateInput &&
        repeatsSelect
    ) {
        defaultScheduleCheckbox.addEventListener("change", (e) => {
            const isChecked = e.target.checked;

            // Toggle required attributes based on checkbox state
            if (isChecked) {
                // Remove required when default schedule is selected
                taskNameSelect.removeAttribute("required");
                dueDateInput.removeAttribute("required");
                repeatsSelect.removeAttribute("required");

                // Optionally disable these fields for clarity
                taskNameSelect.disabled = true;
                dueDateInput.disabled = true;
                repeatsSelect.disabled = true;
            } else {
                // Re-add required when default schedule is not selected
                taskNameSelect.setAttribute("required", "required");
                dueDateInput.setAttribute("required", "required");

                // Re-enable fields
                taskNameSelect.disabled = false;
                dueDateInput.disabled = false;
                repeatsSelect.disabled = false;
            }
        });
    }
});

// Handle task form submission
document.addEventListener("DOMContentLoaded", () => {
    const taskForm = document.querySelector(".newTaskForm");
    const confirmTaskPopUp = document.querySelector(".confirmTask");

    if (!taskForm) {
        return;
    }

    taskForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        // Get the submit button to disable it during submission
        const submitButton = taskForm.querySelector('button[type="submit"]');

        // Prevent double submission
        if (submitButton.disabled) {
            return;
        }

        // Check if "assign default schedule" checkbox is checked
        const assignDefaultSchedule = document.getElementById(
            "assignDefaultSchedule"
        )?.checked;
        const patientId = document.getElementById("taskAssignee")?.value;

        if (assignDefaultSchedule) {
            // Handle default schedule assignment
            if (!patientId) {
                alert("Please select a patient");
                return;
            }

            // Disable submit button
            submitButton.disabled = true;
            const originalText = submitButton.textContent;
            submitButton.textContent = "Assigning...";

            const csrfToken = document.querySelector(
                'meta[name="csrf-token"]'
            )?.content;

            try {
                const response = await fetch(
                    `/api/nurse/patients/${patientId}/task-subscriptions/default-schedule`,
                    {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            Accept: "application/json",
                            "X-CSRF-TOKEN": csrfToken,
                        },
                        credentials: "same-origin",
                    }
                );

                const data = await response.json();

                if (!response.ok) {
                    if (data.errors) {
                        alert(
                            "Validation Error:\n" +
                                Object.values(data.errors).flat().join("\n")
                        );
                    } else if (data.message) {
                        alert(data.message);
                    } else {
                        alert("Failed to assign default schedule");
                    }
                    submitButton.disabled = false;
                    submitButton.textContent = originalText;
                    return;
                }

                // Show confirmation modal
                if (newTaskPopUp && confirmTaskPopUp) {
                    newTaskPopUp.classList.add("close");
                    confirmTaskPopUp.classList.remove("close");
                    taskForm.reset();
                    submitButton.disabled = false;
                    submitButton.textContent = originalText;

                    // Refresh page content
                    refreshBaseContext(window.runPageRefresh);
                } else {
                    alert("Default schedule assigned successfully!");
                    submitButton.disabled = false;
                    submitButton.textContent = originalText;
                }
            } catch (error) {
                console.error("Error assigning default schedule:", error);
                alert("Error: " + error.message);
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            }

            return; // Exit early, don't continue with single task creation
        }

        // Normal single task creation flow
        const taskId = document.getElementById("taskName")?.value;
        const dueDate = document.getElementById("dueDate")?.value;
        const repeats = document.getElementById("repeats")?.value;

        if (!taskId || !patientId || !dueDate) {
            alert("Please fill in all required fields");
            return;
        }

        // Convert repeats to interval_days
        let intervalDays;
        switch (repeats) {
            case "none":
                intervalDays = 999999; // Effectively non-repeating
                break;
            case "daily":
                intervalDays = 1;
                break;
            case "weekly":
                intervalDays = 7;
                break;
            case "monthly":
                intervalDays = 30;
                break;
            default:
                intervalDays = 1;
        }

        const csrfToken = document.querySelector(
            'meta[name="csrf-token"]'
        )?.content;

        // Disable submit button to prevent double submission
        submitButton.disabled = true;
        const originalText = submitButton.textContent;
        submitButton.textContent = "Creating...";

        // Extract time component from dueDate for scheduled_time
        const dueDateObj = new Date(dueDate);
        const hours = String(dueDateObj.getHours()).padStart(2, "0");
        const minutes = String(dueDateObj.getMinutes()).padStart(2, "0");
        const seconds = String(dueDateObj.getSeconds()).padStart(2, "0");
        const scheduledTime = `${hours}:${minutes}:${seconds}`;

        const requestPayload = {
            task_id: parseInt(taskId),
            patient_id: parseInt(patientId),
            start_at: dueDate,
            scheduled_time: scheduledTime,
            interval_days: intervalDays,
            timezone: "UTC",
            is_active: true,
        };

        try {
            const response = await fetch("/api/nurse/task-subscriptions", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                credentials: "same-origin",
                body: JSON.stringify(requestPayload),
            });

            const data = await response.json();

            if (!response.ok) {
                // Check for duplicate subscription error
                if (
                    (data.message &&
                        data.message.includes("duplicate key value")) ||
                    (data.message &&
                        data.message.includes(
                            "task_subscriptions_patient_task_time_active_unique"
                        ))
                ) {
                    alert(
                        "This task is already assigned to this patient at this time. Please choose a different time, task, or patient."
                    );
                } else if (data.errors) {
                    alert(
                        "Validation Error:\n" +
                            Object.values(data.errors).flat().join("\n")
                    );
                } else if (data.message) {
                    alert(data.message);
                } else {
                    alert("Failed to create task subscription");
                }
                // Re-enable button on error
                submitButton.disabled = false;
                submitButton.textContent = originalText;
                return;
            }

            // Show confirmation modal
            if (newTaskPopUp && confirmTaskPopUp) {
                newTaskPopUp.classList.add("close");
                confirmTaskPopUp.classList.remove("close");
                taskForm.reset();
                // Re-enable button after successful submission
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            } else {
                // Fallback if modal elements not found
                alert("Task assigned successfully!");
                // Re-enable button
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            }
        } catch (error) {
            console.error("Error creating task subscription:", error);
            alert("Error: " + error.message);
            // Re-enable button on error
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        }
    });
});

// Function to verify tasks - calls AJAX completeTask() function
async function verifyTask(btn, taskId) {
    console.log("Verifying task ID:", taskId);
    const row = btn.closest(".inboxRow");

    const result = await completeTask(taskId);

    console.log("task verify result: ", result);

    if (result) {
        // On success, animate out the row
        rowAnim(row);
    }
}

function rowAnim(row) {
    row.classList.add("inboxRowAnim");
    console.log("Anim row:", row);

    // Replace status container completedStatus
    setTimeout(() => {
        console.log("Replacing status container with completed status");
        const statusContainer = row.querySelector(".statusContainer");
        const verifyButtonContainer = row.querySelector(
            ".inboxVerifyContainer"
        );
        // there is a gap on inboxRowRight (inbox page), remove gap when clearing verify button
        const inboxRowRight = row.querySelector(".inboxRowRight");
        if (statusContainer) {
            statusContainer.innerHTML =
                "<div class='inboxStatus completedStatus'><img src='/assets/common/complete.svg' alt='' /><span class='statusText'>Completed</span></div>";
        }
        if (verifyButtonContainer) {
            verifyButtonContainer.innerHTML = "";
        }
        if (inboxRowRight) {
            inboxRowRight.style.gap = "0";
        }
    }, 600);

    // Reintroduce row
    setTimeout(() => {
        row.classList.remove("inboxRowAnim");
    }, 2000);
}

// AJAX function to mark task as complete
async function completeTask(taskId) {
    try {
        console.log("Completing task ID:", taskId);
        const response = await fetch(`/api/nurse/task-completions/${taskId}`, {
            method: "PATCH",
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
            },
            body: JSON.stringify({
                status: "completed",
                completed_at: new Date()
                    .toISOString()
                    .slice(0, 19)
                    .replace("T", " "),
            }),
        });

        if (!response.ok) {
            console.error("Failed to complete task:", response.statusText);
            return null;
        }

        return await response.json();
    } catch (error) {
        console.error("Error completing task:", error);
        return null;
    }
}
