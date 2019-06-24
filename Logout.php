<?php 
    include("Session.php");
    include("Databaseconnection.php");
	@session_start();
	if(session_destroy()) // Destroying All Sessions
    {
    	$_SESSION = [];
        header("Location: Index.php"); // Redirecting To Home Page
    }
?>