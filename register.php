<?php
error_reporting(E_ERROR | E_WARNING);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fields = ['student_id', 'name', 'course', 'email'];
    $data = [];

    foreach ($fields as $field) {
        $data[$field] = filter_input(INPUT_POST, $field, FILTER_SANITIZE_STRING);
        if (empty($data[$field])) {
            die("Invalid input. Please fill in all required fields.");
        }
    }

    $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email address.");
    }

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["student_picture"]["name"]);

    if (!empty($_FILES["student_picture"]["name"])) {
        if (!move_uploaded_file($_FILES["student_picture"]["tmp_name"], $target_file)) {
            die("Sorry, there's an error uploading your file. :D");
        }
        $studentPictureValue = "'$target_file'";
    } else {
        $studentPictureValue = "NULL";
    }

    require_once('./db/config.php');

    $check_query = "SELECT * FROM students WHERE student_id='{$data['student_id']}'";
    $check_result = $conn->query($check_query);

    if ($check_result && $check_result->num_rows > 0) {
        die("Student ID already exists. Use a different one.");
    }

    $insert_query = "INSERT INTO students (student_id, name, course, email, student_picture) VALUES ('{$data['student_id']}', '{$data['name']}', '{$data['course']}', '$email', $studentPictureValue)";

    if ($conn->query($insert_query)) {
        header('Location: index.html');
    } else {
        header('Location: register.html');
    }

    $conn->close();
} else {
    die("Invalid.");
}
?>
