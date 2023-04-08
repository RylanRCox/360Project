<?php
    $realRequest = false;
	if ($_SERVER["REQUEST_METHOD"] == "GET"){
        if( isset($_GET["userID"])){
            $userID = $_GET["userID"];
            $realRequest = true;
        } else {
            echo "<script>alert(\"Missing Post ID\");</script>";
        }
    } else if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if( isset($_POST["userID"])){
            $userID = $_POST["userID"];
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
            $sql = "SELECT displayName FROM users WHERE userID = ".$userID;
            $results = $conn -> query($sql);

            while($row = $results->fetch_assoc()){
                $returnArray = array($row["displayName"]);
            }
            echo json_encode($returnArray);
            $conn->close();
        }catch(mysqli_sql_exception $e){
         echo json_encode($e);
        }
    }
    
?>