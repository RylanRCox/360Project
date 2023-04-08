<?php 
	function checkAdmin(){
		if(!isset($_SESSION['isAdmin'])){
			$_SESSION['isAdmin'] = false;
		}
		if(!isset($_SESSION['userID'])){
			$_SESSION['userID'] = -1;
		}
	}
?>