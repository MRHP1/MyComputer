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

// Fetch user information including selected components
$user_info = null;

$user_id = $_SESSION['UID'];

// Handle CPU removal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_cpu'])) {
    $stmt = $conn->prepare("UPDATE users SET user_cpu = NULL WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // Reload the page to reflect changes
    header("location:index.php");
    exit();
}

// Fetch user and component information
$stmt = $conn->prepare("SELECT users.username, 
                                users.user_cpu, CPU.name AS cpu_name, CPU.core, CPU.core_clock, 
                                CPU_Cooler.name AS cooler_name, 
                                GPU.name AS gpu_name, 
                                Motherboard.name AS motherboard_name, 
                                PC_Case.name AS case_name, 
                                PSU.name AS psu_name, 
                                RAM.name AS ram_name, 
                                Storage.name AS storage_name 
                        FROM users 
                        LEFT JOIN CPU ON users.user_cpu = CPU.id 
                        LEFT JOIN CPU_Cooler ON users.user_cpu_cooler = CPU_Cooler.id 
                        LEFT JOIN GPU ON users.user_gpu = GPU.id 
                        LEFT JOIN Motherboard ON users.user_motherboard = Motherboard.id 
                        LEFT JOIN PC_Case ON users.user_case = PC_Case.id 
                        LEFT JOIN PSU ON users.user_psu = PSU.id
                        LEFT JOIN RAM ON users.user_ram = RAM.id 
                        LEFT JOIN Storage ON users.user_storage = Storage.id 
                        WHERE users.id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user_info = $result->fetch_assoc();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* Basic reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: #f4f7fb;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            height: 100vh;
            flex-direction: column;
        }

        /* Floating Header */
        header {
            width: 100%;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 10;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 0 0 20px 20px;
            margin: 20px;
            max-width: 95%;
        }

        header img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        header .logo {
            display: flex;
            align-items: center;
        }

        header .logo img {
            margin-right: 10px;
        }

        header .user-info {
            display: flex;
            align-items: center;
        }

        header .user-info p {
            font-size: 18px;
            font-weight: 500;
            margin-right: 10px;
        }

        header .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        /* Main Container */
        .container {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 400px;
            margin-top: 100px;
            text-align: center;
        }

        h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 20px;
            font-family: 'Merriweather', serif;
        }

        p {
            font-size: 18px;
            color: #555;
        }

        .component-info {
            background-color: #fafafa;
            border-radius: 12px;
            padding: 15px;
            margin-top: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .component-info ul {
            list-style-type: none;
            padding: 0;
        }

        .component-info li {
            font-size: 16px;
            margin-bottom: 10px;
            color: #333;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 20px;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        a {
            text-decoration: none;
            color: #007bff;
            font-size: 16px;
            margin-top: 20px;
            display: inline-block;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #0056b3;
        }

        .logout-btn {
            background-color: #f44336;
            color: white;
            border-radius: 25px;
            padding: 12px 30px;
            cursor: pointer;
            border: none;
            margin-top: 10px;
        }

        .logout-btn:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>

    <!-- Header with profile and logo -->
    <header>
        <div class="logo">
            <img src="logo.png" alt="Logo"> <!-- Replace with your logo -->
            <span style="font-size: 24px; font-weight: 600;">MyComputer</span>
        </div>
        <div class="user-info">
            <p><?= htmlspecialchars($user_info['username']) ?></p>
            <img src="user.png" alt="Profile Picture"> <!-- Replace with actual profile image -->
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <h1>Welcome, <?= htmlspecialchars($user_info['username']) ?>!</h1>

        <!-- Display CPU Information -->
        <?php if ($user_info['cpu_name']): ?>
            <div class="component-info">
                <h2>Your Selected CPU:</h2>
                <ul>
                    <li><strong>Name:</strong> <?= htmlspecialchars($user_info['cpu_name']) ?></li>
                    <li><strong>Cores:</strong> <?= htmlspecialchars($user_info['core']) ?></li>
                    <li><strong>Clock Speed:</strong> <?= htmlspecialchars($user_info['core_clock']) ?> GHz</li>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Display CPU Cooler Information -->
        <?php if ($user_info['cooler_name']): ?>
            <div class="component-info">
                <h2>Your Selected CPU Cooler:</h2>
                <ul>
                    <li><strong>Name:</strong> <?= htmlspecialchars($user_info['cooler_name']) ?></li>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Display GPU Information -->
        <?php if ($user_info['gpu_name']): ?>
            <div class="component-info">
                <h2>Your Selected GPU:</h2>
                <ul>
                    <li><strong>Name:</strong> <?= htmlspecialchars($user_info['gpu_name']) ?></li>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Display Motherboard Information -->
        <?php if ($user_info['motherboard_name']): ?>
            <div class="component-info">
                <h2>Your Selected Motherboard:</h2>
                <ul>
                    <li><strong>Name:</strong> <?= htmlspecialchars($user_info['motherboard_name']) ?></li>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Display PC Case Information -->
        <?php if ($user_info['case_name']): ?>
            <div class="component-info">
                <h2>Your Selected PC Case:</h2>
                <ul>
                    <li><strong>Name:</strong> <?= htmlspecialchars($user_info['case_name']) ?></li>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Display PSU Information -->
        <?php if ($user_info['psu_name']): ?>
            <div class="component-info">
                <h2>Your Selected PSU:</h2>
                <ul>
                    <li><strong>Name:</strong> <?= htmlspecialchars($user_info['psu_name']) ?></li>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Display RAM Information -->
        <?php if ($user_info['ram_name']): ?>
            <div class="component-info">
                <h2>Your Selected RAM:</h2>
                <ul>
                    <li><strong>Name:</strong> <?= htmlspecialchars($user_info['ram_name']) ?></li>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Display Storage Information -->
        <?php if ($user_info['storage_name']): ?>
            <div class="component-info">
                <h2>Your Selected Storage:</h2>
                <ul>
                    <li><strong>Name:</strong> <?= htmlspecialchars($user_info['storage_name']) ?></li>
                </ul>
            </div>
        <?php endif; ?>

        <a href="cpu.php">Choose/Change Components</a><br>
    </div>

</body>
</html>
