<?php 
	

	
	
	class Crud
	{
		public function __construct($db)
		{
			
			$this->_conn = $db;
			$this->_cols = $this->getTheadings();
			$this->_numRows = $this->numOfRows();

		}

		public function genId()
		{
			$y = date('y');
			$m = date('n');

			return $y.$m.rand(100, 1000);
		}

		private function getTheadings()
		{
			$q = "SHOW COLUMNS FROM clients";
			$_cTheadings = $this->_conn->query($q);
			$q2 = "SHOW COLUMNS FROM accused";
			$_aTheadings = $this->_conn->query($q2);
			$q4 = "SHOW COLUMNS FROM children";
			$_chlTheadings = $this->_conn->query($q4);
			return array_merge($_cTheadings->fetchAll(PDO::FETCH_COLUMN), $_aTheadings->fetchAll(PDO::FETCH_COLUMN), $_chlTheadings->fetchAll(PDO::FETCH_COLUMN));
		}
		private function checkIncidentID($iid)
		{
			$q = "SELECT * FROM clients WHERE police_incident_id='".$iid."'";
			$result = $this->_conn->query($q);
			if($result->rowCount() > 0)
			{
				return true;
			}else if($result->rowCount() == 0)
			{
				return false;
			}
		}
		private function numOfRows()
		{
			$q = 'SELECT * FROM clients';
			$result = $this->_conn->query($q);
			return $result->rowCount();
		}

		public function showAll()
		{
			$query = "SELECT * FROM clients";
			$res = $this->_conn->query($query);
			$results = array();
			while($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $results[] = $row;
            }
            $result = new stdClass();
		    $result->data = json_encode($results);

		    return $result;
		}
		public function add($data)
		{
			$iid = $data['police_incident_id'];
			if($this->checkIncidentID($iid) == false)
			{
				try{
				$numItems = count($data);
				$arr = array();
				$sql = "INSERT INTO clients(";
				
				$sql4 = "INSERT INTO children(";
				$cli = array();
				$cliD = array();

				$chi = array();
				$chiD = array();


				foreach ($data as $key => $val) {
					
					
					if(substr($key, 0, 3) == 'cli' || $key == 'police_incident_id')
					{
						$cli[] = $key;
						$cliD[] = $val;
					}
					
					if(substr($key, 0, 3) == 'chi' || $key == 'client_id')
					{
						$chi[] = $key;
						$chiD[] = $val;
					}  
					
				}
				
				$i = 0;
				foreach ($cli as $key => $val) {
					if(++$i === count($cli)) {
                        $sql .= $val.", ";
                        $sql .= "timestamp";
                        
					}else
					{
					  	$sql .= $val.", ";
					}
					
				}
				
				$i = 0;
				foreach ($chi as $key => $val) {
					if(++$i === count($chi)) {
			    		$sql4 .= $val.", ";
                        $sql4 .= "timestamp";
					}else
					{
					  	$sql4 .= $val.", ";
					}
				}

				$sql .= ") VALUES(";
				
				$sql4 .= ") VALUES(";
				
				$i = 0;
				foreach ($cliD as $key => $val) {
                   if(++$i === count($cliD)) {
                        $sql .= "'".$val."', ";
                        $sql .= "CURRENT_TIMESTAMP";
					}else
					{
                       if($cli[$key] == "client_charges")
                          {
                              if(count($val) > 0 && $val != '')
                              {
                                  $str = '';
                                  $a = 0;
                                 
                                  foreach($val as $v)
                                  {
                                      if(++$a === count($val))
                                      {
                                          $str .= $v;
                                      }else{
                                          $str .= $v.",";
                                      }  
                                  }
                                $sql .= "'".$str."', ";
                              }else{
                                $sql .= "'', ";
                              }
      
                          }else
                          {
                              $sql .= "'".$val."', ";
                           
                          }
					  	
					}
                    
					
					
				}
				
				
				$i = 0;
				foreach ($chiD as $key => $val) {
					if(++$i === count($chiD)) {
			    		$sql4 .= "'".$val."', ";
                        $sql4 .= "CURRENT_TIMESTAMP";
					}else
					{
					  	$sql4 .= "'".$val."', ";
					}
				}
				$sql .= ")";
				$sql4 .= ")";
				
				
				$this->_conn->query($sql);
				
				$this->_conn->query($sql4);

				return 'Success!';

			}catch(PDOException $ex) {
		        echo $sql;
		    }

			}else
			{
				echo 'duplicate';
			}
            
			

		}
		
		public function save($data)
		{
			try{
				$numItems = count($data);
				$arr = array();
				$sql = "UPDATE clients SET ";
				$sql4 = "UPDATE children SET ";
				$cli = array();
				$chi = array();
				foreach ($data as $key => $val) {
					
					  if(substr($key, 0, 3) == 'cli')
					  {
                          if($key == "client_charges")
                          {
                              if(count($val) > 0 && $val != '')
                              {
                                  $str = '';
                                  $c = 0;
                                  foreach($val as $v)
                                  {
                                      if(++$c === count($val))
                                      {
                                          $str .= $v;
                                      }else{
                                          $str .= $v.",";
                                      }  
                                  }
                                  $cli[] = $key."='".$str."'";
                              }
                          }else
                          {
                              $cli[] = $key."='".$val."'";
                          }
					  	
					  }
					  else if(substr($key, 0, 3) == 'pol' && $key == 'police_incident_id')
					  {
						$cli[] = $key."='".$val."'";
					  }
					  else if(substr($key, 0, 3) == 'chi')
					  {
						$chi[] = $key."='".$val."'";
					  }  
				}

				$i = 0;
				foreach ($cli as $key => $val) {
					if(++$i === count($cli)) {
                        $sql .= $val;
			    		
					}else
					{
					  	$sql .= $val.", ";
					}
				}
				
				$i = 0;
				foreach ($chi as $key => $val) {
					if(++$i === count($chi)) {
			    		$sql4 .= $val;
					}else
					{
					  	$sql4 .= $val.", ";
					}
				}
				

				$sql .= " WHERE clients.client_id='".$data['client_id']."'";
				$sql4 .= " WHERE children.client_id='".$data['client_id']."'";
				

				$this->_conn->query($sql);
				$this->_conn->query($sql4);

 				return 'Success!';

			}catch(PDOException $ex) {
		        print_r($this->_conn->errorInfo());
		    }

		}

		public function del($cid)
		{
			try {
	        	$sql = "DELETE FROM clients WHERE client_id='".$cid."'";
	        	
	        	$this->_conn->query($sql);
	        	

	        	return 'Success!';


		        }catch(PDOException $ex) {
		            print_r($this->_conn->errorInfo());
		        }

		}

		public function form($cid)
		{
			 try {
                    $results = array();
                    $query = "SELECT * FROM clients t1 JOIN children t4 ON t1.client_id = t4.client_id WHERE t1.client_id='".$cid."'";
                    $res = $this->_conn->query($query);
					$results = array();
					while($row = $res->fetch(PDO::FETCH_ASSOC)) {
		                $results[] = $row;
		            }
		            $result = new stdClass();
				    $result->data = json_encode($results);
					
					return $result;

            } catch(PDOException $ex) {
                print_r($this->_conn->errorInfo());
            }
		}

		public function conp($sql)
		{
			try {
                    $results = array();
                    $query = "SELECT * FROM clients ".$sql;
                    $res = $this->_conn->query($query);
					$results = array();
					while($row = $res->fetch(PDO::FETCH_ASSOC)) {
		                $results[] = $row;
		            }
		            $result = new stdClass();
				    $result->data = json_encode($results);

				    return $result;

            } catch(PDOException $ex) {
                //print_r($db->errorInfo());
            }

		}

		public function search($keyword)
		{
			try {
				$results = array();
				$queryClient = "(SELECT *, CONCAT_WS(' ', client_name, client_surname) AS client FROM clients".
				" HAVING (client_id = '$keyword' OR client_id LIKE '$keyword%' OR client_id LIKE '%$keyword%'".
				" OR client_id LIKE '%$keyword' OR client LIKE '$keyword%' OR client LIKE '%$keyword%' OR client". 
				" LIKE '%$keyword' OR client_gender LIKE '$keyword%'))";

				$queryAccused = "(SELECT *, CONCAT_WS(' ', accused_name, accused_surname) AS acc FROM accused".
				" HAVING (accused_id = '$keyword' OR accused_id LIKE '$keyword%' OR accused_id LIKE '%$keyword%'".
				" OR accused_id LIKE '%$keyword' OR acc LIKE '$keyword%' OR acc LIKE '%$keyword%' OR acc". 
				" LIKE '%$keyword'))";
				

				 $res1 = $this->_conn->query($queryClient);
				while($row = $res1->fetch(PDO::FETCH_ASSOC)) {
					$results[] = $row;
				}

				$res2 = $this->_conn->query($queryAccused);
				if($res2->rowCount() > 0)
				{
					
					$cidRow = $res2->fetch(PDO::FETCH_ASSOC);
					$sql = "SELECT * FROM clients WHERE client_id = '".$cidRow['client_id']."'";
					$r = $this->_conn->query($sql);
					while($row = $r->fetch(PDO::FETCH_ASSOC)) {
		                $results[] = $row;
		            }

				}
	

				return json_encode($results);
			}catch(PDOException $ex) {
				print_r($this->_conn->errorInfo());
			}
		}

		
		

		
		

	}

	

	

















?>
