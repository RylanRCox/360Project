<?php
	include('credentials.php');

	$data = [];
	$errors = [];
	if (!empty($_POST)){
		$email=$_POST['email'];
		$pass=md5($_POST['pass']);
		$displayName=$_POST['displayName'];
	}
	if(empty($email)){
		$errors['email'] = $email;
	}
	if(empty($pass)){
		$errors['pass'] = "Password is required.";
	}
	if(empty($displayName)){
		$errors['displayName'] = "Username is required.";
	}
	if (!empty($errors)) {
    	$data['success'] = false;
    	$data['errors'] = $errors;
		$data['message'] = $errors;
	} else {
		try {
			//open connect
			$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		  
			// prepare sql and bind parameters
			$stmt = $conn->prepare("INSERT INTO users (userID, email, pass, displayName, profileImage, isAdmin, dateCreated)
			VALUES (:userID, :email, :pass, :displayName, NULL, :isAdmin, :dateCreated)");
			$stmt->bindParam(':userID', $userID);
			$stmt->bindParam(':email', $email);
			$stmt->bindParam(':pass', $pass);
			$stmt->bindParam(':displayName', $displayName);
			$stmt->bindParam(':isAdmin', $isAdmin);
			$stmt->bindParam(':dateCreated', $dateCreated);
			$userID = "DEFAULT";
			$isAdmin = "DEFAULT";
			$dateCreated = "DEFAULT";
			$stmt->execute();
		  
		//return success 
    	$data['success'] = true;
    	$data['message'] = 'Successful SignUp! Redirecting...';
		
		}catch(PDOException $e){
			//return failure
			$data['success'] = false;
			$data['message'] = 'Unable to Connect to server';
		}
		$conn = null;

	}



	
	/*
	$sql = "SELECT * FROM users";
	if (mysqli_query($conn, $sql)) {
		echo json_encode(array("statusCode"=>200));
	} 
	else {
		echo json_encode(array("statusCode"=>201));
	}
	mysqli_close($conn);
	*/
	echo json_encode($data);
?>