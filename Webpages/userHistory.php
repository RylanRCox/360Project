<?php
	session_start();
	include './PHP/isAdmin.php';
	checkAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Breadit - User History</title>
	<link rel="stylesheet" href="styles/masterStyle.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script type="text/javascript" src="JS/deletePost.js"></script>
	<script type="text/javascript" src="JS/deleteUser.js"></script>
	<script type="text/javascript" src="./JS/feedGenerator.js"></script>
	<script type="text/javascript" src="./JS/displayUser.js"></script>
</head>
<body>
	<?php
		$realRequest = false;
		if ($_SERVER["REQUEST_METHOD"] == "GET"){
			if( isset($_GET["userID"]) && isset($_GET["userID"])){
				$userID = $_GET["userID"];
				$realRequest = true;
				echo "<script>console.log(\"GET request Received\");</script>";
			} else {
				echo "<script>alert(\"Missing Slice ID\");</script>";
			}
		} else if ($_SERVER["REQUEST_METHOD"] == "POST"){
			if( isset($_POST["userID"]) && isset($_POST["userID"])){
				$userID = $_POST["userID"];
				$realRequest = true;
			} else {
				echo "<script>alert(\"Missing Slice ID\");</script>";
			}
		} else {
			echo '<script>alert(\"Faulty request\");</script>';
		}
	?>
	<nav>
		<ul id = 'headerList'>
			<li><a href="homepage.php"><img id = "breadly" src = "images/Breadly.png" ><div id = "logo">Breadit</div></a></li>
			<li class="dropdown">
				<a href="javascript:void(0)" class="dropbtn">Slices</a>
				<div class="dropdown-content">
					<form method = "GET" action = "slice.php">
					  <button type = "submit" name = sliceID value = 1>Sourdough</button>
					  <button type = "submit" name = sliceID value = 2>Flatbread</button>
					  <button type = "submit" name = sliceID value = 3>Croissant</button>
					</form>
				</div>
			</li>

			<li id = "searchbox">
				<form method = "GET" action = "searchResults.php">
					<input type="text" name="keyWord" placeHolder = "Search" id = "search" required>
					<button type="submit" id = "searchButton">Search</button>
				</form>
			</li>

			<?php
				if($_SESSION['userID'] != -1){
					echo '<li id = prefs><a href ="userSettings.php">Settings</a></li>';
					echo '<li id = logout><a href ="signOut.php">Logout</a></li>';
				} else {
					echo '<li id = logout><a href ="signIn.php">Login</a></li>';
					
				}
			?>
		</ul>
		<script>
			let user = JSON.parse('<?php echo json_encode($_SESSION['userID']); ?>');
			if( user != -1){
				let displayName = JSON.parse('<?php echo json_encode($_SESSION['displayName']); ?>');
				let headerList = document.getElementById('headerList');
				headerList.append(displayUser(user, displayName));
			}
		</script>
	</nav>

	<div class="main-content">
		<div class="order">
			<ul> 
				<li><p>Sort By: </p></li>
				<li> <button id="newButton" type="button">New</button></li>
				<li> <button id="topButton" type="button">Top</button></li>
			</ul>
		</div>
		<div class="feedbox">
			<div class="feed">
				<script>
					function deleteUserButton(userID){
						let isAdmin = JSON.parse('<?php echo json_encode($_SESSION['isAdmin']); ?>');
						if(isAdmin){
							$('#deleteHolder').empty();
							$('#deleteHolder').append('<button type = "button" id = "userID' + userID + '">Delete User</button>');
							setTimeout(function(){
								let deleteButton = document.getElementById('userID' + userID);
								deleteButton.addEventListener('click', function(){
									deleteUser(userID);
									setTimeout(function(){window.location.assign('./homepage.php');}, 500);
									return;
								});
							},100);	
						}
					}
					function displayUser(userID){
						let results = $.get("php/getUser.php?userID=" + userID);
						results.done(function(data){
							let postArray = JSON.parse(data);
							$('#displayerName').empty();
							$('#displayerName').append('<p>' + postArray[0] + '</p>');
							$('#profilePicture').attr('src', 'PHP/image.php?table=users&id=' + userID);
						});
						results.fail(function(jqXHR) { console.log("Error: "+jqXHR.status);});
						results.always(function(){console.log("Feed Update");});
					}
					window.onload = (event) => {
						let sortBy = 0;
						let indexStart = 0;
						let userID = JSON.parse('<?php echo json_encode($userID); ?>');
						if(userID != -1){
							deleteUserButton(userID);
						}
						displayUser(userID);
						let isAdmin = JSON.parse("<?php echo json_encode($_SESSION['isAdmin']); ?>");
						let getCall = './PHP/getFeed.php?userID=' + userID;
						printFeed(getCall, isAdmin);
						/* To be added later
						let newButton = document.getElementById('newButton');
						let topButton = document.getElementById('topButton');
						newButton.addEventListener('click', function(){
							sortBy = 0;
						});
						topButton.addEventListener('click', function(){
							sortBy = 1;
						});
						*/
						window.setInterval(function(){printFeed(getCall, isAdmin);}, 30000);
					};
				</script>
			</div>
			<!--
			<div class="feednav">
				<ul>
					<li id="prev"><a href=prev.json>prev</a></li>
				</ul>
				<ul>
					<li id="next"><a href=next.json>next</a></li>
				</ul>
				-->
			</div>
		</div>
	</div>
	<div class="user-container">
		<div id="submission">
			<p id = "displayerName">Display Name</p>
			<img id="profilePicture" alt="Profile Picture">
			<div id ="deleteHolder">
			</div>
		</div>
	</div>
</body>
</html>