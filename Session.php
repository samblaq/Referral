<?php 
	@session_start();
	include("DatabaseConnection.php");
	//session checks go here
	if (isset($_SESSION["PWID"])) {
		// $Name = $_SESSION["name"];
		$PWID = $_SESSION["PWID"];
		$Email = $_SESSION["email"];
		
		if ((time() - $_SESSION['last_login_timestamp']) > 900) {
			header("Location: logout.php");
		}else{
			$_SESSION['last_login_timestamp'] = time();
		} 
		
	}else{
		header("Location: Index.php");
		exit();
	}
?>