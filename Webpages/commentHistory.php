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
	<title>Breddit - The front page of the the internet</title>
	<link rel="stylesheet" href="styles/masterStyle.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script type="text/javascript" src="JS/deletePost.js"></script>
	<script type="text/javascript" src="JS/deleteComment.js"></script>
	<script type="text/javascript" src="./JS/displayUser.js"></script>
  </head>
  <body>
	  <?php
		$realRequest = false;
		if ($_SERVER["REQUEST_METHOD"] == "GET"){
			if( isset($_GET["userID"])){
				$realRequest = true;
				echo "<script>console.log(\"GET request Received\");</script>";
			} else {
				echo "<script>alert(\"Missing Post ID\");</script>";
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
	<div class = "main-content">
		<div class = "feedbox">
			<div class = "feed">
				<script>

				function writeComments(){
					let results = $.post("php/getComments.php",{ userID: <?php echo json_encode($_GET["userID"]); ?> });
					results.done(function(data){
						data = JSON.parse(data);
						$('#commentHolder').empty();
						let commentHolder = document.getElementById('commentHolder');
						commentGrab(data, 0, commentHolder);
					});

					results.fail(function(jqXHR) { console.log("Error: "+jqXHR.status);});
					results.always(function(){console.log("Done generating comments");});
				}

				function commentGrab(commentArray, layer,  parentElement) {
					for (let i = 0; i < commentArray.length; i++) {
						let commentID = commentArray[i][0];
						let content = commentArray[i][1];
						let votes = commentArray[i][2];
						let dateCreated = commentArray[i][3];
						let displayName = commentArray[i][5];
						let userID = commentArray[i][6];
						
						let currComment = commentPrinter(content, votes, dateCreated, displayName, userID, commentID);
						parentElement.append(currComment);
						
					}
					
				}
				function commentPrinter(content, votes, dateCreated, displayName, userID, commentID){
					console.log(commentID);

					const postDate = new Date(dateCreated);
					const currentDate = new Date();
					let difference = currentDate.getTime() - postDate.getTime();
					let days = Math.ceil(difference / (1000* 3600 * 24));
					if(days == 1){
						days = days + " day";
					} else {
						days = days + " days";
					}
					/*
					let isAdmin = JSON.parse('<?php echo json_encode($_SESSION['isAdmin']); ?>');
					let adminHTML = "";
					if(isAdmin){
						adminHTML = '<button type = "button" id = "commentID' + commentID + '">Delete</button>';
						setTimeout(function(){
							let deleteButton = document.getElementById('commentID' + commentID);
							deleteButton.addEventListener('click', function(){
								deleteComment(commentID);
								setTimeout(function(){writeComments();}, 500);
								return;
							});
						},100);	
					}*/

					/*<div class = "comment"></div> */
					let classDiv = document.createElement("div");
					classDiv.setAttribute('class', 'comment');

					/*<div class = "commentHead"></div> */
					let headDiv = document.createElement("div");
					headDiv.setAttribute('class', 'commentHead');

					/*<div class = "commentContent"></div> */
					let contentDiv = document.createElement("div");
					contentDiv.setAttribute('class', 'commentContent');

					/*<form method="GET" action="post.php"></form> */
					let submitForm = document.createElement("form");
					submitForm.setAttribute('method', 'POST');
					submitForm.setAttribute('enctype', 'multipart/form-data');
					submitForm.setAttribute('onsubmit', 'return validate()');
					submitForm.setAttribute('action', './PHP/addComment.php')

					/*
						<div class="comment">
							<div class="commentHead">
							</div>
							<form method="POST" class="submitForm" enctype="multipart/form-data" onsubmit="return validate()">
							</form>
						</div>
					*/
					classDiv.append(headDiv);
					classDiv.append(contentDiv);
					classDiv.append(submitForm);

					/*<div class = "votes"></div> */
					let votesDiv = document.createElement("div");
					votesDiv.setAttribute('class', 'votes');

					/*<button type="submit" id="breadvote"></button> */
					let breadButtonUp = document.createElement("button");
					breadButtonUp.setAttribute('type', 'submit');
					breadButtonUp.setAttribute('class', 'breadvote');

					/*<button type="submit" id="breadvote"></button> */
					let breadButtonDown = document.createElement("button");
					breadButtonDown.setAttribute('type', 'submit');
					breadButtonDown.setAttribute('class', 'breadvote');

					/*<p>~votes~</p> */
					let voteCount = document.createElement("p")
					voteCount.innerHTML = votes;
					voteCount.style.fontSize = "13px";

					/*<img src = "images/breadstick.png" class = "breadStickImage" alt = "breadStick"/> */
					let breadStickImage = document.createElement("img");
					breadStickImage.setAttribute('src', 'images/breadstick.png');
					breadStickImage.setAttribute('class', 'breadStickImage');
					breadStickImage.setAttribute('alt', 'breadstick');

					/*
					<div class="comment">
						<div class="commentHead">
							<div class="commentVotes">
								<button type="submit" id="breadvote"><img src="images/breadstick.png" class="upbread" alt="UpBread"></button>
									<p>VOTES</p>
								<button type="submit" id="breadvote"> <img src="images/breadstick.png" class="downbread" alt="UpBread"></button>
							</div><!--Close Comment Votes-->
						</div><!--Close Comment Head-->
						<div class="commentContent">
						</div><!--Close comment Content-->
						<form method="POST" class="submitForm" enctype="multipart/form-data" onsubmit="return validate()">
						</form>
					</div>
					*/

					headDiv.append(votesDiv);
					votesDiv.append(breadButtonUp);
					votesDiv.append(voteCount);
					votesDiv.append(breadButtonDown);
					breadButtonUp.append(breadStickImage);
					breadButtonDown.append(breadStickImage.cloneNode(true));

					/*<div class = "commentInfo"></div> */
					let infoDiv = document.createElement("div");
					infoDiv.setAttribute('class', 'commentInfo');

					/*<form method="GET" action="userHistory.php"></form> */
					let userForm = document.createElement("form");
					userForm.setAttribute('method', 'GET');
					userForm.setAttribute('action', 'userHistory.php');

					/*<button type="submit" id="breadvote"></button> */
					let userSubmitButton = document.createElement("button");
					userSubmitButton.setAttribute('type', 'submit');
					userSubmitButton.setAttribute('name', 'userID');
					userSubmitButton.setAttribute('value', userID);
					userSubmitButton.innerHTML = 'Post by ' + displayName + ' ' + days + ' ago';

					/*<p>CONTENT</p>*/
					let contentP = document.createElement("p");
					contentP.innerHTML = content;

					let fieldSet = document.createElement("fieldset");

					/*
					<div class="comment">
						<div class="commentHead">
							<div class="commentVotes">
								<button type="submit" id="breadvote"><img src="images/breadstick.png" class="upbread" alt="UpBread"></button>
									<p>VOTES</p>
								<button type="submit" id="breadvote"> <img src="images/breadstick.png" class="downbread" alt="UpBread"></button>
							</div><!--Close Comment Votes-->
							<div class="commentInfo">
								<form method="GET" action="userHistory.php">
									<button type="submit" name="userID" value="USERID">
										DISPLAYNAME DAYS ago
									</button>
								</form>
							</div><!--Close Comment Info-->
						</div><!--Close Comment Head-->
						<div class="commentContent">
							*<p>CONTENT</p>
						</div><!--Close comment Content-->
						<form method="POST" class="submitForm" enctype="multipart/form-data" onsubmit="return validate()">
						</form>
					</div>
					*/

					userForm.append(userSubmitButton);
					infoDiv.append(userForm);
					headDiv.append(infoDiv);
					contentDiv.append(contentP);
					submitForm.append(fieldSet);

					/*<div class = 'commentContainer></div>*/
					let commentContainer = document.createElement('div')
					commentContainer.setAttribute('class', 'commentContainer');

					/*<textarea id="commentResponseText" name="content" rows="15" cols="102" placeholder="Enter your content here" required></textarea> */
					
					fieldSet.append(commentContainer);
				

					/*<div class = 'commentSubmission></div>*/
					let commentSubmission = document.createElement('div')
					commentSubmission.setAttribute('class', 'commentSubmission');

					/*<input type="submit" class="submitButton" /> */
					

					/*<input type='hidden' class='commentParent' name='commentParent' value='COMMENTID' /> */
					let parentHidden = document.createElement('input');
					parentHidden.setAttribute('type', 'hidden');
					parentHidden.setAttribute('class', 'commentParent');
					parentHidden.setAttribute('name', 'commentParent');
					parentHidden.setAttribute('value', commentID);

					/*<input type='hidden' class='postID' name='postID' value='POSTID' /> */
					let postHidden = document.createElement('input');
					postHidden.setAttribute('type', 'hidden');

					
					
					commentSubmission.append(parentHidden);
					commentSubmission.append(postHidden);



					return classDiv;
				}
				window.onload = (event) => {
					console.log("Is the request real: " + JSON.parse('<?php echo json_encode($realRequest); ?>'));
					if(JSON.parse('<?php echo json_encode($realRequest); ?>')){
						
						writeComments();
						window.setInterval(function(){
							
							writeComments();
						}, 30000);
					}
				}
				
				</script>
				
					
					
				
								<input type='hidden' id='commentParent' name='commentParent' />
						
						<div id="commentHolder">
						<!--Comments go here -->
						</div> <!--Close comment holder-->
					</div><!--Close postbox-->
				</div> <!--Close Post-->
			</div><!--Close Feed-->
		</div><!--Close feedbox-->	
		<div class = "side-container" >
			<div id = "submission">
				<?php
					if($_SESSION['userID'] != -1){
						echo '<p><a href ="postCreator.php" >Submit New Post</a></p>';
					} else {
						echo '<a href ="signUp.php">Create an account and post today!</a>';
					}
				 ?>
				<img id = "ad" src ="images/Future.jpg"  alt = "Add for Breadit Premium">
			</div> <!--Close submission-->
		</div>	<!--Close side-container-->
	</div> <!--Close Main content-->
</body>
</html>
