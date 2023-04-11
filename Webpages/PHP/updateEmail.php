<?php
session_start();
include('credentials.php');
$data = [];
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST['email'])) {
		$email = $_POST['email'];
		if (strlen($email) > 350) {
			$errors['email'] = "Email is too long!";
			$data['message'] = $errors['email'];
		} else if ($email == "") {
			$errors['email'] = "Email is empty!";
			$data['message'] = $errors['email'];
		}
	} else {
		$errors['email'] = "Add Email";
		$data['message'] = $errors['email'];
	}
} else {
	$errors['email'] = "Faulty Request";
	$data['message'] = $errors['email'];
}
if (!empty($errors)) {
	$data['success'] = false;
	$data['errors'] = $errors;
} else {
	try {

		$mysqli = new mysqli($servername, $username, $password, $dbname);
		if ($mysqli->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		$stmt = $mysqli->prepare("SELECT * FROM users WHERE email = ?;");
		$stmt->bind_param('s', $email);
		$stmt->execute();

		$results = $stmt->get_result();

		if (!$results->fetch_assoc()) {
			$stmt->close();
			$stmt = $mysqli->prepare("update users set email = ? where userID = ?;");
			$stmt->bind_param('si', $email, $_SESSION['userID']);
			$stmt->execute();
			$data['success'] = TRUE;
			$data['message'] = 'Email Updated!';
		} else {
			$errors['email'] = "Email taken";
			$data['message'] = $errors['email'];

			$data['success'] = false;
			$data['errors'] = $errors;
		}


	} catch (mysqli_sql_exception $e) {
		//return failure
		$data['success'] = FALSE;
		$data['message'] = 'Unable to Connect to server';
	}
	$conn = null;

}
echo json_encode($data);
?>