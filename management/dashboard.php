<?php
    date_default_timezone_set('UTC');
    // include("session.php");
    include("DatabaseConnection.php");
    
    // if (!isset($_SESSION["username"])) {
    //     header("Location: index.php");
    //     exit();
    // }
    
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
		<script>
			var items = [];
		</script>
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
                        Dashboard
                        <small>Search &amp; Monitor all pending referrals</small>
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
                                <!-- <span id="dday"></span>
                                <span id="dhour"></span>
                                <span id="dmin"></span>
                                <span id="dsecc"></span> -->
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
                                                        <th>Referred Date</th>
                                                        <th>Deadline</th>
                                                        <th>TAT</th>
                                                        <th>Process</th>
                                                    </tr>
                                                ";
                                                while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
                                                    $TAT = $row['TAT'];
                                                    echo"
                                                        <tr>
                                                            <td>".$row['TicketCode']."</td>
                                                            <td>".$row['Name']."</td>
                                                            <td>".$row['Telephone']."</td>
                                                            <td>".$row['Email']."</td>
                                                            <td>".$row['Product']."</td>
                                                            <td>".$row['Timestamps']."</td>
                                                            <td style='font-size:20px;font-weight:bold;'><span id='countdown_" .$row['TicketCode']. "' class='timer'></span></td>
                                                            <td>".$row['TAT']."</td>
                                                            <td>
                                                                <a href='process.php?id=".$row['id']."'>
                                                                    <input type='submit' value='Process' name='Edit' class='btn btn-success btn-xs'>
                                                                </a>
                                                            </td>
                                                        
                                                        </tr>";
														?>
														<script>
															var item = {
																	countdownId: "countdown_<?= $row['TicketCode'] ?>", 
																	endTime: "<?= $row['TAT'] ?>"
																};
																
															items.push(item);
														</script>
														<?php
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
                                                echo "<li><a href = \"dashboard.php?page=$last\">&laquo;</a></li>";
                                             }else if( $page == 0 and $left_rec>$rec_limit) {
                                                $page += 2;
                                                echo "<li><a href = \"dashboard.php?page=$page\">&raquo;</a></li>";
                                             }else if( $page > 0 ) {
                                                $page += 2;
                                                $last = $page - 2;
                                                echo "<li><a href = \"dashboard.php?page=$last\">&laquo;</a></li>";
                                                echo "<li><a href = \"dashboard.php?page=$page\">&raquo;</a></li>";
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
        <script>
            
           
            function timer(id , endDate){
				
				var index;
				for (index = 0; index < items.length; ++index) {
					currentItem = items[index];
               // }
					
					var today = new Date();
					var DD = today.getDate();
					var MM = today.getMonth() + 1;   //January starts from 0
					var YYYY = today.getFullYear();

					//Getting the difference between the current date and the date stored in database in second

					var _DateFromDBProgEndDate = currentItem.endTime;
					console.log(_DateFromDBProgEndDate);
					var ProgEndTime = new Date(_DateFromDBProgEndDate);
					var TodayTime = new Date();

					var differenceTravel = ProgEndTime.getTime() - TodayTime.getTime();
					var seconds = Math.floor((differenceTravel) / (1000));
					var SecDiffFromToday = seconds;
					var seconds = SecDiffFromToday;                              

					var days = Math.floor(seconds/24/60/60);
					var HoursLeft = Math.floor((seconds) - (days * 86400));
					var hours = Math.floor(HoursLeft / 3600);
					var minutesLeft = Math.floor((HoursLeft) - (hours * 3600));
					var minutes = Math.floor(minutesLeft / 60);
					var remainingSeconds = seconds % 60;

					if (remainingSeconds < 10) {
						remainingSeconds = "0" + remainingSeconds;
					}
					if (days < 10) {
						days = "0" + days;
					}
					
					document.getElementById(currentItem.countdownId).innerHTML = days + " : " + hours + " : " + minutes + " : " + remainingSeconds;

					if (seconds == 0) {
						clearInterval(countdownTimer);
						document.getElementById('countdown').innerHTML = "Completed";
					}else{
						seconds--;
					}
            // }
				}
            }
            
            var countdownTimer = setInterval('timer()' , 1000);
        </script>
    </body>
</html>


