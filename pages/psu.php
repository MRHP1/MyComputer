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

// Handle adding psu to user's profile
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['psu_id'])) {
    $psu_id = intval($_POST['psu_id']);
    $user_id = $_SESSION['UID'];

    // Update the user's psu in the database
    $stmt = $conn->prepare("UPDATE users SET user_psu = ? WHERE id = ?");
    $stmt->bind_param("ii", $psu_id, $user_id);
    if ($stmt->execute()) {
        // Redirect to index.php after success
        
        header("Location: index.php");
        exit();
    } else {
        echo "<p style='color: red;'>Failed to add psu. Please try again.</p>";
    }
    $stmt->close();
}

// Handle search
$searchQuery = "";
if (!empty($_GET["search"])) {
    $search = $conn->real_escape_string($_GET["search"]);
    $searchQuery = " WHERE name LIKE '%$search%'";
}

// Fetch the list of psus
$psus = [];
$sql = "SELECT * FROM psu" . $searchQuery;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $psus[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>psu List</title>
    <style>

.navbar {
            position: absolute;
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
        
        body {
            overflow-x: hidden;
            font-family: Arial, sans-serif;
            display: flex; /* Enables Flexbox */
            justify-content: center; /* Horizontally centers the content */
            align-items: center; /* Vertically centers the content (for full height) */
            min-height: 100vh; /* Ensures the body takes full viewport height */
            margin: 0; /* Removes default margins */
            background: url('../images/building.webp') no-repeat center/cover; /* Responsive background image */
            background-attachment: fixed; /* Keeps the background fixed when scrolling */
            background-size: cover; /* Ensures the image covers the entire viewport responsively */
        }

        .parts-container {
            width: 50%;
            margin-top: 10%;
            padding: 20px;
            background-color: #2b1b17;
            border-radius: 24px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .search-bar {
            margin-bottom: 20px;
            text-align: center;
        }

        .search-bar input[type="text"] {
            width: 80%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 20px;
            outline: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background-color: rgb(187, 187, 173);
        }

        .search-bar button {
            padding: 10px 20px;
            background-color: #0984e3;
            color: #fff;
            border: none;
            border-radius: 20px;
            font-size: 1rem;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease; /* Smooth transition */
        }

        .search-bar button:hover {
            background-color: #74b9ff;
        }

        .part {
            border-bottom: 1px solid #ddd;
            padding: 15px;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 15px;
            background-color:#333;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease; /* Smooth transition */
        }

        .part:hover {
            transform: scale(1.05); /* Slightly enlarge the element */
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15); /* Enhance the shadow for a lifted effect */
        }

        .part:last-child {
            border-bottom: none;
        }

        .part h3 {
            color:white;
            margin: 0 0 10px;
        }

        .specs {
            color:white;
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
            border-radius: 10px;
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
            border-radius: 20px;
            font-size: 1rem;
            cursor: pointer;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
            transition: background-color 0.3s ease; /* Smooth transition */
        }

        .add-to-user:hover {
            background-color: #a29bfe;
        }
    </style>
</head>
<body>
<nav class="navbar">
        <div class="left-section">
            <div class="logo">
                <img src="../logo2.png" alt="Logo">
                <span>MyComputer™</span>
            </div>
        </div>
        <ul>
            <li><a href="index.php">Back to index</a></li>
        </ul>
    </nav>
    <div class="parts-container">
        <div class="search-bar">
            <form action="" method="GET">
                <input type="text" name="search" placeholder="Search PSUs...">
                <button type="submit">Search</button>
            </form>
        </div>
        <?php
        
        if (count($psus) > 0) {
            foreach ($psus as $row) {
                $imagePath = $row['image'] ?: "../images/psu/default.jpg";

                echo '<div class="part">';
                echo '<img src="' . $imagePath . '" alt="' . $row['name'] . '">';
                echo '<h3>' . $row['name'] . '</h3>';
                echo '<ul class="specs">';
                echo '<li><strong>Wattage:</strong> ' . $row['wattage'] . '</li>';
                echo '<li><strong>Certification:</strong> ' . $row['certification'] . '</li>';
                echo '<li><strong>Price:</strong> Rp ' . $row['price'] . '</li>';
                echo '<li><strong><a href="' . $row['link'] . '" target="_blank">View More</a></strong></li>';
                echo '</ul>';

                echo '<form action="" method="POST" style="display:inline;">';
                echo '<input type="hidden" name="psu_id" value="' . $row['id'] . '">';
                echo '<button type="submit" class="add-to-user">Add to User</button>';
                echo '</form>';

                echo '<div class="clear"></div>';
                echo '</div>';
            }
        } else {
            echo "<p>No PSUs available.</p>";
        }
        ?>
    </div>
</body>
</html>
