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

$user_id = $_SESSION['UID'];
$total_price = 0; 

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
                                users.user_cpu, CPU.name AS cpu_name, CPU.core, CPU.core_clock, CPU.price AS cpu_price, 
                                CPU_Cooler.name AS cooler_name, CPU_Cooler.price AS cooler_price, 
                                GPU.name AS gpu_name, GPU.price AS gpu_price, 
                                Motherboard.name AS motherboard_name, Motherboard.price AS motherboard_price, 
                                PC_Case.name AS case_name, PC_Case.price AS case_price, 
                                PSU.name AS psu_name, PSU.price AS psu_price, 
                                RAM.name AS ram_name, RAM.price AS ram_price, 
                                Storage.name AS storage_name, Storage.price AS storage_price 
                        FROM users 
                        LEFT JOIN CPU ON users.user_cpu = CPU.id 
                        LEFT JOIN CPU_Cooler ON users.user_cooler = CPU_Cooler.id 
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

// Handle part removal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_part'])) {
    $part = $_POST['part_column']; // The column representing the part to be removed
    $stmt = $conn->prepare("UPDATE users SET $part = NULL WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // Reload the page to reflect changes
    header("location:index.php");
    exit();
}

if ($result->num_rows > 0) {
    $user_info = $result->fetch_assoc();

    // Calculate the total price
    $total_price += $user_info['cpu_price'] ?: 0;
    $total_price += $user_info['cooler_price'] ?: 0;
    $total_price += $user_info['gpu_price'] ?: 0;
    $total_price += $user_info['motherboard_price'] ?: 0;
    $total_price += $user_info['case_price'] ?: 0;
    $total_price += $user_info['psu_price'] ?: 0;
    $total_price += $user_info['ram_price'] ?: 0;
    $total_price += $user_info['storage_price'] ?: 0;

    // Store the total price in the session
    $_SESSION['total_price'] = $total_price;
}

// Close the statement and connection
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
            align-items: center;
            flex-direction: column;
            height: 100vh;
        }

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
        }

        .container {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 700px;
            margin-top: 80px;
        }

        h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 20px;
            font-family: 'Merriweather', serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            font-weight: 700;
        }

        td {
            font-size: 16px;
        }

        button, a {
            padding: 8px 16px;
            font-size: 14px;
            color: white;
            background-color: #007bff;
            border: none;
            border-radius: 25px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover, a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<header>
    <div>
        <img src="logo.png" alt="Logo" style="width: 40px; height: 40px; margin-right: 10px;">
        <span style="font-size: 24px; font-weight: 600;">MyComputer</span>
    </div>
    <div>
        <p><?= htmlspecialchars($user_info['username']) ?></p>
    </div>
</header>

<div class="container">
    <h1>Welcome, <?= htmlspecialchars($user_info['username']) ?>!</h1>
    <table>
        <thead>
            <tr>
                <th>Component</th>
                <th>Details</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>CPU</td>
                <td><?= $user_info['cpu_name'] ? htmlspecialchars($user_info['cpu_name']) . " - " . htmlspecialchars($user_info['core']) . " cores, " . htmlspecialchars($user_info['core_clock']) . " GHz" : "Not selected" ?></td>
                <td><?= $user_info['cpu_price'] ? "Rp " . number_format($user_info['cpu_price'], 2) : "-" ?></td>
                <td>
                    <?php if ($user_info['cpu_name']): ?>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="part_column" value="user_cpu">
                            <button type="submit" name="remove_part">Remove</button>
                        </form>
                    <?php else: ?>
                        <a href="cpu.php">Choose/Change</a>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>RAM</td>
                <td><?= $user_info['ram_name'] ? htmlspecialchars($user_info['ram_name']) : "Not selected" ?></td>
                <td><?= $user_info['ram_price'] ? "Rp " . number_format($user_info['ram_price'], 2) : "-" ?></td>
                <td>
                    <?php if ($user_info['ram_name']): ?>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="part_column" value="user_ram">
                            <button type="submit" name="remove_part">Remove</button>
                        </form>
                    <?php else: ?>
                        <a href="ram.php">Choose/Change</a>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>Storage</td>
                <td><?= $user_info['storage_name'] ? htmlspecialchars($user_info['storage_name']) : "Not selected" ?></td>
                <td><?= $user_info['storage_price'] ? "Rp " . number_format($user_info['storage_price'], 2) : "-" ?></td>
                <td>
                    <?php if ($user_info['storage_name']): ?>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="part_column" value="user_storage">
                            <button type="submit" name="remove_part">Remove</button>
                        </form>
                    <?php else: ?>
                        <a href="storage.php">Choose/Change</a>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>GPU</td>
                <td><?= $user_info['gpu_name'] ? htmlspecialchars($user_info['gpu_name']) : "Not selected" ?></td>
                <td><?= $user_info['gpu_price'] ? "Rp " . number_format($user_info['gpu_price'], 2) : "-" ?></td>
                <td>
                    <?php if ($user_info['gpu_name']): ?>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="part_column" value="user_gpu">
                            <button type="submit" name="remove_part">Remove</button>
                        </form>
                    <?php else: ?>
                        <a href="gpu.php">Choose/Change</a>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>PC Case</td>
                <td><?= $user_info['case_name'] ? htmlspecialchars($user_info['case_name']) : "Not selected" ?></td>
                <td><?= $user_info['case_price'] ? "Rp " . number_format($user_info['case_price'], 2) : "-" ?></td>
                <td>
                    <?php if ($user_info['case_name']): ?>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="part_column" value="user_case">
                            <button type="submit" name="remove_part">Remove</button>
                        </form>
                    <?php else: ?>
                        <a href="pc_case.php">Choose/Change</a>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>PSU</td>
                <td><?= $user_info['psu_name'] ? htmlspecialchars($user_info['psu_name']) : "Not selected" ?></td>
                <td><?= $user_info['psu_price'] ? "Rp " . number_format($user_info['psu_price'], 2) : "-" ?></td>
                <td>
                    <?php if ($user_info['psu_name']): ?>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="part_column" value="user_psu">
                            <button type="submit" name="remove_part">Remove</button>
                        </form>
                    <?php else: ?>
                        <a href="psu.php">Choose/Change</a>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>CPU Cooler</td>
                <td><?= $user_info['cooler_name'] ? htmlspecialchars($user_info['cooler_name']) : "Not selected" ?></td>
                <td><?= $user_info['cooler_price'] ? "Rp " . number_format($user_info['cooler_price'], 2) : "-" ?></td>
                <td>
                    <?php if ($user_info['cooler_name']): ?>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="part_column" value="user_cooler">
                            <button type="submit" name="remove_part">Remove</button>
                        </form>
                    <?php else: ?>
                        <a href="cpu_cooler.php">Choose/Change</a>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>Motherboard</td>
                <td><?= $user_info['motherboard_name'] ? htmlspecialchars($user_info['motherboard_name']) : "Not selected" ?></td>
                <td><?= $user_info['motherboard_price'] ? "Rp " . number_format($user_info['motherboard_price'], 2) : "-" ?></td>
                <td>
                    <?php if ($user_info['motherboard_name']): ?>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="part_column" value="user_motherboard">
                            <button type="submit" name="remove_part">Remove</button>
                        </form>
                    <?php else: ?>
                        <a href="motherboard.php">Choose/Change</a>
                    <?php endif; ?>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" style="font-weight: bold; text-align: right;">Total Price:</td>
                <td style="font-weight: bold;">Rp <?= number_format($total_price, 2) ?></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>

</body>
</html>
