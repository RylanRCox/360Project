<?php

    $isSlice = false;
    $isUser = false;

	if ($_SERVER["REQUEST_METHOD"] == "GET"){
        if(isset($_GET["sliceID"])){
            $sliceID = $_GET["sliceID"];
            $isSlice = true;
        } else if(isset($_GET["userID"])){
            $userID = $_GET["userID"];
            $isUser = true;
        }
    } else if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST["sliceID"])){
            $sliceID = $_POST["sliceID"];
            $isSlice = true;
        } else if(isset($_POST["userID"])){
            $userID = $_POST["userID"];
            $isUser = true;
        }
    } else {
        echo '<script>alert(\"Faulty request\");</script>';
    }
    try{
            
        include("credentials.php");
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        //title 0, images 1, votes 2, dateCreated 3, sliceName 4, sliceImage 5, displayName 6, commentCount 7
        $sql = "SELECT 
                    posts.title, posts.votes, posts.dateCreated, slices.sliceName, slices.sliceImage, users.displayName, count, posts.postID, posts.userID, posts.sliceID
                FROM 
                    posts 
                    LEFT OUTER JOIN slices 
                        ON posts.sliceID = slices.sliceID 
                    LEFT OUTER JOIN users 
                        ON posts.userID = users.userID
                    LEFT JOIN (SELECT postID, COUNT(postID) AS count FROM comments GROUP BY postID HAVING count > 0) AS commentCounter ON posts.postID = commentCounter.postID";

        if($isSlice){
            $sql .= " WHERE posts.sliceID = ".$sliceID;
        } else if($isUser){
            $sql .= " WHERE posts.userID = ".$userID;
        }

        $sql .= " ORDER BY posts.dateCreated DESC";
        $results = $conn -> query($sql);

        

        $arrayOfArrays = array();
        while($row = $results->fetch_assoc()){

            $postArray = array($row['postID'], $row["title"], $row["votes"], $row["dateCreated"], $row['sliceID'], $row["sliceName"], $row['sliceImage'], $row['userID'], $row['displayName'], $row['count']);
            array_push($arrayOfArrays, $postArray);
        }
        echo json_encode($arrayOfArrays);
        $conn->close();
    } catch(mysqli_sql_exception $e){
        echo json_encode($e);
    }
    
        
?>