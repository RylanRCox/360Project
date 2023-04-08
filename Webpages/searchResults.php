<?php
	session_start();
	include './PHP/isAdmin.php';
	checkAdmin();
	$_SESSION['isAdmin'] = false;
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>Breadit - The front page of the the internet</title>
		<link rel="stylesheet" href="styles/masterStyle.css">
		<script type="text/javascript" src="./JS/displayUser.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	</head>
	<body>
		<?php
			$realRequest = false;
			if ($_SERVER["REQUEST_METHOD"] == "GET"){
				if( isset($_GET["keyWord"])){
					$keyWord = $_GET["keyWord"];
					$realRequest = true;
					echo "<script>console.log(\"GET request Received\");</script>";
				} else {
					echo "<script>alert(\"Missing Keyword\");</script>";
				}
			} else if ($_SERVER["REQUEST_METHOD"] == "POST"){
				if( isset($_POST["keyWord"])){
					$keyWord = $_POST["keyWord"];
					$realRequest = true;
				} else {
					echo "<script>alert(\"Missing Keyword\");</script>";
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
				let userID = JSON.parse('<?php echo json_encode($_SESSION['userID']); ?>');
				if( userID != -1){
					let displayName = JSON.parse('<?php echo json_encode($_SESSION['displayName']); ?>');
					let headerList = document.getElementById('headerList');
					headerList.append(displayUser(userID, displayName));
				}
			</script>
		</nav>
		<div class = "feedbox">
			<div class = "feed">
			<script>
				window.onload = (event) => {
					if(JSON.parse('<?php echo json_encode($realRequest); ?>')){
						let keyWord = JSON.parse('<?php echo json_encode($keyWord); ?>');
						queryDatabase(keyWord);
					}
				}
				function queryDatabase(keyWord){
					let results = $.get("php/searchDB.php?keyWord=" + keyWord);
					results.done(function(data){
						console.log(data);
						let resultsArray = JSON.parse(data);
						for(let i = 0; i < resultsArray.length; i++){
							let type = resultsArray[i][0];
							let name = resultsArray[i][1];
							let id = resultsArray[i][2];
							let history = "";
							if(type === 'user'){
								history = "History";
							}

							/*<div class = "searchResult"></div> */
							let resultDiv = document.createElement("div");
							resultDiv.setAttribute('class', 'searchResult');

							/*<form method="GET" action="~type~~history~.php"></form> */
							let resultForm = document.createElement("form");
							resultForm.setAttribute('method', 'GET');
							resultForm.setAttribute('action', type + history + '.php');

							/*<button type = "submit" name = "~type~ID" value = "~id~">~type~: ~name~</button>*/
							let resultButton = document.createElement("button");
							resultButton.setAttribute('type', 'submit');
							resultButton.setAttribute('name', type + "ID");
							resultButton.setAttribute('value', id);
							resultButton.innerHTML = type.toUpperCase() + ': ' + name;

							/*
							<div class = "searchResult">
								<form method="GET" action="~type~~history~.php"><
									<button type = "submit" name = "~type~ID" value = "~id~">~type~: ~name~</button>
								</form>
							</div>
							*/
							resultDiv.append(resultForm);
							resultForm.append(resultButton);

							$('.feed').append(resultDiv);
						}
					});
					results.fail(function(jqXHR) { console.log("Error: "+jqXHR.status);});
					results.always(function(){console.log("Feed Generated");});
				}
			</script>
			</div>
		</div>
		<div class = "side-container" >
			<div id = "submission">
			   <?php
					if(isset($_SESSION['userID'])){
						echo '<p><a href ="postCreator.php" >Submit New Link</a></p>';
						echo '<p><a href ="postCreator.php" >Submit New Text Post</a></p>';
					} else {
						echo '<a href ="signUp.php">Create an account and post today!</a>';
					
					}
				?>
				<img id = "ad" src ="images/Future.jpg"  alt = "Add for Breadit Premium">
			</div>
		</div>
  </body>
</html>