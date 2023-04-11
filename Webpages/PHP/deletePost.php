<?php
    session_start();
    $realRequest = false;
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST["postID"])){
            $postID = $_POST["postID"];
            $realRequest = true;
        } else {
            echo "Missing Post ID";
        }
    } else {
        echo 'Faulty request';
    }
    if($realRequest){
        try{
            include('credentials.php');

            $mysqli = new mysqli($servername, $username, $password, $dbname);

            $conn = $mysqli->prepare("DELETE FROM posts WHERE postID = ?");
			$conn->bind_param('i', $postID);
			$conn->execute();
            $conn->close();

        }catch(mysqli_sql_exception $e){
            echo json_encode($e);
        }
    }
    
        
?>