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
        } else if (isset($_POST["commentID"]) && isset($_POST["userID"])){
            $commentID = $_POST["commentID"];
            $userID = $_POST["userID"];
            $commentLike = true;
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

            $mysqli = new mysqli($servername, $username, $password, $dbname);
            
            if($postLike){
                $stmt = $mysqli->prepare("UPDATE posts SET votes = votes + 1 WHERE postID = ?");
                $stmt->bind_param('i', $postID);
            }else if($commentLike){
                $stmt = $mysqli->prepare("UPDATE comments SET votes = votes + 1 WHERE commentID = ?");
                $stmt->bind_param('i', $commentID);
            }

			$stmt->execute();
            $stmt->close();
            

        }catch(mysqli_sql_exception $e){
            echo json_encode($e);
        }
    }
    
        
?>