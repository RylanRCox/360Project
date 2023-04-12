<?php
	$realRequest = false;
	$post = false;
	$comment = false;
	
	if ($_SERVER["REQUEST_METHOD"] == "POST"){
		if(isset($_POST["pID"]) && isset($_POST["uID"])){
			$postID = $_POST["pID"];
			$userID = $_POST["uID"];
			$post = true;
			$realRequest = true;
		} else if (isset($_POST["cID"]) && isset($_POST["uID"])){
			$commentID = $_POST["cID"];
			$userID = $_POST["uID"];
			$comment = true;
			$realRequest = true;
		} else {
			echo 'Missing ID';
		}
	} else {
		echo 'Faulty request';
	}
	if($realRequest){
		try{
			
			include('credentials.php');

			$conn = mysqli_connect($servername, $username, $password, $dbname);
			
			$error = mysqli_connect_error();
			if($error != null)
			{
				$output = 'Unable to connect to databse!';
				exit($output);
			} else {
				if($post){

					$sql = "SELECT * FROM voted WHERE postID = ? AND userID = ?";
					$stmt = mysqli_prepare($conn, $sql);
					mysqli_stmt_bind_param($stmt, 'ii', $postID, $userID);
					mysqli_stmt_execute($stmt);
					if(mysqli_stmt_fetch($stmt)){
						$stmt->close();
						echo 'true';
					} 
				}else if($comment){

					$sql = "SELECT * FROM voted WHERE commentID = ? AND userID = ?";
					$stmt = mysqli_prepare($conn, $sql);
					mysqli_stmt_bind_param($stmt, 'ii', $commentID, $userID);
					mysqli_stmt_execute($stmt);
					if(mysqli_stmt_fetch($stmt)){
						$stmt->close();
						echo 'true';
					} 
					
				}
			}

			
			mysqli_close($conn);

		}catch(mysqli_sql_exception $e){
			echo json_encode($e);
		}
	}
	
		
?>