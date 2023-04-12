<?php
session_start();
include('credentials.php');
$data = [];
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST['commentParent']) && isset($_POST['content']) && isset($_POST['postID']) && isset($_SESSION['userID'])) {
		$commentParent = (int) $_POST['commentParent'];
		$content = $_POST['content'];
		$postID = (int) $_POST['postID'];
		$userID = $_SESSION['userID'];
		if ($userID == -1) {

			$errors['userID'] = "Log in";
			$data['message'] = $errors['userID'];

		} else if (strlen($content) > 8000) {

			$errors['content'] = "Content too long";
			$data['message'] = $errors['content'];

		} else if ($content == "") {

			$errors['content'] = "Content Missing";
			$data['message'] = $errors['content'];

		}
	} else if (!isset($_POST['commentParent'])) {

		$errors['commentParent'] = "Missing Comment Parent";
		$data['message'] = $errors['commentParent'];

	} else if (!isset($_POST['content'])) {

		$errors['content'] = "Missing Content";
		$data['message'] = $errors['content'];

	} else if (!isset($_POST['postID'])) {

		$errors['postID'] = "Missing Post ID";
		$data['message'] = $errors['postID'];

	} else if (!isset($_SESSION['userID'])) {

		$errors['userID'] = "Missing User ID";
		$data['message'] = $errors['userID'];

	}
} else {
	$errors['postID'] = "Faulty Request";
	$data['message'] = $errors['postID'];
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
		if ($commentParent == 0) {
			$commentParent = null;
			$stmt = $mysqli->prepare("SELECT userID FROM posts WHERE postID = ?");
			$stmt->bind_param('i', $postID);
			$stmt->execute();
			$result = $stmt->get_result();
			while ($row = $result->fetch_array()) {
				$notifyUser = $row[0];
			}
			$stmt->close();
		} else {
			$stmt = $mysqli->prepare("SELECT userID FROM comments WHERE commentID = ?");
			$stmt->bind_param('i', $commentParent);
			$stmt->execute();
			$result = $stmt->get_result();
			while ($row = $result->fetch_array()) {
				$notifyUser = $row[0];
			}
			$stmt->close();
		}
		if ($notifyUser != -1) {
			$stmt = $mysqli->prepare("INSERT INTO notifications VALUES (DEFAULT, ?, ?, ?, ?)");
			$stmt->bind_param('iiii', $userID, $notifyUser, $postID, $commentParent);
			$stmt->execute();
			$stmt->close();

		}
		$stmt = $mysqli->prepare("INSERT INTO comments VALUES (DEFAULT, ?, DEFAULT, DEFAULT, ?, ?, ?)");
		$stmt->bind_param('siii', $content, $postID, $commentParent, $userID);
		$stmt->execute();
		$data['success'] = TRUE;
		$data['message'] = 'Comment Submitted';
		$stmt->close();
	} catch (mysqli_sql_exception $e) {
		//return failure
		$data['success'] = FALSE;
		$data['message'] = 'Unable to Connect to server';
	}

}
echo json_encode($data);
if ($_SESSION['userID'] == -1) {
	echo '<script>alert("Please Sign in to Comment on Posts");</script>';
	header('refresh:0;url=../signIn.php');
} else {
	header('refresh:0;url=../post.php?postID=' . $postID);
}

?>