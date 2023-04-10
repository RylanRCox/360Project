<?php
 
	$data = [];
	$errors = [];
	if (!empty($_GET)){
		$keyWord=$_GET['keyWord'];
	}
	if(empty($keyWord)){
		$errors['keyWord'] = $keyWord;
	}
	if (!empty($errors)) {
    	$data['success'] = false;
    	$data['errors'] = $errors;
	} else {
		try{
			include('credentials.php');
			$mysqli = new mysqli($servername, $username, $password, $dbname);

			$keyWord = '%'.$keyWord.'%';

			$conn = $mysqli->prepare("SELECT title, postID FROM posts WHERE title LIKE ? ORDER BY dateCreated DESC");
			$conn->bind_param('s', $keyWord);
			$conn->execute();
			$results = $conn->get_result();

			$arrayOfArrays = array();
			while($row = $results->fetch_assoc()){
				$postArray = array('post', $row["title"], $row["postID"]);
				array_push($arrayOfArrays, $postArray);
			}

			$conn = $mysqli->prepare("SELECT displayName, userID FROM users WHERE displayName LIKE ? ORDER BY dateCreated DESC");
			$conn->bind_param('s',$keyWord);
			$conn->execute();
			$results = $conn->get_result();

			while($row = $results->fetch_assoc()){
				$postArray = array('user', $row["displayName"], $row["userID"]);
				array_push($arrayOfArrays, $postArray);
			}

			$conn = $mysqli->prepare("SELECT sliceName, sliceID FROM slices WHERE sliceName LIKE ?");
			$conn->bind_param('s',$keyWord);
			$conn->execute();
			$results = $conn->get_result();

			while($row = $results->fetch_assoc()){
				$postArray = array('slice', $row["sliceName"], $row["sliceID"]);
				array_push($arrayOfArrays, $postArray);
			}

			echo json_encode($arrayOfArrays);
			$conn->close();
		}catch(mysqli_sql_exception $e){
			echo json_encode($e);
		}
	}
	
?>