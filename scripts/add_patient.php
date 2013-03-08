<?php
    session_start();
	$login_failed = "Invalid Access !";
	if(!isset($_SESSION['myusername']) || $_SESSION['logged'] != "1True") {
		header("location:../index.html");
		echo "<script type='text/javascript'>alert('$login_failed')</script>";
	}
	
	if ($_SERVER['REQUEST_METHOD'] != 'POST') {
		header("location:../form/P_form.php");
	}
	
	/* Get data from the patient form and strip them of any html chars */
	$firstname = htmlspecialchars( $_POST['fname'] ); 
	$lastname = htmlspecialchars( $_POST['lname'] );
	$p_dob_mm = htmlspecialchars( $_POST['p_dob_mm'] );
	$p_dob_dd = htmlspecialchars( $_POST['p_dob_dd'] );
	$p_dob_yyyy = htmlspecialchars( $_POST['p_dob_yyyy'] );
	//$p_dob = $p_dob_mm . "-" . $p_dob_dd . "-" . $p_dob_yyyy;
	$st_addr1 = htmlspecialchars( $_POST['st_addr1'] );
	$st_addr2 = htmlspecialchars( $_POST['st_addr2'] );
	$city = htmlspecialchars( $_POST['city'] );
	$state = htmlspecialchars( $_POST['state'] );
	$zip = htmlspecialchars( $_POST['zip'] );
	$country = htmlspecialchars( $_POST['country'] );
	$phone = $_POST['phone1'] . " " . $_POST['phone2'] . " " . $_POST['phone3'];
	$primary_fname = htmlspecialchars( $_POST['primary_fname'] );
	$primary_lname = htmlspecialchars( $_POST['primary_lname'] );
	$primary_dob_mm = htmlspecialchars( $_POST['primary_dob_mm'] );
	$primary_dob_dd = htmlspecialchars( $_POST['primary_dob_dd'] );
	$primary_dob_yyyy = htmlspecialchars( $_POST['primary_dob_yyyy'] );
	//$primary_dob = $primary_dob_mm . "-" . $primary_dob_dd . "-" . $primary_dob_yyyy;
	 
		// Insurance info below
	$insurance_name = htmlspecialchars( $_POST['insurance_name'] );
	$insurance_id = htmlspecialchars( $_POST['insurance_id'] );
	$ins_st_addr1 = htmlspecialchars( $_POST['ins_st_addr1'] );
	$ins_st_addr2 = htmlspecialchars( $_POST['ins_st_addr2'] );
	$ins_city = htmlspecialchars( $_POST['ins_city'] );
	$ins_state = htmlspecialchars( $_POST['ins_state'] );
	$ins_zip = htmlspecialchars( $_POST['ins_zip'] );
	$ins_country = htmlspecialchars( $_POST['ins_country'] );
	$notes = htmlspecialchars( $_POST['notes'] );
	
	
/* Create MongoDB dates out of text  */
	$primary_dob = new MongoDate(strtotime($primary_dob_yyyy."-".$primary_dob_mm."-".$primary_dob_dd." 00:00:00"));
	if (strlen($p_dob_yyyy) > 0 && strlen($p_dob_mm) > 0 && strlen($p_dob_dd) > 0 )
		$p_dob = new MongoDate(strtotime($p_dob_yyyy."-".$p_dob_mm."-".$p_dob_dd." 00:00:00"));
	else $p_dob = "";
/* Create array out of above data */
	$p_array = array(
		"fname" => "$firstname",
		"lname" => "$lastname",
		"phone" => "$phone",
		"dob"   => $p_dob,
		"address" => array (
			"st_addr1" => "$st_addr1",
			"st_addr2" => "$st_addr2",
			"city"     => "$city",
			"state"    => "$state",
			"zip"      => "$zip"
		),
		"primary_fname" => "$primary_fname",
		"primary_lname" => "$primary_lname",
		"primary_dob"   => $primary_dob,
		"insurance_name"=> "$insurance_name",
		"insurance_id"=> "$insurance_id",
		"ins_address" => array (
			"ins_st_addr1" => "$ins_st_addr1",
			"ins_st_addr2" => "$ins_st_addr2",
			"ins_city"     => "$ins_city",
			"ins_state"    => "$ins_state",
			"ins_zip"      => "$ins_zip"
		),
		"notes" => "$notes",
		"visits" => array ( 
		)	
	);	
	
	$sucess_mesg = "Patient added succesfully";
	$failure_mesg = "Failed to update database";
	
	/*  Connect to MongoDB  */
	try{
		$connection = new Mongo();
		$db = $connection->mbdata;
		$collection = $connection->mbdata->patients;
		if ( $collection->insert($p_array) ) {
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