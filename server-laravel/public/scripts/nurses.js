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

closeButtons.forEach((button) => {
  button.addEventListener("click", () => {
    console.log("close button clicked");
    centralHeader.classList.toggle("close");
    button.closest(".largeCard").classList.toggle("close");
    loginButtonDiv.classList.remove("close");
  });
});

// Temp: submit button will just close the large card
const submitButtons = document.querySelectorAll(".submitButton");

if (submitButtons) {
  submitButtons.forEach((button) => {
  button.addEventListener("click", () => {
    console.log("submit button clicked");
    centralHeader.classList.toggle("close");
    button.closest(".largeCard").classList.toggle("close");
    loginButtonDiv.classList.remove("close");
  });
});
}

// login and register buttons will close header and open large card
if (loginButton) {
  loginButton.addEventListener("click", () => {
  console.log("login button clicked");
  loginButtonDiv.classList.toggle("close");
  document.querySelector(".login").classList.toggle("close");
});

}
if (registerButton) {
  registerButton.addEventListener("click", () => {
    console.log("register button clicked");
    loginButtonDiv.classList.toggle("close");
    document.querySelector(".register").classList.toggle("close");
  });
}
