<?php
 session_start();
 include('credentials.php');
 $data = [];
 $errors = [];
 if (!empty($_POST)){
	$commentParent=(int)$_POST['commentParent'];
	$content= $_POST['content'];
	$postID = (int) $_POST['postID'];
	
 }
 if(empty($content)){
	$errors['content'] = "No Content";
	$data['message'] = $errors['content'];
}
if($_SESSION['userID'] == -1 ){
	$errors['session'] = "Log in";
	$data['message'] = $errors['session'];
}
 if (!empty($errors)) {
	 $data['success'] = false;
	 $data['errors'] = $errors;

 } else {
	 try {
		 echo "Comment Parent: ".$commentParent." Content: ".$content." postID: ".$postID." UserID: ".$_SESSION['userID'];

		 $mysqli = new mysqli($servername, $username, $password, $dbname);
		 if($commentParent == 0){
			 $commentParent = null;
			 $stmt = $mysqli->prepare("SELECT userID FROM posts WHERE postID = ?");
			 $stmt->bind_param('i', $postID);
			 $stmt->execute();
			 $result = $stmt->get_result();
			 while ($row = $result->fetch_array()){
				 $notifyUser = $row[0];
			 }
			 $stmt->close();
		 } else {
			 $stmt = $mysqli->prepare("SELECT userID FROM comments WHERE commentID = ?");
			 $stmt->bind_param('i', $commentParent);
			 $stmt->execute();
			 $result = $stmt->get_result();
			 while ($row = $result->fetch_array()){
				 $notifyUser = $row[0];
			 }
			 $stmt->close();
		 }

		 $stmt = $mysqli->prepare("INSERT INTO notifications VALUES (DEFAULT, ?, ?, ?, ?)");
		 $stmt->bind_param('iiii', $_SESSION['userID'], $notifyUser, $postID, $commentParent);
		 $stmt->execute();	
		 $stmt->close();

		 
		 $stmt = $mysqli->prepare("INSERT INTO comments VALUES (DEFAULT, ?, DEFAULT, DEFAULT, ?, ?, ?)");
		 $stmt->bind_param('siii', $content, $postID, $commentParent, $_SESSION['userID']);
		 $stmt->execute();	
		 $data['success'] = TRUE;
		 $data['message'] = 'Comment Submitted';
		 $stmt->close();
	 }catch(PDOException $e){
			 //return failure
		 $data['success'] = FALSE;
		 $data['message'] = 'Unable to Connect to server';
	 }

 }
 echo json_encode($data);
 if($_SESSION['userID'] == -1){
	 echo '<script>alert("Please Sign in to Comment on Posts");</script>';
	 header('refresh:0;url=../signIn.php');
 } else {
	 header('refresh:0;url=../post.php?postID='.$postID);
 }

?>