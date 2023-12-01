<?php
session_start();
$student_id = $_POST['student_id'];
$email = $_POST['email'];

require_once('./db/config.php');

$student_id = mysqli_real_escape_string($conn, $student_id);
$email = mysqli_real_escape_string($conn, $email);

$query = "SELECT * FROM students WHERE student_id='$student_id' AND email='$email'";
$result = $conn->query($query);

if (!$result) {
    echo "Database Error: " . $conn->error;
} else {
    $_SESSION['student_id'] = $student_id;
    header('Location: dashboard.php');
}

$conn->close();
?>
