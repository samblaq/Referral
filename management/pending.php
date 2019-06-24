<?php
    date_default_timezone_set('UTC');

    include("Session.php");
    include("DatabaseConnection.php");

    if (!isset($_SESSION["PWID"])) {
        header("Location: index.php");
        exit();
    }

    $sql = "SELECT * FROM ReferralDetails";
    $result = sqlsrv_query($conn, $sql);
?> 

<!DOCTYPE html>
<html>
    <head> 
        <meta charset="UTF-8">
        <title>Admin - Referrals</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link rel="shortcut icon" href="assets/images/cropped-sc-touch-icon-192x192.png">
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
    </head>
    <body class="skin-black">
        <header class="header">
                <div class="logo">
                    Admin
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
                        View Referrals
                    </h1>
                </section>
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box box-info">
                                <div class="box-header">
                                    <h3>Search &amp; Manage all Referrals</h3>
                                </div>

                                <div class="body">
                                    <div class="table-responsive">
                                        <table id="view" class="table table-striped table-bordered">
                                            <?php
                                                if ($result > 0) {
                                                    echo"
                                                        <thead>
                                                            <tr>
                                                                <th>Ticket Code</th>
                                                                <th>Name</th>
                                                                <th>Email</th>
                                                                <th>Telephone</th>
                                                                <th>Product</th>
                                                                <th>Timestamp</th>
                                                                <th>Assigned To</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                    "; 
                                                    
                                                    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
                                                        $id = $row['id'];
                                                        $TicketCode = $row['TicketCode'];
                                                        $Name = $row['Name'];
                                                        $Email = $row['Email'];
                                                        $Telephone = $row['Telephone'];
                                                        $Product = $row['Product'];
                                                        $Staff = $row['Staff'];
                                                        $WIP = $row['WIP'];
                                                        $msg = 'Already assigned';
                                                        echo"
                                                            <tr>
                                                                <td>".$row['TicketCode']."</td>
                                                                <td>".$row['Name']."</td>
                                                                <td>".$row['Email']."</td>
                                                                <td>".$row['Telephone']."</td>
                                                                <td>".$row['Product']."</td>
                                                                <td>".$row['Timestamps']."</td>
                                                                <td>".$row['SalesOfficer']."</td>
                                                                <td>
                                                                    <form action='assign.php?id=".$row['TicketCode']."' method='POST'>
                                                                        <a href='assign.php?id=".$row['TicketCode']."' style='display: " . ($WIP == 1 ? 'none' : 'block') . "'>
                                                                            <input type='submit' value='Assign' name='Assign' class='btn btn-success btn-xs'>
                                                                        </a>
                                                                    </form>
                                                                </td>
                                                                
                                                            </tr>";
                                                        }
                                                    }
                                                ?>
                                        </table>
                                    </div>
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
        
        <script>
            $(document).ready(function(){
                $('#view').DataTable();
            });                                                
        </script>
    </body>
</html>
