<?php
    session_start();
	$login_failed = "Invalid Access !";
	if(!isset($_SESSION['myusername']) || $_SESSION['logged'] != "1True") {
		header("location:../index.html");
		echo "<script type='text/javascript'>alert('$login_failed')</script>";
	}
	
	if ($_SERVER['REQUEST_METHOD'] != 'POST') {
		header("location:../form/V_form.php");
	}
	
	/* Get data from the visit form and strip them of any html chars */
	$firstname = htmlspecialchars( $_POST['p_fname'] ); 
	$lastname = htmlspecialchars( $_POST['p_lname'] );
	$d_code = htmlspecialchars( $_POST['d_code'] );
	$dos_mm = htmlspecialchars( $_POST['dos_mm'] );
	$dos_dd = htmlspecialchars( $_POST['dos_dd'] );
	$dos_yyyy = htmlspecialchars( $_POST['dos_yyyy'] );
	//$dos = $dos_mm . "-" . $dos_dd . "-" . $dos_yyyy;
	$p_code = htmlspecialchars( $_POST['p_code'] );
	$a_code = htmlspecialchars( $_POST['a_code'] );
	$amount_charged = htmlspecialchars( $_POST['amount_charged'] );
	$bill_date_mm = htmlspecialchars( $_POST['bill_date_mm'] );
	$bill_date_dd = htmlspecialchars( $_POST['bill_date_dd'] );
	$bill_date_yyyy = htmlspecialchars( $_POST['bill_date_yyyy'] );
	//$bill_date = $bill_date_mm . "-" . $bill_date_dd . "-" . $bill_date_yyyy;
	//if(strcmp($bill_date, "--") == 0) $bill_date = "";
	$amount_paid = htmlspecialchars( $_POST['ins_paid'] );
	$copay = htmlspecialchars( $_POST['copay'] );
	$is_copay_due = htmlspecialchars( $_POST['is_copay_due'] );
	$credit = htmlspecialchars( $_POST['credit'] );
	$deductible = htmlspecialchars( $_POST['deductible'] );
	$chk_number = htmlspecialchars( $_POST['chk_number'] );	
	$pay_date_mm = htmlspecialchars( $_POST['pay_date_mm'] );
	$pay_date_dd = htmlspecialchars( $_POST['pay_date_dd'] );
	$pay_date_yyyy = htmlspecialchars( $_POST['pay_date_yyyy'] );
	//$pay_date = $pay_date_mm . "-" . $pay_date_dd . "-" . $pay_date_yyyy;
	//if(strcmp($pay_date, "--") == 0) $pay_date = "";
	$d_firstname = htmlspecialchars( $_POST['d_fname'] ); 
	$d_lastname = htmlspecialchars( $_POST['d_lname'] );
	$notes = htmlspecialchars( $_POST['notes'] );
	
	$sucess_mesg = "Patient visit added succesfully !";
	$failure_mesg = "Failed to update database !";
	$no_doctor = "Unable to locate doctor in database ! Please check";
	
	/*  Connect to MongoDB  */
	try{
		$connection = new Mongo();
		$db = $connection->mbdata;
		$collection_patient = $connection->mbdata->patients;
		$collection_doc = $connection->mbdata->doctors;
		
		/* Check Patient  */
		$patient = $collection_patient->findOne( array( "fname" => $firstname, "lname" => $lastname ), array("_id" => 1) );
		if ( $patient === NULL){
			echo "<script type='text/javascript'>alert('NO SUCH PATIENT..! Please add patient first..')</script>";
			echo "<script type='text/javascript'>window.history.back()</script>";
			exit;
		}
				
		/* get doctor ID */
		$doc = $collection_doc->findOne( array( "fname" => $d_firstname, "lname" => $d_lastname ), array("_id" => 1) );
		if ( $doc === NULL){
			echo "<script type='text/javascript'>alert('$no_doctor')</script>";
			echo "<script type='text/javascript'>window.history.back()</script>";
			exit;
		}
		$doc_id = $doc['_id'];
		
		/* create MONGO dates from available dates */
		$dos = new MongoDate(strtotime($dos_yyyy."-".$dos_mm."-".$dos_dd));
		if (strlen($bill_date_yyyy) > 0 && strlen($bill_date_mm) > 0 && strlen($bill_date_dd) > 0 )
			$bill_date = new MongoDate(strtotime($bill_date_yyyy."-".$bill_date_mm."-".$bill_date_dd));
		else $bill_date = "";
		if (strlen($pay_date_yyyy) > 0 && strlen($pay_date_mm) > 0 && strlen($pay_date_dd) > 0 )
			$pay_date = new MongoDate(strtotime($pay_date_yyyy."-".$pay_date_mm."-".$pay_date_dd));
		else $pay_date = "";
		
		/* Create array out of patient visit data */
		$visit_ary = array(
			"dos"   => $dos,
			"d_code" => "$d_code",
			"p_code" => "$p_code",
			"a_code" => "$a_code",
			"amount_charged" => "$amount_charged",
			"bill_date" => $bill_date,
			"amount_paid" => "$amount_paid",
			"copay" => "$copay",
			"is_copay_due" => "$is_copay_due",
			"credit" => "$credit",
			"deductible" => "$deductible",
			"chk_number" => "$chk_number",
			"pay_date" => $pay_date,
			"doctor_id" => "$doc_id",
			"notes" => "$notes"
		);
		
		$criteria = array("fname" => $firstname, "lname" => $lastname );
		
		if ( $collection_patient->update( $criteria, array('$push' => array("visits" => $visit_ary))) ) {
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