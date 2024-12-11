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

// Handle adding motherboard to user's profile
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['motherboard_id'])) {
    $motherboard_id = intval($_POST['motherboard_id']);
    $user_id = $_SESSION['UID'];

    // Update the user's motherboard in the database
    $stmt = $conn->prepare("UPDATE users SET user_motherboard = ? WHERE id = ?");
    $stmt->bind_param("ii", $motherboard_id, $user_id);
    if ($stmt->execute()) {
        echo "<p style='color: green;'>Motherboard added to your profile successfully!</p>";
        header("Location: index.php"); // Redirect to index.php
        exit(); // Ensure no further code is executed after redirect
    } else {
        echo "<p style='color: red;'>Failed to add motherboard. Please try again.</p>";
    }
    $stmt->close();
}


// Fetch motherboards from the database
$sql = "SELECT * FROM Motherboard";
$result = $conn->query($sql);

$motherboards = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $motherboards[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motherboards</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .header {
            text-align: center;
            background-color: #2d3436;
            color: #ffffff;
            padding: 20px 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 2.5rem;
        }

        .back-button {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #0984e3;
            color: #fff;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            font-size: 0.9rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .back-button:hover {
            background-color: #74b9ff;
            text-decoration: none;
        }

        .parts-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .part {
            border-bottom: 1px solid #ddd;
            padding: 15px 0;
        }

        .part:last-child {
            border-bottom: none;
        }

        .part h3 {
            margin: 0 0 10px;
        }

        .specs {
            list-style: none;
            padding: 0;
            margin: 0 0 10px;
        }

        .specs li {
            margin-bottom: 5px;
        }

        .price {
            font-weight: bold;
            color: #0984e3;
        }

        .part img {
            max-width: 200px;
            margin-right: 20px;
            float: left;
            object-fit: contain;
        }

        a {
            color: #0984e3;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .clear {
            clear: both;
        }

        .add-to-user {
            background-color: #6c5ce7;
            color: #fff;
            padding: 10px 15px;
            text-align: center;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .add-to-user:hover {
            background-color: #a29bfe;
        }

        .edit-list-button {
            display: block;
            margin: 0 auto 20px;
            background-color: #00cec9;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .edit-list-button:hover {
            background-color: #81ecec;
        }

        .edit-mode .crud-buttons {
            display: block;
        }
                /* Popup styles */
                .popup {
            position: fixed;
            bottom: -100px; /* Hidden initially */
            left: 50%;
            transform: translateX(-50%);
            background-color: #fff;
            padding: 10px 20px;
            color: #333;
            font-size: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            text-align: center;
            max-width: 90%;
            min-width: 200px;
            z-index: 1000;
            transition: bottom 0.5s ease, opacity 0.5s ease;
        }

        .popup.success {
            border: 2px solid #2ecc71;
            background-color: #dfffd6;
            color: #27ae60;
        }

        .popup.error {
            border: 2px solid #e74c3c;
            background-color: #ffe6e6;
            color: #c0392b;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>Motherboards</h1>
    </header>
    <a href="index.php" class="back-button">Back to Home</a>
    <div class="parts-container">
    <a href="storage_edit.php" class="edit-list-button">Edit List</a>
        <?php
        if (count($motherboards) > 0) {
            foreach ($motherboards as $row) {
                $imagePath = $row['image'] ?: "../images/motherboard/default.jpg";

                echo '<div class="part">';
                echo '<img src="' . $imagePath . '" alt="' . $row['name'] . '">';
                echo '<h3>' . $row['name'] . '</h3>';
                echo '<ul class="specs">';
                echo '<li><strong>Socket:</strong> ' . $row['socket'] . '</li>';
                echo '<li><strong>Form Factor:</strong> ' . $row['form_factor'] . '</li>';
                echo '<li><strong>Memory Max:</strong> ' . $row['memory_max'] . '</li>';
                echo '<li><strong>Memory Slots:</strong> ' . $row['memory_slots'] . '</li>';
                echo '<li><strong>Price:</strong> Rp ' . $row['price'] . '</li>';
                echo '<li><strong><a href="' . $row['link'] . '" target="_blank">View More</a></strong></li>';
                echo '</ul>';

                echo '<form action="" method="POST" style="display:inline;">';
                echo '<input type="hidden" name="motherboard_id" value="' . $row['id'] . '">';
                echo '<button type="submit" class="add-to-user">Add to User</button>';
                echo '</form>';

                echo '<div class="clear"></div>';
                echo '</div>';
            }
        } else {
            echo "<p>No motherboards available.</p>";
        }
        ?>
    </div>

    <div id="popup" class="popup"></div>

    <script>
    // Function to show popup
    function showPopup(message, type) {
        const popup = document.getElementById('popup');
        popup.textContent = message;
        popup.className = `popup ${type}`; // Add success or error class
        popup.style.bottom = '20px'; // Slide up

        // Hide popup after 3 seconds
        setTimeout(() => {
            popup.style.bottom = '-100px'; // Slide back down
        }, 3000);
    }

    // Call showPopup based on PHP logic
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['motherboard_id'])) {
        $motherboard_id = intval($_POST['motherboard_id']);
        $user_id = $_SESSION['UID'];

        $stmt = $conn->prepare("UPDATE users SET user_motherboard = ? WHERE id = ?");
        $stmt->bind_param("ii", $motherboard_id, $user_id);

        if ($stmt->execute()) {
            echo "showPopup('Motherboard added to your profile successfully!', 'success');";
        } else {
            echo "showPopup('Failed to add motherboard. Please try again.', 'error');";
        }
        $stmt->close();
    }
    ?>
</script>

</body>
</html>
