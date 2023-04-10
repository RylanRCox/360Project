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

            $conn = $mysqli->prepare("UPDATE ? SET votes = votes + 1 WHERE ? = ?");
            if($postLike){
                $conn->bind_param('sss', 'posts', 'postID', $postID);
            }else if($commentLike){
                $conn->bind_param('sss', 'comments', 'commentID', $commentID);
            }
			
			$conn->execute();
            $conn->close();

        }catch(mysqli_sql_exception $e){
            echo json_encode($e);
        }
    }
    
        
?>