<?php

$realRequest = false;
$isSlice = false;
$isUser = false;

if ($_SERVER["REQUEST_METHOD"] == "GET") {
	if (isset($_GET["sliceID"]) && isset($_GET['sortBy']) && isset($_GET['activeUser'])) {
		$sliceID = $_GET["sliceID"];
		$sortBy = $_GET['sortBy'];
		$activeUser = $_GET['activeUser'];
		$isSlice = true;
		$realRequest = true;
	} else if (isset($_GET["userID"]) && isset($_GET['sortBy']) && isset($_GET['activeUser'])) {
		$userID = $_GET["userID"];
		$sortBy = $_GET['sortBy'];
		$activeUser = $_GET['activeUser'];
		$isUser = true;
		$realRequest = true;
	} else if (isset($_GET['sortBy']) && isset($_GET['activeUser'])) {
		$sortBy = $_GET['sortBy'];
		$activeUser = $_GET['activeUser'];
		$realRequest = true;
	} else {
		echo 'Parameters Missing';
	}
} else {
	echo "Faulty request";
}
if ($realRequest) {


	try {

		include("credentials.php");
		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		//title 0, images 1, votes 2, dateCreated 3, sliceName 4, sliceImage 5, displayName 6, commentCount 7

		if ($isSlice) {
			$sql = "SELECT 
					posts.title, posts.votes, posts.dateCreated, slices.sliceName, slices.sliceImage, users.displayName, count, posts.postID, posts.userID, posts.sliceID, hider.hiddenBool
				FROM 
					posts 
					LEFT OUTER JOIN slices 
						ON posts.sliceID = slices.sliceID 
					LEFT OUTER JOIN users 
						ON posts.userID = users.userID
					LEFT JOIN (SELECT postID, COUNT(postID) AS count FROM comments GROUP BY postID HAVING count > 0) AS commentCounter ON posts.postID = commentCounter.postID
					LEFT OUTER JOIN (SELECT hiddenBool, postID FROM isHidden WHERE userID = ?) AS hider ON posts.postID = hider.postID WHERE posts.sliceID = ?";
			if ($sortBy == 0) {
				$sql .= " ORDER BY posts.dateCreated DESC";
			} else if ($sortBy == 1) {
				$sql .= " AND posts.votes >= 20 ORDER BY posts.votes DESC";
			}
			$stmt = $conn->prepare($sql);
			$stmt->bind_param('ii', $activeUser, $sliceID);
			$stmt->execute();
			
		} else if ($isUser) {
			$sql = "SELECT 
					posts.title, posts.votes, posts.dateCreated, slices.sliceName, slices.sliceImage, users.displayName, count, posts.postID, posts.userID, posts.sliceID, hider.hiddenBool
				FROM 
					posts 
					LEFT OUTER JOIN slices 
						ON posts.sliceID = slices.sliceID 
					LEFT OUTER JOIN users 
						ON posts.userID = users.userID
					LEFT JOIN (SELECT postID, COUNT(postID) AS count FROM comments GROUP BY postID HAVING count > 0) AS commentCounter ON posts.postID = commentCounter.postID
					LEFT OUTER JOIN (SELECT hiddenBool, postID FROM isHidden WHERE userID = ?) AS hider ON posts.postID = hider.postID WHERE posts.userID = ?";
			if ($sortBy == 0) {
				$sql .= " ORDER BY posts.dateCreated DESC";
			} else if ($sortBy == 1) {
				$sql .= "AND posts.votes >= 20 ORDER BY posts.votes DESC";
			}
			$stmt = $conn->prepare($sql);
			$stmt->bind_param('ii', $activeUser, $userID);
			$stmt->execute();
		} else {
			$sql = "SELECT 
					posts.title, posts.votes, posts.dateCreated, slices.sliceName, slices.sliceImage, users.displayName, count, posts.postID, posts.userID, posts.sliceID, hider.hiddenBool
				FROM 
					posts 
					LEFT OUTER JOIN slices 
						ON posts.sliceID = slices.sliceID 
					LEFT OUTER JOIN users 
						ON posts.userID = users.userID
					LEFT JOIN (SELECT postID, COUNT(postID) AS count FROM comments GROUP BY postID HAVING count > 0) AS commentCounter ON posts.postID = commentCounter.postID
					LEFT OUTER JOIN (SELECT hiddenBool, postID FROM isHidden WHERE userID = ?) AS hider ON posts.postID = hider.postID";
			if ($sortBy == 0) {
				$sql .= " ORDER BY posts.dateCreated DESC";
			} else if ($sortBy == 1) {
				$sql .= " WHERE posts.votes >= 20 ORDER BY posts.votes DESC";
			}
			$stmt = $conn->prepare($sql);
			$stmt->bind_param('i', $activeUser);
			$stmt->execute();
		}

		$results = $stmt->get_result();
		$arrayOfArrays = array();

		while ($row = $results->fetch_assoc()) {

			$postArray = array($row['postID'], $row["title"], $row["votes"], $row["dateCreated"], $row['sliceID'], $row["sliceName"], $row['sliceImage'], $row['userID'], $row['displayName'], $row['count'], $row["hiddenBool"]);
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