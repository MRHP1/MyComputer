<?php
// Check if the necessary parameters are set
if (isset($_POST['component'], $_POST['component_id'])) {
    $component = $_POST['component'];
    $component_id = $_POST['component_id'];
    
    // Database connection (adjust credentials as needed)
    $pdo = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
    
    // Define the SQL DELETE query based on the component
    switch ($component) {
        case 'cpu':
            $sql = "DELETE FROM CPU WHERE id = :id";
            break;
        case 'ram':
            $sql = "DELETE FROM RAM WHERE id = :id";
            break;
        case 'storage':
            $sql = "DELETE FROM Storage WHERE id = :id";
            break;
        case 'gpu':
            $sql = "DELETE FROM GPU WHERE id = :id";
            break;
        case 'case':
            $sql = "DELETE FROM PC_Case WHERE id = :id";
            break;
        case 'psu':
            $sql = "DELETE FROM PSU WHERE id = :id";
            break;
        case 'motherboard':
            $sql = "DELETE FROM Motherboard WHERE id = :id";
            break;
        default:
            die('Invalid component');
    }

    // Prepare and execute the deletion query
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $component_id, PDO::PARAM_INT);
    $stmt->execute();

    // Redirect back to the page after removal
    header("Location: your_page.php");
    exit;
} else {
    // Handle the error if parameters are not set
    die('Invalid request');
}
