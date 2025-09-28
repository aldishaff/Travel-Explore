function exploreMore() {
    alert("Explore more destinations coming soon!");
}

function openSignIn() {
    document.getElementById("signInModal").style.display = "flex";
}

function openSignUp() {
    document.getElementById("signUpModal").style.display = "flex";
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = "none";
}
function toggleMenu() {
    const menu = document.querySelector('.menu');
    menu.classList.toggle('active');
}

