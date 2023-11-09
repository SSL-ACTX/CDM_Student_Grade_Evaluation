<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header('Location: admin.php');
}

require_once('./db/config.php');

$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$student_query = "SELECT * FROM students";
$student_result = $conn->query($student_query);

$grade_query = "SELECT * FROM grades";
$grade_result = $conn->query($grade_query);

echo "<h2>Student List:</h2>";
echo "<table>";
echo "<tr><th>Student ID</th><th>Name</th><th>Course</th><th>Email</th></tr>";
while ($row = $student_result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>".$row['student_id']."</td>";
    echo "<td>".$row['name']."</td>";
    echo "<td>".$row['course']."</td>";
    echo "<td>".$row['email']."</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h2>1st Semester Grades:</h2>";
echo "<table>";
echo "<tr><th>Student ID</th><th>Subject</th><th>Grade</th></tr>";
while ($row = $grade_result->fetch_assoc()) {
    if ($row['semester'] == '1st Sem') {
        echo "<tr>";
        echo "<td>".$row['student_id']."</td>";
        echo "<td>".$row['subject']."</td>";
        echo "<td>".$row['grade']."</td>";
        echo "</tr>";
    }
}
echo "</table>";

$grade_result->data_seek(0);

echo "<h2>2nd Semester Grades:</h2>";
echo "<table>";
echo "<tr><th>Student ID</th><th>Subject</th><th>Grade</th></tr>";
while ($row = $grade_result->fetch_assoc()) {
    if ($row['semester'] == '2nd Sem') {
        echo "<tr>";
        echo "<td>".$row['student_id']."</td>";
        echo "<td>".$row['subject']."</td>";
        echo "<td>".$row['grade']."</td>";
        echo "</tr>";
    }
}
echo "</table>";

$conn->close();
?>

<form action="add_grade.php" method="post">
    <label for="student_id">Student ID:</label>
    <input type="text" id="student_id" name="student_id" required><br>
    <label for="subject">Subject:</label>
    <input type="text" id="subject" name="subject" required><br>
    <label for="semester">Semester:</label>
    <select id="semester" name="semester" required>
        <option value="1st Sem">1st Semester</option>
        <option value="2nd Sem">2nd Semester</option>
    </select><br>
    <label for="grade">Grade:</label>
    <input type="text" id="grade" name="grade" required><br>
    <input type="submit" value="Assign Grade">
</form>

<a href="logout.php">Logout</a>
