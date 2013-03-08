<?php
	session_start();
	$login_failed = "Login Failed !";
	if(!isset($_SESSION['myusername']) || $_SESSION['logged'] != "1True") {
		header("location:../index.html");
		echo "<script type='text/javascript'>alert('$login_failed')</script>";
	}
	else 
		header("location:../panel.php");
?>


