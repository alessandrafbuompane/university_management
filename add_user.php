<?php
// Include the database configuration file
include 'config.php';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from the form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the username already exists in the database
    $check_query = "SELECT * FROM users WHERE username = ?";
    $stmt_check = $conn->prepare($check_query);
    $stmt_check->bind_param("s", $username);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        // If the username already exists, show an error message
        echo "Username already taken, please choose another!";
    } else {
        // Query to insert a new user
        $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $hashed_password, $role);

        if ($stmt->execute()) {
            // User successfully added
            echo "User added successfully! Redirecting to login page...";
            // Redirect to the login page after 3 seconds
            header("Refresh: 3; url=login.php");
            exit();
        } else {
            // Error adding the user
            echo "Error adding the user!";
        }
    }
}
?>

<!-- Form to add a new user -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
</head>
<body>
    <h2>Add New User</h2>
    <form action="add_user.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <label for="role">Role:</label>
        <select id="role" name="role">
            <option value="student">Student</option>
            <option value="teacher">Teacher</option>
            <option value="admin">Administrator</option>
        </select><br><br>
        
        <input type="submit" value="Add User">
    </form>
</body>
</html>
