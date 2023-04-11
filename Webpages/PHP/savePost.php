<?php
//please work
	session_start();
	include('credentials.php');
	
	
	$data = [];
	$errors = [];
	$images = NULL;
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (isset($_POST['title']) && isset($_POST['sliceID'])) {
			$title=$_POST['title'];
			$content=$_POST['content'];
			$sliceID=$_POST['sliceID'];
			if (strlen($title) > 25) {
				$errors['title'] = "Title is too long!";
				$data['message'] = $errors['title'];
			} else if ($title == "") {
				$errors['title'] = "Title is empty!";
				$data['message'] = $errors['title'];
			}
			if (strlen($content) > 8000) {
				$errors['content'] = "Content is too long!";
				$data['message'] = $errors['content'];
			} else if ($content == "") {
				$errors['content'] = "Content is empty!";
				$data['message'] = $errors['content'];
			}
			if ($sliceID > 3 || $sliceID < 1) {
				$errors['sliceID'] = "Please choose a real slice!";
				$data['message'] = $errors['sliceID'];
			}
		} else if (!isset($_POST['title'])) {
			$errors['title'] = "Missing Email";
			$data['message'] = $errors['title'];
		} else if (!isset($_POST['content'])) {
			$errors['content'] = "Missing Content";
			$data['message'] = $errors['content'];
		} else if (!isset($_POST['sliceID'])) {
			$errors['sliceID'] = "Missing sliceID";
			$data['message'] = $errors['sliceID'];
		}
	} else {
		$errors['email'] = "Faulty Request";
		$data['message'] = $errors['email'];
	}
	//change file to base64 encoded item, check if large enough 
	if(!empty($_FILES["images"]["name"]) && !empty($_FILES["images"]["tmp_name"])) { 
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
			$errors['FileType'] = "Choose a jpg, png, or gif to upload.";
			$data["message"] = $errors['FileType'];
		} 
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
			$stmt = $mysqli->prepare("INSERT INTO posts VALUES (DEFAULT,?, ?, ?, ?, DEFAULT, DEFAULT, ?, ? )");
			$stmt->bind_param('ssssii',$_POST['title'],$_POST['content'], $images, $fileType, $_POST['sliceID'],$_SESSION['userID']);
			$stmt->execute();	
			$data['success'] = TRUE;
			$data['message'] = 'Post added!';
			mysqli_stmt_close($stmt);
		}catch(PDOException $e){
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
