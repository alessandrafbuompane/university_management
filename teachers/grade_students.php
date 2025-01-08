<?php
session_start();
include '../config.php'; // Include database configuration

// Check if the user is logged in and is a teacher
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if a course ID is provided
if (!isset($_GET['course_id'])) {
    echo "No course selected.";
    exit();
}

$course_id = $_GET['course_id'];

// Get the list of students in the course
$sql_students = "SELECT s.id, s.username 
                 FROM students s
                 JOIN enrollments e ON s.id = e.student_id
                 WHERE e.course_id = ?";
$stmt_students = $conn->prepare($sql_students);
$stmt_students->bind_param("i", $course_id);
$stmt_students->execute();
$result_students = $stmt_students->get_result();

// Handle grade submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $grade = $_POST['grade'];
    $comments = $_POST['comments'];

    // Insert or update the grade for the student
    $sql_grade = "INSERT INTO grades (student_id, course_id, grade, comments)
                  VALUES (?, ?, ?, ?)
                  ON DUPLICATE KEY UPDATE grade = VALUES(grade), comments = VALUES(comments)";
    $stmt_grade = $conn->prepare($sql_grade);
    $stmt_grade->bind_param("iiss", $student_id, $course_id, $grade, $comments);
    if ($stmt_grade->execute()) {
        echo "<p>Grade updated successfully for student ID: $student_id.</p>";
    } else {
        echo "<p>Failed to update grade for student ID: $student_id.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade Students</title>
</head>
<body>

    <h2>Grade Students for Course ID: <?php echo htmlspecialchars($course_id); ?></h2>

    <?php if ($result_students->num_rows > 0) { ?>
        <table border="1">
            <tr>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Grade</th>
                <th>Comments</th>
                <th>Action</th>
            </tr>
            <?php while ($student = $result_students->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $student['id']; ?></td>
                    <td><?php echo htmlspecialchars($student['username']); ?></td>
                    <td>
                        <form action="grade_students.php?course_id=<?php echo $course_id; ?>" method="POST">
                            <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                            <input type="text" name="grade" placeholder="Enter grade" required>
                    </td>
                    <td>
                            <textarea name="comments" placeholder="Comments"></textarea>
                    </td>
                    <td>
                            <input type="submit" value="Submit Grade">
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p>No students are enrolled in this course.</p>
    <?php } ?>

    <a href="teacher_dashboard.php">Back to Dashboard</a>

</body>
</html>
