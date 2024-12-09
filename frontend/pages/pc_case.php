<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PC Cases</title>
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
    <header class="header">
        <h1>PC Cases</h1>
    </header>
    <div class="parts-container">
        <?php
        // Database connection
        $servername = "localhost";
        $username = "root"; // Adjust as per your DB credentials
        $password = "";
        $dbname = "mycomputer";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Handle file upload
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["upload_image"])) {
            $pc_caseId = $_POST["pc_case_id"];
            $imageFile = $_FILES["image"]["name"];
            $targetDir = "../images/pc_case/";
            $targetFile = $targetDir . basename($imageFile);

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                $updateImageSql = "UPDATE PC_Case SET image = '$targetFile' WHERE id = $pc_caseId";
                if ($conn->query($updateImageSql) === TRUE) {
                    echo "<p>Image uploaded successfully for PC Case ID: $pc_caseId</p>";
                } else {
                    echo "<p>Error updating image: " . $conn->error . "</p>";
                }
            } else {
                echo "<p>Failed to upload image.</p>";
            }
        }

        // Fetch PC cases from the database
        $sql = "SELECT * FROM PC_Case";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $imagePath = $row["image"] ?: "../images/pc_case/default.jpg"; // Default image if none exists

                echo '<div class="part">';
                echo '<img src="' . $imagePath . '" alt="' . $row["name"] . '">';
                echo '<h3>' . $row["name"] . '</h3>';
                echo '<ul class="specs">';
                echo '<li><strong>Type:</strong> ' . $row["type"] . '</li>';
                echo '<li><strong>Dimensions:</strong> ' . $row["dimensions"] . '</li>';
                echo '<li><strong>Price:</strong> $' . $row["price"] . '</li>';
                echo '<li><strong><a href="' . $row["link"] . '" target="_blank">View More</a></strong></li>';
                echo '</ul>';

                // CRUD Buttons
                echo '<div class="crud-buttons">';
                echo '<form action="pc_case_edit.php" method="GET" style="display:inline;">';
                echo '<input type="hidden" name="id" value="' . $row["id"] . '">';
                echo '<button type="submit" class="edit">Edit</button>';
                echo '</form>';
                echo '<form action="pc_case_delete.php" method="POST" style="display:inline;">';
                echo '<input type="hidden" name="id" value="' . $row["id"] . '">';
                echo '<button type="submit" class="delete">Delete</button>';
                echo '</form>';
                echo '<form action="" method="POST" enctype="multipart/form-data" style="display:inline;">';
                echo '<input type="hidden" name="pc_case_id" value="' . $row["id"] . '">';
                echo '<input type="file" name="image" required>';
                echo '<button type="submit" name="upload_image" class="upload">Upload Image</button>';
                echo '</form>';
                echo '</div>';
                echo '<div class="clear"></div>';
                echo '</div>';
            }
        } else {
            echo "<p>No PC cases available.</p>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
