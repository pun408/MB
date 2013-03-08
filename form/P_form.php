<?php 
	session_start();
	$login_failed = "Invalid Access !";
	if(!isset($_SESSION['myusername']) || $_SESSION['logged'] != "1True") {
		echo "<script type='text/javascript'>alert('$login_failed')</script>";
		header("location:../index.html");
	}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Add New Patient</title>
<link rel="stylesheet" type="text/css" href="view.css" media="all">
<script type="text/javascript" src="view.js"></script>
<script type="text/javascript" src="calendar.js"></script>
</head>
<body id="main_body" >
	<header>
				<h3>Medical Billing Process Management</h3>
				<nav>
					<a href="../panel.php" style="text-decoration:none; font-size:12pt; color:#000000"> &nbsp;HOME &nbsp; &nbsp;</a>
					<a href="scripts/logout.php" style="text-decoration:none; font-size:12pt; color:#000000"> &nbsp;LOGOUT &nbsp; &nbsp;</a>
				</nav>
	</header>
	<img id="top" src="top.png" alt="">
	<div id="form_container">
	
		<h1><a>Add New Patient</a></h1>
		<form id="" class="apps"  method="post" action="../scripts/add_patient.php" accept-charset="utf-8" autocomplete="on">
					<div class="form_description">
			<h2>Add New Patient</h2>
			<p>Please fill out patient and patient's insurance information.</p>
		</div>						
			<ul >
			
					<li id="li_1" >
		<label class="description" for="element_1">Patient Name </label>
		<span>
			<input pattern="[A-Za-z ]+" required name= "fname" class="element text" maxlength="255" size="8" value=""/>
			<label>First</label>
		</span>
		<span>
			<input pattern="[A-Za-z ]+" required name= "lname" class="element text" maxlength="255" size="14" value=""/>
			<label>Last</label>
		</span> 
		</li>		<li id="li_2" >
		<label class="description" for="element_2">Patient DOB </label>
		<span>
			<input id="element_2_1"  pattern="[0-9]{2}" required name="p_dob_mm" class="element text" size="2" maxlength="2" value="" type="text"> /
			<label for="element_2_1">MM</label>
		</span>
		<span>
			<input id="element_2_2"  pattern="[0-9]{2}" required name="p_dob_dd" class="element text" size="2" maxlength="2" value="" type="text"> /
			<label for="element_2_2">DD</label>
		</span>
		<span>
	 		<input id="element_2_3"  pattern="[0-9]{4}" required name="p_dob_yyyy" class="element text" size="4" maxlength="4" value="" type="text">
			<label for="element_2_3">YYYY</label>
		</span>
	
		<span id="calendar_2">
			<img id="cal_img_2" class="datepicker" src="calendar.gif" alt="Pick a date.">	
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
		 
		</li>		<li id="li_3" >
		<label class="description" for="element_3">Patient Address </label>
		
		<div>
			<input pattern="[A-Za-z0-9-# ]+" required name="st_addr1" class="element text large" value="" type="text">
			<label for="element_3_1">Street Address</label>
		</div>
	
		<div>
			<input pattern="[A-Za-z0-9-# ]+"  name="st_addr2" class="element text large" value=" " type="text">
			<label for="element_3_2">Address Line 2</label>
		</div>
	
		<div class="left">
			<input pattern="[A-Za-z- ]+" required name="city" class="element text medium" value="" type="text">
			<label for="element_3_3">City</label>
		</div>
	
		<div class="right">
			<input pattern="[A-Za-z- ]+" required name="state" class="element text medium" value="" type="text">
			<label for="element_3_4">State</label>
		</div>
	
		<div class="left">
			<input pattern="[0-9]{5}" required name="zip" class="element text medium" maxlength="15" value="" type="text">
			<label for="element_3_5">Postal / Zip Code</label>
		</div>
	
		<div class="right">
			<select class="element select medium" id="element_3_6" name="country"> 
			<option value="" selected="selected"></option>

				<option value="Canada" >Canada</option>
				<option value="United States" selected="selected">United States</option>

	
			</select>
		<label for="element_3_6">Country</label>
	</div> 
		</li>		<li id="li_4" >
		<label class="description" for="element_4">Patient's phone </label>
		<span>
			<input pattern="[0-9]{3}" required name="phone1" class="element text" size="3" maxlength="3" value="" type="text"> -
			<label for="element_4_1">(###)</label>
		</span>
		<span>
			<input pattern="[0-9]{3}" required name="phone2" class="element text" size="3" maxlength="3" value="" type="text"> -
			<label for="element_4_2">###</label>
		</span>
		<span>
	 		<input pattern="[0-9]{4}" required name="phone3" class="element text" size="4" maxlength="4" value="" type="text">
			<label for="element_4_3">####</label>
		</span>
		 
		</li>		<li id="li_5" >
		<label class="description" for="element_5">Primary insurance holder's name </label>
		<span>
			<input id="element_5_1" pattern="[A-Za-z ]+" name= "primary_fname" class="element text" maxlength="255" size="8" value=""/>
			<label>First</label>
		</span>
		<span>
			<input id="element_5_2" pattern="[A-Za-z ]+" name= "primary_lname" class="element text" maxlength="255" size="14" value=""/>
			<label>Last</label>
		</span><p class="guidelines" id="guide_5"><small>If patient is not primary insurance holder</small></p> 
		</li>		<li id="li_6" >
		<label class="description" for="element_6">Primary insurance holder's DOB </label>
		<span>
			<input id="element_6_1" pattern="[0-9]{2}" name="primary_dob_mm" class="element text" size="2" maxlength="2" value="" type="text"> /
			<label for="element_6_1">MM</label>
		</span>
		<span>
			<input id="element_6_2" pattern="[0-9]{2}" name="primary_dob_dd" class="element text" size="2" maxlength="2" value="" type="text"> /
			<label for="element_6_2">DD</label>
		</span>
		<span>
	 		<input id="element_6_3" pattern="[0-9]{4}" name="primary_dob_yyyy" class="element text" size="4" maxlength="4" value="" type="text">
			<label for="element_6_3">YYYY</label>
		</span>
	
		<span id="calendar_6">
			<img id="cal_img_6" class="datepicker" src="calendar.gif" alt="Pick a date.">	
		</span>
		<script type="text/javascript">
			Calendar.setup({
			inputField	 : "element_6_3",
			baseField    : "element_6",
			displayArea  : "calendar_6",
			button		 : "cal_img_6",
			ifFormat	 : "%B %e, %Y",
			onSelect	 : selectDate
			});
		</script>
		 
		</li>		<li class="section_break">
			<h3>Enter Insurance information below</h3>
			
		</li>		<li id="li_8" >
		<label class="description" for="element_8">Insurance Name </label>
		<div>
			<input id="element_8" pattern="[A-Za-z ]+" required name="insurance_name" class="element text medium" type="text" maxlength="255" value=""/> 
		</div> 
		</li>		<li id="li_9" >
		<label class="description" for="element_9">Insurance ID </label>
		<div>
			<input id="element_9" pattern="[A-Za-z0-9- ]+" required name="insurance_id" class="element text medium" type="text" maxlength="255" value=""/> 
		</div><p class="guidelines" id="guide_9"><small>Patient's insurance ID</small></p> 
		</li>		<li id="li_10" >
		<label class="description" for="element_10">Insurance Address </label>
		
		<div>
			<input id="element_10_1" pattern="[A-Za-z0-9-# ]+" required name="ins_st_addr1" class="element text large" value="" type="text">
			<label for="element_10_1">Street Address</label>
		</div>
	
		<div>
			<input id="element_10_2" pattern="[A-Za-z0-9-# ]+" name="ins_st_addr2" class="element text large" value=" " type="text">
			<label for="element_10_2">Address Line 2</label>
		</div>
	
		<div class="left">
			<input id="element_10_3" pattern="[A-Za-z- ]+" required name="ins_city" class="element text medium" value="" type="text">
			<label for="element_10_3">City</label>
		</div>
	
		<div class="right">
			<input id="element_10_4" pattern="[A-Za-z ]+" required name="ins_state" class="element text medium" value="" type="text">
			<label for="element_10_4">State</label>
		</div>
	
		<div class="left">
			<input id="element_10_5" pattern="[0-9]{5}" required name="ins_zip" class="element text medium" maxlength="15" value="" type="text">
			<label for="element_10_5">Postal / Zip Code</label>
		</div>
	
		<div class="right">
			<select class="element select medium" id="element_10_6" name="ins_country"> 
			<option value="" selected="selected"></option>

				<option value="United States" selected="selected">United States</option>

			</select>
		<label for="element_10_6">Country</label>
	</div> 
		</li>		<li id="li_11" >
		<label class="description" for="element_11">Additional notes </label>
		<div>
			<textarea id="element_11"  name="notes" class="element text medium"  rows="4" cols="40"> </textarea>
		</div><p class="guidelines" id="guide_11"><small>Please add any additional information/notes</small></p> 
		</li>
		
			
					<li class="buttons">
			    <input type="hidden" name="form_id" value="265172" />
			    
				<input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
		</li>
			</ul>
		</form>	
		<div id="footer">
			
		</div>
	</div>
	<img id="bottom" src="bottom.png" alt="">
	</body>
</html>