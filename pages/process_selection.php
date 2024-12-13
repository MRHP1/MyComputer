<?php
session_start();

// Konfigurasi database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mycomputer";

// Koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil data user
$user_id = $_SESSION['UID'] ?? null;
if ($user_id && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $option = $_POST['option'] ?? null;
    $budget = $_POST['budget'] ?? null;

    if ($option && $budget) {
        // Konfigurasi spesifikasi berdasarkan kebutuhan dan budget
        $configurations = [
            'game' => [
                5000000 => ['cpu' => 6, 'gpu' => 3, 'cpu_cooler' => null, 'motherboard' => 1, 'psu' => 1, 'ram' => 6, 'storage' => 4, 'case' => 11],
                10000000 => ['cpu' => 3, 'gpu' => 9, 'cpu_cooler' => null, 'motherboard' => 3, 'psu' => 4, 'ram' => 3, 'storage' => 7, 'case' => 6],
                20000000 => ['cpu' => 26, 'gpu' => 11, 'cpu_cooler' => 5, 'motherboard' => 8, 'psu' => 4, 'ram' => 5, 'storage' => 7, 'case' => 7],
            ],
            'productivity' => [
                5000000 => ['cpu' => 10, 'gpu' => 7, 'cpu_cooler' => null, 'motherboard' => 10, 'psu' => 1, 'ram' => 3, 'storage' => 4, 'case' => 11],
                10000000 => ['cpu' => 5, 'gpu' => 3, 'cpu_cooler' => null, 'motherboard' => 11, 'psu' => 2, 'ram' => 3, 'storage' => 7, 'case' => 5],
                20000000 => ['cpu' => 23, 'gpu' => 9, 'cpu_cooler' => 5, 'motherboard' => 12, 'psu' => 5, 'ram' => 5, 'storage' => 7, 'case' => 7],
            ],
        ];

        // Pilihan spesifikasi yang dipilih
        $selected_config = $configurations[$option][$budget] ?? null;

        if ($selected_config) {
            // Perbarui data pengguna dalam tabel users
            $stmt = $conn->prepare(
                "UPDATE users SET user_cpu = ?, user_cooler = ?, user_gpu = ?, user_motherboard = ?, user_psu = ?, user_ram = ?, user_storage = ?, user_case = ? WHERE id = ?"
            );

            $stmt->bind_param(
                "iiiiiiiii",
                $selected_config['cpu'],
                $selected_config['cpu_cooler'],
                $selected_config['gpu'],
                $selected_config['motherboard'],
                $selected_config['psu'],
                $selected_config['ram'],
                $selected_config['storage'],
                $selected_config['case'],
                $user_id
            );
            $stmt->execute();
            $stmt->close();
        }
    }
}

$conn->close();
header("Location: index.php");
exit();
?>
