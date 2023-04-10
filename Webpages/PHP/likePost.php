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

            $conn = mysqli_connect($servername, $username, $password, $dbname);
            
            $error = mysqli_connect_error();
            if($error != null)
            {
                $output = 'Unable to connect to databse!';
                exit($output);
            } else {
                if($postLike){
                    $sql = "UPDATE posts SET votes = votes + 1 WHERE postID = ?";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, 'i', $postID);
                }else if($commentLike){
                    $sql = "UPDATE comments SET votes = votes + 1 WHERE commentID = ?";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, 'i', $commentID);
                }
            }

            mysqli_stmt_execute($stmt);
            $stmt->close();
            
            if($postLike){
                $sql = "INSERT INTO voted VALUES(default, ?, null, ?)";
                
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, 'ii', $postID, $userID);
                
            }else if($commentLike){
                $sql = "INSERT INTO voted VALUES(default, null, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, 'ii', $commentID, $userID);
            }
            $result = mysqli_stmt_execute($stmt) or die(mysqli_stmt_error($stmt));

            echo $result;

            $stmt->close();
            mysqli_close($conn);

        }catch(mysqli_sql_exception $e){
            echo json_encode($e);
        }
    }
    
        
?>