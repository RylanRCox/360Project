<?php
    // Query string <img src="image.php?table=table&id=id"/>
    require('credentials.php');
    $out = "";

    switch($_GET['table']){
      case "posts":
        $out = 'SELECT images, imageType from posts where postID = ?';
        break;
      case "slices":
        $out = 'SELECT images, imageType from slices where sliceID = ?';
        break;
      case "users":
        $out = 'SELECT profileImage, imageType from users where userID = ?';
        break;
      default:
        $out = "";
        break;
    }

    $stmt = null;
      try{

      $mysqli = new mysqli($servername, $username, $password, $dbname);
      $stmt = $mysqli->prepare($out);
      $stmt->bind_param('s',$_GET['id']);
      $stmt->execute();	
      mysqli_stmt_bind_result($stmt,$image,$imageType);
      }catch(Exception $e){

      }

      if($stmt->fetch() != NULL && isset($imageType) && $imageType != NULL){
        header("Content-type: ".$imageType);
        echo $image;
      }else{
        $file = file_get_contents('..\images\noUser.jpg');
        header("Content-type: jpg");
        echo $file;
      }
?>