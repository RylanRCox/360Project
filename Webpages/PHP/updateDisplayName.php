<?php
    session_start();
    include('credentials.php');
    $data = [];
    $errors = [];

	if ($_SERVER["REQUEST_METHOD"] == "POST"){
		if(isset($_POST['displayName'])){
			$displayName=$_POST['displayName'];
			if(strlen($displayName) > 25){
				$errors['displayName'] = "Display Name is too long!";
				$data['message'] = $errors['displayName'];
			} else if ($displayName == ""){
				$errors['displayName'] = "Display Name is empty!";
				$data['message'] = $errors['displayName'];
			}
		} else{
			$errors['displayName'] = "Add a Display Name";
			$data['message'] = $errors['displayName'];
		}
	} else {
		$errors['displayName'] = "Faulty Request";
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
			$_SESSION['displayName'] = $displayName;
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