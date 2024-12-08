<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mycomputer";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM parts";
$result = $conn->query($sql);

$parts = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $parts[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($parts);

$conn->close();
?>
