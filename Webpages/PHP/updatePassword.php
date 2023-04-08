<?php
    session_start();
    include('credentials.php');
    $data = [];
    $errors = [];
    if (!empty($_POST)){
		$pass=$_POST['pass'];
	}
	if(empty($pass)){
		$errors['pass'] = "Add pass";
        $data['message'] = $errors['pass'];
	}
    if (!empty($errors)) {
    	$data['success'] = false;
    	$data['errors'] = $errors;
	} else {
		try {
			$mysqli = new mysqli($servername, $username, $password, $dbname);
			$stmt = $mysqli->prepare("update users set pass = ? where userID = ?;");
			$pass = md5($pass);
			$stmt->bind_param('si', $pass,$_SESSION['userID']);
			$stmt->execute();	
			$data['success'] = TRUE;
			$data['message'] = 'Password is Updated!';
		}catch(PDOException $e){
				//return failure
			$data['success'] = FALSE;
			$data['message'] = 'Unable to Connect to server';
		}
			$conn = null;

	}
    echo json_encode($data);
?>