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
    $student_picture = $student_data['student_picture'];
} else {
    die("Student not found.");
}

$grades_query = "SELECT * FROM grades WHERE student_id='$student_id'";
$grades_result = $conn->query($grades_query);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/user_dashboard.css">
</head>

<body>

    <div class="container">

        <div class="profile-container">
            <div class="profile-header">
                <?php
                # student pic
                if (!empty($student_picture) && file_exists($student_picture)) {
                    echo "<img src='$student_picture' alt='Student Picture' class='profile-image' id='student-image'>";
                }
                ?>

                <div class="profile-info">
                    <h2 id='welcome-heading'><?php echo $student_name; ?></h2>
                    <p class='stinfo'>Student ID: <?php echo $student_id; ?></p>
                    <p class='stinfo'>Course: <?php echo $student_course; ?></p>
                </div>
            </div>

            <a href="logout.php" class="logout-link">Logout</a>
            <a href="feedback.php" class="logout-link">Feedbacks</a>
        </div>

        <div class="grades-container">
            <?php
            $current_semester = null;

            while ($row = $grades_result->fetch_assoc()) {
                $semester = $row['semester'];
                $subject = $row['subject'];
                $grade = $row['grade'];

                // Check if semester has changed
                if ($semester != $current_semester) {
                    if ($current_semester !== null) {
                        echo "</table></div>";
                    }
                    echo "<div class='semester-container'>";
                    echo "<h2 class='semester-heading'>$semester</h2>";
                    echo "<table class='grades-table'>";
                    echo "<tr><th class='table-header'>Subject</th><th class='table-header'>Grade</th></tr>";
                    $current_semester = $semester;
                }
                echo "<tr>";
                echo "<td class='subject-cell'>$subject</td>";
                echo "<td class='grade-cell'>$grade</td>";
                echo "</tr>";
            }
            if ($current_semester !== null) {
                echo "</table></div>";
            }
            ?>
        </div>

    </div>

</body>

</html>
