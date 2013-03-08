<?php 
	session_start();
	$login_failed = "Invalid Access !";
	if(!isset($_SESSION['myusername']) || $_SESSION['logged'] != "1True") {
		echo "<script type='text/javascript'>alert('$login_failed')</script>";
		header("location:../../index.html");
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
		Remove this if you use the .htaccess -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<title>Report Home</title>
		<meta name="description" content="" />
		<meta name="author" content="lsandhu" />
		<meta name="viewport" content="width=device-width; initial-scale=1.0" />
		<!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
		<script type="text/javascript" src="../../form/view.js"></script>
		<script type="text/javascript" src="../../form/calendar.js"></script>
		<link rel="shortcut icon" href="/favicon.ico" />
		<link rel="apple-touch-icon" href="/apple-touch-icon.png" />
		<link rel="stylesheet" type="text/css" href="../../css/report_home.css" />
		<script type="text/javascript">
			function validate_fields(f) {
				var msg = "";
				var chk1 = true;
				var chk2 = true;
				var from_date = new Date(f.from_yyyy.value, f.from_mm.value - 1, f.from_dd.value);
				var to_date = new Date(f.to_yyyy.value, f.to_mm.value - 1, f.to_dd.value);
				if (from_date > to_date) {
					msg = msg + "'From Date' is later than 'To Date'..... Please correct it.... \n";
					chk1 = false;
				}
				if ( (f.reportFor.value != "nothing") && chk1)
					return true;
				else {
					msg = msg + "Please select whose report do you want to create....\n";
					alert (msg);
					return false
				}
			}
		</script> 
	</head>
	<body>
		<div>
			<header>
				<h1>Medical Billing Process Management</h1>
				<nav>
					<a href="../../panel.php"> &nbsp;HOME &nbsp; &nbsp;</a>
					<a href="../logout.php"> &nbsp;LOGOUT &nbsp; &nbsp;</a>
				</nav>
			</header>
			
			<div id="panel" >
				<h2 align="center">Generate report</h2>
				<form class="center" method="post" action="reports.php" accept-charset="utf-8" autocomplete="on" onsubmit="return validate_fields(this)" target="_blank">
					<div class="formEntry">	
						<label  for="element_1">Dates&nbsp;&nbsp;&nbsp;</label>
						From<input id="element_1_1" pattern="[0-9]{1,2}" required name="from_mm"  size="2" maxlength="2" placeholder="mm" type="text"><input id="element_1_2" pattern="[0-9]{1,2}" required name="from_dd" size="2" maxlength="2" placeholder="dd" type="text"><input id="element_1_3" pattern="[0-9]{4}" required name="from_yyyy" size="4" maxlength="4" placeholder="yyyy" type="text">					
						<span id="calendar_1">
							<img id="cal_img_1" class="datepicker" src="../../form/calendar.gif" alt="Pick a date.">	
						</span>
						<script type="text/javascript">
							Calendar.setup({
								inputField	 : "element_1_3",
								baseField    : "element_1",
								displayArea  : "calendar_1",
								button		 : "cal_img_1",
								ifFormat	 : "%B %e, %Y",
								onSelect	 : selectDate
							});
						</script>
						
						<label  for="element_2">&nbsp;&nbsp;&nbsp;</label>
						To<input id="element_2_1" pattern="[0-9]{1,2}" required name="to_mm" class="" size="2" maxlength="2" value="" placeholder="mm" type="text"><input id="element_2_2" pattern="[0-9]{1,2}" required name="to_dd" class="" size="2" maxlength="2" value="" placeholder="dd" type="text"><input id="element_2_3" pattern="[0-9]{4}" required name="to_yyyy" class="" size="4" maxlength="4" value="" placeholder="yyyy" type="text">
						<span id="calendar_2">
							<img id="cal_img_2" class="datepicker" src="../../form/calendar.gif" alt="Pick a date.">	
						</span>
						<script type="text/javascript">
							Calendar.setup({
								inputField	 : "element_2_3",
								baseField    : "element_2",
								displayArea  : "calendar_2",
								button		 : "cal_img_2",
								ifFormat	 : "%B %e, %Y",
								onSelect	 : selectDate
							});
						</script>
					</div>	
					
					
					<div class="formEntry">	
						Generate Report For? 
						<select title="reportFor" class="" name="reportFor">
							<option value ="nothing" selected="selected"></option>
							<option value ="doctor">Doctor</option>
							<option value ="patient">Patient</option>
						</select> 
					</div>
					
					<div class="formEntry">
						Name<input id="" pattern="[A-Za-z ]+" required name="fname"  maxlength="20" placeholder="First" type="text"/><input id="" pattern="[A-Za-z ]+" required name="lname"  maxlength="30" placeholder="Last" type="text"/></td>
					</div>	
					
					<div class="formEntry" id="patient_options">	
						Report Containing
						<select title="reportFilter" class="" name="reportFilter">
							<option value ="all" selected="selected">Everything</option>
							<option value ="pendingBilling">Visits, for which billing is yet to be done</option>
							<option value ="pendingCopay">Visits, for which co-pay is pending</option>
							<option value ="copayRecieved">Visits, for which co-pay has been recieved/ommited</option>
							<option value ="paymentPending">Visits, for which insurance payment is pending</option>
							<option value ="paymentReceived">Visits, for which insurance payment has been recieved</option>
						</select>		
					</div>
					<div class="formEntry">
						<input class="coolButton" id="" class="" type="submit" name="submit" value="Submit" />
					</div>
				</form>
			</div>
			<footer>
				<p>
					&copy; Copyright  by lsandhu &nbsp; &nbsp; Tel &amp; Fax 510 445 1675
				</p>
			</footer>
		</div>
	</body>
</html>
