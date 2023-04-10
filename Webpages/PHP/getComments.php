<?php
	$realRequest = false;
	if ($_SERVER["REQUEST_METHOD"] == "POST"){
		if( isset($_POST["postID"]) ){
			$postID = $_POST["postID"];
			$realRequest = true;
			$sql = "SELECT 
					comments.commentID, comments.content, comments.votes, comments.dateCreated, comments.commentParent, users.displayName, comments.userID
				FROM 
					comments 
					LEFT OUTER JOIN users 
						ON comments.userID = users.userID 
				WHERE comments.postID = ".$postID."
				ORDER BY comments.dateCreated DESC";
		} else if( isset($_POST["userID"])){
			$userID = $_POST["userID"];
			$realRequest = true;
			$sql = "SELECT comments.commentID, comments.content, comments.votes, comments.dateCreated, comments.commentParent, users.displayName, comments.userID, posts.title, posts.postID from users INNER JOIN comments ON users.userID = comments.userID INNER JOIN posts ON posts.postID = comments.postID where users.userID =".$userID." ORDER BY comments.dateCreated DESC";
		}else {
			echo "<script>alert(\"Missing Post ID\");</script>";
		}
	} else {
		echo '<script>alert(\"Faulty request\");</script>';
	}
	try{
		include('credentials.php');
		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		$results = $conn -> query($sql);
		$arrayOfArrays = array();
		while($row = $results->fetch_assoc()){
			if(isset($_POST["userID"]))
				$postArray = array($row['commentID'], $row['content'], $row['votes'], $row['dateCreated'], $row['commentParent'], $row['displayName'], $row['userID'], $row['title'], $row['postID']);
			else
				$postArray = array($row['commentID'], $row['content'], $row['votes'], $row['dateCreated'], $row['commentParent'], $row['displayName'], $row['userID']);
			
			array_push($arrayOfArrays, $postArray);
		}
		echo json_encode($arrayOfArrays);
		$conn->close();
	}catch(mysqli_sql_exception $e){
		echo json_encode($e);
	}
?>