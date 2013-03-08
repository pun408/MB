<?php
    session_start();
	$login_failed = "Invalid Access !";
	if(!isset($_SESSION['myusername']) || $_SESSION['logged'] != "1True") {
		header("location:../../index.html");
		echo "<script type='text/javascript'>alert('$login_failed')</script>";
	}
	
	if ($_SERVER['REQUEST_METHOD'] != 'GET') {
		header("location:../../panel.php");
	}
	
	$fname = $_GET['fname'];
	$lname = $_GET['lname'];
	$dob = new MongoDate(strtotime($_GET['dob']." 00:00:00"));
	
	$searchQuery = array("fname" => $fname, "lname" => $lname, "dob" => $dob) ;
	
	try{
		$connection = new Mongo();
		$db = $connection->mbdata;
		$collection_patient = $connection->mbdata->patients;
		$collection_doc = $connection->mbdata->doctors;
			
		$results = $collection_patient->find($searchQuery);
		
		$counter = count($results);
		/* Check if DB returned more than one patient */ 
				
		if($counter === 0) {
			echo "<script type='text/javascript'>alert('NO SUCH PATIENT.......!')</script>";
			echo "<script type='text/javascript'>window.history.back()</script>";
		}
		if($counter === 1) {
			foreach($results as $doc) {
				$patientFname = $doc['fname'];
				$patientLname = $doc['lname'];
				$phone = $doc['phone'];
				$email = " ";
				$dob = $doc['dob'];
				$address = $doc['address'];
				$primaryName = $doc['primary_fname'] . " " . $doc['primary_lname'];
				$primary_dob = $doc['primary_dob'];
				$insurance_name = $doc['insurance_name'];
				$insurance_id = $doc['insurance_id'];
				$ins_address = $doc['ins_address'];	
				$visits = $doc['visits'];
				
				echo '<head>
						<meta charset="utf-8" />
						<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
						Remove this if you use the .htaccess -->
						<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
						<title>' . $patientFname . ' ' . $patientLname . ' report </title>
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
							<h3 align="center">Medical Billing Process Management</h3>
							<hr>
							<div id="panel_row" ><div id="panel_person">
								<table border="0" cellspacing="" cellpadding="0">
									<tr><td><p>Patient Name:</p></td><td><p id="bold">'. $patientFname . ' ' . $patientLname .'</p></td></tr>
									<tr><td><p>Patient DOB:</p></td><td><p id="bold">'. date('Y-m-d', $dob->sec) .'</p></td></tr>
									<tr><td><p>Patient Phone:</p></td><td><p id="bold">'. $phone .'</p></td></tr>
									<tr><td><p>Patient Address:</p></td><td><p id="bold">'. $address['st_addr1'] .'</p></td></tr>';
									if (strlen($address['st_addr2'])> 1)
										echo '<tr><td></td><td><p id="bold">'. $address['st_addr2'] .'</p></td></tr>';
									echo '<tr><td></td><td><p id="bold">'. $address['city'] . ' ' . $address['state'] . ' ' . $address['zip'] . '</p></td></tr>
								</table>
							</div>
							
							<div id="panel_insurance">
								<table border="0" cellspacing="" cellpadding="0">
									<tr><td><p>Insurance Name:</p></td><td><p id="bold">'. $insurance_name .'</p></td></tr>
									<tr><td><p>Insurance ID:</p></td><td><p id="bold">'. $insurance_id .'</p></td></tr>
									<tr><td><p>Primary Name:</p></td><td><p id="bold">'. $primaryName . '</p></td></tr>
									<tr><td><p>Primary DOB:</p></td><td><p id="bold">'. date('Y-m-d', $primary_dob->sec) .'</p></td></tr>
								</table>
							</div></div>
							
							<div id="panel_visits">
								<table border="1" cellspacing="0" cellpadding="1">
									<tr id="bold"><td>Date of Service</td><td>Diagnosis Code</td><td>Procedure Code</td><td>Authorization Code</td><td>Billing Date</td><td>Amount Charged</td><td>Copay</td><td>Copay Due?</td><td>Available credit</td><td>Deductible</td><td>Insurance Paid</td><td>Check Number</td><td>Pay Date</td><td>Doctor</td><td>Notes</td></tr>';
									foreach($visits as $key => $visit) {
										/* Get doctor name first*/
										$doc = $collection_doc->findOne( array( "_id" => new MongoId($visit['doctor_id']) ), array("fname" => 1, "lname" => 1) );
										$doc_name = $doc['fname']. " " . $doc['lname'];	
										
										/* Check if  bill date  and pay date are empy??. If they are, then display empty value */
										if ( $visit['bill_date']== "")
											$bill_date = "";
										else $bill_date = date('m-d-Y', $visit['bill_date']->sec);
										if ( $visit['pay_date']== "")
											$pay_date = "";
										else $pay_date = date('m-d-Y', $visit['pay_date']->sec);

										echo '<tr><td>'. date('m-d-Y', $visit['dos']->sec) .'</td><td>'. $visit['d_code'] .'</td><td>'. $visit['p_code'] .'</td><td>'. $visit['a_code'] .'</td><td>'. $bill_date .'</td><td>'. $visit['amount_charged'] .'</td><td>'. $visit['copay'] .'</td><td>'. $visit['is_copay_due'] .'</td><td>'. $visit['credit'] .'</td><td>'. $visit['deductible'] .'</td><td>'. $visit['amount_paid'] .'</td><td>'. $visit['chk_number'] .'</td><td>'. $pay_date .'</td><td>'. $doc_name .'</td><td></td></tr>';
									}
								echo '</table>
							</div> 
						</div>
					<body>
					';
			}
		}
		else {
			echo '<script type="text/javascript">alert("MORE than 1 SUCH PATIENT.......!")</script>';
			exit();
		}
	}
	catch (MongoConnectionException $e) {
		die('Error connecting to MongoDB server');
	}
	catch (MongoException $e) {
		die('Error: ' . $e->getMessage());
	}
?>