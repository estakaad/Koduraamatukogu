<?php
session_start();
require_once("views/header.html");

$page = "index";
if (isset($_GET['page']) && $_GET['page']!=""){
	$page=htmlspecialchars($_GET['page']);
}
switch($page){
	case "login":
		include("views/main.html");
	break;
	case "register":
		include("views/main.html");
	break;
	case "logout":
		include("views/index.html");	
	break;
	case "books":
		include("views/books.html");	
	break;
	default:
		include('views/index.html');	
}

require_once("views/footer.html");
?>
