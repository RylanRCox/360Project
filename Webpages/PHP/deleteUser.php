<?php
    
    $realRequest = false;
	if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if( isset($_POST["userID"])){
            $userID = $_POST["userID"];
            if($userID != -1){
                $realRequest = true;
            } else {
                echo 'Cannot Delete [DELETED] user';
            }
            
        } else {
            echo "Missing Post ID";
        }
    } else {
        echo "Faulty request";
    }
    if($realRequest){
        try{
            include('credentials.php');
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            //title 0, images 1, votes 2, dateCreated 3, sliceName 4, sliceImage 5, displayName 6, commentCount 7

            $sql = "UPDATE comments SET userID = -1 WHERE userID = ?;";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $userID);
            $stmt->execute();
            $stmt->close();

            $sql = "UPDATE posts SET userID = -1 WHERE userID = ?;";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $userID);
            $stmt->execute();
            $stmt->close();

            $sql = "DELETE FROM users WHERE userID = ?;";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $userID); 
            echo $sql;
            $stmt->execute();
            echo 'Post execute';
            $stmt->close();
            $conn->close();
        }catch(mysqli_sql_exception $e){
            echo json_encode($e);
        }
    }
    
        
?>