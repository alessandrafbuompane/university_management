<?php
session_start();
include 'config.php'; // Include the database connection file

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the data from the login form
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        // Query to fetch the user by username
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on the user's role
                switch ($user['role']) {
                    case 'admin':
                        header("Location: admin/admin_dashboard.php");
                        break;
                    case 'student':
                        header("Location: students/student_dashboard.php");
                        break;
                    case 'teacher':
                        header("Location: teachers/teacher_dashboard.php");
                        break;
                    default:
                        echo "<p>Role not recognized.</p>";
                }
                exit();
            } else {
                $error_message = "Invalid credentials. Please try again.";
            }
        } else {
            $error_message = "User not found. Please try again.";
        }
    } else {
        $error_message = "All fields are required.";
    }
}
?>

<!-- Login Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>

    <?php if (isset($error_message)) { ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php } ?>

    <form action="login.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <input type="submit" value="Login">
    </form>
</body>
</html>
