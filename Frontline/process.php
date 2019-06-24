<?php
    date_default_timezone_set('UTC');
    use PHPMailer\PHPMailer\PHPMailer;
    include("DatabaseConnection.php");
    include("Session.php");

    if (!isset($_SESSION["PWID"])) {
        header("Location: Index.php");
        exit();
    }

    if (isset($_GET['id'])) {
        $ID = $_GET['id']; 

        $sql_ReferralDetails = "SELECT * FROM WIP WHERE id = '$ID'";
        $result = sqlsrv_query($conn,$sql_ReferralDetails);
    
        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
            $Name = $row['Name'];
            $Product = $row['Product'];
            $Telephone = $row['Telephone'];
            $TicketCode = $row['TicketCode'];
            $Staff = $row['Staff'];
        }
    }

    if (isset($_POST['submit'])) {

        function sanitize($data)
        { 
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        require_once "PHPMailer/PHPMailer.php";
        require_once "PHPMailer/SMTP.php";
        require_once "PHPMailer/Exception.php";

        if (empty($_POST['status'])){
            $errorInsert = "Please provide the Status of the Referral";
        }else{
            $Status = sanitize($_POST['status']);
            $reason = sanitize($_POST['reason']);
            $Timestamp = date("F j, Y, g:i a");

            echo $sql_ReferralStatus = "INSERT INTO ReferralStatus (TicketCode,Name,Telephone,Product,Status,Reason,Staff,SalesOfficer,Timestamp)VALUES 
            ('$TicketCode' , '$Name', '$Telephone', '$Product' , '$Status' , '$reason' , '$Staff','$PWID','$Timestamp')";
            sqlsrv_query($conn , $sql_ReferralStatus);

            $sql_Update = "UPDATE ReferralDetails SET Status = '$Status', Reason = '$reason', Timestamps_Completion_Failure = '$Timestamp' WHERE TicketCode = '$TicketCode'";
            sqlsrv_query($conn , $sql_Update);

            $sql_Process = "UPDATE WIP SET Status = '1'";
            sqlsrv_query($conn , $sql_Process);

            $staffQuery = "SELECT name,email FROM Staff WHERE PWID = '$Staff'";
            $resultstaffQuery = sqlsrv_query($conn,$staffQuery);
            while ($row = sqlsrv_fetch_array($resultstaffQuery, SQLSRV_FETCH_ASSOC)){
                $StaffQueryEmail = $row['email'];
                $StaffQueryName = $row['name'];
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
            $mail->addAddress($StaffQueryEmail);
            
            if ($Status == 'Success') {
                $mail->Subject = "Referral Completed - Success";
                $mail->Body = "Hello $StaffQueryName, <br><br> Your Referral with Ticket Number <strong>$TicketCode</strong> has been successfully completed. <br><br> Kindly visit your portal to verify. <br><br>
                Thanks once again for your referral and keep on referring. <br><br> #NeverSettle <br> #DOHA1020 <br><br>Best Regards <br>Referrals Team";
            }elseif($Status == 'Failed'){
                $mail->Subject = "Referral Completed - Failed";
                $mail->Body = "Hello $StaffQueryName, <br><br> Your Referral with Ticket Number <strong>$TicketCode</strong> has been completed but not successful. <br><br> Kindly visit your portal to verify.<br><br>
                Thanks once again for your referral and keep on referring. <br><br> #NeverSettle <br> #DOHA1020 <br><br>Best Regards <br>Referrals Team";
            }
            
            if($mail->send()){
                "Email is send";
            }else{
                "Something with: <b>".$mail->ErrorInfo."</br>";
            }

            $ReferralSuccess = "Referral with Ticket Code - $TicketCode is completed";
        } 
    }
?>
<!DOCTYPE html>
<html>
    <head> 
        <meta charset="UTF-8">
        <title>Admin - Referrals</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link rel="shortcut icon" href="../assets/images/cropped-sc-touch-icon-192x192.png">
        <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/AdminLTE.css" rel="stylesheet" type="text/css" />
        <link href="../assets/fonts/css/all.min.css" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Abel" rel="stylesheet">
        
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
    <script src="../assets/js/modernizr.custom.80028.js"></script>
    <body class="skin-black">
        <header class="header">
                <div class="logo">
                    Frontline Referral
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
                        Processing Referrals
                    </h1>
                </section>
                <section class="content">
                    <div class="row">
                        <div class="col-xs-5">
                            <div class="box box-success">
                                <div class="box-header">
                                    <h3>Referring Process Flow</h3>
                                </div>

                                <div class="box-body">
                                    <form action="" method='POST'>
                                        <div class="form-group">
                                            <label for="Name" class="label-control">Name</label>
                                            <input type="text" name='name'value="<?php echo $Name; ?>" class="form-control" readonly = 'readonly'>
                                        </div>
                                        <div class="form-group">
                                            <label for="Product" class="label-control">Product</label>
                                            <input type="text" name='product' value="<?php echo $Product; ?>" class="form-control" readonly ='readonly'>
                                        </div>
                                        <div class="form-group">
                                            <label for="status" class="label-control">Status of Referral</label>
                                            <select name="status" class='form-control' id='status' onchange = "ShowHideDiv()">
                                                <option value="0"> -- Choose Option --</option>
                                                <option value="Success">Success</option>
                                                <option value="Failed">Failed</option>
                                            </select>
                                        </div>
                                        <div class="form-group" id='reasons' style='display:none'>
                                            <label for="reasons" class="label-control">Give reasons for failure</label>
                                            <textarea name="reason" cols="30" rows="10" style="resize: none;" class='form-control'></textarea>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <input type="submit" class='btn btn-info btn-xs form-control' name='submit'>
                                        </div>
                                    </form>
                                </div>

                                <div class="box-footer clearfix">
                                    <ul class="pagination pagination-sm no-margin pull-right">
                                    <!--  -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </aside> 
        </div>
        <script src="../assets/js/jquery.min.js" type="text/javascript"></script>
        <script src="../assets/js/bootstrap.min.js" type="text/javascript"></script>
        
        <script type="text/javascript">
            function ShowHideDiv() {
                var ddlPassport = document.getElementById("status");
                var dvPassport =  document.getElementById("reasons");
                dvPassport.style.display = ddlPassport.value == "Failed" ? "block" : "none";
            }
        </script>
    </body>
</html>


