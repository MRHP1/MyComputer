<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CPU Parts</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .search-bar {
            margin-bottom: 20px;
        }
        .search-bar input {
            width: calc(100% - 100px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .search-bar button {
            padding: 10px 20px;
            background-color: #4A90E2;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .cpu-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .cpu-card {
            display: flex;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            background: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 15px;
        }
        .cpu-card img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            margin-right: 20px;
        }
        .cpu-details {
            flex: 1;
        }
        .cpu-details h3 {
            font-size: 20px;
            margin: 0 0 10px;
        }
        .cpu-details p {
            margin: 5px 0;
            font-size: 14px;
            display: flex;
            justify-content:space-between;
        }
        .cpu-details .rating {
            font-weight: bold;
            color: #ff9900;
            margin-top: 10px;
        }

        .crud-buttons {
            margin-top: 10px;
        }

        .crud-buttons button {
            padding: 8px 12px;
            margin-right: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .crud-buttons .edit {
            background-color: #00cec9;
            color: #fff;
        }

        .crud-buttons .delete {
            background-color: #d63031;
            color: #fff;
        }

        .crud-buttons .upload {
            background-color: #0984e3;
            color: #fff;
        }

        .crud-buttons button:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Available CPU Parts</h1>
        <div class="search-bar">
            <form method="GET" action="">
                <input type="text" name="search" id="search-input" placeholder="Search CPU...">
                <button type="submit">Search</button>
            </form>
        </div>
        <div id="cpu-list" class="cpu-list">
            <?php
            // Database connection
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "mycomputer";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Handle file upload
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["upload_image"])) {
                $cpuId = $_POST["cpu_id"];
                $imageFile = $_FILES["image"]["name"];
                $targetDir = "../images/cpu/";
                $targetFile = $targetDir . basename($imageFile);

                if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                    $updateImageSql = "UPDATE CPU SET image = '$targetFile' WHERE id = $cpuId";
                    if ($conn->query($updateImageSql) === TRUE) {
                        echo "<p>Image uploaded successfully for CPU ID: $cpuId</p>";
                    } else {
                        echo "<p>Error updating image: " . $conn->error . "</p>";
                    }
                } else {
                    echo "<p>Failed to upload image.</p>";
                }
            }

            // Handle search
            $searchQuery = "";
            if (!empty($_GET["search"])) {
                $search = $conn->real_escape_string($_GET["search"]);
                $searchQuery = " WHERE name LIKE '%$search%'";
            }

            // Fetch CPU parts
            $sql = "SELECT * FROM CPU" . $searchQuery;
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $imagePath = $row["image"] ?: "../images/cpu.png"; // Default image if none exists
                    echo '<div class="cpu-card">';
                    echo '<img src="' . $imagePath . '" alt="' . $row["name"] . '">';
                    echo '<div class="cpu-details">';
                    echo '<h3>' . $row["name"] . '</h3>';
                    echo '<p><strong>Core Count:</strong> ' . $row["core"] . ' <strong>Microarchitecture:</strong> ' . $row["microarchitecture"] . '</p>';
                    echo '<p><strong>Core Clock:</strong> ' . $row["core_clock"] . ' GHz <strong>TDP:</strong> ' . $row["tdp"] . '</p>';
                    echo '<p><strong>Boost Clock:</strong> ' . $row["boost_clock"] . ' GHz <strong>IGPU:</strong> ' . $row["igpu"] . '</p>';
                    echo '<p class="rating">Rating: ' . $row["rating"] . '</p>';
                    echo '<div class="crud-buttons">';
                    echo '<form action="cpu_edit.php" method="GET" style="display:inline;">';
                    echo '<input type="hidden" name="id" value="' . $row["id"] . '">';
                    echo '<button type="submit" class="edit">Edit</button>';
                    echo '</form>';
                    echo '<form action="cpu_delete.php" method="POST" style="display:inline;">';
                    echo '<input type="hidden" name="id" value="' . $row["id"] . '">';
                    echo '<button type="submit" class="delete">Delete</button>';
                    echo '</form>';
                    echo '<form action="" method="POST" enctype="multipart/form-data" style="display:inline;">';
                    echo '<input type="hidden" name="cpu_id" value="' . $row["id"] . '">';
                    echo '<input type="file" name="image" required>';
                    echo '<button type="submit" name="upload_image" class="upload">Upload Image</button>';
                    echo '</form>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "<p>No CPU parts available.</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
