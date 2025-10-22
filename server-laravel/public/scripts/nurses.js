const sidebar = document.getElementById("sidebar");
const toggleBtn = document.getElementById("hamburger");
const centralHeader = document.querySelector(".centralHeader");
const loginButton = document.querySelector(".loginButton");
const registerButton = document.querySelector(".registerButton");
const loginButtonDiv = document.querySelector(".loginButtons");

if (toggleBtn) {
    toggleBtn.addEventListener("click", () => {
        sidebar.classList.toggle("open");
    });
}

// Large Cards: close button functionality
const closeButtons = document.querySelectorAll(".closeButton");

if (closeButtons) {
    closeButtons.forEach((button) => {
        button.addEventListener("click", () => {
            console.log("close button clicked");
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

// Temp: submit button will just close the large card
const submitButtons = document.querySelectorAll(".submitButton");

if (submitButtons) {
    submitButtons.forEach((button) => {
        button.addEventListener("click", () => {
            console.log("submit button clicked");
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

// newTaskButton will opent the new task popup
const newTaskButtons = document.querySelectorAll(".addNewTask");
const newTaskPopUp = document.querySelector(".newTask");

if (newTaskButtons) {
    newTaskButtons.forEach((button) => {
        button.addEventListener("click", () => {
            showTaskModal();
        });
    });
}

// Create button (sidebar) will open create menu
const createButton = document.querySelector(".createButton");
const createPatientButton = document.querySelector(".createPatientButton");
const createTaskButton = document.querySelector(".createTaskButton");
const createMenu = document.querySelector(".createMenu");

if (createButton) {
    createButton.addEventListener("click", () => {
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
                    <small>${item.subtext}</small>
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
            else if (patient) console.log("View patient:", patient);
        } else if (!e.target.closest(".searchArea")) {
            results.classList.add("close");
        }
    });
});

// Show task modal
function showTaskModal() {
    // Open the new task pop up
    newTaskPopUp.classList.remove("close");

    // Open the pop up region if it's closed
    if (newTaskPopUp.parentElement.classList.contains("close")) {
        newTaskPopUp.parentElement.classList.remove("close");
    }
}

// Show patient modal
function showPatientModal() {
    // Open the new patient pop up
    newPatientPopUp.classList.remove("close");

    // Open the pop up region if it's closed
    if (newPatientPopUp.parentElement.classList.contains("close")) {
        newPatientPopUp.parentElement.classList.remove("close");
    }
}
