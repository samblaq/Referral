<?php
    include('DatabaseConnection.php');
    include('Session.php');

    if (!isset($_SESSION["PWID"])) {
        header("Location: Index.php");
        exit();
    }
 
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Dashboard</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" /> 
        <link href="assets/fonts/web-fonts/css/all.min.css" rel="stylesheet" type="text/css" /> 
        <link rel="shortcut icon" href="assets/images/cropped-sc-touch-icon-192x192.png">        
        <!-- <link href="//code.ionicframework.com/ionicons/1.5.2/css/ionicons.min.css" rel="stylesheet" type="text/css" /> -->
        <link href="assets/css/AdminLTE.css" rel="stylesheet" type="text/css" /> 
        <link href="https://fonts.googleapis.com/css?family=Abel" rel="stsylesheet">
    </head>
    <body class="skin-black">
        <header class="header">
                <div class="logo">
                    My Client Referrals
                </div>
            <nav class="navbar navbar-static-top" role="navigation">
                
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
                        Dashboard
                        <small>Control panel</small>
                    </h1>
                </section>
                <section class="content">
                    <div class="row">
                        <div class="col-lg-3 col-xs-6">
                            <div class="small-box bg-aqua">
                                <div class="inner">
                                    <h3>
                                        <?php
                                            $sql_ReferralDetails = "SELECT COUNT(*) as Count FROM ReferralDetails WHERE Staff = '$PWID' and Status = 'Pending'";
                                            $result = sqlsrv_query($conn,$sql_ReferralDetails);
                                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                                echo $count = $row['Count'];
                                            }
                                        ?>
                                    </h3>
                                    <p>
                                        <h4>Pending Referral</h4>
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-sync fa-xs"></i> 
                                </div>
                                <a href="view.php" class="small-box-footer">
                                    View Pending Requests <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-xs-6">
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h3>
                                        <?php
                                            $sql_ReferralDetails = "SELECT COUNT(*) AS Count FROM ReferralDetails WHERE Status = 'Success' and Staff = '$PWID'";
                                            $result = sqlsrv_query($conn,$sql_ReferralDetails);
                                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                                echo $count = $row['Count'];
                                            }
                                        ?>
                                    </h3>
                                    <p>
                                        <h4>Successful Referral</h4>
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check fa-xs"></i>
                                </div>
                                <a href="success.php" class="small-box-footer">
                                    View Successful Requests <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-xs-6">
                            <div class="small-box bg-red">
                                <div class="inner">
                                    <h3>
                                        <?php
                                            $sql_ReferralDetails = "SELECT COUNT(*) AS Count FROM ReferralDetails WHERE Status = 'Failed' and Staff = '$PWID'";
                                            $result = sqlsrv_query($conn,$sql_ReferralDetails);
                                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                                echo $count = $row['Count'];
                                            }
                                        ?>
                                    </h3>
                                    <p>
                                        <h4>Failed Referral</h4>
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-times fa-xs"></i>
                                </div>
                                <a href="failed.php" class="small-box-footer">
                                    View Failed Requests <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        
                     </div>

                </br></br>
                </section>
            </aside>
        </div>
        <script src="assets/js/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="//code.jquery.com/ui/1.11.1/jquery-ui.min.js" type="text/javascript"></script>
        <script src="assets/js/AdminLTE/app.js" type="text/javascript"></script>
        <script src="assets/js/AdminLTE/dashboard.js" type="text/javascript"></script>
        <script src="assets/js/AdminLTE/demo.js" type="text/javascript"></script>
    </body>
</html>
