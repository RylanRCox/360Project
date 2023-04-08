<?php
    session_start();
    include('credentials.php');
    $data = [];
    $errors = [];
    if (!empty($_POST)){
		$email=$_POST['email'];
	}
	if(empty($email)){
		$errors['email'] = "Add Email";
        $data['message'] = $errors['email'];
	}
    if (!empty($errors)) {
    	$data['success'] = false;
    	$data['errors'] = $errors;
	} else {
		try {
			$mysqli = new mysqli($servername, $username, $password, $dbname);
			$stmt = $mysqli->prepare("update users set email = ? where userID = ?;");
			$stmt->bind_param('si', $email,$_SESSION['userID']);
			$stmt->execute();	
			$data['success'] = TRUE;
			$data['message'] = 'Email Updated!';
		}catch(PDOException $e){
				//return failure
			$data['success'] = FALSE;
			$data['message'] = 'Unable to Connect to server';
		}
			$conn = null;

	}
    echo json_encode($data);
?>