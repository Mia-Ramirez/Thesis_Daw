document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("prescriptionModal");
    const checkOutButton = document.querySelector(".btn"); // Attach to the "Check Out" button
    const closeButton = document.querySelector(".close-btn");

    // Show the modal when "Check Out" is clicked
    checkOutButton.addEventListener("click", function (event) {
        event.preventDefault(); // Prevent form submission or navigation
        modal.style.display = "block";
    });

    // Close the modal when the close button is clicked
    closeButton.addEventListener("click", function () {
        modal.style.display = "none";
    });

    // Close the modal when clicking outside of the modal content
    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });
});
