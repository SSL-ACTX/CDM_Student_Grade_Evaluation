<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = isset($_POST['student_id']) ? $_POST['student_id'] : '';
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $course = isset($_POST['course']) ? $_POST['course'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';

    $student_id = filter_var($student_id, FILTER_SANITIZE_STRING);
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $course = filter_var($course, FILTER_SANITIZE_STRING);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    require_once('./db/config.php');

    $check_query = "SELECT * FROM students WHERE student_id='$student_id'";
    $check_result = $conn->query($check_query);

    if ($check_result) {
        if ($check_result->num_rows > 0) {
            echo "Student ID already exists. Please use a different one.";
        } else {
            $insert_query = "INSERT INTO students (student_id, name, course, email) VALUES ('$student_id', '$name', '$course', '$email')";

            if ($conn->query($insert_query) === TRUE) {
                echo "Registration successful. You can now log in.";
            } else {
                echo "Registration failed. Please try again.";
            }
        }
    } else {
        echo "Database Error: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Invalid form submission.";
}
?>

<a href="index.php">Already registered? Login</a>
