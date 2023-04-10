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
		 if($commentParent == 0){
			 $commentParent = null;
		 }
		 $mysqli = new mysqli($servername, $username, $password, $dbname);
		 $stmt = $mysqli->prepare("INSERT INTO comments VALUES (DEFAULT, ?, DEFAULT, DEFAULT, ?, ?, ?)");
		 $stmt->bind_param('siii', $content, $postID, $commentParent, $_SESSION['userID']);
		 $stmt->execute();	
		 $data['success'] = TRUE;
		 $data['message'] = 'Comment Submitted';
	 }catch(PDOException $e){
			 //return failure
		 $data['success'] = FALSE;
		 $data['message'] = 'Unable to Connect to server';
	 }
		 $conn = null;

 }
 echo json_encode($data);
 if($_SESSION['userID'] == -1){
	 echo '<script>alert("Please Sign in to Comment on Posts");</script>';
	 header('refresh:0;url=../signIn.php');
 } else {
	 header('refresh:0;url=../post.php?postID='.$postID);
 }

?>