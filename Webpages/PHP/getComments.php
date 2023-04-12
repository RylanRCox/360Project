<?php
$realRequest = false;
$isPost = false;
$isUser = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST["postID"])) {
		$postID = $_POST["postID"];
		$realRequest = true;
		$isPost = true;
	} else if (isset($_POST["userID"])) {
		$userID = $_POST["userID"];
		$realRequest = true;
		$isUser = true;
	} else {
		echo "Missing Post ID";
	}
} else {
	echo "Faulty request";
}
if ($realRequest) {
	try {
		include('credentials.php');
		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		if ($isPost) {
			$sql = "SELECT 
					comments.commentID, comments.content, comments.votes, comments.dateCreated, comments.commentParent, users.displayName, comments.userID
					FROM 
						comments 
					LEFT OUTER JOIN 
						users ON comments.userID = users.userID 
					WHERE 
						comments.postID = ?
					ORDER BY 
						comments.dateCreated DESC";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param('i', $postID);
			$stmt->execute();
		} else if ($isUser) {
			$sql = "SELECT 
						comments.commentID, comments.content, comments.votes, comments.dateCreated, comments.commentParent, users.displayName, comments.userID, posts.title, posts.postID 
					FROM 
						users 
					INNER JOIN 
						comments ON users.userID = comments.userID 
					INNER JOIN 
						posts ON posts.postID = comments.postID 
					WHERE 
						users.userID = ? 
					ORDER BY 
						comments.dateCreated DESC";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param('i', $userID);
			$stmt->execute();
		}




		$results = $stmt->get_result();
		$arrayOfArrays = array();
		while ($row = $results->fetch_assoc()) {
			if (isset($_POST["userID"]))
				$postArray = array($row['commentID'], $row['content'], $row['votes'], $row['dateCreated'], $row['commentParent'], $row['displayName'], $row['userID'], $row['title'], $row['postID']);
			else
				$postArray = array($row['commentID'], $row['content'], $row['votes'], $row['dateCreated'], $row['commentParent'], $row['displayName'], $row['userID']);

			array_push($arrayOfArrays, $postArray);
		}
		echo json_encode($arrayOfArrays);
		$conn->close();
		$stmt->close();
	} catch (mysqli_sql_exception $e) {
		echo json_encode($e);
	}
}
?>