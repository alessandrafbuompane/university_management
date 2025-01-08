<?php
// Start the session
session_start();

// Check if the user is logged in and is an admin
if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
    // Destroy the session and clear all session variables
    session_unset();  // Removes all session variables
    session_destroy();  // Destroys the session

    // Redirect to the login page
    header("Location: http://localhost/university_management/login.php");  // Redirect to the login page
    exit();
}
?>
