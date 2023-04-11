<?php
    session_start();
    include('credentials.php');
    $data = [];
    $errors = [];
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
		if(isset($_POST['userBio'])){
			$userBio=$_POST['userBio'];
			if(strlen($userBio) > 200){
				$errors['userBio'] = "User Bio is too long!";
				$data['message'] = $errors['userBio'];
			} else if ($userBio == ""){
				$errors['userBio'] = "User Bio is empty!";
				$data['message'] = $errors['userBio'];
			}
		} else{
			$errors['userBio'] = "Add a valid bio";
			$data['message'] = $errors['userBio'];
		}
	} else {
		$errors['userBio'] = "Faulty Request";
		$data['message'] = $errors['userBio'];
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