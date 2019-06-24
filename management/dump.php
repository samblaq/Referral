<?php
    date_default_timezone_set('UTC');
    // include("session.php");
    include("DatabaseConnection.php");

?>
<!DOCTYPE html>
<html>
    <head> 
        <meta charset="UTF-8">
        <title>Admin - Referrals</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link rel="shortcut icon" href="assets/images/cropped-sc-touch-icon-192x192.png">
        <link href="assets/css/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/AdminLTE.css" rel="stylesheet" type="text/css" />
        <link href="assets/fonts/css/all.min.css" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Abel" rel="stylesheet">
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
                        Pending Referrals
                        <small>Search &amp; Manage all Pending Referrals</small>
                    </h1>
                </section>
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">
                                   
                                   <div class="box-tools">
                                        <div class="input-group" style="text-align: right;">
                                            <form action="#" method="POST" >
                                                <input type="text" name="table_search" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search"/>
                                                <div class="input-group-btn">
                                                    <button type="submit" name="submit_table_search" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <?php 
                                        if (isset($_POST['submit_table_search'])) {
                                             $TableSearch = $_POST['table_search'];
                                            echo "<div style=\"text-align:center;\">
                                        <h3 style=\"text-align:center; float:none;\" class=\"box-title\">Search Results with '$TableSearch'</h3>
                                    </div>";
                                        }

                                    ?>
                                </div>
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover">               
                                        <?php
                                            if(isset($_POST['submit_table_search'])){
                                                $SearchValue = $_POST['table_search'];
                                            }
                                            $rec_limit = 20;

                                            if(isset($_POST['submit_table_search'])){
                                                $sql = "SELECT count(*) as count FROM ReferralDetails where (TicketCode LIKE '%$SearchValue%' OR Name LIKE '%$SearchValue%' OR Telephone LIKE '%$SearchValue%'OR Email LIKE '%$SearchValue%'OR Product LIKE '%$SearchValue%'OR Staff LIKE '%$SearchValue%')";
                                            }else{
                                                $sql = "SELECT count(*) as count FROM ReferralDetails";

                                            }   
                                            $result = sqlsrv_query($conn,$sql);
                                           
                                              if ($result) {
                                                while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                                    $rec_count = $row["count"];
                                                }
                                            }
                                            

                                            if( isset($_GET{'page'} ) ) {
                                                $page = $_GET{'page'} - 1;
                                                $offset = $rec_limit * $page;
                                             }else {
                                                $page = 0;
                                                $offset = 0;
                                             }

                                             $left_rec = $rec_count - ($page * $rec_limit);
                                             
                                             if (isset($_POST['submit_table_search'])) {
                                                $sql = "SELECT * FROM ReferralDetails where (TicketCode LIKE '%$SearchValue%' OR Name LIKE '%$SearchValue%' OR Telephone LIKE '%$SearchValue%'OR Email LIKE '%$SearchValue%'OR Product LIKE '%$SearchValue%'OR Staff LIKE '%$SearchValue%')";
                                             }else{
                                                $sql = "SELECT * FROM ReferralDetails ORDER BY id ASC OFFSET $offset ROWS FETCH NEXT $rec_limit ROWS ONLY";
                
                                             }
                                             
                                            $result = sqlsrv_query($conn,$sql);
                                              if($result){
                                                echo"
                                                    <tr>
                                                        <th>Transaction #</th>
                                                        <th>Client's Name</th>
                                                        <th>Client's Telephone</th>
                                                        <th>Client's Email</th>
                                                        <th>Product</th>
                                                        <th>Referer (Staff)</th>
                                                        <th>Referred Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                ";
                                                while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
                                                    echo"
                                                        <tr>
                                                            <td>".$row['TicketCode']."</td>
                                                            <td>".$row['Name']."</td>
                                                            <td>".$row['Telephone']."</td>
                                                            <td>".$row['Email']."</td>
                                                            <td>".$row['Product']."</td>
                                                            <td>".$row['Staff']."</td>
                                                            <td>".$row['Timestamps']."</td>
                                                            <td>
                                                                <a href=".$row['id'].">
                                                                    <input type='submit' value='Process' name='Edit' class='btn btn-success btn-xs'>
                                                                </a>
                                                            </td>
                                                        </tr>";
                                                }
                                            }else {
                                                echo "<br><br><br><h4 style='text-align:center;'> No Records found.</h4><br><br><br><br><br><br>";
                                            }
                                           
                                        ?>
                                    </table>
                                </div>
                                <div class="box-footer clearfix">
                                    <ul class="pagination pagination-sm no-margin pull-right">
                                    <?php 

                                        if (!isset($_POST['submit_table_search'])) {

                                         if( $left_rec <= $rec_limit and isset($_GET['page'])) {
                                            $last = $page;
                                                echo "<li><a href = \"dump.php?page=$last\">&laquo;</a></li>";
                                             }else if( $page == 0 and $left_rec>$rec_limit) {
                                                $page += 2;
                                                echo "<li><a href = \"dump.php?page=$page\">&raquo;</a></li>";
                                             }else if( $page > 0 ) {
                                                $page += 2;
                                                $last = $page - 2;
                                                echo "<li><a href = \"dump.php?page=$last\">&laquo;</a></li>";
                                                echo "<li><a href = \"dump.php?page=$page\">&raquo;</a></li>";
                                             }
                                         }
                                    ?>
                                    </ul>
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


