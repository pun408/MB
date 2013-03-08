<?php
    session_start();
	$login_failed = "Invalid Access !";
	if(!isset($_SESSION['myusername']) || $_SESSION['logged'] != "1True") {
		header("location:../index.html");
		echo "<script type='text/javascript'>alert('$login_failed')</script>";
	}
	
	if ($_SERVER['REQUEST_METHOD'] != 'POST') {
		header("location:../form/D_form.php");
	}
	
	/* Get data from the patient form and strip them of any html chars */
	$firstname = htmlspecialchars( $_POST['fname'] ); 
	$lastname = htmlspecialchars( $_POST['lname'] );
	$st_addr1 = htmlspecialchars( $_POST['st_addr1'] );
	$st_addr2 = htmlspecialchars( $_POST['st_addr2'] );
	$city = htmlspecialchars( $_POST['city'] );
	$state = htmlspecialchars( $_POST['state'] );
	$zip = htmlspecialchars( $_POST['zip'] );
	$country = htmlspecialchars( $_POST['country'] );
	$phone = $_POST['phone1'] . " " . $_POST['phone2'] . " " . $_POST['phone3'];
	$email = htmlspecialchars( $_POST['email'] );
	$t_id = htmlspecialchars( $_POST['t_id'] );
	$npi = htmlspecialchars( $_POST['npi'] );
	$commision = htmlspecialchars( $_POST['commision'] );
	$speciality = htmlspecialchars( $_POST['speciality'] );
	
	
	/* Create array out of above data */
	$d_array = array(
		"fname" => "$firstname",
		"lname" => "$lastname",
		"phone" => "$phone",
		"email" => "$email",
		"address" => array (
			"st_addr1" => "$st_addr1",
			"st_addr2" => "$st_addr2",
			"city"     => "$city",
			"state"    => "$state",
			"zip"      => "$zip"
		),
		"t_id" => "$t_id",
		"npi" => "$npi",
		"commision" => "$commision",
		"speciality" => "$speciality"
	);	
	
	$sucess_mesg = "Doctor added succesfully";
	$failure_mesg = "Failed to update database";
	
	/*  Connect to MongoDB  */
	try{
		$connection = new Mongo();
		$db = $connection->mbdata;
		$collection = $connection->mbdata->doctors;
		if ( $collection->insert($d_array) ) {
			echo "<script type='text/javascript'>alert('$sucess_mesg')</script>";
			echo "<script type='text/javascript'>window.history.back()</script>";
		}
		else {
			echo "<script type='text/javascript'>alert('$faliure_mesg')</script>";
			echo "<script type='text/javascript'>window.history.back()</script>";
		}
	}
	catch (MongoConnectionException $e) {
		die('Error connecting to MongoDB server');
	}
	catch (MongoException $e) {
		die('Error: ' . $e->getMessage());
	}
?>