<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header('Location: delete_grade.php');
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $grade_id = $_GET['id'];

    require_once('./db/config.php');

    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $delete_query = "DELETE FROM grades WHERE id='$grade_id'";
    if ($conn->query($delete_query) === TRUE) {
        echo "Grade deleted successfully.";
    } else {
        echo "Error deleting grade: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Invalid request.";
}
?>
