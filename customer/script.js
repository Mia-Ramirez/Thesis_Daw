
const gearMenu = document.getElementById("nav_menu");

// Toggle dropdown visibility
gearMenu.addEventListener("click", () => {
    gearMenu.classList.toggle("clicked");
});

// Close dropdown when clicking outside
window.addEventListener("click", (event) => {
    if (!gearMenu.contains(event.target)) {
        gearMenu.classList.remove("clicked");
    }
});