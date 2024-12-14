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

// Handle adding to community cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    // Fetch the user's selected parts from the database
    $stmt = $conn->prepare("SELECT user_cpu, user_gpu, user_ram, user_storage, user_motherboard, user_case, user_psu, user_cooler FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user_parts = $result->fetch_assoc();

        // Prepare the SQL query to insert into community_carts
        $stmt = $conn->prepare("INSERT INTO community_carts (user_id, user_cpu, user_gpu, user_ram, user_storage, user_motherboard, user_case, user_psu, user_cooler) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiiiiiii", 
            $user_id,
            $user_parts['user_cpu'],
            $user_parts['user_gpu'],
            $user_parts['user_ram'],
            $user_parts['user_storage'],
            $user_parts['user_motherboard'],
            $user_parts['user_case'],
            $user_parts['user_psu'],
            $user_parts['user_cooler']
        );

        if ($stmt->execute()) {
            // Redirect or show a success message
            echo "Parts added to the community cart successfully!";
            // Redirect to avoid form resubmission on page refresh
            header("location: community_carts.php"); // Redirect to prevent resubmission
            exit();

        } else {
            echo "Error: Could not add to the community cart.";
        }
    }
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
    <title>Build Your Own</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    <style>

        .navbar {
            position: fixed;
            display: flex;
            top: 10px; /* Adjust this value to move the navbar down */
            width: calc(100% - 20px); /* Optional: Adjust width if needed for spacing */
            margin: 0 10px; /* Optional: Add horizontal margins */
            background-color: rgba(0, 0, 0, 0.8);
            z-index: 1000; /* Ensure it stays above the hero section */
            padding: 1rem 2rem;
            color: white;
            font-size: 1.2rem; /* Make text bigger */
            border-radius: 14px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease, display 0.3s ease, font-size 0.3s ease, padding 0.3s ease; /* Transition all relevant properties */
            align-items: center; /* Centers the content vertically */
        }

        .navbar .left-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .navbar .logo {
            font-size: 2rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: font-size 0.3s ease, gap 0.3s ease; /* Transition logo size and spacing */
        }

        .navbar .logo img {
            height: 60px;
            width: auto;
        }

        .navbar ul {
            list-style: none;
            display: flex;
            gap: 1rem;
            transition: gap 0.3s ease; /* Transition gap */
        }

        .navbar ul li {
            position: relative;
        }

        .navbar ul li a {
            font-size: 1.2rem; /* Increase font size */
            color: white; /* Ensure text is visible on the hero image */
            font-weight: bold;
            transition: color 0.3s ease, font-size 0.3s ease; /* Transition text color and size */
        }

        .navbar ul li a:hover {
            color: white;
        }
        
        /* Basic reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: url('../images/building.webp') no-repeat center/cover;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
        }

        .container {
            background: #2b1b17;
            border-radius: 0.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 50%;
            height: 80%;
            margin-top: 100px;
        }

        h1 {
            font-size: 3rem;
            color: white;
            margin-bottom: 20px;
            font-weight:bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
            color:white;
        }

        th {
            font-weight: bold;
        }

        td {
            font-size: 16px;
        }

        button {
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
        
        /* Media Queries for mobile responsiveness */
        @media (max-width: 1000px) {
            .navbar {
                position: fixed;
                top: 10px; /* Adjust this value to move the navbar down */
                width: calc(100% - 20px); /* Optional: Adjust width if needed for spacing */
                margin: 0 10px; /* Optional: Add horizontal margins */
                z-index: 1000; /* Ensure it stays above the hero section */
                padding: 1rem 2rem;
                font-size: 0.8rem; /* Make text bigger */
                border-radius: 14px;
                transition: background-color 0.3s ease, box-shadow 0.3s ease, display 0.3s ease, font-size 0.3s ease, padding 0.3s ease; /* Transition all relevant properties */
            }

            .navbar .left-section {
                display: flex;
                align-items: center;
                gap: 1rem;
            }

            .navbar .logo {
                font-size: 1.2rem;
                font-weight: bold;
                display: flex;
                align-items: center;
                gap: 0.5rem;
                transition: font-size 0.3s ease, gap 0.3s ease; /* Transition logo size and spacing */
            }

            .navbar .logo img {
                height: 80px;
                width: auto;
                transition: height 0.3s ease; /* Transition logo image size */
            }

            .navbar ul {
                list-style: none;
                display: flex;
                gap: 1rem;
                transition: gap 0.3s ease; /* Transition gap */
            }

            .navbar ul li {
                position: relative;
            }

            .navbar ul li a {
                font-size: 0.8rem; /* Increase font size */
                font-weight: bold;
                transition: color 0.3s ease, font-size 0.3s ease; /* Transition text color and size */
            }
            
            /* Basic reset */
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Roboto', sans-serif;
                background: url('../images/building.webp') no-repeat center/cover;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                flex-direction: column;
                height: 100vh;
            }

            .container {
                border-radius: 0.5rem;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                padding: 30px;
                width: 90%;
                height:60%;
                margin-top: 80px;
            }

            h1 {
                font-size: 1rem;
                margin-bottom: 20px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th, td {
                padding: 5px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }

            th {
                font-weight: 700;
            }

            td {
                font-size: 0.6rem;
            }

            button, a {
                padding: 8px 16px;
                font-size: 0.6rem;
                border: none;
                border-radius: 25px;
                text-decoration: none;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }
        }
    </style>
</head>
<body>

<nav class="navbar">
        <div class="left-section">
            <div class="logo">
                <img src="../logo2.png" alt="Logo">
            </div>
        </div>
        <ul>
        <li><p>MyComputer™</p></li>
            <li><a href='../logout.php'>Logout</a></li>
            <li><a href='../landing.php'>Back to Home</a></li>
            <li><a href='community_carts.php'>Community Carts</a></li>
        </ul>
    </nav>

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
                        <button onclick="window.location.href='cpu.php'">Choose</button>
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
                        <button onclick="window.location.href='ram.php'">Choose</button>
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
                        <button onclick="window.location.href='storage.php'">Choose</button>
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
                        <button onclick="window.location.href='gpu.php'">Choose</button>
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
                        <button onclick="window.location.href='pc_case.php'">Choose</button>
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
                        <button onclick="window.location.href='psu.php'">Choose</button>
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
                        <button onclick="window.location.href='cpu_cooler.php'">Choose</button>
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
                        <button onclick="window.location.href='motherboard.php'">Choose</button>
                    <?php endif; ?>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" style="font-weight: bold; text-align: center;">Total Price:</td>
                <td style="font-weight: bold;">Rp <?= number_format($total_price, 2) ?></td>
                <td></td>
            </tr>
        </tfoot>
        
    </table>
    <form method="POST">
    <button type="submit" name="add_to_cart" style="margin-top:12px;">Add Parts to Community Cart</button>
</form>

</div>

</body>
</html>