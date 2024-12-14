<?php
// Include database connection (replace with your connection details)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mycomputer";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from community_carts table
$sql = "
    SELECT 
        community_carts.user_id, 
        community_carts.user_cpu, 
        community_carts.user_gpu, 
        community_carts.user_ram, 
        community_carts.user_storage,
        community_carts.user_case,
        users.username,
        cpu.name AS cpu_name,
        gpu.name AS gpu_name,
        ram.name AS ram_name,
        storage.name AS storage_name,
        pc_case.name AS case_name,
        pc_case.image AS case_image
    FROM 
        community_carts
    INNER JOIN users ON community_carts.user_id = users.id
    LEFT JOIN cpu ON community_carts.user_cpu = cpu.id
    LEFT JOIN gpu ON community_carts.user_gpu = gpu.id
    LEFT JOIN ram ON community_carts.user_ram = ram.id
    LEFT JOIN storage ON community_carts.user_storage = storage.id
    LEFT JOIN pc_case ON community_carts.user_case = pc_case.id
";
$result = $conn->query($sql);

// Close the database connection
$conn->close();
?>

<?php
// Include database connection (replace with your connection details)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mycomputer";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from community_carts table
$sql = "
    SELECT 
        community_carts.user_id, 
        community_carts.user_cpu, 
        community_carts.user_gpu, 
        community_carts.user_ram, 
        community_carts.user_storage,
        community_carts.user_case,
        users.username,
        cpu.name AS cpu_name,
        gpu.name AS gpu_name,
        ram.name AS ram_name,
        storage.name AS storage_name,
        pc_case.name AS case_name,
        pc_case.image AS case_image
    FROM 
        community_carts
    INNER JOIN users ON community_carts.user_id = users.id
    LEFT JOIN cpu ON community_carts.user_cpu = cpu.id
    LEFT JOIN gpu ON community_carts.user_gpu = gpu.id
    LEFT JOIN ram ON community_carts.user_ram = ram.id
    LEFT JOIN storage ON community_carts.user_storage = storage.id
    LEFT JOIN pc_case ON community_carts.user_case = pc_case.id
";
$result = $conn->query($sql);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Carts</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #060606;
            padding-top: 100px;
            overflow-x: hidden;
        }

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

        .navbar ul li a:hover {
            color: white;
        }

        .cart {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* 3 columns of equal width */
            gap: 20px; /* Space between grid items */
            margin: 20px;
        }

        .part {
            padding: 15px;
            border-radius: 12px;
            overflow: hidden;
            background-color: #333;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .part:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }

        .part:last-child {
            border-bottom: none;
        }

        .part h3 {
            color: white;
            margin: 0 0 10px;
        }

        .specs {
            color: white;
            list-style: none;
            padding: 0;
            margin: 0 0 10px;
        }

        .specs li {
            margin-bottom: 5px;
        }

        .part img {
            max-width: 200px;
            margin-right: 20px;
            float: left;
            object-fit: contain;
            border-radius: 10px;
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
            transition: background-color 0.3s ease;
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

    <div class="cart">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="part">
                    <h3>Cart by: <span class="user"><?php echo htmlspecialchars($row['username']); ?></span></h3>
                    <ul class="specs">
                        <li><strong>CPU:</strong> <?php echo htmlspecialchars($row['cpu_name']); ?></li>
                        <li><strong>GPU:</strong> <?php echo htmlspecialchars($row['gpu_name']); ?></li>
                        <li><strong>RAM:</strong> <?php echo htmlspecialchars($row['ram_name']); ?></li>
                        <li><strong>Storage:</strong> <?php echo htmlspecialchars($row['storage_name']); ?></li>
                        <li><strong>Case:</strong> <?php echo htmlspecialchars($row['case_name']); ?></li>
                        <?php if (!empty($row['case_image'])): ?>
                            <li><img src="<?php echo htmlspecialchars($row['case_image']); ?>" alt="Case Image"></li>
                        <?php else: ?>
                            <li>No image available for this case.</li>
                        <?php endif; ?>
                    </ul>
                    <div class="clear"></div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No community carts found.</p>
        <?php endif; ?>
    </div>

</body>
</html>

