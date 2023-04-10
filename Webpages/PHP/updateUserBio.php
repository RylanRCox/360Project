<?php
    session_start();
    include('credentials.php');
    $data = [];
    $errors = [];
    if (!empty($_POST)){
		$userBio=$_POST['userBio'];
	}
	if(empty($userBio)){
		$errors['userBio'] = "Add a valid bio";
        $data['message'] = $errors['userBio'];
        if(strlen($userBio) > 200){
            $errors['userBio'] = "User Bio is too long!";
            $data['message'] = $errors['userBio'];
        }
	}
    if (!empty($errors)) {
    	$data['success'] = false;
    	$data['errors'] = $errors;
	} else {
		try {
			$mysqli = new mysqli($servername, $username, $password, $dbname);
			$stmt = $mysqli->prepare("update users set userBio = ? where userID = ?;");
			$stmt->bind_param('si', $userBio,$_SESSION['userID']);
			$stmt->execute();	
			$data['success'] = TRUE;
			$data['message'] = 'User Bio Updated!';
		}catch(mysqli_sql_exception $e){
				//return failure
			$data['success'] = FALSE;
			$data['message'] = 'Unable to Connect to server';
		}
			$conn = null;

	}
    echo json_encode($data);
?>