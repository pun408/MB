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
	
	function go_back($f_name, $l_name) {
		echo '<form name="myform" method="post" action="doctor_info.php">
					<table>
						<tr><td> <input type="hidden" name="first" size="25" value="'. $f_name .'"/></td> <td> <input type="hidden" name="last" size="25" value="'. $l_name .'"/></td></tr>
						<tr><td><input style="display: none;" type="submit" value="submit"/></td></tr>
					</table>
				</form> 
				<script type="text/javascript">document.myform.submit();</script> ';
	}
	
	$doctorFname = htmlspecialchars($_POST['doctor_fname']);
	$doctorLname = htmlspecialchars($_POST['doctor_lname']);
	
	$query_newobj = array();
	$address = array();
	
		if( strlen($_POST['first']) > 0  )
			$query_newobj["fname"] = htmlspecialchars($_POST['first']);
		if( strlen($_POST['last']) > 0  )
			$query_newobj["lname"] = htmlspecialchars($_POST['last']);
		if( strlen($_POST['phone']) > 0  )
			$query_newobj["phone"] = htmlspecialchars($_POST['phone']);
		if( strlen($_POST['email']) > 0  )
			$query_newobj["email"] = htmlspecialchars($_POST['email']);
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
		if( strlen($_POST['tid']) > 0  )
			$query_newobj['t_id'] = htmlspecialchars($_POST['tid']);
		if( strlen($_POST['npi']) > 0  )
			$query_newobj['npi'] = htmlspecialchars($_POST['npi']);
		if( strlen($_POST['commision']) > 0  )
			$query_newobj['commision'] = htmlspecialchars($_POST['commision']);
		
		if (count($query_newobj) == 0 ){
			echo "<script type='text/javascript'>alert('NOTHING TO BE CHANGED....!')</script>";
			go_back($doctorFname, $doctorLname);
		}
		
	try{
		$connection = new Mongo();
		$db = $connection->mbdata;
		$collection = $connection->mbdata->doctors;
		
		$doc = $collection->findOne( array( "fname" => $doctorFname, "lname" => $doctorLname ), array("_id" => 1) );
		if ( $doc === NULL){
			echo "<script type='text/javascript'>alert('Error : NO SUCH DOCTOR')</script>";
			echo "<script type='text/javascript'>window.history.back()</script>";
			exit;
		}
		$doc_id = $doc['_id'];
		
		$query_criteria = array("_id" => $doc_id);
		
		$return = $collection->update( $query_criteria, array('$set' => $query_newobj ), array("safe" => true) );
		
		if ($return['updatedExisting']=== true){
			echo "<script type='text/javascript'>alert('SUCCESSFUL::  Doctor Information updated succesfully')</script>";
			go_back($_POST['first'], $_POST['last']);
		}
		else {
			echo '<script type="text/javascript">alert("FAILED to update database....! :  \n Error'. $return['err'].'")</script>';
			go_back($doctorFname, $doctorLname);
		}
	}
	catch (MongoConnectionException $e) {
		die('Error connecting to MongoDB server');
	}
	catch (MongoException $e) {
		die('Error: ' . $e->getMessage());
	}
?>