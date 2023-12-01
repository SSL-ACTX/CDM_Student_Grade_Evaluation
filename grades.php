<?php
session_start();

if (!isset($_SESSION['admin_user'])) {
    header("Location: admin_login.php");
    exit();
}

error_reporting(E_ERROR | E_WARNING);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/admin_grade.css">
    <title>Grades</title>
</head>

<body>
    <div class="container">
        <?php
        function connectToDatabase() {
            require_once('./db/config.php');
            $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            return $conn;
        }

        function searchGrades($conn, $searchedStudentId) {
            $searchedStudentId = $conn->real_escape_string($searchedStudentId);
            $grade_query = "SELECT * FROM grades WHERE student_id = '$searchedStudentId' ORDER BY semester";
            return $conn->query($grade_query);
        }

        function fetchAllGrades($conn) {
            $grade_query = "SELECT * FROM grades ORDER BY student_id, semester";
            return $conn->query($grade_query);
        }

        session_start();

        $conn = connectToDatabase();

        $grade_result = isset($_POST['search']) ? searchGrades($conn, $_POST['search']) : fetchAllGrades($conn);

        $currentStudentId = null;

        echo "<h2>Grades:</h2>";

        echo "<form method='post' id='searchForm'>";
        echo "<label for='search'>Search by Student ID:</label>";
        echo "<input type='text' id='search' name='search' placeholder='Enter Student ID'>";
        echo "<input type='button' value='Search' onclick='performSearch()'>";
        echo "</form>";        

        echo "<table>";
        echo "<tr><th>Student ID</th><th>Subject</th><th>Semester</th><th>Grade</th></tr>";

        while ($row = $grade_result->fetch_assoc()) {
            if ($row['student_id'] !== $currentStudentId) {
                if ($currentStudentId !== null) {
                    echo "</tr>";
                }

                echo "<tr>";
                echo "<td>" . $row['student_id'] . "</td>";
                echo "<td>" . $row['subject'] . "</td>";
                echo "<td>" . $row['semester'] . "</td>";
                echo "<td>" . $row['grade'] . "</td>";
                $currentStudentId = $row['student_id'];
            } else {
                echo "<tr>";
                echo "<td></td>"; 
                echo "<td>" . $row['subject'] . "</td>";
                echo "<td>" . $row['semester'] . "</td>";
                echo "<td>" . $row['grade'] . "</td>";
            }
        }

        echo "</tr>";
        echo "</table>";

        $conn->close();
        ?>
    </div>
</body>

</html>