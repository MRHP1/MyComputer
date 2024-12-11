<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mycomputer";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $in_uid = $conn->real_escape_string($_POST['in_username']);
    $in_password = $_POST['in_password'];

    // Check credentials
    $sql = "SELECT * FROM users WHERE username = '$in_uid'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify the password
        if (password_verify($in_password, $user['password'])) {
            $_SESSION['UID'] = $user['id'];
            setcookie("UID", $user['id'], time() + 60, "/"); // 60 seconds
            header("location:index.php");
            exit();
        } else {
            session_destroy();
            setcookie("login_pesan", "Maaf, password salah", time() + 60, "/");
            header("location:login.php");
            exit();
        }
    } else {
        session_destroy();
        setcookie("login_pesan", "Maaf, username tidak ditemukan", time() + 60, "/");
        header("location:login.php");
        exit();
    }
}

// Check if the user is already logged in
if (isset($_COOKIE['UID'])) {
    header("location:index.php");
    exit();
}

// Display any login message stored in a cookie
$loginMessage = "";
if (isset($_COOKIE['login_pesan'])) {
    $loginMessage = $_COOKIE['login_pesan'];
    setcookie("login_pesan", "", time() - 3600, "/"); // Clear the login message cookie
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <?php if ($loginMessage): ?>
        <p style="color: red; text-align: center;"><?= htmlspecialchars($loginMessage) ?></p>
    <?php endif; ?>

    <form action="" method="post">
        <table align="center">
            <tr>
                <td>Username</td>
                <td><input type="text" name="in_username" required></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><input type="password" name="in_password" required></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">
                    <input type="submit" value="Login">
                </td>
            </tr>
            <a href='register.php'>register here</a>
        </table>
    </form>
</body>
</html>
