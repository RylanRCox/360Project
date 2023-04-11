<?php
session_start();
include('credentials.php');
$data = [];
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST['postID']) && isset($_SESSION['userID'])) {
		$userID = $_SESSION['userID'];
		$postID = $_POST['postID'];
	} else if (!isset($_POST['postID'])){
		$errors['$postID'] = 'Missing Post ID';
	} else if (!isset($_SESSION['userID'])){
		$errors['$userID'] = 'Missing User ID';
	}
} else {
	$errors['postID'] = "Faulty Request";
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

		$stmt = $mysqli->prepare("DELETE FROM notifications WHERE notificationUserID = ? AND postID = ?");
		$stmt->bind_param('ii', $userID, $postID);
		$stmt->execute();

		$stmt->close();
	} catch (PDOException $e) {
		//return failure
		$data['success'] = FALSE;
		$data['message'] = 'Unable to Connect to server';
	}
}
?>