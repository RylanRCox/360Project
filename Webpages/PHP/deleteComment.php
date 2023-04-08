<?php
    //$sql = "SELECT * FROM posts JOIN slice ON posts.sliceID = slices.sliceID WHERE votes > 20 ORDER BY votes DESC"
    //postID 0, title 1, content 2, images 3, votes 4, dateCreated 5, sliceID 6, userID 7, sliceName 8, sliceImage 9
    $realRequest = false;
	if ($_SERVER["REQUEST_METHOD"] == "GET"){
        if( isset($_GET["commentID"]) && isset($_GET["commentID"])){
            $commentID = $_GET["commentID"];
            $realRequest = true;
        } else {
            echo "<script>alert(\"Missing Post ID\");</script>";
        }
    } else if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if( isset($_POST["commentID"]) && isset($_POST["commentID"])){
            $commentID = $_POST["commentID"];
            $realRequest = true;
        } else {
            echo "<script>alert(\"Missing Post ID\");</script>";
        }
    } else {
        echo '<script>alert(\"Faulty request\");</script>';
    }
    if($realRequest){
        try{
            include('credentials.php');
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            //title 0, images 1, votes 2, dateCreated 3, sliceName 4, sliceImage 5, displayName 6, commentCount 7
            $sql = "DELETE FROM comments WHERE commentID = ".$commentID;
            if($conn -> query($sql) === TRUE){
                echo "Record ".$commentID." deleted successfully";
            } else {
                echo "Failure to delete record";
            }
            $conn->close();
        }catch(mysqli_sql_exception $e){
            echo json_encode($e);
        }
    }
    
        
?>