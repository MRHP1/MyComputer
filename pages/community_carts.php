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
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .cart {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-top: 20px;
        }
        .cart-item {
            background-color: #fff;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 48%;
            border-radius: 8px;
            box-sizing: border-box;
        }
        .cart-item h3 {
            margin: 0;
            color: #333;
        }
        .cart-item p {
            margin: 5px 0;
            color: #666;
        }
        .cart-item .user {
            font-weight: bold;
            color: #333;
        }
        .cart-item img {
            max-width: 100%;
            height: auto;
            margin-top: 10px;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Community Carts</h1>
    
    <div class="cart">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="cart-item">
                    <h3>Cart by: <span class="user"><?php echo htmlspecialchars($row['username']); ?></span></h3>
                    <p><strong>CPU:</strong> <?php echo htmlspecialchars($row['cpu_name']); ?></p>
                    <p><strong>GPU:</strong> <?php echo htmlspecialchars($row['gpu_name']); ?></p>
                    <p><strong>RAM:</strong> <?php echo htmlspecialchars($row['ram_name']); ?></p>
                    <p><strong>Storage:</strong> <?php echo htmlspecialchars($row['storage_name']); ?></p>
                    <p><strong>Case:</strong> <?php echo htmlspecialchars($row['case_name']); ?></p>
                    <?php if (!empty($row['case_image'])): ?>
                        <img src="<?php echo htmlspecialchars($row['case_image']); ?>" alt="Case Image">
                    <?php else: ?>
                        <p>No image available for this case.</p>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No community carts found.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
