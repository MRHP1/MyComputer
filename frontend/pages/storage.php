<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root"; // Adjust as per your DB credentials
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

// Handle adding Storage to user's profile
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['storage_id'])) {
    $storage_id = intval($_POST['storage_id']);
    $user_id = $_SESSION['UID'];

    $stmt = $conn->prepare("UPDATE users SET user_storage = ? WHERE id = ?");
    $stmt->bind_param("ii", $storage_id, $user_id);
    if ($stmt->execute()) {
        // Redirect to index.php after successful update
        header("Location: index.php");
        exit();
    } else {
        echo "<p style='color: red;'>Failed to add Storage. Please try again.</p>";
    }
    $stmt->close();
}


// Fetch Storage from the database
$sql = "SELECT * FROM Storage";
$result = $conn->query($sql);

$storages = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $storages[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Storage</title>
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

        .edit-list-button {
            display: block;
            margin: 0 auto 20px;
            background-color: #00cec9;
            color: #fff;
            padding: 10px 15px;
            text-align: center;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-decoration: none;
        }

        .edit-list-button:hover {
            background-color: #81ecec;
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
    </style>
</head>
<body>
    <header class="header">
        <h1>Storage</h1>
    </header>
    <a href="index.php" class="back-button">Back to Home</a>
    <div class="parts-container">
        <a href="storage_edit.php" class="edit-list-button">Edit List</a>
        <?php
        if (count($storages) > 0) {
            foreach ($storages as $row) {
                $imagePath = $row['image'] ?: "../images/storage/default.jpg";

                echo '<div class="part">';
                echo '<img src="' . $imagePath . '" alt="' . $row['name'] . '">';
                echo '<h3>' . $row['name'] . '</h3>';
                echo '<ul class="specs">';
                echo '<li><strong>Read Speed:</strong> ' . $row['read_speed'] . '</li>';
                echo '<li><strong>Write Speed:</strong> ' . $row['write_speed'] . '</li>';
                echo '<li><strong>Form Factor:</strong> ' . $row['form_factor'] . '</li>';
                echo '<li><strong>Price:</strong> $' . $row['price'] . '</li>';
                echo '<li><strong><a href="' . $row['link'] . '" target="_blank">View More</a></strong></li>';
                echo '</ul>';

                echo '<form action="" method="POST" style="display:inline;">';
                echo '<input type="hidden" name="storage_id" value="' . $row['id'] . '">';
                echo '<button type="submit" class="add-to-user">Add to User</button>';
                echo '</form>';

                echo '<div class="clear"></div>';
                echo '</div>';
            }
        } else {
            echo "<p>No Storage available.</p>";
        }
        ?>
    </div>
</body>
</html>
