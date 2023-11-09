<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="/css/style.css">
</head>
<body>
    <h1>Login</h1>
    <form action="login.php" method="post">
        <label for="student_id">Student ID:</label>
        <input type="text" id="student_id" name="student_id" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <input type="submit" value="Login">
    </form>
    <a href="register.html">Register</a>
</body>
</html>
