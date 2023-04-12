<?php 
	function checkAdmin(){
		if(!isset($_SESSION['isAdmin'])){
			$_SESSION['isAdmin'] = false;
		}
		if(!isset($_SESSION['userID'])){
			$_SESSION['userID'] = -1;
		}
		if(!isset($_SESSION['displayName'])){
			$_SESSION['displayName'] = null;
		}
	}
?>