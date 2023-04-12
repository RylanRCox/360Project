<?php
    namespace App;

    class functions{

        function checkLogin($email,$pass){
            
            $_SERVER["REQUEST_METHOD"] = "POST";
            $_POST['email'] = $email;
            $_POST['pass'] = $pass;
            require('checkLogin.php');
            
            return $data['success'];
        }

        function getUser($userID){
            $_SERVER["REQUEST_METHOD"] = "POST";
            $_POST['userID'] = $userID;
            require('getUser.php');
            $_SERVER["REQUEST_METHOD"] = NULL;
            $_POST['userID'] = NULL;
            return $returnArray[0];
        }

        function addComment($parent,$content, $postID){
            $_SERVER["REQUEST_METHOD"] = "POST";
            $_POST['commentParent'] = $parent;
            $_POST['content'] = $content;
            $_POST['postID'] = $postID;
            $_SESSION['userID'] = 1;
            require('addComment.php');
            $_POST['commentParent'] = NULL;
            $_POST['content'] = NULL;
            $_POST['postID'] = NULL;
            $_SESSION['userID'] = 1;
            return $data['success'];
        }

        function deleteHidden($userID, $postID){
            $_SERVER["REQUEST_METHOD"] = "POST";
            $_POST['userID'] = $userID;
            $_POST['postID'] = $postID;
            require('deleteHIdden.php');
            $_SERVER["REQUEST_METHOD"] = NULL;
            $_POST['userID'] = NULL;
            $_POST['postID'] = NULL;
            return $error;
        }

        function deletePost(){
            return null;
        }

        function deleteUser(){
            return null;
        }

        function getComment($postID){
            $_SERVER["REQUEST_METHOD"] = "POST";
            $_POST['postID'] = $postID;
            require('getComments.php');
            $_SERVER["REQUEST_METHOD"] = NULL;
            $_POST['postID'] = NULL;
            return $arrayOfArrays; 
        }


        function getFeedSlice($sliceID, $sortBy){
            $_SERVER["REQUEST_METHOD"] = "GET";
            $_GET['sliceID'] = $sliceID;
            $_GET['sortBy'] = $sortBy;
            $_GET['activeUser'] = 1;
            require('getFeed.php');
            $_SERVER["REQUEST_METHOD"] = NULL;
            $_GET['sliceID'] = NULL;
            $_GET['sortBy'] = NULL;
            return $arrayOfArrays;
        }

        function getHidden($postID, $userID){
            $_SERVER['REQUEST_METHOD'] = 'POST';
            $_POST['postID'] = $postID;
            $_POST['userID'] = $userID;
            $realRequest = true;
            require('getHidden.php');
            return $row;
        }

        function getNotifications($userID){
            $_SERVER['REQUEST_METHOD'] = 'POST';
            $_SESSION['userID'] = $userID;
            require('getNotifications.php');
            return $data['success'];
        }

        function getPost($postID){
            $_POST['postID'] = $postID;
            $_SERVER['REQUEST_METHOD'] = 'POST';
            require('getPost.php'); 
            return $postArray;
        }

        function savePost($title, $content, $sliceID, $userID){
            $_SERVER['REQUEST_METHOD'] = 'POST';
            $_POST['title'] = $title;
            $_POST['content'] = $content;
            $_POST['sliceID'] = $sliceID;
            $_SESSION['userID'] = $userID;
            require('savePost.php');
            return $data['success'];
        }

        function saveSignUp($email, $pass, $displayName){
            $_SERVER['REQUEST_METHOD'] = 'POST';
            $_POST['email'] = $email;
            $_POST['pass'] = $pass;
            $_POST['displayName'] = $displayName;
            require('saveSignUp.php');
            return $data['success'];
        }

        function updateDisplayName($displayName){
            $_SERVER['REQUEST_METHOD'] = 'POST';
            $_POST['displayName'] = $displayName;
            $_SESSION['userID'] = 1;
            require('updateDisplayName.php');
            return $data['success'];

        }

        function updateEmail($email){
            $_SERVER['REQUEST_METHOD'] = 'POST';
            $_POST['email'] = $email;
            $_SESSION['userID'] = 1;
            require('updateEmail.php');
            return $data['success'];
        }

        function updatePassword($pass){
            $_SERVER['REQUEST_METHOD'] = 'POST';
            $_POST['pass'] = $pass;
            $_SESSION['userID'] = 1;
            require('updatePassword.php');
            return $data['success'];
        }
       
        function updateUserBio($bio){
            $_SERVER['REQUEST_METHOD'] = 'POST';
            $_POST['userBio'] = $bio;
            $_SESSION['userID'] = 1;
            require('updateUserBio.php');
            return $data['success'];
        }

        function updateUserImage($isNull){
            $file = file_get_contents('noUser.jpg');
            $_SESSION['userID'] = 1;
            if($isNull){
                $_FILES["images"]["name"] = NULL;
                $_FILES["images"]["tmp_name"] = NULL;
            }else{
                $_FILES["images"]["name"] = $file;
                $_FILES["images"]["tmp_name"] = $file;
            }
            require('updateUserImage.php');
            return $data['success'];
        }

        

    }

    $test = new functions();
    
    
    

   
?>