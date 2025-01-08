<?php
session_start();

// Check if the user is authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include the database configuration
include 'config.php';

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_username = $_POST['username'];
    $user_id = $_SESSION['user_id'];

    if (!empty($new_username)) {
        // Update the username in the database
        $sql = "UPDATE users SET username = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_username, $user_id);
        if ($stmt->execute()) {
            $_SESSION['username'] = $new_username; // Update session value
            echo "Profile updated successfully!";
        } else {
            echo "Error updating profile.";
        }
    } else {
        echo "Please fill out all fields.";
    }
}

// Retrieve the current user data
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="styles.css"> <!-- Optional CSS -->
</head>
<body>
    <h1>Edit Your Profile</h1>
    <form action="edit_profile.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required><br><br>
        
        <button type="submit">Save Changes</button>
    </form>
    <br>
    <a href="profile.php">Back to Profile</a>
</body>
</html>
