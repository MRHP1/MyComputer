<?php
	session_start();
	session_destroy();
	setcookie("UID", "", -1, "/");
	
	setcookie("login_pesan", "Anda telah LOGOUT", time() + 60, "/"); // 86400 = 1 day
	header("location:login.php");
?>