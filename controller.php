<?php
session_start();
require_once("views/header.html");

$page = "";

if (isset($_POST['loginmail']) && isset($_POST['loginpassword']){
	$page=htmlspecialchars($_POST['login']);
}

switch($page){
	case "login":
		include("views/main.php");
	break;
	case "register":
		include("views/register.php");
	break;
	default:
		include('views/login.php');	
}

require_once("views/footer.html");
?>
