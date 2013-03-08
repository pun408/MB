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
		$collection_doc = $connection->mbdata->doctors;
			
		$results = $collection_doc->find($searchQuery);
		
		/* Check if DB returned more than one patient */ 
		$counter = count($results);
		
		if($counter === 0) {
			echo "<script type='text/javascript'>alert('NO SUCH DOCTOR.......!')</script>";
			echo "<script type='text/javascript'>window.history.back()</script>";
		}

		if($counter === 1) {
			foreach($results as $doc) {
				$docFname = $doc['fname'];
				$docLname = $doc['lname'];
				$phone = $doc['phone'];
				$email = $doc['email'];
				$address = $doc['address'];
				$speciality = $doc['speciality'];
				$npi = $doc['npi'];
				$commision = $doc['commision'];
				$t_id = $doc['t_id'];	
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
		<title>Doctor Info</title>
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
			
			<div id="center_panel" class="doctor_edit">
				<h3>&nbsp; &nbsp; Enter the value that you want to change. Leave others blank</h3>
				<form  class="center" autocomplete="on" accept-charset="UTF-8" method="post" action="edit_doctor_info.php" onsubmit="return validate_confirm_doctor(this)">
					<input type="hidden" name="form_type" value="doctor">
					<input type="hidden" name="doctor_fname" value="<?php echo "$docFname";  ?>">
					<input type="hidden" name="doctor_lname" value="<?php echo "$docLname";  ?>">
					<table id="table-2">
						<tr><td> <p> Name </p> </td> <td> <input type="text" name="first" size="20" autofocus="autofocus" placeholder="First name" pattern="[A-Za-z ]+" value="<?php echo "$docFname";  ?>"/>  <input type="text" name="last" size="20" placeholder="Last name" pattern="[A-Za-z ]+" value="<?php echo "$docLname";  ?>"/> </td></tr>
						<tr><td> <p> Phone </p> </td> <td> <input type="tel" name="phone" size="15"/ placeholder="### ### ####" pattern="[0-9 ]+" /> </td></tr>
						<tr><td> <p> Email </p> </td> <td> <input type="email" name="email" size="25" pattern="\w+@[a-zA-z_]+?\.[a-zA-Z]{2,6}"/> </td></tr>
						<tr><td> <p> Address </p> </td> <td> <input type="text" name="addr1" size="25" pattern="[A-Za-z0-9- #]+" value="<?php echo "$address[st_addr1]";  ?>"/> <br> <input type="text" name="addr2" size="25" pattern="[A-Za-z0-9- #]+" value="<?php echo "$address[st_addr2]";?>" /> <br> <input type="text" name="city" size="25" placeholder="City" pattern="[A-Za-z- ]+" value="<?php echo "$address[city]"; ?>" /> <br> <input type="text" name="state" size="25" placeholder="State" pattern="[A-Za-z- ]+" value="<?php echo "$address[state]"; ?>" /> <br> <input type="text" name="zip" size="6"placeholder="Zip" pattern="[0-9]{5}" value="<?php echo "$address[zip]"; ?>" /> </td> </tr>
						<tr><td> <p> NPI Number </p> </td> <td> <input type="text" name="npi" size="20"  pattern="[0-9A-Za-z- ]+"/> </td></tr>
						<tr><td> <p> Tax ID </p> </td> <td> <input type="text" name="tid" size="20"  pattern="[0-9 ]+"/>  </td></tr>
						<tr><td> <p> Commision % </p> </td> <td> <input type="text" name="commision" size="20"  pattern="[0-9 ]+"/>  </td></tr>
						<tr><td> </td> <td align="right" > <input style="background: #3366FF; font-size: 14px;" type="submit" value="submit"/> </td> </tr>
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
				<h3 align="center"><?php echo "$docFname $docLname";  ?></h3>					
																
				<table id="table-3" class="basic">
					<tr><th>Doctor's Information <a id="doctor_edit"  href="#">(Edit)</a></th><th></th></tr>
					<tr><td> <p> First name: </p> </td> <td> <?php echo "<p>  $docFname </p>";  ?> </td></tr>
					<tr><td> <p> Last name: </p> </td> <td> <?php echo "<p>  $docLname </p>";  ?> </td></tr>
					<tr><td> <p> Phone: </p> </td> <td> <?php echo "<p>  $phone </p>";  ?> </td></tr>
					<tr><td> <p> Email: </p> </td> <td> <?php echo "<p>  $email </p>";  ?> </td></tr>
					<tr><td> <p> Address: </p> </td> <td> <?php echo "<p>  $address[st_addr1] <br> $address[st_addr2] <br> $address[city] <br> $address[state] <br> $address[zip] </p>";  ?> </td></tr>
					<tr><td> <p> NPI Number: </p> </td> <td> <?php echo " <p> $npi </p>";  ?> </td></tr>
					<tr><td> <p> Tax ID: </p> </td> <td> <?php echo "<p> $t_id </p>";  ?> </td></tr>
					<tr><td> <p> Commision %: </p> </td> <td> <?php echo "<p> $commision </p>";  ?> </td></tr>
				</table>
			</div>
			
			<footer>
				<p>
					&copy; Copyright  by lsandhu
				</p>
			</footer>
		</div>
	</body>
</html>
			