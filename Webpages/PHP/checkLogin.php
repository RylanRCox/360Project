<?php
    session_start();
    include('credentials.php');
    
 
    $data = [];
    $errors = [];
    if (!empty($_POST)){
        $email=$_POST['email'];
        $pass=$_POST['pass'];
    }
    if(empty($email)){
        $errors['email'] = $email;
    }
    if(empty($pass)){
        $errors['pass'] = "Password is required.";
    }
    if (!empty($errors)) {
        $data['success'] = false;
        $data['errors'] = $errors;
    } else {
        try {
            //open connect
            $mysqli = new mysqli($servername, $username, $password, $dbname);
          
            // prepare sql and bind parameters
            $stmt = $mysqli->prepare("SELECT userID, displayName, isAdmin FROM users WHERE email = ? AND pass = ?");
            $pass = md5($pass);
            $stmt->bind_param('ss',$email,$pass);
            $stmt->execute();
            mysqli_stmt_bind_result($stmt, $userID, $displayName, $isAdmin);
            if(!mysqli_stmt_fetch($stmt)){
                $data['success'] = false;
                $data['message'] = 'No User Found.';
            }else{
                $data['success'] = true;
                $data['message'] = 'Successful Login!';
                $data['displayName'] = $displayName;
                $_SESSION['userID'] = $userID;
                $_SESSION['isAdmin'] = $isAdmin;
                $_SESSION['displayName'] = $displayName;
            }
        }catch(PDOException $e){
            //return failure
            $data['success'] = false;
            $data['errors'] = 'Unable to Connect to server'.$e;
        }
        $conn = null;

    }
    echo json_encode($data);
?>