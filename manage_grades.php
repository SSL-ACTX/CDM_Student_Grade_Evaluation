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
    <link rel="stylesheet" href="./css/admin_manage_grade.css">
    <link rel="stylesheet" href="./css/sidenav.css">
    <script defer src="./js/manage_grades.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <title>Grade Management System</title>
</head>

<body>
    <div class="sidenav">
        <h1>Admin Panel</h1>
        <a href="./admin_dashboard.php#student_list">Student List</a>
        <a href="./admin_dashboard.php#grades">Grades</a>
        <a href="./manage_grades.php">Manage Grades</a>
        <a id="logout" href="admin_login.php">Logout</a>
    </div>

    <button class="hamburger" onclick="toggleNav()">&#9776;</button>

    <div class="container">
        <?php
        session_start();
        require_once('./db/config.php');
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        function executeQuery($conn, $query)
        {
            return $conn->query($query);
        }

        function getGrades($conn, $studentId)
        {
            $studentId = $conn->real_escape_string($studentId);
            $query = "SELECT * FROM grades WHERE student_id = '$studentId'";
            return executeQuery($conn, $query);
        }

        function deleteGrade($conn, $gradeId)
        {
            $gradeId = $conn->real_escape_string($gradeId);
            $query = "DELETE FROM grades WHERE id = '$gradeId'";
            return executeQuery($conn, $query);
        }

        function editGrade($conn, $gradeId, $newGrade)
        {
            $gradeId = $conn->real_escape_string($gradeId);
            $newGrade = $conn->real_escape_string($newGrade);
            $query = "UPDATE grades SET grade = '$newGrade' WHERE id = '$gradeId'";
            return executeQuery($conn, $query);
        }

        function addGrade($conn, $studentId, $subject, $grade, $semester)
        {
            $studentId = $conn->real_escape_string($studentId);
            $subject = $conn->real_escape_string($subject);
            $grade = $conn->real_escape_string($grade);
            $semester = $conn->real_escape_string($semester);
            $query = "INSERT INTO grades (id, student_id, subject, grade, semester) VALUES (NULL, '$studentId', '$subject', '$grade', '$semester')";
            return executeQuery($conn, $query);
        }

        function searchStudents($conn, $searchQuery)
        {
            $searchQuery = $conn->real_escape_string($searchQuery);
            $query = "SELECT * FROM students WHERE student_id LIKE '%$searchQuery%'";
            return executeQuery($conn, $query);
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['delete'])) {
                $studentId = $_POST['delete_student_id'];
                $gradeIdToDelete = $_POST['grades_dropdown_delete'];
                if (!empty($gradeIdToDelete)) {
                    echo deleteGrade($conn, $gradeIdToDelete) ? "Grade deleted successfully." : "Error deleting grade.";
                } else {
                    echo "Please select a grade to delete.";
                }
            } elseif (isset($_POST['edit'])) {
                $studentId = $_POST['edit_student_id'];
                $gradeId = $_POST['grades_dropdown'];
                $newGrade = $_POST['new_grade'];
                echo editGrade($conn, $gradeId, $newGrade) ? "Grade edited successfully." : "Error editing grade.";
            } elseif (isset($_POST['add'])) {
                $studentId = $_POST['add_grade_student_id'];
                $subject = $_POST['add_grade_subject'];
                $grade = $_POST['add_grade_grade'];
                $semester = $_POST['add_grade_semester'];
                echo addGrade($conn, $studentId, $subject, $grade, $semester) ? "Grade added successfully." : "Error adding grade.";
            } elseif (isset($_POST['search'])) {
                $searchQuery = $_POST['search_query'];
                $student_result = searchStudents($conn, $searchQuery);
            }
        }

        if (!isset($student_result)) {
            $student_query = "SELECT * FROM students";
            $student_result = executeQuery($conn, $student_query);
        }
        ?>
        <h2>Student List:</h2>
        <form method="post">
            <label for="search_query">Search by Student ID:</label>
            <input type="text" id="search_query" name="search_query" placeholder="Enter Student ID">
            <input type="submit" name="search" value="Search">
        </form>
        <table>
            <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Course</th>
                <th>Delete Grades</th>
                <th>Edit Grades</th>
                <th>Add Grades</th>
            </tr>
            <?php
            while ($student = $student_result->fetch_assoc()) {
                $grades_result = getGrades($conn, $student['student_id']);
                ?>
                <tr>
                    <td>
                        <?= $student['student_id']; ?>
                    </td>
                    <td>
                        <?= $student['name']; ?>
                    </td>
                    <td>
                        <?= $student['course']; ?>
                    </td>
                    <td>
                        <form method='post'>
                            <select name='grades_dropdown_delete'>
                                <option value=''>Select grade to delete</option>
                                <?php while ($gradeRow = $grades_result->fetch_assoc()): ?>
                                    <option value='<?= $gradeRow['id']; ?>'>
                                        Subject:
                                        <?= $gradeRow['subject']; ?>,
                                        Grade:
                                        <?= $gradeRow['grade']; ?>,
                                        Semester:
                                        <?= $gradeRow['semester']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <input type='hidden' name='delete_student_id' value='<?= $student['student_id']; ?>'>
                            <input type='submit' name='delete' value='Delete'>
                        </form>
                    </td>
                    <td>
                        <form method='post'>
                            <select name='grades_dropdown'>
                                <option value=''>Select Grade</option>
                                <?php $grades_result = getGrades($conn, $student['student_id']); ?>
                                <?php while ($gradeRow = $grades_result->fetch_assoc()): ?>
                                    <option value='<?= $gradeRow['id']; ?>'>
                                        Subject:
                                        <?= $gradeRow['subject']; ?>,
                                        Grade:
                                        <?= $gradeRow['grade']; ?>,
                                        Semester:
                                        <?= $gradeRow['semester']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <input type='hidden' name='edit_student_id' value='<?= $student['student_id']; ?>'>
                            <input type='text' name='new_grade' placeholder='New Grade'>
                            <input type='submit' name='edit' value='Edit'>
                        </form>
                    </td>
                    <td>
                        <form method='post'>
                            <input type='hidden' name='add_grade_student_id' value='<?= $student['student_id']; ?>'>
                            <label for='add_grade_subject'>Subject:</label>
                            <input type='text' id='add_grade_subject' name='add_grade_subject' required>
                            <label for='add_grade_grade'>Grade:</label>
                            <input type='text' id='add_grade_grade' name='add_grade_grade' required>
                            <label for='add_grade_semester'>Semester:</label>
                            <input type='text' id='add_grade_semester' name='add_grade_semester' required>
                            <input type='submit' name='add' value='Add Grade'>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <?php $conn->close(); ?>
    </div>

</body>

</html>