<?php
    include('credentials.php');
    $realRequest = false;
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST["commentID"])){
            $commentID = $_POST["commentID"];
            $realRequest = true;
            echo $commentID;
        } else {
            echo "Missing Comment ID";
        }
    } else {
        echo 'Faulty request';
    }
    if($realRequest){
        try{
            $mysqli = new mysqli($servername, $username, $password, $dbname);

            $conn = $mysqli->prepare("UPDATE comments SET userID = -1, content = '[Deleted]' WHERE commentID = ?");
			$conn->bind_param('i', $commentID);
			$conn->execute();

            $conn->close();
        }catch(mysqli_sql_exception $e){
            echo json_encode($e);
        }
    }
    
        
?>