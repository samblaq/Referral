<?php
    date_default_timezone_set('UTC');
    use PHPMailer\PHPMailer\PHPMailer;

    include("Session.php");
    include("DatabaseConnection.php");

    
    if (!isset($_SESSION["PWID"])) {
        header("Location: Index.php");
        exit();
    } 

    if (isset($_POST['submit'])) {
        require_once "PHPMailer/PHPMailer.php";
        require_once "PHPMailer/SMTP.php";
        require_once "PHPMailer/Exception.php";
        
        function sanitize($data)
        { 
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        if (empty($_POST['name'])||empty($_POST['Telephone'])||empty($_POST['product'])){
            $errorInsert = "Please provide all details";
        }elseif(!is_numeric($_POST['Telephone'])){
                $notNumeric = "Telephone field should be Digits";
        }else{
            $name = sanitize($_POST['name']);
            $Telephone = sanitize($_POST['Telephone']);
            $email = sanitize($_POST['email']);
            $product = sanitize($_POST['product']);
            $Timestamps = date("F j, Y, g:i a");
            $SalesOfficer = 'Not Assigned';
            $Status = 'Pending';

            $Ticket_ID    = "RF"."-".date('d') . date('m') . date('Y')."-";

            $count_sql = "SELECT count(*) as count from ReferralDetails";
            $result = sqlsrv_query($conn,$count_sql);
            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                $count = $row['count'] + 1;
            }
            // echo $count;
            if ($count < 10) {
                $Ticket_ID = $Ticket_ID . "000" . (string) $count;
            } else if ($count < 100) {
                $Ticket_ID = $Ticket_ID . "00" . (string) $count;
            } else if ($count < 1000) {
                $Ticket_ID = $Ticket_ID . "0" . (string) $count;
            } else {
                $Ticket_ID = $Ticket_ID . (string) $count;
            }

            $date = date('Y-m-d');

            $TAT = date('Y-m-d' , strtotime($date. '+ 10 days'));

            $sql_InsertReferralDetails = "INSERT INTO ReferralDetails(TicketCode,Name,Telephone,Email,Product,Timestamps,Staff,SalesOfficer,Status) VALUES('$Ticket_ID','$name','$Telephone','$email','$product','$Timestamps','$PWID','$SalesOfficer','$Status')";
            $ResultReferralDetails = sqlsrv_query($conn , $sql_InsertReferralDetails);

            
            if($product == 'Personal Account' || $product == 'Personal Loans' || $product == 'FX' || $product == 'Wealth Management' || $product == 'Credit Cards' || $product == 'SC Mobile'){
                $i = 0;
                $AdminName = array();
                $AdminEmail = array();

                $AdminSql = "SELECT * FROM Admin WHERE Segment = 'Retail Banking'";
                $result = sqlsrv_query($conn,$AdminSql);
                while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                    $AdminName[$i] = $row['Name'];
                    $AdminEmail[$i] = $row['Email'];
                    $i++;
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
                $mail->setFrom('samuelaryeeteynii@gmail.com', 'SCB GH Referrals '); //system mail should be here
                $mail->addAddress($AdminEmail[0]);
                $mail->addAddress($AdminEmail[1]);
                $mail->addCC($Email);

                $mail->Subject = "New Referral";
                $mail->Body = "Dear Both, <br><br> Please you have a new referral with Ticket Code <strong>$Ticket_ID</strong> in your queue. <br><br> Kindly check it and assign to the appropriate RM, Sales Officer or Digital Support person to engage the client.
                <br><br> With Regards. <br> Referral Team";
                
                $mail->send();
                // if(){
                //     echo "Email is send";
                // }else{
                //     "Something with: <b>".$mail->ErrorInfo."</br>";
                // }
    
            }elseif ($product == 'Corporate Account' || $product == 'Corporate Loans') {
                $i = 0;
                $AdminName = array();
                $AdminEmail = array();
                
                $AdminSql = "SELECT * FROM Admin WHERE Segment = 'Business Banking'";
                $result = sqlsrv_query($conn,$AdminSql);
                while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                    $AdminName[$i] = $row['Name'];
                    $AdminEmail[$i] = $row['Email'];
                    $i++;
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
                $mail->setFrom('samuelaryeeteynii@gmail.com', 'SCB GH Referrals ');
                $mail->addAddress($AdminEmail[0]);
                // $mail->addAddress($AdminEmail[1]);
                $mail->addCC($Email);
                $mail->Subject = "New Referral";
                $mail->Body = "Dear Both, <br><br> Please you have a new referral with Ticket Code <strong>$Ticket_ID</strong> in your queue. <br><br> Kindly check it and assign to the appropriate RM, Sales Officer or Digital Support person to engage the client.
                <br><br> With Regards. <br> Referral Team";
    
                if($mail->send()){
                    // "Email is send";
                }else{
                    "Something with: <b>".$mail->ErrorInfo."</br>";
                }
    
            }
            
            $success = "Thank you for Referring a client";
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
            height:50%;
        }
        
        body{
           padding:0;
           margin:0;
           /* position:absolute; */
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

<body>
    <?php include("errorandsuccessmessages.php"); ?>
    <div>
        <?php include("user-account.php"); ?>
    </div>
    <div class="main">
        <section class="signup">
            <div class="container">
                <div class="signup-content">
                    <form method="POST" id="signup-form" class="signup-form">
                        <h2 class="form-title">Refer a Client</h2>
                        <div class="form-group">
                            <input type="text" class="form-input" name="name" id="name" placeholder="Client's Name"/>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-input" name="Telephone" id="telephone" placeholder="Client's Phone #"/>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-input" name="email" id="email" placeholder="Client's Email"/>
                        </div>
                        <div class="form-group">
                            <?php
                                $sql_Product = "SELECT * FROM Product";
                                $result = sqlsrv_query($conn,$sql_Product);
                            ?>
                            <select name="product" class="form-input">
                                <option value="0"> -- Select Product Type -- </option>
                                <?php
                                    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
                                        $pro = $row['Product_Name'];
                                        echo"
                                            <option value = '$pro'>$pro</option>
                                        ";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="submit" id="submit" class="form-submit" value="Refer"/>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>