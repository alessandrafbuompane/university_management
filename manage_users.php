<?php
session_start();
include 'config.php';

// Allow only admin
if ($_SESSION['role'] != 'admin') {
    echo "Access denied!";
    exit();
}

$sql = "SELECT * FROM users";
$result = $conn->query($sql);

echo "<h1>Manage Users</h1>";
if ($result->num_rows > 0) {
    echo "<ul>";
    while ($user = $result->fetch_assoc()) {
        echo "<li>" . htmlspecialchars($user['username']) . " - " . htmlspecialchars($user['role']) . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No users found.</p>";
}

echo "<a href='dashboard.php'>Back to Dashboard</a>";
?>
