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

// Check for login status
if (!isset($_SESSION['UID'])) {
    setcookie("login_pesan", "MAAF, anda harus login dulu", time() + (86400 * 30), "/");
    header("location:login.php");
    exit();
}

// Handle adding CPU to user's profile
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cpu_id'])) {
    $cpu_id = intval($_POST['cpu_id']);
    $user_id = $_SESSION['UID'];

    // Update the user's CPU in the database
    $stmt = $conn->prepare("UPDATE users SET user_cpu = ? WHERE id = ?");
    $stmt->bind_param("ii", $cpu_id, $user_id);
    if ($stmt->execute()) {
        echo "<p style='color: green;'>CPU added to your profile successfully!</p>";
    } else {
        echo "<p style='color: red;'>Failed to add CPU. Please try again.</p>";
    }
    $stmt->close();
}

// Fetch the list of CPUs
$cpus = [];
$sql = "SELECT id, name, core, core_clock FROM CPU";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cpus[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CPU List</title>
</head>
<body>
    <h1>Available CPUs</h1>
    <a href="index.php">back to index</a>
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>CPU Name</th>
                <th>Core</th>
                <th>Clock Speed (GHz)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($cpus)): ?>
                <?php foreach ($cpus as $cpu): ?>
                    <tr>
                        <td><?= htmlspecialchars($cpu['name']) ?></td>
                        <td><?= htmlspecialchars($cpu['core']) ?></td>
                        <td><?= htmlspecialchars($cpu['core_clock']) ?></td>
                        <td>
                            <form action="" method="post" style="display:inline;">
                                <input type="hidden" name="cpu_id" value="<?= $cpu['id'] ?>">
                                <button type="submit">Add</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No CPUs available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="index.php">back to index</a>
</body>
</html>