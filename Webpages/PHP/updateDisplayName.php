<?php
    session_start();
    include('credentials.php');
    $data = [];
    $errors = [];
    if (!empty($_POST)){
		$displayName=$_POST['displayName'];
	}
	if(empty($displayName)){
		$errors['displayName'] = "Add displayName";
        $data['message'] = $errors['displayName'];
	}
    if (!empty($errors)) {
    	$data['success'] = false;
    	$data['errors'] = $errors;
	} else {
		try {
			$mysqli = new mysqli($servername, $username, $password, $dbname);
			$stmt = $mysqli->prepare("update users set displayName = ? where userID = ?;");
			$stmt->bind_param('si', $displayName,$_SESSION['userID']);
			$stmt->execute();	
			$data['success'] = TRUE;
			$data['message'] = 'Display Name is Updated!';
		}catch(PDOException $e){
				//return failure
			$data['success'] = FALSE;
			$data['message'] = 'Unable to Connect to server';
		}
			$conn = null;

	}
    echo json_encode($data);
?>