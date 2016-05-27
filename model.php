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

			if($rows > 0){
				$_SESSION['user'] = $users_name;
			} 
		
		} else {
			include_once 'views/register.html';
		}

	}

}

function login(){
	
	if (isset($_SESSION['user'])) {
		include_once 'views/view_books.html';
	}

	if ($_SERVER['REQUEST_METHOD'] == 'POST') { 

		$errors = array();

		global $connection;

  		$users_email = mysqli_real_escape_string($connection, $_POST['login_email']);
  		$users_password = mysqli_real_escape_string($connection, $_POST['login_password']);
  		
  		$query = mysqli_query($connection, "SELECT count(*) AS count_rows FROM eprangel_users WHERE email='$users_email' AND passw=SHA1('$users_password')");

		$row = mysqli_fetch_assoc($query);

		if($row['count_rows'] != 1){
			$errors[] = "Sisselogimine ebaõnnestus.";
		}

		if(empty($_POST['login_email'])) {
			$errors[] = "E-posti aadress on puudu.";
		}
		if(empty($_POST['login_password'])) {
			$errors[] = "Salasõna on puudu.";
		}

		if (empty($errors)) {
			$query = mysqli_query($connection, "SELECT name AS session_name FROM eprangel_users WHERE email='$users_email'");
			$result = mysqli_fetch_assoc($query);
			$_SESSION['user'] = $result['session_name'];
			echo $_SESSION['user'];

			$query = mysqli_query($connection, "SELECT id AS session_id FROM eprangel_users WHERE email='$users_email'");
			$result = mysqli_fetch_assoc($query);
			$_SESSION['id'] = $result['session_id'];
			echo $_SESSION['id'];

		}

	}

}

function changePassword() {
	if (isset($_SESSION['user'])) {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		$errors = array();

		global $connection;
		$id = $_SESSION['id'];
  		$old_password = mysqli_real_escape_string($connection, $_POST['old_password']);
  		
  		$new_password1 = mysqli_real_escape_string($connection, $_POST['new_password1']);
  		$new_password2 = mysqli_real_escape_string($connection, $_POST['new_password2']);
  		
  		$query = mysqli_query($connection, "SELECT count(*) AS count_rows FROM eprangel_users WHERE id='$id' AND passw=SHA1('$old_password')");

		$row = mysqli_fetch_assoc($query);

		if($row['count_rows'] != 1){
			$errors[] = "Vale vana parool.";
		}

		if(empty($_POST['old_password'])) {
			$errors[] = "Vana salasõna on puudu.";
		}
		if(empty($_POST['new_password1'])) {
			$errors[] = "Uus salasõna on puudu.";
		}
		if(empty($_POST['new_password2'])) {
			$errors[] = "Salasõna kordus on puudu.";
		}
		if($_POST['new_password1'] != $_POST['new_password2']) {
			$errors[] = "Uus salasõna pole see, mis selle kordus.";
		}
		print_r($errors);
		if (empty($errors)) {
			echo "hakkab salasõna uuendama";
			$query = mysqli_query($connection, "UPDATE eprangel_users SET passw=SHA1('$new_password1') WHERE id='$id'");
			$rows = mysqli_affected_rows($connection);
			print_r($rows);
		}
	} else {
		include_once 'views/settings.html';
	}	

	}
}

function addBook(){

	if (isset($_SESSION['user'])) {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		
		$errors = array();

		if(empty($_POST['author_lastname'])) {
			$errors[] = "Autori perekonnanimi on puudu.";
		}
		if(empty($_POST['author_firstname'])) {
			$errors[] = "Autori eesnimi on puudu.";
		}
		if(empty($_POST['book_title'])) {
			$errors[] = "Teose pealkiri on puudu.";
		}
		if(!isset($_POST['bookstatus'])) {
			$errors[] = "Staatus on puudu.";
		}

		global $connection;
		$id = $_SESSION['id'];
  		$lastname = mysqli_real_escape_string($connection, $_POST['author_lastname']);
  		$firstname = mysqli_real_escape_string($connection, $_POST['author_firstname']);
  		$title = mysqli_real_escape_string($connection, $_POST['book_title']);
  		$notes = mysqli_real_escape_string($connection, $_POST['book_notes']);
  		$status = mysqli_real_escape_string($connection, $_POST['bookstatus']);

		if (empty($errors)) {
			$result = mysqli_query($connection, "INSERT INTO eprangel_books (user_id, last_name, first_name, title, status, notes) VALUES ('$id', '$lastname', '$firstname', '$title', '$status', '$notes')");

			$rows = mysqli_affected_rows($connection);

			if($rows > 0){
				include_once 'views/view_books.html';
			} 
		
		} else {
			print_r("midagi on valesti");
			include_once 'views/add_book.html';
		} 

		}

	}

}

function getBookInfo($id) {
	global $connection;

	$query = "SELECT * FROM eprangel_books WHERE id=".$id;

	$result = mysqli_query($connection, $query) or die("Ei saanud raamatu infot.");
 	
 	if ($bookInfo = mysqli_fetch_assoc($result)) {
		return $bookInfo;
	}
	else {
		header("Location: ?page=view");
	}
}

function editBook() {
	global $connection;
	
	if (empty($_SESSION['user'])) {
		header("Location: ?page=login");
	}
	
	if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id']) && $_GET['id'] != "") {
		$id = $_GET['id'];
		$book = getBookInfo(mysqli_real_escape_string($connection, $id)); 
	} else {
		header("Location: ?page=view");
	}

	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {

		$id = $_POST['edit'];	

		$book = getBookInfo(mysqli_real_escape_string($connection, $id));
		
		$book['lastname'] = mysqli_real_escape_string($connection, $_POST["author_lastname"]);
		$book['firstname'] = mysqli_real_escape_string($connection, $_POST["author_firstname"]);
		$book['title'] = mysqli_real_escape_string($connection, $_POST["book_title"]);
		//$book['status'] = mysqli_real_escape_string($connection, $_POST["status"]);
		$book['notes'] = mysqli_real_escape_string($connection, $_POST["book_notes"]);

		$query = "UPDATE eprangel_books SET lastname='$book['lastname']', firstname='$book['firstname']', title='$book['title']', notes='$book['notes']' WHERE id='$id'"; 

		$result = mysqli_query($connection, $query) or die("Ei muutnud midagi");

		include_once('views/edit_book.html');
	}

function removeBook() {

}

function viewBooks(){
	if (isset($_SESSION['user'])) {
		
		global $connection;
		$id = $_SESSION['id'];
		$query = mysqli_query($connection, "SELECT * FROM eprangel_books WHERE user_id='$id'");
		
		$row = mysqli_fetch_assoc($query);
		
		$books = array();

		$result = $connection->query("SELECT id, last_name, first_name, title, status, notes FROM eprangel_books WHERE user_id='$id'");
		for ($books = array (); $row = $result->fetch_assoc(); $books[] = $row);

		return $books;

	}

}

function logout(){
	session_destroy();

	unset($_SESSION['user']);
	$id = false;
}



?>