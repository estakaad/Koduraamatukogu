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
				header("Location: http://enos.itcollege.ee/~eprangel/Koduraamatukogu/index.php?page=index");
			} else {
				$_SESSION['errors'] = $errors;
				header("Location: http://enos.itcollege.ee/~eprangel/Koduraamatukogu/index.php?page=register");
			}
			break;
		case "bookadded":
			$errors = addBook();
			if (empty($errors)) {
				header("Location: http://enos.itcollege.ee/~eprangel/Koduraamatukogu/index.php?page=view");
			} else {
				$_SESSION['errors'] = $errors;
				header("Location: http://enos.itcollege.ee/~eprangel/Koduraamatukogu/index.php?page=add");
			}
			break;
		case "bookedited":
			$errors = editBook();
			if (empty($errors)) {
				header("Location: http://enos.itcollege.ee/~eprangel/Koduraamatukogu/index.php?page=view");
			} else {
				$_SESSION['errors'] = $errors;
				header("Location: http://enos.itcollege.ee/~eprangel/Koduraamatukogu/index.php?page=edit&id=".$_POST['id']);
			}
			break;		
		case "removebook":
			removeBook();
			header("Location: http://enos.itcollege.ee/~eprangel/Koduraamatukogu/index.php?page=view");
			break;	
		case "loginsuccess":
			$errors = login();
			if (empty($errors)) {
				header("Location: http://enos.itcollege.ee/~eprangel/Koduraamatukogu/index.php?page=index");
			} else {
				$_SESSION['errors'] = $errors;
				header("Location: http://enos.itcollege.ee/~eprangel/Koduraamatukogu/index.php?page=login");
			}
			break;
		case "passwordchanged":
			$errors = changePassword();
			$_SESSION['errors'] = $errors;
			break;
		case "view":
			$books = viewBooks();
			break;	
		case "edit":
			$bookInfo = getBookInfo($_GET['id']);
			if (empty($bookInfo)) {
				header("Location: http://enos.itcollege.ee/~eprangel/Koduraamatukogu/index.php?page=index");
			} 
			break;	
		case "logout":
			logout();
			header("Location: http://enos.itcollege.ee/~eprangel/Koduraamatukogu/index.php?page=index");
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
		case "passwordchanged":
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