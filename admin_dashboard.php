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
    <link rel="stylesheet" href="./css/admin_dashboard.css">
    <link href="https://fonts.cdnfonts.com/css/google-sans" rel="stylesheet">
    <script defer src="./js/admin.js"></script>
    <title>Admin Panel</title>
</head>

<body>
    <div class="sidenav">
        <h1>Admin Panel</h1>
        <a href="#student_list">Student List</a>
        <a href="#grades">Grades</a>
        <a href="./manage_grades.php">Manage Grades</a>
        <a id="logout" href="./admin_login.php">Logout</a>
    </div>

    <div id="content-container"></div>

    <button class="hamburger" onclick="toggleNav()">&#9776;</button>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            loadContentFromHash();

            window.addEventListener('hashchange', function () {
                loadContentFromHash();
            });

            function loadContentFromHash() {
                var hash = window.location.hash.substring(1);
                if (hash !== "") {
                    loadPage(hash + '.php');
                } else {
                    loadPage('student_list.php');
                }
            }

            function loadPage(page) {
                fetch(page)
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('content-container').innerHTML = data;
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
    </script>

</body>

</html>
