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

// Handle adding gpu to user's profile
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['gpu_id'])) {
    $gpu_id = intval($_POST['gpu_id']);
    $user_id = $_SESSION['UID'];

    // Update the user's gpu in the database
    $stmt = $conn->prepare("UPDATE users SET user_gpu = ? WHERE id = ?");
    $stmt->bind_param("ii", $gpu_id, $user_id);
    if ($stmt->execute()) {
        echo "<p style='color: green;'>gpu added to your profile successfully!</p>";
    } else {
        echo "<p style='color: red;'>Failed to add gpu. Please try again.</p>";
    }
    $stmt->close();
}

// Fetch the list of gpus
$gpus = [];
$sql = "SELECT id, name, vram, recommended_psu FROM gpu";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $gpus[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>gpu List</title>
</head>
<body>
    <h1>Available gpus</h1>
    <a href="index.php">back to index</a>
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>gpu Name</th>
                <th>vram</th>
                <th>Clock Speed (GHz)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($gpus)): ?>
                <?php foreach ($gpus as $gpu): ?>
                    <tr>
                        <td><?= htmlspecialchars($gpu['name']) ?></td>
                        <td><?= htmlspecialchars($gpu['vram']) ?></td>
                        <td><?= htmlspecialchars($gpu['recommended_psu']) ?></td>
                        <td>
                            <form action="" method="post" style="display:inline;">
                                <input type="hidden" name="gpu_id" value="<?= $gpu['id'] ?>">
                                <button type="submit">Add</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No gpus available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="index.php">back to index</a>
</body>
</html>
