<?php
session_start();
include '../config.php'; // Include the database connection

// Check if the user is logged in and is a teacher
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    header("Location: login.php"); // Redirect to login page if not a teacher
    exit();
}

// Check if a course ID is provided
if (!isset($_GET['course_id']) || empty($_GET['course_id'])) {
    echo "No course selected.";
    exit();
}

$course_id = $_GET['course_id']; // Get the course ID from the URL

// Query to get students enrolled in the course
$sql_students = "SELECT s.id, s.username, s.email 
                 FROM students s
                 JOIN enrollments e ON s.id = e.student_id
                 WHERE e.course_id = ?";
$stmt_students = $conn->prepare($sql_students);
$stmt_students->bind_param("i", $course_id);
$stmt_students->execute();
$result_students = $stmt_students->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students</title>
</head>
<body>

    <h2>Students Enrolled in Course</h2>
    <p><strong>Course ID:</strong> <?php echo htmlspecialchars($course_id); ?></p>

    <?php if ($result_students->num_rows > 0) { ?>
        <table border="1">
            <tr>
                <th>Student ID</th>
                <th>Username</th>
                <th>Email</th>
            </tr>
            <?php while ($student = $result_students->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($student['id']); ?></td>
                    <td><?php echo htmlspecialchars($student['username']); ?></td>
                    <td><?php echo htmlspecialchars($student['email']); ?></td>
                </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p>No students are enrolled in this course.</p>
    <?php } ?>

    <a href="teacher_dashboard.php">Back to Dashboard</a>

</body>
</html>
