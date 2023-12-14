<?php
session_start();

if(!isset($_SESSION['student_id'])) {
    header('Location: dashboard.php');
}

require_once('./db/config.php');

$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

if($conn->connect_error) {
    die("Connection failed: ".$conn->connect_error);
}

$student_id = $_SESSION['student_id'];

$student_query = "SELECT * FROM students WHERE student_id='$student_id'";
$student_result = $conn->query($student_query);

if($student_result->num_rows > 0) {
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
    <link href="https://fonts.cdnfonts.com/css/google-sans" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

    <div id="main">
        <div class="container">

            <div class="profile-container">
                <div class="profile-header">
                    <?php
                    # student pic
                    if(!empty($student_picture) && file_exists($student_picture)) {
                        echo "<img src='$student_picture' alt='Student Picture' class='profile-image' id='student-image'>";
                    }
                    ?>

                    <div class="profile-info">
                        <h2 id='welcome-heading'>
                            <?php echo $student_name; ?>
                        </h2>
                        <p class='stinfo'>Student ID:
                            <?php echo $student_id; ?>
                        </p>
                        <p class='stinfo'>Course:
                            <?php echo $student_course; ?>
                        </p>
                    </div>
                </div>

                <a href="logout.php" class="logout-link">Logout</a>
            </div>

            <div class="grades-container">
                <?php
                $current_semester = null;
                $gradesArray = array(); // New array to store grades for both sections
                
                while($row = $grades_result->fetch_assoc()) {
                    $gradesArray[] = $row; // Store the grades data in the array
                
                    $semester = $row['semester'];
                    $subject = $row['subject'];
                    $grade = $row['grade'];

                    // Check if semester has changed
                    if($semester != $current_semester) {
                        if($current_semester !== null) {
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
                if($current_semester !== null) {
                    echo "</table></div>";
                }
                ?>
                <div>
                    <button id="toggleGradeBreakdown">&#9776;</button>
                </div>
            </div>

            <div class="grade-breakdown" id="gradeBreakdown">
                <h4>Grade Breakdown</h4>
                <?php
                $semester_grades = array();

                foreach($gradesArray as $row) {
                    $semester = $row['semester'];
                    $grade = $row['grade'];

                    if(!isset($semester_grades[$semester])) {
                        $semester_grades[$semester] = array();
                    }

                    $semester_grades[$semester][] = $grade;
                }

                $semester_averages = array();

                foreach($semester_grades as $semester => $grades) {
                    $average = count($grades) > 0 ? array_sum($grades) / count($grades) : 0;
                    $semester_averages[$semester] = $average;
                }

                $totalSemesters = count($semester_averages);
                $totalGPA = array_sum($semester_averages) / $totalSemesters;
                ?>

                <canvas id="gradesChart" width="300" height="150"></canvas>
                <hr>

                <div class="gpa-container">
                    <h5>Total Semesters:
                        <?php echo $totalSemesters; ?>
                    </h5>
                    <h5>GPA:
                        <?php echo number_format($totalGPA, 2); ?>
                    </h5>
                </div>

                <script>
                    var gradeBreakdown = document.getElementById('gradeBreakdown');
                    var toggleButton = document.getElementById('toggleGradeBreakdown');

                    toggleButton.addEventListener('click', function () {
                        if (gradeBreakdown.style.display === 'none') {
                            gradeBreakdown.style.display = 'block';
                        } else {
                            gradeBreakdown.style.display = 'none';
                        }
                    });
                </script>
            </div>

            <script>
                var semesterAverages = <?php echo json_encode($semester_averages); ?>;
                var ctx = document.getElementById('gradesChart').getContext('2d');

                var chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: Object.keys(semesterAverages),
                        datasets: [{
                            label: 'Average Grade',
                            data: Object.values(semesterAverages),
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>
        </div>
</body>

</html>