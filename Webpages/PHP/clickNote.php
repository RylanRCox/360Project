<?php
 session_start();
 include('credentials.php');
 $data = [];
$errors = [];
if (!empty($_POST)){
    $postID=$_POST['postID'];
}
if(empty($postID)){
    $errors['$postID'] = $postID;
}
if (!empty($errors)) {
    $data['success'] = false;
    $data['errors'] = $errors;
} else {

    try {
		

	    $mysqli = new mysqli($servername, $username, $password, $dbname);

	    $stmt = $mysqli->prepare("DELETE FROM notifications WHERE notificationUserID = ? AND postID = ?");
	    $stmt->bind_param('ii', $_SESSION['userID'], $postID);
	    $stmt->execute();	
	
	    $stmt->close();
    }catch(PDOException $e){
	    //return failure
	    $data['success'] = FALSE;
	    $data['message'] = 'Unable to Connect to server';
    }
}
?>