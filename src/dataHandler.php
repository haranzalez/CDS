<?php 
use uploader\DocumentUpload;
	include 'dbcred.php';
	include 'crud/Crud.php';
    $query = (isset($_GET['q']))?$_GET['q']:false;
    $cid = (isset($_GET['client_id']))?$_GET['client_id']:false;
    $aid = (isset($_GET['accused_id']))?$_GET['accused_id']:false;
    $callId = (isset($_GET['call_id']))?$_GET['call_id']:false;
	$sql = (isset($_GET['sql']))?$_GET['sql']:false;
	$keyword = (isset($_GET['keyword']))?$_GET['keyword']:false;
	$file = (isset($_FILES['client_documents']))?$_FILES['client_documents']:false;
    $fileName = (isset($_GET['fileName']))?$_GET['fileName']:false;
    $callFilter = (isset($_GET['keyw']))?$_GET['keyw']:false;


	
	
	

    $crud = new Crud($db);

    if($query != false && $query == 'all')
    {
    	$rec = $crud->showAll();
    	echo $rec->data;
    }else if($query != false && $query == 'del' && $cid != false)
    {
    	echo $crud->del($cid);
    }else if ($query != false && $query == 'form' && $cid != false) 
    {
    	$rec = $crud->form($cid);
    	echo $rec->data;
    }
    else if ($query != false && $query == 'update') 
    {
    	$formK = array();
	    for ($i=0; $i < count($crud->_cols); $i++) { 
            
            $formK[$crud->_cols[$i]] = (isset($_POST[$crud->_cols[$i]]))?$_POST[$crud->_cols[$i]]:false;
           
		}
	    echo $crud->save($formK);
    }else if ($query != false && $query == 'add') 
    {
    	$formK = array();
	    for ($i=0; $i < count($crud->_cols); $i++) { 
            
            $formK[$crud->_cols[$i]] = (isset($_POST[$crud->_cols[$i]]))?$_POST[$crud->_cols[$i]]:false;
           
		}
	    echo $crud->add($formK);
    }else if ($query != false && $query == 'comp' && $sql != false) 
    {
	    $res = $crud->conp($sql);
		echo $res->data;
    }else if ($query != false && $query == 'search' && $keyword != false) 
    {
	    $res = $crud->search($keyword);
		echo $res;
    }else if ($query != false && $query == 'rows') 
    {
	    echo $crud->_numRows;
    }else if ($query != false && $query == 'genId') 
    {
	    echo $crud->genId();
    }
	
if($query != false && $query == 'file' && $file != false){
    
    $max = 2000 * 1024;
    $result = array();

    require_once 'uploader/DocumentUpload.php';
    $destination = __DIR__ . '/uploads/';
    try{
        $upload = new DocumentUpload($destination, $cid);
        $upload->setMaxSize($max);
        $upload->upload();
        $result = $upload->getMessages();
    }catch(Exception $e){
        $result[] = $e->getMessage();
    }
    
    if(count($result) > 0)
    {
        foreach ($result as $key => $value) {
            echo $value;
        }
    }
    else{
        return false;
    }
}

if($query != false && $query == 'getFiles')
{
    $dir = __DIR__ . '/uploads/'.$cid;
        
    if(file_exists($dir))
    {
        $files = scandir($dir);
        echo json_encode($files);
    }else{
        echo false;
    }
        
}

if($query != false && $query == 'delFile' && $fileName != false && $cid != false)
{
    $dir = __DIR__ . '/uploads/'.$cid;
    $file = $dir.'/'.$fileName;
    
    if(is_writable($file))
    {
        if(file_exists($file))
        {
            unlink($file);
            echo 'Success!';
        }else
        {
            echo 'No such file';
        }
    }else
    {
        echo 'Directory for client: ' . $cid . ' is not writable.';
    }
}
	

