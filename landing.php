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
        html,body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            scroll-behavior: smooth;
        }

        .navbar {
            position: fixed;
            top: 10px; /* Adjust this value to move the navbar down */
            width: calc(100% - 20px); /* Optional: Adjust width if needed for spacing */
            margin: 0 10px; /* Optional: Add horizontal margins */
            background-color: transparent; /* Make navbar invisible */
            z-index: 1000; /* Ensure it stays above the hero section */
            padding: 1rem 2rem;
            color: white;
            font-size: 1.2rem; /* Make text bigger */
            border-radius: 14px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease, display 0.3s ease, font-size 0.3s ease, padding 0.3s ease; /* Transition all relevant properties */
        }

        .navbar.scrolled {
            background-color: rgba(0, 0, 0, 0.8); /* Solid background when scrolled */
            color: white; /* Ensure text stays visible */
            display: flex;
            transition: background-color 0.3s ease, box-shadow 0.3s ease, display 0.3s ease, font-size 0.3s ease, padding 0.3s ease; /* Transition all relevant properties */
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
            transition: font-size 0.3s ease, gap 0.3s ease; /* Transition logo size and spacing */
        }

        .navbar.scrolled .logo {
            font-size: 2rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar .logo img {
            height: 100px;
            width: auto;
            transition: height 0.3s ease; /* Transition logo image size */
        }

        .navbar.scrolled .logo img {
            height: 60px;
            width: auto;
        }

        .navbar ul {
            list-style: none;
            display: flex;
            gap: 1.5rem;
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
            color: #80ed99;
        }

        .dropdown {
            background-color: rgba(0, 0, 0, 0.7);
            display: none;
            position: absolute;
            top: 100%;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .dropdown a {
            display: block;
            padding: 0.5rem 1rem;
            color: white;
            text-decoration: none;
            font-weight: normal;
        }

        .dropdown a:hover {
            background-color: #060606;
            color: #80ed99;
        }

        .navbar ul li:hover .dropdown {
            display: block;
        }

        .hero {
            text-align: center;
            padding: 5rem 2rem;
            padding-top: 7rem;
            background: url('images/landing2.jpg') no-repeat center/cover;
            height: 80vh;
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
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }

        .hero .cta {
            display: inline-block;
            padding: 1rem 2rem;
            background-color: #80ed99;
            color: #060606;
            font-size: 1.2rem;
            font-weight: bold;
            border: none;
            text-decoration: none;
            border-radius: 2rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .hero .cta:hover {
            background-color: #77c58b;
        }

        .features {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 2rem;
            padding: 3rem 2rem;
            background-color: #28272c;
            padding-top: 20rem;
            padding-bottom: 20rem;
            background: url('images/amd.webp') no-repeat center/cover;
            position: relative; /* Ensure the bottom text can be positioned */
        }

        .feature-container {
            background: #2b1b17;
            border-radius: 24px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 2rem;
            max-width: 300px;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: transform 0.3s ease;
        }

        .feature-container:hover {
            transform: scale(1.1);
        }

        .feature-container i {
            font-size: 3rem;
            color: #fffff0;
        }

        .feature-container h3 {
            font-size: 2rem;
            margin: 0.5rem 0;
            color: #fffff0;
        }

        .feature-container p {
            color: #fffff0;
            margin-bottom: 1rem;
        }

        .feature-btn {
            padding: 0.75rem 1.5rem;
            background-color: #80ed99;
            color: #060606;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-weight: bold;
        }

        .feature-btn:hover {
            background-color: #16a085;
        }

        /* Styling the bottom text */
        .feature-bottom-text {
            position: absolute; /* Absolute position within the parent .features */
            bottom: 20px; /* Push it to the bottom of the section */
            width: 100%; /* Take up full width of the container */
            text-align: center;
            color: #fffff0;
            font-size: 0.8rem;
            font-weight: normal;
        }

        /* Mobile optimizations */
        @media (max-width: 768px) {
            .navbar {
                position: absolute;
                padding: 0.5rem 1rem; /* Reduced padding */
            }

            .navbar .logo {
                font-size: 2rem; /* Adjust logo size */
            }

            .navbar .logo img {
                height: 80px; /* Adjust logo image size */
                width: auto;
                transition: height 0.3s ease; /* Transition logo image size */
            }

            .navbar ul li a {
                font-size: 1rem; /* Adjust font size */
            }

            /* Remove the scrolled styles on mobile */
            .navbar.scrolled {
                background-color: transparent; /* Keep navbar transparent on mobile */
                box-shadow: none; /* Remove box shadow */
                display: block; /* Keep the navbar visible */
            }

            /* Make sure dropdown still works */
            .dropdown {
                background-color: rgba(0, 0, 0, 0.7);
                display: none;
                position: absolute;
                top: 100%;
                width: 100%;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
            }

            .navbar ul li:hover .dropdown {
                display: block;
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
            <li><a href="about.php">About</a></li>
            <li><a href="#contact">Contact</a></li>
            
            <li class="dropdown-menu">
            <a href="#myaccount">MyAccount</a>
            <div class="dropdown">
                <a href="pages/index.php">MyList</a>
                <a href="logout.php">Logout</a>
            </div>
        </li>
        </ul>
    </nav>

    <section class="hero" id="home">
        <h1>Build Your Dream PC</h1>
        <p>Experience the ultimate PC building simulator. Customize, optimize, and build your perfect setup.</p>
        <a href="#features" class="cta">Get Started</a>
    </section>

    <section class="features" id="features">
    <div class="feature">
        <div class="feature-container">
            <i class="fas fa-cogs"></i>
            <h3>Customizable Builds</h3>
            <p>Choose from a wide variety of components and design your ideal PC.</p>
            <button class="feature-btn" onclick="window.location.href='pages/index.php';">Explore More</button>
        </div>
    </div>

    <div class="feature">
        <div class="feature-container">
            <i class="fas fa-rocket"></i>
            <h3>Recommended Specs</h3>
            <p>Answer a few of our questions and we'll make your ideal PC.</p>
            <button class="feature-btn" onclick="window.location.href='pages/build-your-own.html';">Build now!</button>
        </div>
    </div>
    <div class="feature-bottom-text" id="contact">
        <p>MyComputer™ | <a href="mailto:mycomputer@gmail.com">Contact Us</a></p>
        <p>Kuliah Pemrograman Web Jurusan Teknik Informatika ITS (2023).</p>
        <p>Dosen: Imam Kuswardayan, S.Kom, M.T.</p>
    </div>
</section>
</body>
<script>
    window.addEventListener('scroll', function () {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 150) { // Adjust the value as needed
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

</script>
</html>
