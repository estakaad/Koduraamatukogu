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
			$errors = register();
			if (empty($errors)) {
				header("Location: http://enos.itcollege.ee/~eprangel/blogi/index.php?page=index");
			} else {
				$_SESSION['errors'] = $errors;
				header("Location: http://enos.itcollege.ee/~eprangel/blogi/index.php?page=register");
			}
			break;	
		case "loginsuccess":
			$errors = login();
			if (empty($errors)) {
				header("Location: http://enos.itcollege.ee/~eprangel/blogi/index.php?page=index");
			} else {
				$_SESSION['errors'] = $errors;
				header("Location: http://enos.itcollege.ee/~eprangel/blogi/index.php?page=login");
			}
			break;
		case "view":
			$posts = viewPosts();
			break;	
		case "logout":
			logout();
			header("Location: http://enos.itcollege.ee/~eprangel/blogi/index.php?page=index");
		}

	require_once('views/head.html'); 

	switch($page){
		case "index":
			include('views/index.html');
			break;		
		case "login":
			if (isset($_SESSION['usersIdForSession'])) {
				include('views/index.html');				
			} else {
				include('views/login.html');				
			}		
			break;
		case "register":
			if (isset($_SESSION['usersIdForSession'])) {
				include('views/login.html');				
			} else {
				include('views/register.html');				
			}				
			break;			
		case "viewPosts":
			include('views/index.html');				
			break;
		default:
			include('views/index.html');
	} 

	require_once('views/foot.html'); 

?>