if($query != false && $query == 'addCall' && $cid != false)
{
   
    $call_outcome = $_POST['call_outcome'];
    $call_notes = $_POST['call_notes'];
    $call_date = $_POST['call_date'];
        
    try{
    $q = "INSERT INTO calls(client_id, call_date, call_outcome, call_notes)VALUES('$cid','$call_date','$call_outcome','$call_notes')";
    $db->query($q);
    echo 'Success!';
    }catch(Exception $e){
        $result[] = $e->getMessage();
    }
}
if($query != false && $query == 'updateCall' && $callId != false)
{
   try{
    $sql = "UPDATE calls SET ";
    $numOfItems = count($_POST);
    $i = 0;
    foreach($_POST as $key=>$value)
    {
        ++$i;
        if($key != 'q' && $key != 'call_id')
        {
             if($i == $numOfItems)
            {
                $sql .= $key."='".$value."'";
                }else{
                    $sql .= $key."='".$value."',";
                }
            }
    }
    $sql .= " WHERE call_id='".$callId."'";
   
    $res = $db->query($sql);
    echo 'Success!';
    //echo $sql;

   }catch(Exception $e){
        print_r($db->errorInfo());

    }
  

}
if($query != false && $query == 'callFilter' && $cid != false && $callFilter != false)
{
    try{
    $q = ($callFilter != 'All')?"SELECT * FROM calls WHERE client_id='$cid' AND call_outcome='$callFilter'":"SELECT * FROM calls WHERE client_id='$cid'";
    $res = $db->query($q);
    $results = array();
    while($row = $res->fetch(PDO::FETCH_ASSOC)) {
        $results[] = $row;
    }
    echo json_encode($results);
    }catch(Exception $e){
        echo $q;
    }
    
}

if($query != false && $query == 'getAccused' && $cid != false)
{
    try{
    $sql = "SELECT * FROM accused WHERE client_id='$cid'";
    $res = $db->query($sql);
    $results = array();
    while($row = $res->fetch(PDO::FETCH_ASSOC)) {
        $results[] = $row;
    }
    echo json_encode($results);
    }catch(Exception $e){
        echo $q;
    }
}

if($query != false && $query == 'addAccused' && $cid != false)
{
    try{
    $heads = "SHOW COLUMNS FROM accused";
    $resHeads = $db->query($heads);
    $i = 1;
    $stmt = "INSERT INTO accused (";
    $accValues = array();
    foreach($resHeads->fetchAll(PDO::FETCH_COLUMN) as $val) {
       if($val == 'timestamp')
       {
           $stmt .= $val;
       }else
       {
           $stmt .= $val.', ';
           
       }
       if($val == 'client_id')
       {
           $accValues[] = $cid;
       }else
       {
           $accValues[] = (isset($_POST[$val]))?$_POST[$val]:false;
       }

    }
   $stmt .= ')VALUES(';
   $j = 0;
   foreach($accValues as $key => $value)
   {
       if(++$j == count($accValues))
       {
            $stmt .= 'CURRENT_TIMESTAMP';
       }else
       {
           $stmt .= "'".$value."', " ;
       }
   }
   $stmt .= ")";
   
    /*echo $stmt;*/
    $res = $db->query($stmt);
     echo 'Success!';
    }catch(Exception $e){
        print_r($db->errorInfo());
    }
}

if($query != false && $query == 'delAccused' && $aid != false)
{
    try{
    $sql = "DELETE FROM accused WHERE accused_id='".$aid."'";
    $res = $db->query($sql);
    echo 'Success!';
    }catch(Exception $e){
        echo $q;
    }

}
if($query != false && $query == 'updateAccused' && $aid != false)
{
   try{
    $sql = "UPDATE accused SET ";
    $numOfItems = count($_POST);
    $i = 0;
    foreach($_POST as $key=>$value)
    {
        ++$i;
        if($key != 'q' && $key != 'accused_id')
        {
             if($i == $numOfItems)
            {
                $sql .= $key."='".$value."'";
                }else{
                    $sql .= $key."='".$value."',";
                }
            }
    }
    $sql .= " WHERE accused_id='".$aid."'";
   
    $res = $db->query($sql);
    echo 'Success!';
    //echo $sql;

   }catch(Exception $e){
        print_r($db->errorInfo());

    }
  

}
			
		
	

    



    









?>
