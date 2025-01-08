<?php
session_start();
include '../config.php';  // Include the database connection file

// Check if the user is logged in and is a teacher
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];  // Get the user ID from the session

// Retrieve teacher profile
$sql_profile = "SELECT * FROM users WHERE id = ?";
$stmt_profile = $conn->prepare($sql_profile);
$stmt_profile->bind_param("i", $user_id);
$stmt_profile->execute();
$result_profile = $stmt_profile->get_result();
$profile = $result_profile->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Profile</title>
</head>
<body>

    <h2>Your Profile</h2>
    <p>Username: <?php echo htmlspecialchars($profile['username']); ?></p>
    <p>Email: <?php echo htmlspecialchars($profile['email']); ?></p>

    <h3>Update Profile</h3>
    <form action="update_profile.php" method="POST">
        <label for="email">New Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($profile['email']); ?>" required><br><br>
        <label for="password">New Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Update Profile">
    </form>

    <a href="teacher_dashboard.php">Back to Dashboard</a>

</body>
</html>
