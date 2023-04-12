<?php
    $realRequest = false;
	if ($_SERVER["REQUEST_METHOD"] == "GET"){
        if( isset($_GET["userID"])){
            $userID = $_GET["userID"];
            $realRequest = true;
        } else {
            echo "Missing Post ID";
        }
    } else if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if( isset($_POST["userID"])){
            $userID = $_POST["userID"];
            $realRequest = true;
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
            $sql = "SELECT displayName, userBio FROM users WHERE userID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $userID);
            $stmt->execute();
            $results = $stmt ->get_result();
            while($row = $results->fetch_assoc()){
                $returnArray = array($row["displayName"],$row["userBio"]);
            }
            echo json_encode($returnArray);
            $conn->close();
        }catch(mysqli_sql_exception $e){
         echo json_encode($e);
        }
    }
    
?>