<?php
 session_start();
 include('credentials.php');

try {

	$mysqli = new mysqli($servername, $username, $password, $dbname);

	$arrayOfArrays = array();

	$stmt = $mysqli->prepare("SELECT count(notificationUserID) AS notifyCount FROM notifications WHERE notificationUserID = ?");
	$stmt->bind_param('i', $_SESSION['userID']);
	$stmt->execute();	
	$result = $stmt->get_result();
	while ($row = $result->fetch_array()){
		$postArray = array($row['notifyCount']);
		array_push($arrayOfArrays, $postArray);
	}
	$stmt->close();

	$stmt = $mysqli->prepare("SELECT displayName, postID, commentParentID FROM notifications LEFT OUTER JOIN users ON commentingUserID = users.userID WHERE notificationUserID = ?");
	$stmt->bind_param('i', $_SESSION['userID']);
	$stmt->execute();	
	$result = $stmt->get_result();
	
	while ($row = $result->fetch_array()){
		$postArray = array($row['displayName'], $row['postID'], $row['commentParentID']);
		array_push($arrayOfArrays, $postArray);
	}
	echo json_encode($arrayOfArrays);
	$stmt->close();
}catch(PDOException $e){
	//return failure
	$data['success'] = FALSE;
	$data['message'] = 'Unable to Connect to server';
}

?>