<?php
	$serverName = "SAMMY";
	$connectionInfo = array("Database" =>"ReferralSystem" , "UID"=>"ReferralSystem" , "PWD" => "Referral2019Updated");

	$conn = sqlsrv_connect($serverName , $connectionInfo);

	if(!$conn){
		echo "Connection could not be established.<br />";
		die(print_r(sqlsrv_errors() , true));
	}

?>