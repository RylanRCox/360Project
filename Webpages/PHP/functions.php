<?php
    namespace App;

    class functions{

        function checkLogin(){
            $_POST['email'] = 'test';
            $_POST['pass'] = 'test';
            require('checkLogin.php');
            return $data['success'];
        }

    }

?>