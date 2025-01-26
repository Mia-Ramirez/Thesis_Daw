// Set the timeout period in milliseconds
var inactivityTimeout = 60000; // 1 hour -> To change this 100 * n seconds
inactivityTimeout = 15000;
var refreshTimer;
var countdownTimer;
var timeLeft = inactivityTimeout / 1000;  // Convert to seconds for display

// Function to refresh the page
function refreshPage() {
    location.reload();
}

// Reset the inactivity timer and update countdown
function resetInactivityTimer() {
    clearTimeout(refreshTimer);  // Clear previous timer
    clearInterval(countdownTimer);  // Clear the countdown
    timeLeft = inactivityTimeout / 1000;  // Reset the countdown to 10 seconds
    updateCountdownDisplay();  // Update the display immediately
    refreshTimer = setTimeout(refreshPage, inactivityTimeout);  // Set a new refresh timer

    // Start a new countdown timer to update every second
    countdownTimer = setInterval(function() {
        timeLeft--;  // Decrease the time left by 1 second
        updateCountdownDisplay();  // Update the countdown display
        if (timeLeft <= 0) {
            clearInterval(countdownTimer);  // Stop the countdown when time is up
        }
    }, 1000);
}

// Update the countdown display
function updateCountdownDisplay() {
    document.getElementById('countdownDisplay').textContent = "Time to refresh: " + timeLeft + " seconds";
}

// Set up event listeners to track user activity
// window.onload = function() {
//     // Reset the timer on mouse movement or keypress
//     document.body.onmousemove = resetInactivityTimer;
//     document.body.onkeydown = resetInactivityTimer;

//     resetInactivityTimer(); // Start the inactivity timer initially

// };