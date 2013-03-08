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
<title>Add Doctor</title>
<link rel="stylesheet" type="text/css" href="view.css" media="all">
<script type="text/javascript" src="view.js"></script>

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
	
		<h1><a>Add Doctor</a></h1>
		<form id="" class="apps"  method="post" action="../scripts/add_doctor.php" accept-charset="utf-8" autocomplete="on">
					<div class="form_description">
			<h2>Add Doctor</h2>
			<p>Please fill out the Doctor's information below</p>
		</div>						
			<ul >
			
					<li id="li_1" >
		<label class="description" for="element_1">Name </label>
		<span>
			<input pattern="[A-Za-z ]+" required name= "fname" class="element text" maxlength="255" size="8" value=""/>
			<label>First</label>
		</span>
		<span>
			<input pattern="[A-Za-z ]+" required name= "lname" class="element text" maxlength="255" size="14" value=""/>
			<label>Last</label>
		</span><p class="guidelines" id="guide_1"><small>Doctor's name</small></p> 
		</li>		<li id="li_2" >
		<label class="description" for="element_2">Address </label>
		
		<div>
			<input pattern="[A-Za-z0-9- ]+" required name="st_addr1" class="element text large" value="" type="text">
			<label for="element_2_1">Street Address</label>
		</div>
	
		<div>
			<input pattern="[A-Za-z0-9-# ]+" name="st_addr2" class="element text large" value=" " type="text">
			<label for="element_2_2">Address Line 2</label>
		</div>
	
		<div class="left">
			<input pattern="[A-Za-z- ]+" required name="city" class="element text medium" value="" type="text">
			<label for="element_2_3">City</label>
		</div>
	
		<div class="right">
			<input pattern="[A-Za-z ]+" required name="state" class="element text medium" value="" type="text">
			<label for="element_2_4">State</label>
		</div>
	
		<div class="left">
			<input pattern="[0-9]{5}" required name="zip" class="element text medium" maxlength="15" value="" type="text">
			<label for="element_2_5">Postal / Zip Code</label>
		</div>
	
		<div class="right">
			<select class="element select medium" id="element_2_6" name="country"> 
			<option value="" selected="selected"></option>
<option value="Canada" >Canada</option>
<option value="France" >France</option>
<option value="Germany" >Germany</option>
<option value="India" >India</option>
<option value="United Kingdom" >United Kingdom</option>
<option value="United States" selected="selected">United States</option>
			</select>
		<label for="element_2_6">Country</label>
	</div><p class="guidelines" id="guide_2"><small>Doctor's Address</small></p> 
		</li>		<li id="li_3" >
		<label class="description" for="element_3">Phone </label>
		<span>
			<input pattern="[0-9]{3}" required name="phone1" class="element text" size="3" maxlength="3" value="" type="text"> -
			<label for="element_3_1">(###)</label>
		</span>
		<span>
			<input pattern="[0-9]{3}" required name="phone2" class="element text" size="3" maxlength="3" value="" type="text"> -
			<label for="element_3_2">###</label>
		</span>
		<span>
	 		<input pattern="[0-9]{4}" required name="phone3" class="element text" size="4" maxlength="4" value="" type="text">
			<label for="element_3_3">####</label>
		</span>
		<p class="guidelines" id="guide_3"><small>Doctor's Phone</small></p> 
		</li>		<li id="li_7" >
		<label class="description" for="element_7">Email </label>
		<div>
			<input pattern="\w+@[a-zA-z_\.]+?\.[a-zA-Z]{2,6}" required name="email" class="element text medium" type="text" maxlength="255" value=""/> 
		</div><p class="guidelines" id="guide_7"><small>Doctor's email</small></p> 
		</li>		<li id="li_4" >
		<label class="description" for="element_4">Tax ID </label>
		<div>
			<input pattern="[A-Za-z0-9]+" required name="t_id" class="element text medium" type="text" maxlength="255" value=""/> 
		</div><p class="guidelines" id="guide_4"><small>Doctor's tax id</small></p> 
		</li>		<li id="li_5" >
		<label class="description" for="element_5">NPI Number  </label>
		<div>
			<input pattern="[A-Za-z0-9]+" required name="npi" class="element text medium" type="text" maxlength="255" value=""/> 
		</div><p class="guidelines" id="guide_5"><small>Doctor's National provider Number</small></p> 
		</li>		<li id="li_6" >
		<label class="description" for="element_6">Speciality </label>
		<div>
			<input id="element_6" name="speciality" class="element text medium" type="text" maxlength="255" value=""/> 
		</div> 
		</li>		<li id="li_7" >
		<label class="description" for="element_7">Commision %</label>
		<div>
			<input id="element_7" pattern="[0-9]+" required name="commision" class="element text medium" type="text" maxlength="10" value=""/> 
		</div> 
		</li>
			
					<li class="buttons">
			    <input type="hidden" name="form_id" value="268314" />
			    
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