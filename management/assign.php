<?php
    date_default_timezone_set('UTC');
    use PHPMailer\PHPMailer\PHPMailer;

 
   
    include("DatabaseConnection.php");
    include("Session.php");

    if (!isset($_SESSION["PWID"])) {
        header("Location: Index.php");
        exit();
    }

    if(isset($_GET['id'])){
        $ID = $_GET['id'];
        $sql = "SELECT * FROM ReferralDetails WHERE TicketCode = '$ID'";
        $result = sqlsrv_query($conn, $sql);

        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
            $Name = $row['Name'];
            $TicketCode = $row['TicketCode'];
            $Product = $row['Product'];
            $Telephone = $row['Telephone'];
            $Email = $row['Email'];
            $Staff = $row['Staff'];
        }
    }

    $Admin  = 'null';   //this is going to be admin id when i include session on the page kk
        $Timestamps = date("F j, Y, g:i a");

    if(isset($_POST['Assign'])){
        function sanitize($data){
            $data = stripcslashes($data);
            $data = htmlspecialchars($data);
            $data = trim($data);
            return $data;
        }

        require_once "PHPMailer/PHPMailer.php";
        require_once "PHPMailer/SMTP.php";
        require_once "PHPMailer/Exception.php";

        if (empty($_POST['sales'])){
            $ErrorAssign = "Please Assign an RM, Sales Agent or Digital Support to a Referral";
        }else{
            $SalesOfficer = sanitize($_POST['sales']);

            $UpdateStatus = "UPDATE ReferralDetails SET SalesOfficer = '$SalesOfficer', WIP = '1' WHERE TicketCode = '$ID'";
            $result = sqlsrv_query($conn,$UpdateStatus);

            $WIPSQL = "INSERT INTO WIP(TicketCode,Name,Telephone,Email,Product,Staff,DateAssigned,AdminWhoAssigned,SalesOfficer) VALUES ('$TicketCode','$Name','$Telephone','$Email','$Product','$Staff','$Timestamps','$PWID','$SalesOfficer')";
            $resultWIPSQL = sqlsrv_query($conn,$WIPSQL);

            $mailQuery = "SELECT Name, Email FROM SalesOfficers WHERE PWID = '$SalesOfficer'";
            $resultmailQuery = sqlsrv_query($conn,$mailQuery);
            while ($row = sqlsrv_fetch_array($resultmailQuery, SQLSRV_FETCH_ASSOC)){
                $SalesOfficerName = $row['Name'];
                $SalesOfficerEmail = $row['Email'];
            }

            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = "samuelaryeeteynii@gmail.com";
            $mail->Password = "P@ssw0rd1@gmailyahoo.com";
            $mail->Port = 465;
            $mail->SMTPSecure = "ssl";

            //Email Settings
            $mail->isHTML(true);
            $mail->setFrom($Email, 'SCB GH Referrals ');
            $mail->addAddress($SalesOfficerEmail);
            // $mail->addCC('samuelaryeeteynii@gmail.com');
            $mail->Subject = "New Referral";
            $mail->Body = "Hello $SalesOfficerName, <br><br> Referral with Ticket Number $TicketCode has been assigned to you. <br><br> Kindly visit your portal to check the referral details for your perusal. <br><br> You have a maximum of 3 days to close this Ticket.<br><br>
            Best Regards<br> Referral Team";

            if($mail->send()){
                "Email is send";
            }else{
                "Something with: <b>".$mail->ErrorInfo."</br>";
            }

            $EditSuccess = "Referral with Ticket Number -  $TicketCode has been assigned to Sales Officer with PWID - $SalesOfficer";
        }
    }
?> 

<!DOCTYPE html>
<html>
    <head> 
        <meta charset="UTF-8">
        <title>Admin - Referrals</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link rel="shortcut icon" href="assets/img/cropped-sc-touch-icon-192x192.png">
        <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/AdminLTE.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/fonts/css/all.min.css" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Abel" rel="stylesheet">

        <style>
            th{
                background-color:powderblue;
            }
        </style>
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
    <body class="skin-black">
    
        <header class="header">
        
                <div class="logo">
                    Admin
                </div>
            <nav class="navbar navbar-static-top" role="navigation">
            <?php include("errorandsuccessmessages.php"); ?>
                <div class="navbar-right">
                    <ul class="nav navbar-nav">
                        <?php 
                            include("user-account-bar.php");
                        ?>
                    </ul>
                </div>
            </nav>
        </header>
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <aside class="left-side sidebar-offcanvas">
                <section class="sidebar">
                <br>
                    <br>    
                    <?php include "side-bar-menu.php"; ?>
                </section>
            </aside>
            <aside class="right-side">
                <section class="content-header">
                    <h1>
                        Assign an RM, Sales Agent or Digital Support to a Referral
                    </h1>
                </section>
                <section class="content">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="box box-info">
                                <div class="box-header">
                                    <h3>Search &amp; Manage all Referrals</h3>
                                </div>

                                <div class="body">
                                    <form action="" method="POST">
                                        <div class="form-group">
                                            <label for="FullName" class="label-control">FullName</label>
                                            <input type="text" value="<?php echo $Name; ?>" class="form-control" readonly>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="Ticket" class="label-control">Ticket Number</label>
                                            <input type="text" value="<?php echo $TicketCode; ?>" class="form-control" readonly>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="Product" class="label-control">Product</label>
                                            <input type="text" value="<?php echo $Product; ?>" class="form-control" readonly>
                                        </div>

                                        <div class="form-group">
                                            <select name="sales" class='form-control'>
                                                <option value='0'>--Select an Agent--</option>
                                                <?php
                                                    $sql = "SELECT PWID FROM SalesOfficers";
                                                    $result = $result = sqlsrv_query($conn, $sql);
                                                    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
                                                        echo"
                                                            <option value = ".$row['PWID'].">".$row['PWID']."</option>
                                                        ";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" name="Assign" value="Assign" class="btn btn-success form-control">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </aside> 
        </div>
        <script src="../assets/js/jquery.min.js" type="text/javascript"></script>
        <script src="../assets/js/jquery.dataTables.min.js" type="text/javascript"></script>
        <script src="../assets/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../assets/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
    </body>
</html>
