<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mycomputer";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check for login status
if (!isset($_SESSION['UID'])) {
    setcookie("login_pesan", "MAAF, anda harus login dulu", time() + (86400 * 30), "/");
    header("location:login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyComputer</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
        }

        /* Navbar */
        .navbar {
            position: fixed;
            top: 10px;
            width: 100%;
            margin: 0 10px;
            background-color: transparent;
            z-index: 1000;
            padding: 1rem 2rem;
            color: white;
            font-size: 1.2rem;
            border-radius: 14px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease, font-size 0.3s ease, padding 0.3s ease;
        }

        .navbar.scrolled {
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
        }

        .navbar .left-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .navbar .logo {
            font-size: 4rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: font-size 0.3s ease, gap 0.3s ease;
        }

        .navbar.scrolled .logo {
            font-size: 2rem;
        }

        .navbar .logo img {
            height: 100px;
            width: auto;
            transition: height 0.3s ease;
        }

        .navbar.scrolled .logo img {
            height: 60px;
        }

        .navbar ul {
            list-style: none;
            display: flex;
            gap: 1.5rem;
            transition: gap 0.3s ease;
        }

        .navbar ul li a {
            font-size: 1.2rem;
            color: white;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .navbar ul li a:hover {
            color: #80ed99;
        }

        /* Hero Section */
        .hero {
            text-align: center;
            padding: 5rem 2rem;
            background: url('images/landing2.jpg') no-repeat center/cover;
            height: 83.2vh;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 5rem;
            margin-bottom: 2rem;
            font-weight: bold;
        }

        /* Mobile optimizations */
        @media (max-width: 768px) {
            .navbar {
                position: absolute;
                padding: 0.5rem 1rem;
            }

            .navbar .logo {
                font-size: 2rem;
            }

            .navbar .logo img {
                height: 80px;
            }

            .navbar ul li a {
                font-size: 1rem;
            }

            .navbar.scrolled {
                background-color: transparent;
                box-shadow: none;
            }

            .hero {
                padding: 3rem 1rem;
                height: 100vh;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 3rem;
            }

            .hero pre {
                font-size: 1rem;
                white-space: pre-wrap; /* Ensures text wraps properly on small screens */
                word-wrap: break-word; /* Prevents overflow */
                margin: 1rem 0;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="left-section">
            <div class="logo">
                <img src="logo2.png" alt="Logo">
                MyComputer™
            </div>
        </div>
        <ul>
            <li><a href="landing.php">back to main page.</a></li>
        </ul>
    </nav>

    <section class="hero" id="home">
        <p>"Bangun PC Impianmu"</p>
        <pre style="font-size:1rem; text-shadow: 4px 4px #000000;">
Di sini kami percaya bahwa semua orang bisa merakit PC impian mereka. Dengan pilihan komponen 
terkini dan panduan yang mudah dipahami, kamu bebas menyesuaikan setiap detail untuk memenuhi 
kebutuhan dan gaya kamu. Baik untuk gaming, kerja produktif, atau proyek kreatif, kami hadir 
untuk membantu kamu menciptakan performa terbaik. Yuk, mulai wujudkan PC impianmu sekarang!
</pre>
    </section>
</body>

<script>
    window.addEventListener('scroll', function () {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 150) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
</script>

</html>
