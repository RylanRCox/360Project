<?php
	$realRequest = false;
	$postLike = false;
	$commentLike = false;
	
	if ($_SERVER["REQUEST_METHOD"] == "POST"){
		if(isset($_POST["postID"]) && isset($_POST["userID"])){
			$postID = $_POST["postID"];
			$userID = $_POST["userID"];
			$postLike = true;
			$realRequest = true;
		} else {
			echo "Missing ID";
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
					
				$sql = "SELECT * FROM isHidden WHERE userID = ? AND postID = ?";
				$stmt = mysqli_prepare($conn, $sql);
					
				mysqli_stmt_bind_param($stmt, 'ii', $userID, $postID);
				mysqli_stmt_execute($stmt);

				$result = mysqli_stmt_get_result($stmt);
				while($row = mysqli_fetch_array($result)){
					echo $row;
				}
				

				$stmt->close();
			}
			
			mysqli_close($conn);

		}catch(mysqli_sql_exception $e){
			echo json_encode($e);
		}
	}
	
		
?>