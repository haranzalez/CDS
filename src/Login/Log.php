 <?php 

   class Log
   {
       public function __construct($db)
       {
           $this->_conn = $db;
           $this->_user = (isset($_SESSION['user']))?$_SESSION['user']:false;
           $this->_sessid = (isset($_SESSION['sess']))?$_SESSION['sess']:false;
           $this->_isLogedIn = ($this->_user && $this->_sessid)?$this->checkLogIn():false;
           
       }
        private function sessionID()
       {
            $y = date('y');
			$m = date('n');
			return 'S'.$y.$m.rand(100, 1000);
         
       }

       private function checkLogIn()
       {
                try{
                    $sql = "SELECT * FROM users WHERE user_name = '$this->_user' AND user_session_id = '$this->_sessid' LIMIT 1";
                    $res = $this->_conn->query($sql);
                    if($res->rowCount() > 0)
                    {
                       $destination = 'http://local.dev/brantfordpd';
                       header("Location: ".$destination);
                       exit;
                    }
                }catch(Exception $e){
                    print_r($this->_conn->errorInfo());
                    
               }
                
           
       }

       public function login($username, $pass)
       {
           $destination = 'http://local.dev/brantfordpd';
           if($this->_isLogedIn == false)
           {
               try{
                $sql = "SELECT * FROM users WHERE user_name = '$username' AND user_pass = '$pass' LIMIT 1";
                $res = $this->_conn->query($sql);
                
                if($res->rowCount() > 0)
                {
                    $row = $res->fetch(PDO::FETCH_ASSOC);
                    $this->_isLogedIn = true;
                    $this->_sessid = $this->sessionID();
                    $this->_user = $row['user_first_name'] . ' ' . $row['user_last_name'];

                    $_SESSION['user'] = $row['user_name'];
                    $_SESSION['user_full_name'] = $this->_user;
                    $_SESSION['sess'] = $this->_sessid;

                    $sqlUpdt = "UPDATE users SET user_session_id = '$this->_sessid' WHERE user_name = '$username' AND user_pass = '$pass'";
                    $this->_conn->query($sqlUpdt);
                    
                    header("Location: ".$destination);
                    exit;
                    
                }else{
                    return false;
                }
               }catch(Exception $e){
                    print_r($this->_conn->errorInfo());
                    
               }
               
           }
       }

      
   }
 
 
 
 
 
 
 
 ?>