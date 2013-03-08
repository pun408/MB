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
<title>Add Patient Visit</title>
<link rel="stylesheet" type="text/css" href="view_green.css" media="all">
<script type="text/javascript" src="view.js"></script>
<script type="text/javascript" src="calendar.js"></script>
<script type="text/javascript">
	function validate_fields(f) {
			var chk1 = false;
			var chk2 = false;
			var chk3 = true;
			var chk4 = true;
			var chk5 = true;
			var msg = "RULE IS: \n ";
			if ( f.amount_charged.value.length < 1 &&  f.bill_date_mm.value.length < 1 )
				chk1 = true;
			else if(f.amount_charged.value.length > 1 &&  f.bill_date_mm.value.length >= 1)
				chk1 = true;
			else {
				chk1 = false;
				msg = msg + "Please enter BOTH 'Bill date' and 'Amount charged' or NONE.......!\n" ;
			}
			
			if ( f.chk_number.value.length < 1 &&  f.pay_date_mm.value.length < 1  &&  f.ins_paid.value.length < 1 )
				chk2 = true;
			else if ( f.chk_number.value.length > 1 &&  f.pay_date_mm.value.length >= 1  &&  f.ins_paid.value.length >= 1 )
				chk2 = true;
			else {
				chk2 = false;
				msg = msg + "Enter ALL 'Insurance Paid' , 'Check Number' and 'Payment Date' or NONE..... \n";
			}	
				
			if ( f.chk_number.value.length > 1)
				if (f.amount_charged.value.length < 1 ) {
					chk3 = false;
					msg = msg + "If you enter Insurance paid information, Please enter billing information too..\n";
				}
			else 
				chk3 = true;
			
			if (f.pay_date_mm.value.length > 0 && f.bill_date_mm.value.length > 0 && f.dos_mm.value.length > 0) {
				var service_date = new Date(parseInt(f.dos_yyyy.value), parseInt(f.dos_mm.value) - 1, parseInt(f.dos_dd.value));
				var billing_date = new Date(f.bill_date_yyyy.value, f.bill_date_mm.value - 1, f.bill_date_dd.value);
				var pay_day = new Date(f.pay_date_yyyy.value, f.pay_date_mm.value - 1, f.pay_date_dd.value);
				if ( pay_day >= billing_date && billing_date >= service_date )
					chk4 = true; 
				else {
					msg = msg + "'Service Date', 'Billing Date' and 'Pay Date' are NOT in correct order..\n";
					chk4 = false;
				}
			}
			
			if (f.amount_charged.value.length > 0 && f.ins_paid.value.length > 0) {
				if (f.amount_charged.value < f.ins_paid.value){
					chk5 = false;
					msg = msg + "Amount paid by insurance is greater than amount billed.....!\n";
				}
			}
			
			if (chk1 && chk2 && chk3 && chk4 && chk5) return true;
			else {
				alert(msg);
				return false;
			}		
		}
</script>
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
	
		<h1><a>Add Patient Visit</a></h1>
		<form id="" class="apps" method="post" action="../scripts/add_visit.php" accept-charset="utf-8" autocomplete="on" onsubmit="return validate_fields(this)">
					<div class="form_description">
			<h2>Add Patient Visit</h2>
			<p>Enter patient visit description below</p>
		</div>						
			<ul >
			
					<li id="li_1" >
		<label class="description" for="element_1">Name </label>
		<span>
			<input id="element_1_1" pattern="[A-Za-z ]+" required name= "p_fname" class="element text" maxlength="255" size="8" value="" autofocus="autofocus"/>
			<label>First</label>
		</span>
		<span>
			<input id="element_1_2" pattern="[A-Za-z ]+" required name= "p_lname" class="element text" maxlength="255" size="14" value=""/>
			<label>Last</label>
		</span><p class="guidelines" id="guide_1"><small>Patient's First and last name</small></p> 
		</li>		<li id="li_12" >
		<label class="description" for="element_12">Diagnostic code </label>
		<div>
			<input id="element_12" pattern="[A-Za-z0-9]+" required name="d_code" class="element text medium" type="text" maxlength="255" value=""/> 
		</div> 
		</li>		<li id="li_2" >
		<label class="description" for="element_2">Date of service </label>
		<span>
			<input id="element_2_1" pattern="[0-9]{1,2}" required name="dos_mm" class="element text" size="2" maxlength="2" value="" type="text"> /
			<label for="element_2_1">MM</label>
		</span>
		<span>
			<input id="element_2_2" pattern="[0-9]{1,2}" required name="dos_dd" class="element text" size="2" maxlength="2" value="" type="text"> /
			<label for="element_2_2">DD</label>
		</span>
		<span>
	 		<input id="element_2_3" pattern="[0-9]{4}" required name="dos_yyyy" class="element text" size="4" maxlength="4" value="" type="text">
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
		<p class="guidelines" id="guide_2"><small>Date when Service was provided to the patient</small></p> 
		</li>		<li id="li_3" >
		<label class="description" for="element_3">Procedure code </label>
		<div>
			<input id="element_3" pattern="[A-Za-z0-9]+" required name="p_code" class="element text medium" type="text" maxlength="255" value=""/> 
		</div> 
		</li>		<li id="li_3" >
		<label class="description" for="element_3_5">Authorization code </label>
		<div>
			<input id="element_3_5" pattern="[A-Za-z0-9]+"  name="a_code" class="element text medium" type="text" maxlength="255" value=""/> 
		</div> 
		</li>		<li id="li_4" >
		<label class="description" for="element_4">Charged Amount </label>
		<div>
			<input id="element_4" pattern="[0-9,\.]+"  name="amount_charged" class="element text medium" type="text" maxlength="255" value=""/> 
		</div><p class="guidelines" id="guide_4"><small>Change the charged amount if necessary</small></p> 
		</li>		<li id="li_5" >
		<label class="description" for="element_5">Billing Date </label>
		<span>
			<input id="element_5_1" pattern="[0-9]{1,2}" name="bill_date_mm" class="element text" size="2" maxlength="2" value="" type="text"> /
			<label for="element_5_1">MM</label>
		</span>
		<span>
			<input id="element_5_2" pattern="[0-9]{1,2}"  name="bill_date_dd" class="element text" size="2" maxlength="2" value="" type="text"> /
			<label for="element_5_2">DD</label>
		</span>
		<span>
	 		<input id="element_5_3" pattern="[0-9]{4}"  name="bill_date_yyyy" class="element text" size="4" maxlength="4" value="" type="text">
			<label for="element_5_3">YYYY</label>
		</span>
	
		<span id="calendar_5">
			<img id="cal_img_5" class="datepicker" src="calendar.gif" alt="Pick a date.">	
		</span>
		<script type="text/javascript">
			Calendar.setup({
			inputField	 : "element_5_3",
			baseField    : "element_5",
			displayArea  : "calendar_5",
			button		 : "cal_img_5",
			ifFormat	 : "%B %e, %Y",
			onSelect	 : selectDate
			});
		</script>
		<p class="guidelines" id="guide_5"><small>Date on which  insurance was billed</small></p> 
		</li>		<li id="li_6" >
		<label class="description" for="element_6">Insurance Paid </label>
		<div>
			<input id="element_6" pattern="[0-9,\.]+"  name="ins_paid" class="element text medium" type="text" maxlength="255" value=""/> 
		</div><p class="guidelines" id="guide_6"><small>Amount that insurance paid</small></p> 
		</li>		<li id="li_7" >
		<label class="description" for="element_7">Patient Co-pay </label>
		<div>
			<input id="element_7" pattern="[0-9,\.]+" required name="copay" class="element text medium" type="text" maxlength="255" value=""/> 
		</div> 
		</li>		<li id="li_13" >
		<label class="description" for="element_13">Select if copay is due </label>
		<span>
			<select title="Is copay due" class="" name="is_copay_due">
					<option value ="" selected="selected">---</option>
					<option value ="Yes">YES</option>
					<option value ="No">NO</option>
					<option value ="omit">OMIT</option>
				</select>
			<!-- <input id="element_13_1" name="is_copay_due" class="element checkbox" type="checkbox" value="1" />
