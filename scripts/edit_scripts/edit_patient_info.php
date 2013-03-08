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
	
	
	/* Check the type of form  (basic info, insurance or visit) */
	$form_type = htmlspecialchars($_POST['form_type']);
	
	$patienFname = htmlspecialchars($_POST['patient_fname']);
	$patienLname = htmlspecialchars($_POST['patient_lname']);
	$p_DOB = htmlspecialchars($_POST['patient_dob']);
	$patienDOB = new MongoDate(strtotime($p_DOB." 00:00:00"));
	
	
	/* Go back to original screen function*/
	function go_back($f_name, $l_name) {
		echo '<form name="myform" method="post" action="patient_info.php">
					<table>
						<tr><td> <input type="hidden" name="first" size="25" value="'. $f_name .'"/></td> <td> <input type="hidden" name="last" size="25" value="'. $l_name .'"/></td></tr>
						<tr><td><input style="display: none;" type="submit" value="submit"/></td></tr>
					</table>
				</form> 
				<script type="text/javascript">document.myform.submit();</script> ';
	}
	
	$query_criteria =  array("fname" => $patienFname, "lname" => $patienLname, "dob" => $patienDOB);
	$query_newobj = array();
	$address = array();
	
	if(strcmp($form_type, "basic") == 0 ){
	
		if( strlen($_POST['first']) > 0  )
			$query_newobj["fname"] = htmlspecialchars($_POST['first']);
		if( strlen($_POST['last']) > 0  )
			$query_newobj["lname"] = htmlspecialchars($_POST['last']);
		if( strlen($_POST['phone']) > 0  )
			$query_newobj["phone"] = htmlspecialchars($_POST['phone']);
		//if( strlen($_POST['email']) > 0  )
			//$query_newobj["email"] = htmlspecialchars($_POST['email']);
		if( strlen($_POST['addr1']) > 0  )
			$query_newobj = array_merge($query_newobj, array('address.st_addr1' => htmlspecialchars($_POST['addr1']) ) );
		if( strlen($_POST['addr2']) > 0  )
			$query_newobj = array_merge($query_newobj, array('address.st_addr2' => htmlspecialchars($_POST['addr2']) ) );
		if( strlen($_POST['city']) > 0  )
			$query_newobj = array_merge($query_newobj, array('address.city' => htmlspecialchars($_POST['city']) ) );
		if( strlen($_POST['state']) > 0  )
			$query_newobj = array_merge($query_newobj, array('address.state' => htmlspecialchars($_POST['state']) ) );
		if( strlen($_POST['zip']) > 0  )
			$query_newobj = array_merge($query_newobj, array('address.zip' => htmlspecialchars($_POST['zip']) ) );
		if( strlen($_POST['fname']) > 0  )
			$query_newobj['primary_fname'] = htmlspecialchars($_POST['fname']);
		if( strlen($_POST['lname']) > 0  )
			$query_newobj['primary_lname'] = htmlspecialchars($_POST['lname']);
		
		$new_prim_dob =  htmlspecialchars( $_POST['yyyy'] ) . "-" . htmlspecialchars( $_POST['mm'] ) . "-" . htmlspecialchars( $_POST['dd'] );
		
		if( (strlen($_POST['mm']) > 0) &&  (strlen($_POST['dd']) > 0) && (strlen($_POST['yyyy']) > 0) )
			$query_newobj['primary_dob'] = new MongoDate(strtotime($new_prim_dob." 00:00:00"));
						
	}
	
	
	elseif(strcmp($form_type, "insurance") == 0 ){
		if( strlen($_POST['ins_name']) > 0  )
			$query_newobj['insurance_name'] = htmlspecialchars($_POST['ins_name']);
		if( strlen($_POST['ins_id']) > 0  )
			$query_newobj['insurance_id'] = htmlspecialchars($_POST['ins_id']);
		if( strlen($_POST['addr1']) > 0  )
			$query_newobj = array_merge($query_newobj, array('ins_address.ins_st_addr1' => htmlspecialchars($_POST['addr1']) ) );
		if( strlen($_POST['addr2']) > 0  )
			$query_newobj = array_merge($query_newobj, array('ins_address.ins_st_addr1' => htmlspecialchars($_POST['addr2']) ) );
		if( strlen($_POST['city']) > 0  )
			$query_newobj = array_merge($query_newobj, array('ins_address.ins_city' => htmlspecialchars($_POST['city']) ) );
		if( strlen($_POST['state']) > 0  )
			$query_newobj = array_merge($query_newobj, array('ins_address.ins_state' => htmlspecialchars($_POST['state']) ) );
		if( strlen($_POST['zip']) > 0  )
			$query_newobj = array_merge($query_newobj, array('ins_address.ins_zip' => htmlspecialchars($_POST['zip']) ) );
	}
	
	
	elseif(strcmp($form_type, "visit") == 0 ){
		$visit_number = htmlspecialchars($_POST['visit_number']);
		$bill_date = explode("-", $_POST['billdate']);
		$pay_date = explode("-", $_POST['paydate']);
		
		if( strcmp($_POST['a_code'], $_POST['orig_a_code']) != 0  ) {
			//$mesg = $mesg . "Changing Authorization code from " . $_POST['orig_a_code'] . " to " . $_POST['a_code'];	
			$query_newobj = array_merge($query_newobj, array('visits.'.$visit_number.'.a_code' => htmlspecialchars($_POST['a_code']) ) );
		}
		if( strcmp($_POST['billdate'], $_POST['orig_billdate']) != 0  ) {
			//$mesg = $mesg . "\nChanging bill date from " .	$_POST['orig_billdate'] . " to ". $_POST['billdate'];
			$query_newobj = array_merge($query_newobj, array('visits.'.$visit_number.'.bill_date' => new MongoDate(strtotime($bill_date[2]."-".$bill_date[0]."-".$bill_date[1]." 00:00:00")) ) );
		}
		if( strcmp($_POST['amountcharged'], $_POST['orig_amountcharged']) != 0  ){
			//$mesg = $mesg . "\nChanging amount charged from " .	$_POST['orig_amountcharged'] . " to " . $_POST['amountcharged'];
			$query_newobj = array_merge($query_newobj, array('visits.'.$visit_number.'.amount_charged' => htmlspecialchars($_POST['amountcharged']) ) );
		}
		if( strcmp($_POST['copay'], $_POST['orig_copay']) != 0  ){
			//$mesg = $mesg . "\nChanging copay from " .	$_POST['orig_copay'] . " to " . $_POST['copay'];
			$query_newobj = array_merge($query_newobj, array('visits.'.$visit_number.'.copay' => htmlspecialchars($_POST['copay']) ) );
		}
		if( (strcmp($_POST['is_copay_due'], $_POST['orig_is_copay_due']) != 0 ) && (strcmp($_POST['is_copay_due'], "noChange") != 0) ) {
			//$mesg = $mesg . "\nChanging 'is copay due' from " . $_POST['orig_is_copay_due'] . " to " . $_POST['is_copay_due'];	
			$query_newobj = array_merge($query_newobj, array('visits.'.$visit_number.'.is_copay_due' => htmlspecialchars($_POST['is_copay_due']) ) );
		}
		if( strcmp($_POST['credit'], $_POST['orig_credit']) != 0  ) {
			//$mesg = $mesg . "\nChanging credit from " . $_POST['orig_credit'] . " to " . $_POST['credit'];	
			$query_newobj = array_merge($query_newobj, array('visits.'.$visit_number.'.credit' => htmlspecialchars($_POST['credit']) ) );
		}
		if( strcmp($_POST['deductible'], $_POST['orig_deductible']) != 0  ) {
			//$mesg = $mesg . "\nChanging deductible from " . $_POST['orig_deductible'] . " to " . $_POST['deductible'];	
			$query_newobj = array_merge($query_newobj, array('visits.'.$visit_number.'.deductible' => htmlspecialchars($_POST['deductible']) ) );
		}
		if( strcmp($_POST['amount_paid'], $_POST['orig_amount_paid']) != 0  ) {
			//$mesg = $mesg . "\nChanging Amount Paid from " . $_POST['orig_amount_paid'] . " to " . $_POST['amount_paid'];	
			$query_newobj = array_merge($query_newobj, array('visits.'.$visit_number.'.amount_paid' => htmlspecialchars($_POST['amount_paid']) ) );
		}
		if( strcmp($_POST['chk_number'], $_POST['orig_chk_number']) != 0  ) {
			//$mesg = $mesg . "\nChanging Check Number from " . $_POST['orig_chk_number'] . " to " . $_POST['chk_number'];
			$query_newobj = array_merge($query_newobj, array('visits.'.$visit_number.'.chk_number' => htmlspecialchars($_POST['chk_number']) ) );
		}
		if( strcmp($_POST['paydate'], $_POST['orig_paydate']) != 0  ) {
			//$mesg = $mesg . "\nChanging Pay Date from " . $_POST['orig_paydate'] . " to " . $_POST['paydate'];
			$query_newobj = array_merge($query_newobj, array('visits.'.$visit_number.'.pay_date' => new MongoDate(strtotime($pay_date[2]."-".$pay_date[0]."-".$pay_date[1]." 00:00:00")) ) );
		}
		if( strcmp($_POST['notes'], $_POST['orig_notes']) != 0  ) {
			//$mesg = $mesg . "\nChanging notes from  " . $_POST['orig_notes'] . " to " . $_POST['notes'];		
			$query_newobj = array_merge($query_newobj, array('visits.'.$visit_number.'.notes' => htmlspecialchars($_POST['notes']) ) );			
		}
	}
	
	else echo("Invalid Form !");
	
	if (count($query_newobj) == 0 ){
			echo "<script type='text/javascript'>alert('NOTHING TO BE CHANGED....!')</script>";
			go_back($patienFname, $patienLname);
	}
	
	/* Connect to Mongo DB */
	try{
		$connection = new Mongo();
		$db = $connection->mbdata;
		$collection = $connection->mbdata->patients;
		
		
		
		$return = $collection->update( $query_criteria, array('$set' => $query_newobj ), array("safe" => true) );
		
		if ($return['updatedExisting']=== true){
			echo "<script type='text/javascript'>alert('SUCCESSFUL::  Patient Information updated succesfully')</script>";
			go_back($patienFname, $patienLname);
		}
		else {
			echo '<script type="text/javascript">alert("FAILED to update database....! :  \n Error'. $return['err'].'")</script>';
			go_back($patienFname, $patienLname);
		}
	}
	catch (MongoConnectionException $e) {
		die('Error connecting to MongoDB server');
	}
	catch (MongoException $e) {
		die('Error: ' . $e->getMessage());
	}
	
	
?>