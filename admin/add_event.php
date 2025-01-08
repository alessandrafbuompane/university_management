<?php
session_start();
include '../config.php';

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_description = $_POST['event_description'];

    // Insert the new event into the database
    $sql = "INSERT INTO events (event_name, event_date, event_description) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $event_name, $event_date, $event_description);

    if ($stmt->execute()) {
        echo "Event added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event</title>
</head>
<body>

    <h2>Add New Event</h2>
    <form action="add_event.php" method="POST">
        <label for="event_name">Event Name:</label>
        <input type="text" name="event_name" id="event_name" required><br><br>

        <label for="event_date">Event Date:</label>
        <input type="date" name="event_date" id="event_date" required><br><br>

        <label for="event_description">Event Description:</label>
        <textarea name="event_description" id="event_description" required></textarea><br><br>

        <input type="submit" value="Add Event">
    </form>

    <a href="admin_dashboard.php">Back to Dashboard</a>

</body>
</html>