<label class="choice" for="element_13_1">Copay due</label> -->

		</span> 
		</li>		<li id="li_11" >
		<label class="description" for="element_11">Credit </label>
		<div>
			<input id="element_11" pattern="[0-9,\.]+"  name="credit" class="element text medium" type="text" maxlength="255" value=""/> 
		</div><p class="guidelines" id="guide_11"><small>if any</small></p> 
		</li>		<li id="li_10" >
		<label class="description" for="element_10">Deductible  </label>
		<div>
			<input id="element_10" pattern="[0-9,\. ]+" name="deductible" class="element text medium" type="text" maxlength="255" value=""/> 
		</div><p class="guidelines" id="guide_10"><small>If applicable</small></p> 
		</li>		<li id="li_8" >
		<label class="description" for="element_8">Check # </label>
		<div>
			<input id="element_8" pattern="[0-9, ]+"  name="chk_number" class="element text medium" type="text" maxlength="255" value=""/> 
		</div><p class="guidelines" id="guide_8"><small>Check number sent by Insurance</small></p> 
		</li>		<li id="li_5" >
		<label class="description" for="element_8_5">Payment Date </label>
		<span>
			<input id="element_8_5_1" pattern="[0-9]{1,2}" name="pay_date_mm" class="element text" size="2" maxlength="2" value="" type="text"> /
			<label for="element_8_5_1">MM</label>
		</span>
		<span>
			<input id="element_8_5_2" pattern="[0-9]{1,2}"  name="pay_date_dd" class="element text" size="2" maxlength="2" value="" type="text"> /
			<label for="element_8_5_2">DD</label>
		</span>
		<span>
	 		<input id="element_8_5_3" pattern="[0-9]{4}"  name="pay_date_yyyy" class="element text" size="4" maxlength="4" value="" type="text">
			<label for="element_8_5_3">YYYY</label>
		</span>
	
		<span id="calendar_8_5">
			<img id="cal_img_8_5" class="datepicker" src="calendar.gif" alt="Pick a date.">	
		</span>
		<script type="text/javascript">
			Calendar.setup({
			inputField	 : "element_8_5_3",
			baseField    : "element_8_5",
			displayArea  : "calendar_8_5",
			button		 : "cal_img_8_5",
			ifFormat	 : "%B %e, %Y",
			onSelect	 : selectDate
			});
		</script>
		<p class="guidelines" id="guide_8_5"><small>Date on which insurance made the payment</small></p> 
		</li>	<li id="li_9" >
		<label class="description" for="element_9">Doctor's Name </label>
		<span>
			<input id="element_9_1" pattern="[A-Za-z ]+" required name= "d_fname" class="element text" maxlength="255" size="8" value=""/>
			<label>First</label>
		</span>
		<span>
			<input id="element_9_2" pattern="[A-Za-z ]+" required name= "d_lname" class="element text" maxlength="255" size="14" value=""/>
			<label>Last</label>
		</span> 
		</li>		<li id="li_10" >
		<label class="description" for="element_10">Additional notes </label>
		<div>
			<textarea id="element_10"  name="notes" class="element text medium"  rows="4" cols="40"> </textarea>
		</div><p class="guidelines" id="guide_10"><small>Please add any additional information/notes</small></p> 
		</li>
			
					<li class="buttons">
			    <input type="hidden" name="form_id" value="272509" />
			    
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