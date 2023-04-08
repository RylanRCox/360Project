<?php session_start(); ?>
<!DOCTYPE html>
<body>
<?php 

if(isset($_SESSION['userID'])) {
	$_SESSION['userID'] = NULL;
	echo "signed out!";
	echo "<script>
			window.onload = (event) => {
				window.location.assign('./homepage.php');
			}
		</script>";
}
?>
</body>
</html>


