<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header('Location: add_grade.php');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $subject = $_POST['subject'];
    $semester = $_POST['semester'];
    $grade = $_POST['grade'];

    require_once('./db/config.php');

    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $student_id = mysqli_real_escape_string($conn, $student_id);
    $subject = mysqli_real_escape_string($conn, $subject);
    $semester = mysqli_real_escape_string($conn, $semester);
    $grade = mysqli_real_escape_string($conn, $grade);

    $insert_query = "INSERT INTO grades (student_id, subject, semester, grade) VALUES ('$student_id', '$subject', '$semester', '$grade')";

    if ($conn->query($insert_query) === TRUE) {
        echo "Grade assigned successfully.";
        header('Location: admin.php');
    } else {
        echo "Error assigning grade: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Invalid form submission.";
}
?>
