<?php

require_once('model.php'); 
session_start();
connect_db();
	
	$page = "index";

	if (isset($_GET['page']) && $_GET['page']!=""){
		$page=htmlspecialchars($_GET['page']);
	}

	switch($page){
		case "registersuccess":
			register();
			break;
		case "logout":
			logout();
			header("Location: http://enos.itcollege.ee/~eprangel/uus/controller.php?page=index");
		}

	require_once('views/head.html'); 

	switch($page){
		case "index":
			include('views/index.html');
			break;		
		case "login":
			include('views/login.html');
			break;
		case "register":
			include('views/register.html');				
			break;
		case "registersuccess":
			include('views/register_success.html');		
			break;			
		case "add":
			include('views/add_book.html');
			break;
		case "view":
			include('views/view_books.html');
			break;
		case "settings":
			include('views/settings.html');
			break;
		case "logout":
			include('views/index.html');
			break;
		default:
			include('views/index.html');
	} 

	require_once('views/foot.html'); 

?>