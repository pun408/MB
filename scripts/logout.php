<?php
    session_start();
	$logout_mesg = "Successfuly logged out of the system.....!";
	if(!isset($_SESSION['myusername']) || $_SESSION['logged'] != "1True") {
		header("location:../index.html");
		echo "<script type='text/javascript'>alert('$login_failed')</script>";
	}
	else {
		session_destroy();
		echo "<script type='text/javascript'>alert('$logout_mesg')</script>";
		echo "<script type='text/javascript'>window.location='../index.html'</script>";
	}
?>