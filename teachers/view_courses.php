<?php
session_start();
include '../config.php';  // Include the database connection file

// Check if the user is logged in and is a teacher
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    header("Location: login.php");  // Redirect to login if not a teacher
    exit();
}

$user_id = $_SESSION['user_id'];  // Get the user ID from the session

// Check if a course ID is provided
if (!isset($_GET['course_id']) || empty($_GET['course_id'])) {
    echo "No course selected.";
    exit();
}

$course_id = $_GET['course_id'];  // Get the course ID from the URL

// Query to get students enrolled in the course
$sql_students = "SELECT s.username, s.id 
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
    <title>Students Enrolled</title>
</head>
<body>

    <h2>Students Enrolled in Course</h2>
    
    <?php
    // Get course name or other details
    $sql_course = "SELECT name FROM courses WHERE id = ?";
    $stmt_course = $conn->prepare($sql_course);
    $stmt_course->bind_param("i", $course_id);
    $stmt_course->execute();
    $result_course = $stmt_course->get_result();
    
    if ($result_course->num_rows > 0) {
        $course = $result_course->fetch_assoc();
        echo "<p>Course: " . htmlspecialchars($course['name']) . "</p>";
    } else {
        echo "<p>Course not found.</p>";
    }
    ?>

    <?php if ($result_students->num_rows > 0) { ?>
        <h3>List of Students:</h3>
        <ul>
            <?php while ($student = $result_students->fetch_assoc()) { ?>
                <li><?php echo htmlspecialchars($student['username']); ?></li>
            <?php } ?>
        </ul>
    <?php } else { ?>
        <p>No students are enrolled in this course.</p>
    <?php } ?>

    <a href="teacher_dashboard.php">Back to Dashboard</a>

</body>
</html>
