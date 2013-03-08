<?php
   try{
		$connection = new Mongo();
		$db = $connection->mbdata;
		$collection = $connection->mbdata->test;
		//$v = 1;
		//$z = 1903;
		$aa = array('visits.1.payment' => '111') ;
		$date = new MongoDate(strtotime(" "));
		//$date1 = new MongoDate(strtotime($z."-03-15 00:00:00"));
		//echo date('m-d-Y', $date->sec); 
		$aa = array_merge($aa, array('visits.1.service_date' => $date));	
	    $return = $collection->update( array("name" => "Kam"), array('$set' => $aa), array("safe" => true) );
		
		
	//	var_dump(iterator_to_array($result));
		
		var_dump($return);
		if ($return['updatedExisting']=== true){
			echo "<script type='text/javascript'>alert('Patient Information updated succesfully')</script>";
			echo "<script type='text/javascript'>window.history.back()</script>";		
		}
		else {
			echo '<script type="text/javascript">alert("Failed to update database: \n Error'. $return['err'].'")</script>';
			echo "<script type='text/javascript'>window.history.back()</script>";
		}
	}
	catch (MongoConnectionException $e) {
		die('Error connecting to MongoDB server');
	}
	catch (MongoException $e) {
		die('Error: ' . $e->getMessage());
	}
	
?>

