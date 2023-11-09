<?php
// databse connection :) Use xampp and create your database
$db_host = 'localhost'; // <--leave this as it is
$db_user = 'root'; 
$db_password = '';
$db_name = 'student_grade_evaluation'; // <- The database name, feel free to change it

$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
