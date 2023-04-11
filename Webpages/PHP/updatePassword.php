<?php
    session_start();
    include('credentials.php');
    $data = [];
    $errors = [];

	if ($_SERVER["REQUEST_METHOD"] == "POST"){
		if(isset($_POST['pass'])){
			$pass=$_POST['pass'];
			if(strlen($pass) > 100){
				$errors['pass'] = "Password is too long!";
				$data['message'] = $errors['pass'];
			} else if ($pass == ""){
				$errors['pass'] = "Password is empty!";
				$data['message'] = $errors['pass'];
			}
		} else{
			$errors['pass'] = "Add pass";
			$data['message'] = $errors['pass'];
		}
	} else {
		$errors['pass'] = "Faulty Request";
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
		}catch(mysqli_sql_exception $e){
				//return failure
			$data['success'] = FALSE;
			$data['message'] = 'Unable to Connect to server';
		}
			$conn = null;

	}
    echo json_encode($data);
?>