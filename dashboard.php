<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header('Location: dashboard.php');
}

require_once('./db/config.php');

$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$student_id = $_SESSION['student_id'];

$student_query = "SELECT * FROM students WHERE student_id='$student_id'";
$student_result = $conn->query($student_query);

if ($student_result->num_rows > 0) {
    $student_data = $student_result->fetch_assoc();
    $student_name = $student_data['name'];
    $student_course = $student_data['course'];
} else {
    die("Student not found.");
}

$grades_query = "SELECT * FROM grades WHERE student_id='$student_id'";
$grades_result = $conn->query($grades_query);

echo "<h2>Welcome, $student_name!</h2>";
echo "<p>Student ID: $student_id</p>";
echo "<p>Course: $student_course</p>";

echo "<h2>1st Sem</h2>";
echo "<table>";
echo "<tr><th>Subject</th><th>Grade</th></tr>";
while ($row = $grades_result->fetch_assoc()) {
    if ($row['semester'] == '1st Sem') {
        echo "<tr>";
        echo "<td>".$row['subject']."</td>";
        echo "<td>".$row['grade']."</td>";
        echo "</tr>";
    }
}
echo "</table>";

$grades_result->data_seek(0);

echo "<h2>2nd Sem:</h2>";
echo "<table>";
echo "<tr><th>Subject</th><th>Grade</th></tr>";
while ($row = $grades_result->fetch_assoc()) {
    if ($row['semester'] == '2nd Sem') {
        echo "<tr>";
        echo "<td>".$row['subject']."</td>";
        echo "<td>".$row['grade']."</td>";
        echo "</tr>";
    }
}
echo "</table>";

$conn->close();
?>

<a href="logout.php">Logout</a>
<a href="feedback.php">Feedbacks</a>
