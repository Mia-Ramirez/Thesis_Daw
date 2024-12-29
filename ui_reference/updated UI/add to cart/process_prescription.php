<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $age = intval($_POST['age']);
    $medication = htmlspecialchars($_POST['medication']);

    // Save prescription to database or file
    // Example: Save to a text file
    $file = fopen('prescriptions.txt', 'a');
    fwrite($file, "Patient Name: $name\nAge: $age\nPrescription: $medication\n\n");
    fclose($file);

    echo "Prescription submitted successfully!";
} else {
    echo "Invalid request.";
}
?>