<?php
	session_start();
	include('credentials.php');
	
	
	$data = [];
	$errors = [];
	$images = NULL;
	
	//change file to base64 encoded item, check if large enough 
	if(!empty($_FILES["images"]["name"]) && !empty($_FILES["images"]["tmp_name"]) ) { 
		// Get file info 
		$fileName = basename($_FILES["images"]["name"]); 
		$fileType = pathinfo($fileName, PATHINFO_EXTENSION); 
		 
		// Allow certain file formats 
		$allowTypes = array('jpg','png','jpeg','gif'); 
		if(in_array($fileType, $allowTypes)){ 
			$image = $_FILES['images']['tmp_name']; 
			$images = file_get_contents($image); 
			$size = strlen(addslashes(file_get_contents($image)));
			if($size >= 4294967295){
				$errors['FileSize'] = "File is too large"; 
				$data["message"] = $error['FileSize'];
			}
		}else{ 
			$errors['FileType'] = "FileType is required.";
			$data["message"] = $errors['FileType'];
			
		} 
	}else{
		$errors['fileExists'] = "No File found. Check your path.";
		$data['message'] = $errors['fileExists'];
	} 
	if (!empty($errors)) {
    	$data['success'] = false;
    	$data['errors'] = $errors;
	} else {
		try {
			$mysqli = new mysqli($servername, $username, $password, $dbname);
			if ($mysqli->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
			$stmt = $mysqli->prepare("UPDATE users SET profileImage = ?, imageType = ? WHERE userID = ? ");
			$stmt->bind_param('sss',$images, $fileType, $_SESSION['userID']);
			$stmt->execute();	
			$data['success'] = TRUE;
			$data['message'] = 'Image Updated!';
		}catch(mysqli_sql_exception $e){
				//return failure
			$data['success'] = FALSE;
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