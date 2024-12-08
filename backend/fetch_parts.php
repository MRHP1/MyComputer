<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mycomputer";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['category'])) {
    $category = $conn->real_escape_string($_GET['category']);

    // Define the allowed categories and their corresponding tables
    $allowed_categories = [
        'CPU' => 'CPU',
        'CPU Cooler' => 'CPU_Cooler',
        'GPU' => 'GPU',
        'RAM' => 'RAM',
        'Storage' => 'Storage',
        'PSU' => 'PSU',
        'Case' => 'PC_Case',
        'Motherboard' => 'Motherboard'
    ];

    // Check if the category is valid
    if (array_key_exists($category, $allowed_categories)) {
        $table = $allowed_categories[$category];
        
        // Prepare the SQL query for the selected category's table
        $sql = "SELECT * FROM $table";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $parts = [];
            while($row = $result->fetch_assoc()) {
                $parts[] = $row;
            }
            echo json_encode($parts); // Return data as JSON
        } else {
            echo json_encode([]); // Return empty array if no parts found
        }
    } else {
        echo json_encode(["error" => "Invalid category"]);
    }
}

$conn->close();
?>
