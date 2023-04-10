<?php
    $realRequest = false;
	if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if( isset($_POST["postID"])){
            $postID = $_POST["postID"];
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
            $sql = "SELECT 
                        posts.title, posts.content, posts.votes, posts.dateCreated, slices.sliceName, users.displayName, count, posts.sliceID, posts.userID, posts.imageType
                    FROM 
                        posts 
                        LEFT OUTER JOIN slices 
                            ON posts.sliceID = slices.sliceID 
                        LEFT OUTER JOIN users 
                            ON posts.userID = users.userID
                        LEFT JOIN (SELECT postID, COUNT(postID) AS count FROM comments GROUP BY postID HAVING count > 0) AS commentCounter ON posts.postID = commentCounter.postID WHERE posts.postID = ".$postID." ORDER BY posts.dateCreated DESC";
            $results = $conn -> query($sql);

            while($row = $results->fetch_assoc()){
                $postArray = array($row["title"], $row["content"], $row["votes"], $row["dateCreated"], $row["sliceName"], $row['displayName'], $row['count'], $row['sliceID'], $row['userID'], $row['imageType']);
            }
            echo json_encode($postArray);
            $conn->close();
        }catch(mysqli_sql_exception $e){
         echo json_encode($e);
        }
    }
    
?>