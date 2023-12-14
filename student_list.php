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
    <link rel="stylesheet" href="./css/student_list.css">
    <title>Student List</title>
    <script>
        function perfSearch() {
            var searchValue = document.getElementById('search').value;
            document.getElementById('searchForm').submit();
        }
    </script>
</head>
<body>
    <div class="container">
        <?php
        session_start();

        require_once('./db/config.php');

        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        function searchGrades($conn, $searchedStudentId) {
            $searchedStudentId = $conn->real_escape_string($searchedStudentId);
            $student_query = "SELECT * FROM students WHERE student_id = '$searchedStudentId' ORDER BY student_id";
            return $conn->query($student_query);
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
            $searchedStudentId = $_POST['search'];
            $student_result = searchGrades($conn, $searchedStudentId);
        } else {
            $student_query = "SELECT * FROM students";
            $student_result = $conn->query($student_query);
        }

        echo "<h2>Student List</h2>";
        echo "<form method='post' id='searchForm'>";
        echo "<input type='text' id='search' name='search' placeholder='Enter Student ID'>";
        echo "<input type='button' value='Search' onclick='perfSearch()'>";
        echo "</form>";  
        echo "<table>";
        echo "<tr><th>Student ID</th><th>Name</th><th>Course</th><th>Email</th></tr>";
        while ($row = $student_result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>".$row['student_id']."</td>";
            echo "<td>".$row['name']."</td>";
            echo "<td>".$row['course']."</td>";
            echo "<td class='email-cell'>".$row['email']."</td>";
            echo "</tr>";
        }
        echo "</table>";

        $conn->close();
        ?>
    </div>
</body>
</html>
