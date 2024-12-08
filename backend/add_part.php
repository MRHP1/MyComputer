<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mycomputer";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $category = $conn->real_escape_string($_POST['category']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $description = $conn->real_escape_string($_POST['description']);

    $sql = "INSERT INTO parts (name, category, price, stock, description)
            VALUES ('$name', '$category', $price, $stock, '$description')";

    if ($conn->query($sql) === TRUE) {
        echo "New part added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
