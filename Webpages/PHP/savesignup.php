<?php
session_start();
include('credentials.php');

$data = [];
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST['email']) && isset($_POST['pass']) && isset($_POST['displayName'])) {
		$email = $_POST['email'];
		$pass = $_POST['pass'];
		$displayName = $_POST['displayName'];
		if (strlen($email) > 350) {
			$errors['email'] = "Email is too long!";
			$data['message'] = $errors['email'];
		} else if ($email == "") {
			$errors['email'] = "Email is empty!";
			$data['message'] = $errors['email'];
		}
		if (strlen($pass) > 100) {
			$errors['pass'] = "Password is too long!";
			$data['message'] = $errors['pass'];
		} else if ($pass == "") {
			$errors['pass'] = "Password is empty!";
			$data['message'] = $errors['pass'];
		}
		if (strlen($displayName) > 25) {
			$errors['displayName'] = "DisplayName is too long!";
			$data['message'] = $errors['displayName'];
		} else if ($displayName == "") {
			$errors['displayName'] = "DisplayName is empty!";
			$data['message'] = $errors['displayName'];
		}
	} else if (!isset($_POST['email'])) {
		$errors['email'] = "Missing Email";
		$data['message'] = $errors['email'];
	} else if (!isset($_POST['pass'])) {
		$errors['pass'] = "Missing Password";
		$data['message'] = $errors['pass'];
	} else if (!isset($_POST['displayName'])) {
		$errors['displayName'] = "Missing DisplayName";
		$data['message'] = $errors['displayName'];
	}
} else {
	$errors['email'] = "Faulty Request";
	$data['message'] = $errors['email'];
}

if (!empty($errors)) {
	$data['success'] = false;
	$data['errors'] = $errors;
	$data['message'] = $errors;
} else {
	try {

		$pass = md5($pass);
		//open connect
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		// prepare sql and bind parameters
		$stmt = $conn->prepare("INSERT INTO users (userID, email, pass, displayName, profileImage, isAdmin, dateCreated)
			VALUES (:userID, :email, :pass, :displayName, NULL, :isAdmin, :dateCreated)");
		$stmt->bindParam(':userID', $userID);
		$stmt->bindParam(':email', $email);
		$stmt->bindParam(':pass', $pass);
		$stmt->bindParam(':displayName', $displayName);
		$stmt->bindParam(':isAdmin', $isAdmin);
		$stmt->bindParam(':dateCreated', $dateCreated);
		$userID = "DEFAULT";
		$isAdmin = "DEFAULT";
		$dateCreated = "DEFAULT";
		$stmt->execute();

		//return success 
		$data['success'] = true;
		$data['message'] = 'Successful SignUp! Redirecting...';

	} catch (PDOException $e) {
		//return failure
		$data['success'] = false;
		$data['message'] = 'Unable to Connect to server';
	}
	$conn = null;

}

echo json_encode($data);
?>