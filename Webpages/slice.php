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
	<title>Breadit - Slice</title>
	<link rel="stylesheet" href="styles/masterStyle.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script type="text/javascript" src="JS/deletePost.js"></script>
	<script type="text/javascript" src="./JS/feedGenerator.js"></script>
	<script type="text/javascript" src="./JS/displayUser.js"></script>
</head>

<body>
	<?php
	$realRequest = false;
	if ($_SERVER["REQUEST_METHOD"] == "GET") {
		if (isset($_GET["sliceID"])) {
			$sliceID = $_GET["sliceID"];
			$realRequest = true;
			echo "<script>console.log(\"GET request Received\");</script>";
		} else {
			echo "<script>alert(\"Missing Slice ID\");</script>";
		}
	} else {
		echo '<script>alert(\"Faulty request\");</script>';
	}
	?>
	<nav>
		<ul id='headerList'>
			<li><a href="homepage.php"><img id="breadly" src="images/Breadly.png">
					<div id="logo">Breadit</div>
				</a></li>
			<li class="dropdown">
				<a href="javascript:void(0)" class="dropbtn">Slices</a>
				<div class="dropdown-content">
					<form method="GET" action="slice.php">
						<button type="submit" name=sliceID value=1>Sourdough</button>
						<button type="submit" name=sliceID value=2>Flatbread</button>
						<button type="submit" name=sliceID value=3>Croissant</button>
					</form>
				</div>
			</li>
			<li id="searchbox">
				<form method="GET" action="searchResults.php">
					<input type="text" name="keyWord" placeHolder="Search" id="search" required>
					<button type="submit" id="searchButton">Search</button>
				</form>
			</li>
			<?php
			if ($_SESSION['userID'] != -1) {
				echo '<li id = prefs><a href ="userSettings.php">Settings</a></li>';
				echo '<li id = logout><a>Logout</a></li>';
			} else {
				echo '<li id = logout><a href ="signIn.php">Login</a></li>';

			}
			?>
		</ul>
		<script>
			let userID = JSON.parse('<?php echo json_encode($_SESSION['userID']); ?>');
			if (userID != -1) {
				let displayName = JSON.parse('<?php echo json_encode($_SESSION['displayName']); ?>');
				let headerList = document.getElementById('headerList');
				headerList.append(displayUser(userID, displayName));
			}
		</script>
	</nav>
	<div class="slice-banner">
		<div class="banner-end">
			<img id="headerImage" />
			<div id="banner-title">
			</div>
		</div>
	</div>
	<div class="main-content">
		<div class="order">
			<ul>
				<li>
					<p>Sort By: </p>
				</li>
				<li> <button id="newButton" type="button">New</button></li>
				<li> <button id="topButton" type="button">Top</button></li>
			</ul>
		</div>
		<div class="feedbox">
			<div class="feed">
				<script>
					window.onload = (event) => {
						if ($('#logout')) {
							$('#logout').children().first().css({ 'cursor': 'pointer' });;
							$('#logout').on("click", function () {
								$.get("PHP/logOut.php");

								const myTimeout = setTimeout(logout, 500);
								function logout() {
									$('#prefs').remove();
									$('#userDiv').empty();
									$('#userDiv').remove();
									$('#notesLi').empty();
									$('#notesLi').remove();
									$('#logout').html('<a href="signIn.php">Login</a>');
									$('#logout').attr("id", "signUp");
								}
							})
						}
						let sortBy = 0;
						let isAdmin = JSON.parse("<?php echo json_encode($_SESSION['isAdmin']); ?>");
						let activeUser = JSON.parse("<?php echo json_encode($_SESSION['userID']); ?>");
						let sliceID = JSON.parse("<?php echo $sliceID; ?>");
						let sliceImage = document.getElementById('headerImage');
						sliceImage.setAttribute('src', './images/sliceImage' + sliceID + '.jpg');
						let sliceTitle = document.getElementById('banner-title');
						switch (sliceID) {
							case 1:
								sliceTitle.innerHTML = '<h1>Sourdough</h1><h3>s/Sourdough</h3>';
								break;
							case 2:
								sliceTitle.innerHTML = '<h1>Flatbread</h1><h3>s/Flatbread</h3>';
								break;
							case 3:
								sliceTitle.innerHTML = '<h1>Croissant</h1><h3>s/Croissant</h3>';
								break;
						}

						let getCall = './PHP/getFeed.php?sliceID=' + sliceID + '&sortBy=' + sortBy;
						let countList = document.getElementById('countList');
						let count = document.createElement('p');
						count.setAttribute('id', 'postCount');
						count.innerHTML = 0;
						count.hidden = true;
						countList.append(count);
						printFeed(getCall, isAdmin, activeUser);
						let newButton = document.getElementById('newButton');
						let topButton = document.getElementById('topButton');
						newButton.addEventListener('click', function () {
							sortBy = 0;
							getCall = './PHP/getFeed.php?sliceID=' + sliceID + '&sortBy=' + sortBy;
							printFeed(getCall, isAdmin, activeUser);
						});
						topButton.addEventListener('click', function () {
							sortBy = 1;
							getCall = './PHP/getFeed.php?sliceID=' + sliceID + '&sortBy=' + sortBy;
							printFeed(getCall, isAdmin, activeUser);
						});
						window.setInterval(function () {
							getCall = './PHP/getFeed.php?sliceID=' + sliceID + '&sortBy=' + sortBy;
							printFeed(getCall, isAdmin, activeUser);
						}, 30000);
					};
				</script>
			</div>
			<div class="feednav">
				<ul id='prevNext'>
					<li id="prev"></li>
					<li id="countList"></li>
					<li id="next"></li>
				</ul>
			</div>
		</div>
		<div class="side-container">
			<div id="submission">
				<?php
				if ($_SESSION['userID'] != -1) {
					echo '<p><a href ="postCreator.php" >Submit New Post</a></p>';
				} else {
					echo '<a href ="signUp.php">Create an account and post today!</a>';
				}
				?>
				<img id="ad" src="images/Future.jpg" alt="Add for Breddit Premium">
			</div>
		</div>
	</div>
</body>

</html>