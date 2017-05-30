<?php 
    ob_start();
    session_start();
    include 'dbcred.php';
    include 'Login/Log.php';
    $log = new Log($db);
    $user = (isset($_POST['user_name']))?$_POST['user_name']:false;
    $pass = (isset($_POST['user_pass']))?$_POST['user_pass']:false;

    if($user && $pass)
    {
        $res = $log->login($user, $pass);
        if($res == false)
        {
            $mes = "Wrong username or password. Please try again.";
        }
    }
   



?>



<html>

<head>
    <meta charset="UTF-8">
    <title>CDS - Log In</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <style>
        body {
            background-color: #202C3A;
        }
        
        .contain {
            margin-top: 3%;
        }
        
        .logo {
            width: 250px;
            margin: auto;
            position: relative;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .logo img {
            width: 150px;
        }
        
        .logo h2 {
            color: white;
            font-weight: 200;
        }
        
        .logBox {
            width: 430px;
            padding: 10px 40;
            margin: auto;
            border: #8799AD 1px solid;
            border-radius: 7px;
            background-color: white;
        }
        
        .logBox h3 {
            padding-bottom: 20px;
            text-align: center;
            color: #202C3A;
        }
        
        .logBox input[type=text],
        .logBox input[type=password] {
            padding: 10px;
            padding-left: 50px;
            width: 100%;
            border-radius: 5px;
            border: #8799AD solid .5px;
            outline: none;
        }
        
        .logBox input:focus {
            border: #202C3A solid .5px;
        }
        
        .logBox .row {
            margin-bottom: 20px;
        }
        
        .logBox .userIcon,
        .logBox .passIcon {
            font-size: 16px;
            position: absolute;
            top: 12.5;
            left: 30;
            padding-right: 10px;
            border-right: 1px solid #8799AD;
            color: #202C3A;
        }
        
        .logBox .logInBtn {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: #8799AD solid .5px;
            color: white;
            background-color: #202C3A;
        }
        
        .logBox .logInBtn:hover {
            background-color: #2C3D51;
        }
        
        .logBox .newUserLinkBox {
            margin-top: 10px;
            border-top: 1px solid #8799AD;
            padding-top: 20px;
        }
        
        .logBox .newUserLinkBox p {
            text-align: center;
        }
        .logBox .loginMess
        {
            color: orangered;
            text-align: center;
        }
    </style>

</head>

<body>
    <div class="contain">
      
        <div class="logo">
            <img src="../img/vslogo.png" alt="">
            <h2>Court Data System</h2>
        </div>
        <div class="logBox">
            <h3>Log in to CDS</h3>
            <form id="loginForm" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
            <div class="row">
                <div class="col-md-12"><i class="userIcon glyphicon glyphicon-user"></i><input class="userName" type="text" name="user_name" placeholder="Username"></div>
            </div>
            <div class="row">
                <div class="col-md-12"><i class="passIcon glyphicon glyphicon-lock"></i><input class="userPass" type="password" name="user_pass" placeholder="Password"></div>
            </div>
            <div class="row">
                <div class="col-md-12"><input type="submit" class="logInBtn" value="Log In"></div>
            </div>
            </form>
            <?php 
            if(isset($mes)){
                echo "<p class='loginMess'>".$mes."</p>";
            }
            
        
            ?>
            <div class="row">
                <div class="col-md-12 newUserLinkBox">
                    <p>Admin? <a href="#">Sign In</a></p>
                </div>
            </div>
        </div>
    </div>

     <footer style="text-align: center; margin:50px;">
            <p style="color: #2c3d51">In development by <a style="color: #2c3d51" href="mailto:haranzalez@gmail.com">Hans Aranzalez</a></p>
        </footer>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>

</html>
