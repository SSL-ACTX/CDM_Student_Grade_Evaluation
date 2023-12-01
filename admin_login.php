<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    require_once('./db/config.php');

    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admin_users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $_SESSION['admin_user'] = $username;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/admin_login.css">
    <link href="https://fonts.cdnfonts.com/css/google-sans" rel="stylesheet">
    <title>Admin Login</title>
</head>

<body>
    <div class="login-container">
        <h2>Admin Login</h2><hr>
        <form method="post" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
            <a class="tlg" href="./index.html">Go to user login</a>
        </form>
        <?php if (isset($error))
            echo "<p>$error</p>"; ?>
    </div>
</body>

</html>