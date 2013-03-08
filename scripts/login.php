<?php
	/* Check if it is a valid POST */
	if ($_SERVER['REQUEST_METHOD'] != 'POST') {
		header("location:../index.html");
	}
	
	/* Get data from the login form and strip them of any html chars */
	$myusername=$_POST['username']; 
	$mypassword=$_POST['password'];
	$myusername = htmlspecialchars($myusername);
	$myusername = htmlspecialchars($mypassword);
	
	$crypt_mypassword = hash('sha256', $mypassword);
	

	/*  Connect to MongoDB  */
	try{
		$connection = new Mongo();
		$db = $connection->login;
		$collection = $connection->login->loginCol;
	
		$db_returnVAL = $collection->findOne( array( "username" => $myusername ), array("password" => 1) );
	}
	catch (MongoConnectionException $e) {
		die('Error connecting to MongoDB server');
	}
	catch (MongoException $e) {
		die('Error: ' . $e->getMessage());
	}
	

	if ($db_returnVAL['password'] === $crypt_mypassword) {
		session_start();
		$_SESSION['logged'] = "1True";
		$_SESSION['myusername'] = $myusername;
		session_write_close();
		header("location:../panel.php");		
	} 
	else {
		$login_failed = "Login Failed! Username or Password do not match";
		echo "<script type='text/javascript'>alert('$login_failed')</script>";
		echo "<script type='text/javascript'>window.history.back()</script>";
		//header("location:login.php");
	}
	exit;
?>