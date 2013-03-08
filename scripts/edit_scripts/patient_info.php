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
	
	$counter = 0;
	$fname = new MongoRegex('/^'.htmlspecialchars($_POST['first']).'$/i');  
	$lname = new MongoRegex('/^'.htmlspecialchars($_POST['last']).'$/i');
	
	/* create a search query  */
	$searchQuery = array("fname" => $fname, "lname" => $lname) ;
	
	/*  Connect to MongoDB  */
	try{
		$connection = new Mongo();
		$db = $connection->mbdata;
		$collection_patient = $connection->mbdata->patients;
		$collection_doc = $connection->mbdata->doctors;
			
		$results = $collection_patient->find($searchQuery);
		
		/* Check if DB returned more than one patient */ 
		foreach($results as $doc)
		{
			$counter = $counter + 1;
		}
		
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
				$notes = $doc['notes'];	
				$visits = $doc['visits'];
			}
		}
		else {
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
	
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
		Remove this if you use the .htaccess -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<title>Patient Info</title>
		<meta name="description" content="" />
		<meta name="author" content="lsandhu" />
		<meta name="viewport" content="width=device-width; initial-scale=1.0" />
		<!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
		<link rel="shortcut icon" href="/favicon.ico" />
		<link rel="apple-touch-icon" href="/apple-touch-icon.png" />
		<link rel="stylesheet" type="text/css" href="../../css/edit_page.css" />
		<style type="text/css">tr:hover{background-color:#C6DEFF ;}</style>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
		<script type="text/javascript" src="edit_pages.js"> </script>	
	</head>
	<body>		
		<div>
			
			<!-- Div for modal popup/ Mask START  -->
			<div id="mask"></div>
			
			<div id="center_panel" class="basic_edit">
				<h3>&nbsp; &nbsp; Enter the value that you want to change. Leave others blank</h3>
				<form  class="center" autocomplete="on" accept-charset="UTF-8" method="post" action="edit_patient_info.php" onsubmit="return validate_confirm_basic(this)">
					<input type="hidden" name="form_type" value="basic">
					<input type="hidden" name="patient_fname" value="<?php echo "$patientFname";  ?>">
					<input type="hidden" name="patient_lname" value="<?php echo "$patientLname";  ?>">
					<input type="hidden" name="patient_dob" value="<?php echo (date('Y-m-d', $dob->sec));  ?>">
					<table id="table-2">
						<tr><td> <p> Name </p> </td> <td> <input type="text" name="first" size="20" autofocus="autofocus" placeholder="First name" pattern="[A-Za-z ]+" value="<?php echo "$patientFname";  ?>"/>  <input type="text" name="last" size="20" placeholder="Last name" pattern="[A-Za-z ]+" value="<?php echo "$patientLname";  ?>"/> </td></tr>
						<tr><td> <p> Phone </p> </td> <td> <input type="tel" name="phone" size="15"/ placeholder="### ### ####" pattern="[0-9 ]+" /> </td></tr>
						<tr><td> <p> Email </p> </td> <td> <input type="email" name="email" size="25" pattern="\w+@[a-zA-z_]+?\.[a-zA-Z]{2,6}"/> </td></tr>
						<tr><td> <p> Address </p> </td> <td> <input type="text" name="addr1" size="25" pattern="[A-Za-z0-9- #]+" value="<?php echo "$address[st_addr1]";  ?>"/> <br> <input type="text" name="addr2" size="25" pattern="[A-Za-z0-9- #]+" value="<?php echo "$address[st_addr2]";?>" /> <br> <input type="text" name="city" size="25" placeholder="City" pattern="[A-Za-z- ]+" value="<?php echo "$address[city]"; ?>" /> <br> <input type="text" name="state" size="25" placeholder="State" pattern="[A-Za-z- ]+" value="<?php echo "$address[state]"; ?>" /> <br> <input type="text" name="zip" size="6"placeholder="Zip" pattern="[0-9]{5}" value="<?php echo "$address[zip]"; ?>" /> </td> </tr>
						<tr><td> <p> Primary Insurance Holder </p> </td> <td> <input type="text" name="fname" size="20" placeholder="First name"  pattern="[A-Za-z ]+"/> <input type="text" name="lname" size="20" placeholder="Last name" pattern="[A-Za-z ]+"/> </td></tr>
						<tr><td> <p> Primary Insurance Holder DOB </p> </td> <td> <input type="text" name="mm" size="2" placeholder="mm" pattern="[0-9]{1,2}"/> <input type="text" name="dd" size="2" placeholder="dd"  pattern="[0-9]{1,2}"/> <input type="text" name="yyyy" size="4" placeholder="yyyy" pattern="[0-9]{4}"/>  </td></tr>
						<tr><td> <p> Addnitional Notes </p> </td> <td> <textarea name="notes" rows="3" cols="30"><?php echo "$notes";  ?></textarea>  </td></tr>
						<tr><td> </td> <td align="right" > <input style="background: #3366FF; font-size: 14px;" type="submit" value="submit"/> </td> </tr>
					</table>
				</form>				
			</div>
			
			<div id="center_panel" class="insurance_edit">
				<h3>&nbsp; &nbsp; Enter the value that you want to change. Leave others blank</h3>
				<form  class="center" autocomplete="on" accept-charset="UTF-8" method="post" action="edit_patient_info.php" onsubmit="return validate_confirm_insurance(this)>
					<input type="hidden" name="form_type" value="insurance">
					<input type="hidden" name="patient_fname" value="<?php echo "$patientFname";  ?>">
					<input type="hidden" name="patient_lname" value="<?php echo "$patientLname";  ?>">
					<input type="hidden" name="patient_dob" value="<?php (date('Y-m-d', $dob->sec));  ?>">
					<table id="table-2" class="center">
						<tr><td> <p> Insurance Name </p> </td> <td> <input type="text" name="ins_name" size="25" autofocus="autofocus" pattern="[A-Za-z ]+" /> </td></tr>
						<tr><td> <p> Insurance ID </p> </td> <td> <input type="text" name="ins_id" size="25" pattern="[A-Za-z0-9- ]+"/> </td></tr>
						<tr><td> <p> Indurance Address </p> </td> <td> <input type="text" name="addr1" size="25" pattern="[A-Za-z0-9- #]+" value="<?php echo "$ins_address[ins_st_addr1]";  ?>"/> <br> <input type="text" name="addr2" size="25" pattern="[A-Za-z0-9- #]+" value="<?php echo "$ins_address[ins_st_addr2]";?>" /> <br> <input type="text" name="city" size="25" placeholder="City" pattern="[A-Za-z- ]+" value="<?php echo "$ins_address[ins_city]"; ?>" /> <br> <input type="text" name="state" size="25" placeholder="State" pattern="[A-Za-z- ]+" value="<?php echo "$ins_address[ins_state]"; ?>" /> <br> <input type="text" name="zip" size="6"placeholder="Zip" pattern="[0-9]{5}" value="<?php echo "$ins_address[ins_zip]"; ?>" /> </td> </tr>
						<tr><td> </td> <td align="center"> <input style="background: #3366FF; font-size: 14px;" type="submit" value="submit"/> </td> </tr>
					</table>
				</form>				
			</div>
			
			
			<!-- Div for modal popup/ Mask END  -->
				
					
			<header>
				<h1>Medical Billing Process Management</h1>
				<nav>
					<a href="../../panel.php"> &nbsp;HOME &nbsp; &nbsp;</a>
					<a href="../logout.php"> &nbsp;LOGOUT &nbsp; &nbsp;</a>
				</nav>
			</header>
			
			<!-- Central  panel -->
			<div id="panel">
				<h2></h2>
				<h3 align="center"><?php echo "$patientFname $patientLname";  ?></h3>
				<nav align="center">
					<button id="basic">Basic</button>
					<button id="insurance">Insurance</button>
					<button id="visits">Visits</button>
				</nav>							
																
				<table id="table-3" class="basic">
					<tr><th>Basic Information <a id="basic_edit"  href="#">(Edit)</a></th><th></th></tr>
					<tr><td> <p> First name: </p> </td> <td> <?php echo "<p>  $patientFname </p>";  ?> </td></tr>
					<tr><td> <p> Last name: </p> </td> <td> <?php echo "<p>  $patientLname </p>";  ?> </td></tr>
					<tr><td> <p> Date Of Birth: </p> </td> <td> <?php echo ("<p>".  date('m-d-Y', $dob->sec). "</p>"); ?> </td></tr>
					<tr><td> <p> Phone: </p> </td> <td> <?php echo "<p>  $phone </p>";  ?> </td></tr>
					<tr><td> <p> Email: </p> </td> <td> <?php echo "<p>  $email </p>";  ?> </td></tr>
					<tr><td> <p> Address: </p> </td> <td> <?php echo "<p>  $address[st_addr1] <br> $address[st_addr2] <br> $address[city] <br> $address[state] <br> $address[zip] </p>";  ?> </td></tr>
					<tr><td> <p> Primary Insurance Holder: </p> </td> <td> <?php echo "<p>  $primaryName </p>";  ?> </td></tr>
					<tr><td> <p> Primary Insurance Holder DOB: </p> </td> <td> <?php echo ("<p>".  date('m-d-Y', $primary_dob->sec). "</p>");  ?> </td></tr>
					<tr><td> <p> Additional Notes: </p> </td> <td> <?php echo "<p> $notes </p>";  ?> </td></tr>
				</table>
				
				<table id="table-3" class="insurance">
					<tr><th>Insurance Information <a id="insurance_edit" href="#">(Edit)</a></th><th></th></tr>
					<tr><td> <p> Insurance Name: </p> </td> <td> <?php echo "<p>  $insurance_name </p>";  ?> </td></tr>
					<tr><td> <p> Insurance ID: </p> </td> <td> <?php echo "<p>  $insurance_id </p>";  ?> </td></tr>
					<tr><td> <p> Address: </p> </td> <td> <?php echo "<p>  $ins_address[ins_st_addr1] <br> $ins_address[ins_st_addr2] <br> $ins_address[ins_city] <br> $ins_address[ins_state] <br> $ins_address[ins_zip] </p>";  ?> </td></tr>
				</table>	
				
				<table id="table-3" class="visits">
					<tr><th>Visits <a  href="../../form/V_form.php">(Add Visit)</a> </th><th></th><th></th></tr>
					<tr> <td><p style="color:black; font-size: 16px;" > Date Of Service </p></td> <td><p style="color:black; font-size: 16px;"> Procedure Code </p></td> <td><p style="color:black; font-size: 16px;"> Billing Date </p></td><td><a href="../reports/detail_report.php?fname=<?php echo "$patientFname";  ?>&lname=<?php echo "$patientLname";  ?>&dob=<?php echo(date('Y-m-d', $dob->sec));  ?>" target="_blank" > <img src="../../images/report2.gif" alt="Full Report" title="Full Report" width="35" height="35"/></a></td> </tr>
					<?php foreach($visits as $key => $visit) {
						
						/* Check if  bill date are empy??. If they are, then display empty value */
						if ( $visit['bill_date']== "")
							$bill_date = "";
						else $bill_date = date('m-d-Y', $visit['bill_date']->sec);
	
						/* <!-- For Each visit, check if  pay check is recieved and copay is not due, else display them in diff color --> */
						if ( preg_match('/[0-9- ]{5,}/', $visit['chk_number'] ) == 0 )
							echo ('<tr ><td><p style="color:red;">' . date('m-d-Y', $visit['dos']->sec) . '</p> </td> <td> <p style="color:red;">' . $visit['p_code'] . '</p> </td> <td><p style="color:red;">' . $bill_date . '</p> </td> <td> <a class="visit_edit_pic" id="visitTable-'.$key.'" href="#"> <img src="../../images/edit.png" alt="Edit" title="Edit" width="25" height="25"/> </a> </td> </tr>');
						else if (  ($visit['copay'] > 0 ) && ( strcmp($visit['is_copay_due'], "Yes")== 0 ) )
							echo ('<tr ><td><p style="color:blue;">' . date('m-d-Y', $visit['dos']->sec) . '</p> </td> <td> <p style="color:blue;">' . $visit['p_code'] . '</p> </td> <td><p style="color:blue;">' . $bill_date . '</p> </td> <td> <a class="visit_edit_pic" id="visitTable-'.$key.'" href="#"> <img src="../../images/edit.png" alt="Edit" title="Edit" width="25" height="25"/> </a> </td> </tr>');
						else 
							echo ("<tr><td><p>" . date('m-d-Y', $visit['dos']->sec) . "</p> </td> <td> <p>" . $visit['p_code'] . "</p> </td> <td><p>" . date('m-d-Y', $visit['bill_date']->sec) . '</p> </td> <td> <a class="visit_edit_pic" id="visitTable-'.$key.'" href="#"> <img src="../../images/edit.png" alt="Edit" title="Edit" width="25" height="25"/></a> </td> </tr>');	
						}
					?>			    
				</table>
				
				
				
				
				<!--  Visit tables -->
				<?php foreach($visits as $key => $visit) {
					/* Check if  bill date  and pay date are empy??. If they are, then display empty value */
					if ( $visit['bill_date']== "")
						$bill_date = "";
					else $bill_date = date('m-d-Y', $visit['bill_date']->sec);
					if ( $visit['pay_date']== "")
						$pay_date = "";
					else $pay_date = date('m-d-Y', $visit['pay_date']->sec);
						
					echo ('<form  autocomplete="on" accept-charset="UTF-8" method="post" action="edit_patient_info.php" id="visitForm-'.$key.'" onsubmit="return validate_visit_fields(this)">');
					echo ('<input type="hidden" name="form_type" value="visit">');
					echo ('<input type="hidden" name="patient_fname" value="' . $patientFname .'">');
					echo ('<input type="hidden" name="patient_lname" value="' . $patientLname .'">');
					echo ('<input type="hidden" name="patient_dob" value="' . date('Y-m-d', $dob->sec) .'">');
					echo ('<input type="hidden" name="visit_number" value="' . $key .'">');
					echo ('<input type="hidden" name="orig_a_code"  value="'.$visit['a_code']. '"/>');
					echo ('<input type="hidden" name="orig_billdate"  value="'.$bill_date. '"/>');
					echo ('<input type="hidden" name="orig_amountcharged" value="'.$visit['amount_charged']. '"/>');
					echo ('<input type="hidden" name="orig_copay" value="'.$visit['copay']. '"/>');
					echo ('<input type="hidden" name="orig_is_copay_due" value="'.$visit['is_copay_due']. '"/>');
					echo ('<input type="hidden" name="orig_credit"  value="'.$visit['credit']. '"/>');
					echo ('<input type="hidden" name="orig_deductible"  value="'.$visit['deductible']. '"/>');
					echo ('<input type="hidden" name="orig_amount_paid" value="'.$visit['amount_paid']. '"/>');
					echo ('<input type="hidden" name="orig_chk_number" value="'.$visit['chk_number']. '"/>');
					echo ('<input type="hidden" name="orig_paydate"  value="'.$pay_date. '"/>');
					echo ('<input type="hidden" name="orig_notes" value="'.$visit['notes']. '"/>');
					echo ('<input type="hidden" name="orig_dos" value="'.date('m-d-Y', $visit['dos']->sec). '"/>');
					
					echo ('<table id="table-3" class="visitTable-'.$key.'">');
					echo ('<tr><th>Visit</th><th></th><th></th></tr>');
					echo ('<tr> <td><p> Date of Service </p></td> <td><p>' . date('m-d-Y', $visit['dos']->sec) . '</p></td> </tr>');
					echo ('<tr> <td><p> Diagnosis Code </p></td> <td><p>' . $visit['d_code'] . '</p></td> </tr>');
					echo ('<tr> <td><p> Procedure Code </p></td> <td><p>' . $visit['p_code'] . '</p></td> </tr>');
					
					echo ('<tr> <td><p> Authorization Code </p></td> <td><input type="text" name="a_code" pattern="[A-Za-z0-9]+" value="'.$visit['a_code']. '"/> </td> </tr>');
					echo ('<tr> <td><p> Billing Date </p></td> <td><input type="text" name="billdate" size="15"  pattern="[0-9- ]{6,10}" value="'.$bill_date. '" placeholder="mm-dd-yyyy"/> </td> </tr>');
					echo ('<tr> <td><p> Amount Charged to Insurance </p></td> <td><input type="text" name="amountcharged" size="15"  pattern="[0-9\. ]+" value="'.$visit['amount_charged']. '"/> </td> </tr>');
					echo ('<tr> <td><p> Patient Copay </p></td> <td><input type="text" name="copay" size="10"  pattern="[0-9\. ]+" value="'.$visit['copay']. '"/> </td> </tr>');
					echo ('<tr> <td><p> Is Copay Due &nbsp;:&nbsp;&nbsp;'. $visit['is_copay_due'] . '</p></td> <td> Change to <select title="Is copay due" name="is_copay_due"> <option value="noChange"></option> <option value ="Yes">YES</option><option value ="No">NO</option> <option value ="Omit">OMIT</option> </select> </td> </tr>');
					echo ('<tr> <td><p> Available Credit </p></td> <td><input type="text" name="credit" size="15"  pattern="[0-9\. ]+" value="'.$visit['credit']. '"/> </td> </tr>');
					echo ('<tr> <td><p> Deductible </p></td> <td><input type="text" name="deductible" size="10"  pattern="[0-9\. ]+" value="'.$visit['deductible']. '"/> </td> </tr>');
					echo ('<tr> <td><p> Amount Paid by Insurance </p></td> <td><input type="text" name="amount_paid" size="15"  pattern="[0-9\. ]+" value="'.$visit['amount_paid']. '"/> </td> </tr>');
					echo ('<tr> <td><p> Check Number  </p></td> <td><input type="text" name="chk_number" size="25"  pattern="[0-9, -]+" value="'.$visit['chk_number']. '"/> </td> </tr>');
						/* Get doctor name */
						$doc = $collection_doc->findOne( array( "_id" => new MongoId($visit['doctor_id']) ), array("fname" => 1, "lname" => 1) );
						$doc_name = $doc['fname']. " " . $doc['lname'];	
					echo ('<tr> <td><p> Pay Date </p></td> <td><input type="text" name="paydate" size="15"  pattern="[0-9- ]{6,10}" value="'.$pay_date. '" placeholder="mm-dd-yyyy"/> </td> </tr>');	
					echo ('<tr> <td><p> Doctor </p></td> <td><p>' . $doc_name . '</p></td> </tr>');
					echo ('<tr> <td><p> Additional Notes </p></td> <td><textarea name="notes" rows="5" cols="30">'.$visit['notes']. '</textarea> </td> </tr>');
					echo ('<tr> <td></td> <td align="center"> <input style="background: #3366FF; font-size: 14px;" type="button" value="Cancel" id="cancel_button" /> <input style="background: #3366FF; font-size: 14px;" type="submit" value="Save Changes"/> </td> </tr>'); 
					echo ('</table>');	
					echo ('</form>');
				} ?>
					
			</div>
			
			<footer>
				<p>
					&copy; Copyright  by lsandhu
				</p>
			</footer>
		</div>
	</body>
</html>	