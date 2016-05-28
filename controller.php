<?php

require_once('model.php'); 
session_start();
connect_db();
	
	//$statuses = array(1=>"Laenasin kelleltki",2=>"Laenasin kellelegi",3=>"Riiulis olemas");

	$page = "index";

	if (isset($_GET['page']) && $_GET['page']!=""){
		$page=htmlspecialchars($_GET['page']);
	}

	switch($page){
		case "registersuccess":
			register();
			break;
		case "bookadded":
			$errors = addBook();
			if (empty($errors)) {
				header("Location: http://enos.itcollege.ee/~eprangel/uus/controller.php?page=view");
			} else {
				$_SESSION['errors'] = $errors;
				header("Location: http://enos.itcollege.ee/~eprangel/uus/controller.php?page=add");
			}
			break;
		case "loginsuccess":
			login();
			break;	
		case "passwordchanged":
			changePassword();
			break;
		case "view":
			$books = viewBooks();
			break;	
		case "edit":
			$bookInfo = getBookInfo($_GET['id']);
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
			if (isset($_SESSION['user'])) {
				include('views/index.html');				
			} else {
				include('views/login.html');				
			}		
			break;
		case "register":
			if (isset($_SESSION['user'])) {
				include('views/login.html');				
			} else {
				include('views/register.html');				
			}				
			break;	
		case "passwordchanged":
			if (isset($_SESSION['user'])) {
				include('views/passwordchanged.html');				
			} else {
				include('views/index.html');				
			}		
			break;	
		case "add":
			if (isset($_SESSION['user'])) {
				include('views/add_book.html');				
			} else {
				include('views/index.html');				
			}			
			break;
		case "view":
			if (isset($_SESSION['user'])) {
				include('views/view_books.html');				
			} else {
				include('views/index.html');				
			}
			break;
		case "settings":
			if (isset($_SESSION['user'])) {
				include('views/settings.html');				
			} else {
				include('views/index.html');				
			}
			break;
		case "edit":
			if (isset($_SESSION['user'])) {
				include('views/edit_book.html');				
			} else {
				include('views/index.html');				
			}
			break;
		default:
			include('views/index.html');
	} 

	require_once('views/foot.html'); 

?>