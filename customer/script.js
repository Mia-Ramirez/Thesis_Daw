
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

function showYesNoModal(data) {
    if (data){
        let modal_data = data.split("-");
        var modal_input = document.getElementById("modal_value");
        var modal_button = document.getElementById("button_yes");

        modal_button.value = modal_data[0];
        
        modal_input.value = modal_data[1];
        modal_input.name = modal_data[0];
    };

    var modal = document.getElementById("yesNoModal");
    modal.style.display = "block";
};

// Hide the modal
function closeModal(modalID) {
    var modal = document.getElementById(modalID);
    modal.style.display = "none";
};

// Action when 'No' is clicked
function onNo(modalID) {
    closeModal(modalID);
};
