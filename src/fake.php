<?php

	include 'dbcred.php';

	require_once '../vendor/fzaninotto/faker/src/autoload.php';
	date_default_timezone_set('America/Toronto');
	$fker = Faker\Factory::create();

	$y = date('y');
	$m = date('n');
	$range = array('Under 16', '16-19', '20-25', '26-45', '46-65', '65+');
	$gen = array('Male', 'Female', 'Transgender');
	$char = array('Bail', 'Plea', 'Trial');
	$branch = array('Brantford Police', 'Brant County OPP', 'Six Nations Police');
	$chilOut = array('Child Victim', 'Witness/Heard');

	

	

		try{	
			function checkDuplicate($cid, $db)
			{

			    try
				{
					$sq = "SELECT * FROM clients t1 JOIN accused t2 ON t1.client_id = t2.client_id JOIN referal t3 ON t1.client_id = t3.client_id JOIN children t4 ON t1.client_id = t4.client_id WHERE t1.client_id='".$cid."' LIMIT 1";
					$stmt = $db->query($sq);
					
					while($row = $stmt->fetch(PDO::FETCH_ASSOC))
					{
						if($row['client_id'] == $cid)
						{
							return true;
						}else
						{
							return false;
						}
						
					}
					
				}catch(PDOException $ex) {
		        	print_r($db->errorInfo());
		    	}

				
			}
	    for ($i=0; $i < 15000; $i++) { 

		    	$client_id = 'C'.$i.$y.$m.$fker->numberBetween($min = 20, $max = 900);
			    $client_name = $fker->firstName($gender = null|'male'|'female');
			    $client_surname = $fker->lastName;
			    $client_dob = $fker->date('Y-m-d', 'now');
			    $client_home_phone = $fker->phoneNumber;
			    $client_alt_phone = $fker->phoneNumber;
			    $client_address = $fker->streetAddress;
			    $client_city = $fker->city;
			    $client_postal = $fker->postcode;
			    $client_indigenous = $fker->boolean;
			    $client_intake_date = $fker->date('Y-m-d', 'now');
			    $client_age_range = $range[rand(0,5)];
			    $client_gender = $gen[rand(0,2)];
			    $client_alt_contact = $fker->name;
			    $client_notes = $fker->text($maxNbChars = 50);
			    $police_id = $fker->numberBetween($min = 20, $max = 900);
			  
			

			    $children_names = $fker->firstName($gender = null|'male'|'female').', '.$fker->firstName($gender = null|'male'|'female').', '.$fker->firstName($gender = null|'male'|'female');
			    $children_outcome = $chilOut[rand(0,1)];
			    $children_cas_notified_date = $fker->date('Y-m-d', 'now');
			    $children_cas_worker = $fker->name;




			   
			    	
			    	$sql = "INSERT INTO clients (client_id, client_name, client_surname, client_dob, client_home_phone, client_alt_phone, client_address, client_city, client_postal, client_indigenous, client_intake_date, client_age_range, client_gender, client_alt_contact, client_notes, police_incident_id, timestamp) VALUES('".$client_id."', '".$client_name."', '".$client_surname."', '".$client_dob."', '".$client_home_phone."', '".$client_alt_phone."', '".$client_address."', '".$client_city."', '".$client_postal."', '".$client_indigenous."', '".$client_intake_date."', '".$client_age_range."', '".$client_gender."', '".$client_alt_contact."', '".$client_notes."', '".$police_id."', 'CURRENT_TIMESTAMP')";

			        $sql3 = "INSERT INTO children (client_id, children_names, children_cas_notified_date, children_cas_worker, children_outcome) VALUES('".$client_id."', '".$children_names."', '".$children_cas_notified_date."', '".$children_cas_worker."', '".$children_outcome."')";

			    
		                    $db->query($sql);
		                    $db->query($sql3);



			}
			echo 'Success!';
			}
             catch(PDOException $ex) {
	        	//print_r($db->errorInfo());
	    	}
			



        
    

    

	

  





?>
