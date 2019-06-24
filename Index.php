<?php
    if (isset($_POST['login'])) {

        if(empty($_POST['PWID']) || empty($_POST['password'])){
            $empty = "Please provide all details";
        }elseif (!is_numeric($_POST['PWID'])) {
            $notnumeric = "Please your Bank ID should be numeric";
        }else{
            
            $StaffPwid = htmlspecialchars($_POST["PWID"]);
            $password = htmlspecialchars($_POST["password"]);
            $matchFound = 0;
            
            include("DatabaseConnection.php");
        
            $sql = "SELECT * FROM Staff WHERE PWID = '$StaffPwid'";

            $result = sqlsrv_query($conn,$sql);
            //if ($result->num_rows > 0) {
                while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {    
                //check if you have a matching record;   
                    if ($StaffPwid == $row["PWID"] and $password == $row["Password"]) {
                        $matchFound = 1;   
                        $Name = $row["name"];
                        $PWID = $row["PWID"];
                        $Email = $row["email"];
                    }
                } 
                
            if ($matchFound == 1) {
                @session_start();
                $_SESSION["PWID"] = $StaffPwid;
                $_SESSION["email"] = $Email;
                $_SESSION['last_login_timestamp'] = time();
                // $_SESSION["PWID"] = $StaffPwid;
                // $_SESSION["name"] = $Name;
                header("Location: Refer.php");
                exit();
            }else{
                $error = "Incorrect username or password";
            }
        }

		
	}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SCB CLIENT REFERRAL</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Quicksand">
    <link rel="shortcut icon" href="assets/images/cropped-sc-touch-icon-192x192.png">
    <link rel="stylesheet" href="assets/css/OcOrato---Login-form.css">
    <link rel="stylesheet" href="assets/css/OcOrato---Login-form1.css">
    <link rel="stylesheet" href="assets/css/styless.css">
    <style type="text/css">
        #note {
            position: absolute;
            z-index: 101;
            top: 0;
            left: 0;
            right: 0;
            font-size: 16px;
            color: white;
            background: green;
            text-align: center;
            line-height: 3;
            overflow: hidden; 
            -webkit-box-shadow: 0 0 5px black;
            -moz-box-shadow:    0 0 5px black;
            box-shadow:         0 0 5px black;
        }

        @-webkit-keyframes slideDown {
            0%, 100% { -webkit-transform: translateY(-50px); }
            10%, 90% { -webkit-transform: translateY(0px); }
        }

        @-moz-keyframes slideDown {
            0%, 100% { -moz-transform: translateY(-50px); }
            10%, 90% { -moz-transform: translateY(0px); }
        }

        .cssanimations.csstransforms #note {
            -webkit-transform: translateY(-50px);
            -webkit-animation: slideDown 5s 1.0s 1 ease forwards;
            -moz-transform:    translateY(-50px);
            -moz-animation:    slideDown 5s 1.0s 1 ease forwards;
        }

        .cssanimations.csstransforms #close {
            display: none;
        }

        body {
            background-image:url("assets/images/NHO.JPG");
            background-size:cover;
            background-repeat:no-repeat;
            height:100%;
            width:100%;
        }
        
        html{
            height:auto;
        }
    </style>

</head>
<script src="assets/js/modernizr.custom.80028.js"></script>
<body>
<?php include("errorandsuccessmessages.php"); ?>

    <form method="POST" action="" id="form" style="font-family:Quicksand, sans-serif;background-color:rgba(44,40,52,0.73);width:320px;padding:40px; margin-top:50px;">
        <h4 id="head" style="color:rgb(0,159,218);"><strong>MY CLIENT REFERRAL</strong></h4>
        <br>
        <div><img class="img-rounded img-responsive" src="assets/images/cropped-sc-touch-icon-192x192.png" id="image" style="width:auto;height:100px;"></div>
        <br>
        
        <div class="form-group">
            <input class="form-control" type="text" id="PWID" name="PWID" placeholder="PWID">
        </div>
        <div class="form-group">
            <input class="form-control" type="password" name="password" placeholder="Password">
        </div>

        <div class="from-group">
            <input type="submit" class="btn btn-primary" value="Login" name="login" style="width:100%;height:100%;margin-bottom:10px;">
        </div>
        <!-- <button class="btn btn-default" type="button" name="Register" style="width:100%;height:100%;margin-bottom:10px;background-color:rgb(106,176,209);">Register</button> -->
    </form>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>

</html>