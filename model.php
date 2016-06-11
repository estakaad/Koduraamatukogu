<?php

function connect_db() {
	global $connection;
	$host = "localhost";
	$user = "test";
	$pass = "t3st3r123";
	$db = "test";
	$connection = mysqli_connect($host, $user, $pass, $db) or die("Ei saa ühendust mootoriga: " . mysqli_error());
	mysqli_query($connection, "SET CHARACTER SET UTF8") or die("Ei saanud baasi UTF-8-sse: " . mysqli_error($connection));
}

function register() {
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		
		global $connection;

		$errors = array();

		if (empty(trim($_POST['name']))) {
			$errors[] = "Nimi on puudu.";
		}

		if (empty(trim($_POST['email']))) {
			$errors[] = "E-posti aadress on puudu.";
		}

		if (empty(trim($_POST['password']))) {
			$errors[] = "Salasõna on puudu.";
		}

		if (strlen(trim($_POST['password'])) < 8) {
			$errors[] = "Salasõna peab koosnema vähemalt 8 tähemärgist.";
		}

		if (empty(trim($_POST['confirmPassword']))) {
			$errors[] = "Salasõna kordus on puudu.";
		}

		if ($_POST['password'] != $_POST['confirmPassword']) {
			$errors[] = "Salasõnad ei ole ühesugused.";
		}

		$users_email = mysqli_real_escape_string($connection, htmlspecialchars($_POST["email"]));

		$query = mysqli_query($connection, "SELECT count(*) AS count_rows FROM eprangel_blog_users WHERE email='$users_email'") or die("Ei õnnestu e-posti aadressi kontrollida.");
		$row = mysqli_fetch_assoc($query);

		if ($row['count_rows'] > 0) {
			$errors[] = "Selle e-posti aadressiga on juba registreeritud kasutaja.";
		}

		if (empty($errors)) {

			$users_name = mysqli_real_escape_string($connection, htmlspecialchars($_POST['name']));
			$users_email = mysqli_real_escape_string($connection, htmlspecialchars($_POST['email']));
			$users_password = mysqli_real_escape_string($connection, htmlspecialchars($_POST['password']));

			$result = mysqli_query($connection, "INSERT INTO eprangel_blog_users (name, email, password) VALUES ('$users_name', '$users_email', SHA1('$users_password'))") or die("Ei õnnestu kasutajat luua.");
			$rows = mysqli_affected_rows($connection);

			if ($rows > 0) {

				$query = mysqli_query($connection, "SELECT id AS session_id FROM eprangel_blog_users WHERE email='$users_email'");
				$result = mysqli_fetch_assoc($query);
			
				$_SESSION['usersIdForSession'] = $result['session_id'];

				$query = mysqli_query($connection, "SELECT name AS session_name FROM eprangel_blog_users WHERE email='$users_email'");
				$result = mysqli_fetch_assoc($query);
			
				$_SESSION['userNameForSession'] = $result['session_name'];

			}
		}
		else {
			return $errors;
		}
	}
}

function login() {

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		
		global $connection;

		$errors = array();
		
		if (empty(trim(htmlspecialchars($_POST['loginEmail'])))) {
			$errors[] = "E-posti aadress on puudu.";
		}

		if (empty(trim(htmlspecialchars($_POST['loginPassword'])))) {
			$errors[] = "Salasõna on puudu.";
		}

		$users_email = mysqli_real_escape_string($connection, trim(htmlspecialchars($_POST['loginEmail'])));
		$users_password = mysqli_real_escape_string($connection, trim(htmlspecialchars($_POST['loginPassword'])));

		$query = mysqli_query($connection, "SELECT count(*) AS count_rows FROM eprangel_blog_users WHERE email='$users_email' AND password=SHA1('$users_password')") or die("Ei õnnestu kindlaks teha sellise e-posti aadressi ja salasõnaga kasutajat.");
		$row = mysqli_fetch_assoc($query);

		if ($row['count_rows'] != 1) {
			$errors[] = "Sisselogimine ebaõnnestus.";
		}

		if (empty($errors)) {
			
			$query = mysqli_query($connection, "SELECT name AS session_name FROM eprangel_blog_users WHERE email='$users_email'") or die("Ei saanud kasutaja nime.");
			$result = mysqli_fetch_assoc($query);

			$_SESSION['userNameForSession'] = $result['session_name'];

			$query = mysqli_query($connection, "SELECT id AS session_id FROM eprangel_blog_users WHERE email='$users_email'") or die("Ei saanud kasutaja id-d.");
			$result = mysqli_fetch_assoc($query);
			
			$_SESSION['usersIdForSession'] = $result['session_id'];

		}
		else {
			return $errors;
		}
	}
}

function logout() {
	session_destroy();
	unset($_SESSION['usersIdForSession']);
	$id = false;
}

?>

