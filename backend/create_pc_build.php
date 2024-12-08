<?php
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

// Assuming you receive the selected part IDs via a POST request (e.g., from a form submission)
$cpu_id = $_POST['cpu_id'];
$cpu_cooler_id = $_POST['cpu_cooler_id'];
$gpu_id = $_POST['gpu_id'];
$ram_id = $_POST['ram_id'];
$storage_id = $_POST['storage_id'];
$psu_id = $_POST['psu_id'];
$case_id = $_POST['case_id'];
$motherboard_id = $_POST['motherboard_id'];

// Fetch the prices of all selected parts
$part_ids = [$cpu_id, $cpu_cooler_id, $gpu_id, $ram_id, $storage_id, $psu_id, $case_id, $motherboard_id];
$total_price = 0;

foreach ($part_ids as $part_id) {
    if ($part_id) {
        $sql = "SELECT price FROM parts WHERE id = $part_id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $total_price += $row['price'];
        }
    }
}

// Insert into the PC table
$sql = "INSERT INTO PC (cpu_id, cpu_cooler_id, gpu_id, ram_id, storage_id, psu_id, case_id, motherboard_id, total_price)
        VALUES ($cpu_id, $cpu_cooler_id, $gpu_id, $ram_id, $storage_id, $psu_id, $case_id, $motherboard_id, $total_price)";

if ($conn->query($sql) === TRUE) {
    echo "PC build added successfully!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the connection
$conn->close();
?>
