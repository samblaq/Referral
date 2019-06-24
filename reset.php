<?php
    date_default_timezone_set('UTC');

    include("Session.php");
    include("DatabaseConnection.php");

    if (!isset($_SESSION["PWID"])) {
        header("Location: Index.php");
        exit();
    }

    $sql= "SELECT * FROM staff where PWID = '$PWID'";
    $result = sqlsrv_query($conn,$sql);

    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
        $name = $row['name'];
        $password = $row['Password'];
        $PWID = $row['PWID'];
    }

    if (isset($_POST['reset'])) {
        function sanitize($data){
            $data = stripcslashes($data);
            $data = htmlspecialchars($data);
            $data = trim($data);
            return $data;
        }

        if (empty($_POST['password'])) {
            $EmptyPassword = "Please Set your new password";
        }elseif (strlen($_POST['password']) < 8) {
            $passwordLength = "Password length should be greater than 8 chars";
        }else{
            $password = sanitize($_POST['password']);

            $sql_EditPassword = "UPDATE staff SET Password = '$password' where PWID = '$PWID'";
            $result = sqlsrv_query($conn,$sql_EditPassword);
            $EditSuccess = "Staff with ID - $PWID has successfully changed the Password";
        }
    }

?>

<!DOCTYPE html>
<html>
    <head> 
        <meta charset="UTF-8">
        <title>My Client Referrals</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link rel="shortcut icon" href="assets/images/cropped-sc-touch-icon-192x192.png">
        <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/AdminLTE.css" rel="stylesheet" type="text/css" />
        <link href="assets/fonts/web-fonts/css/all.min.css" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Abel" rel="stylesheet">
        <style>
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

        body{
            height:100%;
            width:100%;
        }
        </style>

        <style>
            th{
                background-color:powderblue;
            }
        </style>
    </head>
    <script src="assets/js/modernizr.custom.80028.js"></script>
    <body class="skin-black">
        <header class="header">
                <div class="logo">
                    Reset Password
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
                    <h3>
                        Staff
                    </h3>
                </section>
                <section class="content">
                    <div class="row">
                        <div class="col-xs-3">
                            <div class="box box-info">
                                <div class="box-header">
                                    <h3> Reset Password</h3>
                                    <br>
                                </div>

                                <div class="body">
                                    <form action="" method="POST">
                                        <div class="form-group">
                                            <label for="fname" class="label-control">FullName</label>
                                            <input type="text" value="<?php echo $name; ?>" class="form-control" readonly>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="pwid" class="label-control">Bank ID</label>
                                            <input type="text" value="<?php echo $PWID; ?>" class="form-control" readonly>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="password" class="label-control">Old Password</label>
                                            <input type="text" value="<?php echo $password; ?>" class="form-control" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="password" class="label-control">Set New Password</label>
                                            <input type="password" name="password" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" name="reset" value="Reset" class="btn btn-success form-control">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </aside> 
        </div>
        <script src="assets/js/jquery.min.js" type="text/javascript"></script>
        <script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
    </body>
</html>


