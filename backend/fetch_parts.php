<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mycomputer";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM cpu";
$result = $conn->query($sql);

$cpu = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $cpu[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($cpu);

$conn->close();
?>
