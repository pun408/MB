<?php
   session_start();
	$login_failed = "Invalid Access !";
	if(!isset($_SESSION['myusername']) || $_SESSION['logged'] != "1True") {
		header("location:../../index.html");
		echo "<script type='text/javascript'>alert('$login_failed')</script>";
	}
	
	if ($_SERVER['REQUEST_METHOD'] != 'POST') {
		header("location:../../panel.php");
	}
	
	/* Function to get result of the query,  query-criteria and then refine the result.
	 * It the prints html report out of result */
	function refine_print_doctor_invoice($query_result, $include_copay, $detailed, $from, $to, $doc_id, $comm) {
		$total_billed = 0;
		$total_insurance_paid = 0;
		$total_copay_paid = 0;
		if (strcmp($detailed, "yes" ) == 0) {
			echo '<div id="visit_row"><div id="panel_visits" class="center">
				<table  border="1" cellspacing="0" cellpadding="1">
				<tr id="bold"><td>Date of Service</td><td>Diagnosis Code</td><td>Procedure Code</td><td>Patient Name</td><td>Insurance</td><td>Authorization Code</td><td>Billing Date</td><td>Amount Charged</td><td>Copay</td><td>Copay Due?</td><td>Available credit</td><td>Deductible</td><td>Insurance Paid</td><td>Check Number</td><td>Pay Date</td><td>Notes</td></tr>';
		}
		foreach($query_result as $patient) {
			$visits = $patient['visits'];
			foreach($visits as $visit) {
				if($visit['pay_date'] != "") {								
					if( ($visit['pay_date'] >= $from) && ($visit['pay_date'] <= $to) && ($visit['doctor_id'] == $doc_id) && ($visit['amount_paid'] > 0) ) {
						$total_billed = $total_billed + $visit['amount_charged'];
						$total_insurance_paid = $total_insurance_paid + $visit['amount_paid'];
						if (( strcmp($visit['is_copay_due'],"No") == 0)&& (strcmp($include_copay, "yes" ) == 0)) $total_copay_paid = $total_copay_paid + $visit['copay'];
						
						if (strcmp($detailed, "yes" ) == 0) 
						echo '<tr><td>'. date('m-d-Y', $visit['dos']->sec) .'</td><td>'. $visit['d_code'] .'</td><td>'. $visit['p_code'] .'</td><td>'. $patient['fname'] . ' ' . $patient['lname'] .'</td><td>'. $patient['insurance_name'] .'</td><td>'. $visit['a_code'] .'</td><td>'. date('m-d-Y', $visit['bill_date']->sec) .'</td><td>'. $visit['amount_charged'] .'</td><td>'. $visit['copay'] .'</td><td>'. $visit['is_copay_due'] .'</td><td>'. $visit['credit'] .'</td><td>'. $visit['deductible'] .'</td><td>'. $visit['amount_paid'] .'</td><td>'. $visit['chk_number'] .'</td><td>'. date('m-d-Y', $visit['pay_date']->sec) .'</td><td>'. $visit['notes'] .'</td></tr>';
					}
				}
			}
		}
		if (strcmp($detailed, "yes" ) == 0) {
			echo '</table></div></div>'; 
		}
		
		echo '<div id="panel_row" ><div id="panel_paid">
				<table border="0" cellspacing="0" cellpadding="1">';
		if ($total_billed > 0)	
				echo '<tr><td>Total Amount billed :&nbsp;</td><td><p id="total">$ ' . $total_billed . '</p></td></tr>';
		if ($total_insurance_paid > 0)	
				echo '<tr><td>Total Insurance paid :&nbsp;</td><td><p id="total">$ ' . $total_insurance_paid . '</p></td></tr>';
		if ($total_copay_paid > 0)	
				echo '<tr><td>Total Copay Recieved :&nbsp;</td><td><p id="total">$ ' . $total_copay_paid . '</p></td></tr>';
		
		if (strcmp($include_copay, "yes" ) == 0)
			$total = $total_insurance_paid + $total_copay_paid;
		else 
			$total = $total_insurance_paid;
		
		$commision = ($comm/100)*$total;
		echo '<tr><td>Commision :&nbsp;</td><td><p id="total">$ ' . $commision . '</p></td></tr>';
		echo '</table>
			</div></div>';	
	}
	
	$include_copay = "No";
	
	$fname = htmlspecialchars( $_POST['fname'] ); 
	$lname = htmlspecialchars( $_POST['lname'] ); 
	if (isset($_POST['include_copay'])) $include_copay = "yes";
	else  $include_copay = "no";
	if (isset($_POST['detailed'])) $detailed = "yes";
	else $detailed = "no";
	$from_mm = htmlspecialchars( $_POST['from_mm'] );
	$from_dd = htmlspecialchars( $_POST['from_dd'] );
	$from_yyyy = htmlspecialchars( $_POST['from_yyyy'] );
	$to_mm = htmlspecialchars( $_POST['to_mm'] );
	$to_dd = htmlspecialchars( $_POST['to_dd'] );
	$to_yyyy = htmlspecialchars( $_POST['to_yyyy'] );
	
	$from_date = new MongoDate(strtotime($from_yyyy."-".$from_mm."-".$from_dd." 00:00:00")); 
	$to_date = new MongoDate(strtotime($to_yyyy."-".$to_mm."-".$to_dd." 00:00:00"));
	
	try{
		$connection = new Mongo();
		$db = $connection->mbdata;
		$collection_patient = $connection->mbdata->patients;
		$collection_doc = $connection->mbdata->doctors;
	}
	catch (MongoConnectionException $e) {
		die('Error connecting to MongoDB server');
	}
	catch (MongoException $e) {
		die('Error: ' . $e->getMessage());
	}
	
	
	echo '<head>
						<meta charset="utf-8" />
						<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
						Remove this if you use the .htaccess -->
						<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
						<title> Invoice - ' . $fname . ' ' . $lname . '</title>
						<meta name="description" content="" />
						<meta name="author" content="lsandhu" />
						<meta name="viewport" content="width=device-width; initial-scale=1.0" />
						<!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
						<link rel="shortcut icon" href="/favicon.ico" />
						<link rel="apple-touch-icon" href="/apple-touch-icon.png" />
						<link rel="stylesheet" type="text/css" href="../../css/detail_report.css" />
						
		</head>
			<body>
				<div id="panel">
					<h2 align="center">Medical Billing Process Management</h2>
					<hr>';
	
	
		$doc = $collection_doc->findOne( array( "fname" => $fname, "lname" => $lname ) );
		if ( $doc === NULL){
			echo '<script type="text/javascript">alert("Cannot Find Doctor.....")</script>';
			echo "<script type='text/javascript'>window.history.back()</script>";
			exit();
		}
		$doc_id = $doc['_id'];
		$phone = $doc['phone'];
		$email = $doc['email'];
		$address = $doc['address'];
		$commision = $doc['commision'];
		
		$searchQuery = array ('visits.doctor_id' => "$doc_id",'visits.pay_date' => array('$gte' => $from_date, '$lte' => $to_date));
		$result = $collection_patient->find($searchQuery);
		echo '<div id="panel_row" ><div id="panel_person">
					<table border="0" cellspacing="" cellpadding="0">
						<tr><td><p>Doctor :</p></td><td><p id="bold">'. $fname . ' ' . $lname .'</p></td></tr>
						<tr><td><p>Doctor Phone:</p></td><td><p id="bold">'. $phone .'</p></td></tr>
						<tr><td><p>Doctor Email:</p></td><td><p id="bold">'. $email .'</p></td></tr>
						<tr><td><p>Doctor Address:</p></td><td><p id="bold">'. $address['st_addr1'] .'</p></td></tr>';
									if (strlen($address['st_addr2'])> 1)
										echo '<tr><td></td><td><p id="bold">'. $address['st_addr2'] .'</p></td></tr>';
									echo '<tr><td></td><td><p id="bold">'. $address['city'] . ' ' . $address['state'] . ' ' . $address['zip'] . '</p></td></tr>
					</table>
				</div>
				<div id="panel_insurance">
					<table border="0" cellspacing="" cellpadding="0">
						<tr><td><p>Invoice From:</p></td><td><p id="bold">'. $from_mm. '-' . $from_dd . '-' . $from_yyyy .'</p></td><td><p>To:</p></td><td><p id="bold">'. $to_mm. '-' . $to_dd . '-' . $to_yyyy .'</p></td></tr>
						<tr><td><p>Include Copay:</p></td><td><p id="bold">'. $include_copay . '</p></td></tr>
						<tr><td><p>Commision %:</p></td><td><p id="bold">'. $commision . '</p></td></tr>
					</table>
				</div></div>';
				 refine_print_doctor_invoice($result, $include_copay, $detailed, $from_date, $to_date, $doc_id, $commision);
		echo '</body> ';	
	
?>