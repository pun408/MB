<?php 
	session_start();
	$login_failed = "Login Failed !";
	if(!isset($_SESSION['myusername']) || $_SESSION['logged'] != "1True") {
		echo "<script type='text/javascript'>alert('$login_failed')</script>";
		header("location:index.html");
	}
?>


<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
		Remove this if you use the .htaccess -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<title>MB Home</title>
		<meta name="description" content="Medical Business Process Management" />
		<meta name="author" content="lsandhu" />
		<meta name="viewport" content="width=device-width; initial-scale=1.0" />
		<!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
		<script type="text/javascript" src="form/view.js"></script>
		<script type="text/javascript" src="form/calendar.js"></script>
		<link rel="shortcut icon" href="/favicon.ico" />
		<link rel="apple-touch-icon" href="/apple-touch-icon.png" />
		<link rel="stylesheet" type="text/css" href="css/panel.css" />
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
		
		<script type="text/javascript"> 
		$(document).ready( function(){
			/* Hide the central modal forms */
			$("div#center_panel").hide();
		    /* Common function for modal window display */
			var modalWin = function(arg) {
				/* Get window height and width  */
				var maskHeight = $(document).height();
				var maskWidth = $(window).width();	
			
				/* Set height and width to mask to fill up the whole screen */
				$('#mask').css({'width':maskWidth,'height':maskHeight});
				
				/* transition effect */     
				$('#mask').fadeIn(500);    
				$('#mask').fadeTo("slow",0.7);  
				
				/* Get the window height and width */
				var winH = ( ($(document).height())/2 )-120;
				var winW = ( ($(document).width())/2 )-225;
								
				/* set the location of popup almost halfway from the top */
				$("."+arg).css("top", ( parseInt(winH, 10) ) + "px");
				$("."+arg).css("left", ( parseInt(winW, 10) ) + "px");
				
				/* transition effect */
				$("div."+arg).fadeIn(1000);	
			};
			
			
			/* individual functions for modal window display */
			$("#edit_patient").click(function(){
        		modalWin("edit_patient");
			});	
			
			$("#edit_doctor").click(function(){
        		modalWin("edit_doctor");
			});	
				
			$("#invoice").click(function(){
        		modalWin("invoice");
			});	
						
    		/* if mask is clicked */
			$('#mask').click(function () {
				$(this).hide();
				$("div.edit_patient").hide();
				$("div.edit_doctor").hide(); 
			}); 
		});
		
	</script>
	</head>
	<body>
		<div>
			
			<!-- Div for modal popup START  -->
			<div id="mask"></div>
			<div id="center_panel" class="edit_patient">
				<form  class="center1" autocomplete="on" accept-charset="UTF-8" method="post" action="scripts/edit_scripts/patient_info.php">
					<table id="table-2">
						<tr><td> <h4>First</h4></td><td><h4>Last</h4></td></tr>
						<tr><td> <input type="text" name="first" size="25" autofocus="autofocus"/></td> <td> <input type="text" name="last" size="25"/></td></tr>
						<tr><td></td><td align="right"><input type="submit" value="submit"/></td></tr>
					</table>
				</form>			
			</div>
			<div id="center_panel" class="edit_doctor">
				<form  class="center1" autocomplete="on" accept-charset="UTF-8" method="post" action="scripts/edit_scripts/doctor_info.php">
					<table id="table-2">
						<tr><td> <h4>First</h4></td><td><h4>Last</h4></td></tr>
						<tr><td> <input type="text" name="first" size="25" autofocus="autofocus"/></td> <td> <input type="text" name="last" size="25"/></td></tr>
						<tr><td></td><td align="right"><input type="submit" value="submit"/></td></tr>
					</table>
				</form>			
			</div>
			<!-- Div for modal popup END  -->
			
			<header>
				<h1>Medical Billing Process Management</h1>
				<nav>
					<a href="panel.php"> &nbsp;HOME &nbsp; &nbsp;</a>
					<a href="scripts/logout.php"> &nbsp;LOGOUT &nbsp; &nbsp;</a>
				</nav>
			</header>	
			
			<div id="panel" >
				
				<table class="center" summary="buttons" border="0px">
					<tr id="pane_table_tr">
						<td><div> <a class="myButton" href="form/D_form.php"> Add Doctor &nbsp; </a> </div></td>
						<td><div> <a class="myButton" href="form/P_form.php"> Add Patient </a> </div></td>
						<td><div><a class="myButton" href="form/V_form.php">&nbsp; Add Visit &nbsp; </a> </div></td>
						<td><div><a class="myButton" href="modal_forms/patient_name_form.html">&nbsp;  Reserved &nbsp; &nbsp; </a> </div></td>
					</tr>
					<tr id="pane_table_tr">
						<td><div> <a class="myButton" id="edit_doctor" href="#"> Edit Doctor &nbsp; </a> </div></td>
						<td><div> <a class="myButton" id="edit_patient" href="#" > Edit Patient </a> </div></td>
						<td><div><a class="myButton" href="scripts/reports/report_home.php">&nbsp;  Reports  &nbsp; &nbsp; </a> </div></td>
						<td><div><a class="myButton" href="scripts/reports/invoice_home.php">&nbsp; &nbsp; Invoice &nbsp; &nbsp; </a> </div></td>
					</tr>
					<tr id="pane_table_tr"></tr>
					<tr id="pane_table_tr">
						<td align="right"><h2>$ Billed &nbsp;</h2></td><td align="left"><h2 id="r">455000</h2></td><td align="right"><h2>$ Approved &nbsp;</h2></td><td align="left"><h2 id="r">342000</h2></td>
					</tr>
				</table>
				<!-- <img class="center" src="images/graph.jpg" height="250px" width="300px" /> -->
			</div>
			
			<footer>
				<p>
					&copy; Copyright  by lsandhu &nbsp; &nbsp; Tel &amp; Fax 510 445 1675
				</p>
			</footer>
		</div>
		
	</body>
</html>
