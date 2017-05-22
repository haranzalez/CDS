<?php

	include 'dbcred.php';
	$ts = array('clients', 'accused', 'calls', 'children');
	try
		{
		for ($i=0; $i < count($ts); $i++) { 
			
			$db->query("TRUNCATE ".$ts[$i]);
			
		}
		echo 'Success!';
	} catch(PDOException $ex) {
	    print_r($db->errorInfo());
	}





?>
