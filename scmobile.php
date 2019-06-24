<?php
    date_default_timezone_set('UTC');
    include("Session.php");
    include("DatabaseConnection.php");
    
    if (!isset($_SESSION["PWID"])) {
        header("Location: Index.php");
        exit();
    } 
      

    if (isset($_POST['submit'])) {
        
        function sanitize($data)
        { 
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        $sub = substr($_POST['Account'], 2, 3);
        if (empty($_POST['name'])||empty($_POST['Telephone'])||empty($_POST['Account'])){
            $scerrorInsert = "Please provide all details";
        }elseif(!is_numeric($_POST['Telephone'])){
                $scnotNumeric = "Telephone field should be Digits";
        }elseif(strlen($_POST['Account']) != 13){
                $AccountLength = "Please Account Number must 13 Digits";
        }elseif($sub != '505' && $sub != '035' && $sub != '010'){
                $InvalidAccountNumber = "Please enter a valid SC Mobile Account number";
        }elseif(!is_numeric($_POST['Account'])){
                $scnotNumericAccount = "Account field should be Digits";
        }else{
            $name = sanitize($_POST['name']);
            $Telephone = sanitize($_POST['Telephone']);
            $Account = sanitize($_POST['Account']);
            $Timestamps = date("F j, Y, g:i a");

            if ($sub == '505') {
                $AccountType = 'e-Savings';
            }elseif ($sub == '035') {
                $AccountType = 'e-Youth';
            }elseif ($sub == '010') {
                $AccountType = 'e-Current';
            }
            
            $sql_SCMobile = "INSERT INTO SCMobile(Name,Telephone,Account,Staff,AccountType,Timestamps) VALUES('$name','$Telephone','$Account','$PWID','$AccountType','$Timestamps')";
            $ResultSCMobile = sqlsrv_query($conn , $sql_SCMobile);
            $scsuccess = "Thank you for Logging your client";
        }
        
    }
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SCB Client Referral</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Navigation-with-Button1.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="shortcut icon" href="assets/images/cropped-sc-touch-icon-192x192.png">
    <link rel="stylesheet" href="assets/fonts/material-icon/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style type="text/css">
        .signup-content {
            border-radius: 0px; 
            padding: 50px 85px;
        }

        .form-input{
            width:100%;
        }

    </style>
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
    </style>
</head>
<script src="assets/js/modernizr.custom.80028.js"></script>

<body style="padding:0;">
    <?php include("errorandsuccessmessages.php"); ?>
    <div>
        <?php include("user.php"); ?>
    </div>
    <div class="main">
        <section class="signup">
            <div class="container">
                <div class="signup-content">
                    <form method="POST" id="signup-form" class="signup-form">
                        <h2 class="form-title">OWN YOUR CLIENT</h2>
                        <div class="form-group">
                            <input type="text" class="form-input" name="name" placeholder="Client's Name"/>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-input" name="Account" placeholder="Account Number #"/>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-input" name="Telephone" placeholder="Phone Number"/>
                        </div>
                        
                        <div class="form-group">
                            <input type="submit" name="submit" id="submit" class="form-submit" value="LOG IT"/>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>