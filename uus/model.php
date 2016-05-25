<?php

function connect_db(){
	global $connection;
	$host="localhost";
	$user="test";
	$pass="t3st3r123";
	$db="test";
	$connection = mysqli_connect($host, $user, $pass, $db) or die("Ei saa ühendust mootoriga: ".mysqli_error());
	mysqli_query($connection, "SET CHARACTER SET UTF8") or die("Ei saanud baasi UTF-8-sse: ".mysqli_error($connection));
}

function register(){
	
	if (!empty($_SESSION['user'])) {
		include_once 'views/register.html';
	}

	if ($_SERVER['REQUEST_METHOD'] == 'POST') { 

		$errors = array();

		global $connection;
  		$users_email = mysqli_real_escape_string($connection, $_POST["email"]);
  		$query = mysqli_query($connection, "SELECT count(*) AS count_rows FROM eprangel_users WHERE email='$users_email'");
		$row = mysqli_fetch_assoc($query);

		//print_r($row);

		if($row['count_rows'] > 0){
			$errors[] = "Selle e-posti aadressiga on juba registreeritud kasutaja.";
		}
  		if(empty($_POST['name'])) {
	    	$errors[] = "Nimi on puudu.";
		}
		if(empty($_POST['email'])) {
			$errors[] = "E-posti aadress on puudu.";
		}
		if(empty($_POST['password1'])) {
			$errors[] = "Salasõna on puudu.";
		}
		if(empty($_POST['password2'])) {
			$errors[] = "Salasõna kordus on puudu.";
		}
		if($_POST['password1'] != $_POST['password2']) {
			$errors[] = "Salasõnad ei ole ühesugused.";
		}

		if (empty($errors)) {

			$users_name = mysqli_real_escape_string($connection, $_POST['name']);
	  		$users_email = mysqli_real_escape_string($connection, $_POST['email']);
	  		$users_password = mysqli_real_escape_string($connection, $_POST['password1']);

			$result = mysqli_query($connection, "INSERT INTO eprangel_users (name, email, passw) VALUES ('$users_name', '$users_email', SHA1('$users_password'))");

			$rows = mysqli_affected_rows($connection);

			print_r($rows);

			if($rows > 0){
				$_SESSION['user'] = $users_name;
			} 
		
		} else {
			include_once 'views/register.html';
		}

	}

}

function login(){
	
	if (!empty($_SESSION['user'])) {
		include_once 'views/login.html';
	}

	if ($_SERVER['REQUEST_METHOD'] == 'POST') { 

		$errors = array();

		global $connection;
  		$users_email = mysqli_real_escape_string($connection, $_POST["login_email"]);
  		$users_password = mysqli_real_escape_string($connection, $_POST["login_password"]);
  		$query = mysqli_query($connection, "SELECT id FROM eprangel_users WHERE email='$users_email' && passw=SHA1('$users_password')");
		$result = mysqli_query($connection, $query) or die("midagi läks valesti");
		if(!$result){
			$errors[] = "E-posti aadress ja salasõna ei klapi.";
		}
		if(empty($_POST['login_email'])) {
			$errors[] = "E-posti aadress on puudu.";
		}
		if(empty($_POST['login_password'])) {
			$errors[] = "Salasõna on puudu.";
		}

		$query = mysqli_query($connection, "SELECT name FROM eprangel_users WHERE email='$users_email'");
		$result = mysqli_query($connection, $query) or die("Midagi läks valesti");

		if (empty($errors)) {
			$_SESSION['user'] = $result;
			include_once 'views/view_books.html';
		} else {
			include_once 'views/login.html';
		}


	}

}

function addBook(){

}

function showBooks(){

}

function logout(){
	session_destroy();

	unset($_SESSION['user']);
	$id = false;
}



?>