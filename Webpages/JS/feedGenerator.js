function pageIndexing(count, index, postCount, cap, getCall, isAdmin, activeUser) {
	if (index != 0) {

		let prevLink = document.createElement('button');
		prevLink.setAttribute('id', 'prevButton');
		prevLink.innerHTML = 'prev';
		$('#prev').empty();
		$('#prev').prepend(prevLink);

		setTimeout(function () {
			prevLink.addEventListener('click', function () {
				//ARE THEY STORING VARIABLES IN THE HTML TO GET AROUND JS?
				//You bet your goshdarn buttox we are.
				count.innerHTML = (index - 10);
				count.hidden = true;
				printFeed(getCall, isAdmin, activeUser);
			});
		}, 500);


	} else {
		$('#prev').empty();
	}
	if (index + 10 <= postCount) {

		let nextLink = document.createElement('button');
		nextLink.setAttribute('id', 'nextButton');
		nextLink.innerHTML = 'next';
		$('#next').empty();
		$('#next').append(nextLink);

		setTimeout(function () {
			nextLink.addEventListener('click', function () {
				count.innerHTML = (index + 10);
				count.hidden = true;
				printFeed(getCall, isAdmin, activeUser);
			});
		}, 500);

	} else {
		$('#next').empty();
	}
}
function printFeed(getCall, isAdmin, activeUser) {
	console.log('Active user: ' + activeUser);
	//The first thing we do is we clear the feed, this avoids printing duplicates
	getCall += "&activeUser=" + activeUser;
	$('.feed').empty();
	let results = $.get(getCall);
	results.done(function (data) {
		console.log(data);
		//We parse our data into a JS array.
		let postArray = JSON.parse(data);
		let postCount = postArray.length;

		let count = document.getElementById('postCount');
		let countVals = count.innerHTML;
		let index = parseInt(countVals);


		//Now we generate our cap, this posts maximum 10 values, but won't throw and error if we have less.
		let cap = index + 10;
		if (postArray.length < cap) {
			cap = postArray.length;
		}
		//We iterate through our posts.
		for (let i = index; i < cap; i++) {

			//We label our data for readibility.
			let postID = postArray[i][0];
			let title = postArray[i][1];
			let votes = postArray[i][2];
			let dateCreated = postArray[i][3];
			let sliceID = postArray[i][4];
			let sliceName = postArray[i][5];
			let sliceImage = postArray[i][6];
			let userID = postArray[i][7];
			let displayName = postArray[i][8];
			let commentCount = postArray[i][9];
			let isHidden = postArray[i][10];
			console.log(isHidden);

			//Determine whether or not the post has an image attached.


			//Calculate the days since created
			days = calculateDateDifference(dateCreated);

			//If the post has no comments it returns null. So we check for that.
			let count = 0;
			if (commentCount !== null) {
				count = commentCount;
			}
			console.log('Outer Log: ' + postID);
			buildPostDiv(votes, postID, title, sliceID, sliceName, userID, displayName, days, count, isAdmin, getCall, activeUser, isHidden);

		}

		pageIndexing(count, index, postCount, cap, getCall, isAdmin, activeUser);
		setTimeout(function () {
			let breadVotes = document.getElementsByClassName('breadvote');
			for (let i = 0; i < breadVotes.length; i++) {

				breadVotes[i].addEventListener('click', function () {
					if (activeUser != -1) {

						let results = $.post('./PHP/likePost.php', { postID: breadVotes[i].getAttribute('value'), userID: activeUser });
						results.done(function (data) {
							console.log(data);
							printFeed(getCall, isAdmin, activeUser);
						});
						results.fail(function (jqXHR) { console.log("Error: " + jqXHR.status); });
						results.always(function () {
						});
					} else {
						alert('Please sign in to like posts or comments');
					}

				});
			}
			let hideButtons = document.getElementsByClassName('hideButton');
			for (let i = 0; i < hideButtons.length; i++) {

				hideButtons[i].addEventListener('click', function () {
					results = $.post('./PHP/setHidden.php', { postID: hideButtons[i].getAttribute('value'), userID: activeUser });
					results.done(function (data) {
						console.log(data);
						printFeed(getCall, isAdmin, activeUser);
					});
					results.fail(function (jqXHR) { console.log("Error: " + jqXHR.status); });
					results.always(function () {
					});
				});
			}
			let revertHide = document.getElementsByClassName('unHide');
			for (let i = 0; i < revertHide.length; i++) {

				revertHide[i].addEventListener('click', function () {
					results = $.post('./PHP/deleteHidden.php', { postID: revertHide[i].getAttribute('value'), userID: activeUser });
					results.done(function (data) {
						console.log(data);
						printFeed(getCall, isAdmin, activeUser);
					});
					results.fail(function (jqXHR) { console.log("Error: " + jqXHR.status); });
					results.always(function () {
					});
				});
			}
			//This mentally broke me. I think Im the lowest I've ever been. If you're reading, please... send help... and delete this repo.
		}, 500);
	});
	results.fail(function (jqXHR) { console.log("Error: " + jqXHR.status); });
	results.always(function () {
		console.log("Feed Update");
	});
}
function buildPostDiv(votes, postID, title, sliceID, sliceName, userID, displayName, days, commentCount, isAdmin, getCall, activeUser, isHidden) {

	/*<div class = "post"></div> */
	let postDiv = document.createElement("div");
	postDiv.setAttribute('class', 'post');
	postDiv.setAttribute('id', postID);

	if (isHidden) {
		let unHide = document.createElement("button");
		unHide.setAttribute('class', 'unHide');
		unHide.setAttribute('value', postID);
		unHide.innerHTML = 'Un-hide Post';
		postDiv.append(unHide);
		console.log('Inner Log Lower: ' + postID);
		$(".feed").append(postDiv);
		return;
	}

	/*<div class = "votes"></div> */
	let votesDiv = document.createElement("div");
	votesDiv.setAttribute('class', 'votes');

	/*<div class = "post-content"></div>*/
	let postContent = document.createElement("div");
	postContent.setAttribute('class', 'post-content');

	/* APPEND POST-CONTENT, AND VOTES DIV
		<div class = "post">
			<div class = "votes">
			</div>
			<div class = "post-content">
			</div>
		</div>
	*/
	postDiv.append(votesDiv);
	postDiv.append(postContent);



	/*<button type="submit" id="breadvote"></button> */
	let breadButtonUp = document.createElement("button");
	breadButtonUp.setAttribute('type', 'submit');
	breadButtonUp.setAttribute('class', 'breadvote');
	breadButtonUp.setAttribute('value', postID);
	results = $.post('./PHP/isLiked.php', { pID: postID, uID: activeUser });
	results.done(function (data) {
		//console.log(data);
		if (data == 'true') {
			breadButtonUp.style.backgroundColor = 'deepskyblue';
			breadButtonUp.style.borderRadius = '1em';
			breadButtonUp.style.width = '1em';
			breadButtonUp.style.height = '1em';
		}
	});
	results.fail(function (jqXHR) { console.log("Error: " + jqXHR.status); });
	results.always(function () {
	});

	/*<button type="submit" id="breadvote"></button> 
	let breadButtonDown = document.createElement("button");
	breadButtonDown.setAttribute('type', 'submit');
	breadButtonDown.setAttribute('class', 'breadvote');
	*/
	/*<p>~votes~</p> */
	let voteCount = document.createElement("p")
	voteCount.innerHTML = votes;

	/*<img src = "images/breadstick.png" class = "breadStickImage" alt = "breadStick"/> */
	let breadStickImage = document.createElement("img");
	breadStickImage.setAttribute('src', 'images/breadstick.png');
	breadStickImage.setAttribute('class', 'breadStickImage');
	breadStickImage.setAttribute('alt', 'breadstick');

	/* INSERT BUTTONS WITH IMAGES AND VOTE COUNT INTO VOTES DIV
		<div class = "post">
			<div class = "votes">
				<button type="submit" id="breadvote">
					<img src = "images/breadstick.png" class = "breadStickImage" alt = "breadStick"/>
				</button> 
				<p>~votes~</p>
			</div>
			<div class = "post-content">
			</div>
		</div>
	*/
	votesDiv.append(breadButtonUp);
	votesDiv.append(voteCount);
	//votesDiv.append(breadButtonDown);
	breadButtonUp.append(breadStickImage);
	//breadButtonDown.append(breadStickImage.cloneNode(true));

	/*<img src = "~image~" class = "post-image" alt = "Post Content"/> */
	let postImage = document.createElement("img");
	postImage.setAttribute('src', 'PHP/image.php?table=posts&id=' + postID);
	postImage.setAttribute('class', 'post-image');
	postImage.setAttribute('alt', 'Post Content');

	/*<div class = "title"></div>*/
	let titleDiv = document.createElement("div");
	titleDiv.setAttribute('class', 'title');

	/* INSERT DISPLAY IMAGE AND TITLE DIV INTO POST CONTENT DIV
		<div class = "post">
			<div class = "votes">
				<button type="submit" id="breadvote">
					<img src = "images/breadstick.png" class = "breadStickImage" alt = "breadStick"/>
				</button> =
				<p>~votes~</p>
				<button type="submit" id="breadvote">
					<img src = "images/breadstick.png" class = "breadStickImage" alt = "breadStick"/>
				</button>
			</div>
			<div class = "post-content">
				<img src = "~image~" class = "post-image" alt = "Post Content"/>
				<div class = "title">
				</div>
			</div>
		</div>
	*/
	postContent.append(postImage);
	postContent.append(titleDiv);

	/*<form method="GET" action="post.php"></form> */
	let titleForm = document.createElement("form");
	titleForm.setAttribute('method', 'GET');
	titleForm.setAttribute('action', 'post.php');

	/*<button type="submit" name="postID" value="~postID~">~title~</button>*/
	let postButton = document.createElement("button");
	postButton.setAttribute('type', 'submit');
	postButton.setAttribute('name', 'postID');
	postButton.setAttribute('value', postID);
	postButton.innerHTML = title;

	/* INSERT TITLE FORM AND BUTTON INTO TITLE DIV
		<div class = "post">
			<div class = "votes">
				<button type="submit" id="breadvote">
					<img src = "images/breadstick.png" class = "breadStickImage" alt = "breadStick"/>
				</button> 
				<p>~votes~</p>
				<button type="submit" id="breadvote">
					<img src = "images/breadstick.png" class = "breadStickImage" alt = "breadStick"/>
				</button>
			</div>
			<div class = "post-content">
				<img src = "~image~" class = "post-image" alt = "Post Content"/>
				<div class = "title">
					<form method="GET" action="post.php">
						<button type="submit" name="postID" value="~postID~">~title~</button>
					</form>
				</div>
			</div>
		</div>
	*/
	titleDiv.append(titleForm);
	titleForm.append(postButton);

	/*<ul></ul> */
	let upperList = document.createElement("ul");
	upperList.setAttribute('class', 'upperUL');

	/*<ul></ul> */
	let lowerList = document.createElement("ul");
	lowerList.setAttribute('class', 'lowerUL');

	/* INSERT UNORDERED LISTS
		<div class = "post">
			<div class = "votes">
				<button type="submit" id="breadvote">
					<img src = "images/breadstick.png" class = "breadStickImage" alt = "breadStick"/>
				</button> 
				<p>~votes~</p>
				<button type="submit" id="breadvote">
					<img src = "images/breadstick.png" class = "breadStickImage" alt = "breadStick"/>
				</button>
			</div>
			<div class = "post-content">
				<img src = "~image~" class = "post-image" alt = "Post Content"/>
				<div class = "title">
					<form method="GET" action="post.php">
						<button type="submit" name="postID" value="~postID~">~title~</button>
					</form>
				</div>
				<ul>
				</ul
				<ul>
				</ul>
			</div>
		</div>
	*/
	postContent.append(upperList);
	postContent.append(lowerList);

	/*<li id = "slice"> </li> */
	let slice = document.createElement("li");
	slice.setAttribute('id', 'slice');

	/*<li id = "userTime"> </li> */
	let userTime = document.createElement("li");
	userTime.setAttribute('id', 'usertime');

	/* INSERT LIST ELEMENTS INTO UPPER LIST
		<div class = "post">
			<div class = "votes">
				<button type="submit" id="breadvote">
					<img src = "images/breadstick.png" class = "breadStickImage" alt = "breadStick"/>
				</button> 
				<p>~votes~</p>
				<button type="submit" id="breadvote">
					<img src = "images/breadstick.png" class = "breadStickImage" alt = "breadStick"/>
				</button>
			</div>
			<div class = "post-content">
				<img src = "~image~" class = "post-image" alt = "Post Content"/>
				<div class = "title">
					<form method="GET" action="post.php">
						<button type="submit" name="postID" value="~postID~">~title~</button>
					</form>
				</div>
				<ul>
					<li id = "slice">
					</li>
					<li id = "userTime">
					</li>
				</ul
				<ul>
				</ul>
			</div>
		</div>
	*/
	upperList.append(slice);
	upperList.append(userTime);

	/*<form method = "GET" action = "slice.php"></form> */
	let sliceForm = document.createElement("form");
	sliceForm.setAttribute('method', 'GET');
	sliceForm.setAttribute('action', 'slice.php');

	/*<form method = "GET" action = "userHistory.php"></form> */
	let userForm = document.createElement("form");
	userForm.setAttribute('method', 'GET');
	userForm.setAttribute('action', 'userHistory.php');

	/* INSERT FORMS INTO LIST ELEMENTS IN UPPER LIST
		<div class = "post">
			<div class = "votes">
				<button type="submit" id="breadvote">
					<img src = "images/breadstick.png" class = "breadStickImage" alt = "breadStick"/>
				</button> 
				<p>~votes~</p>
				<button type="submit" id="breadvote">
					<img src = "images/breadstick.png" class = "breadStickImage" alt = "breadStick"/>
				</button>
			</div>
			<div class = "post-content">
				<img src = "~image~" class = "post-image" alt = "Post Content"/>
				<div class = "title">
					<form method="GET" action="post.php">
						<button type="submit" name="postID" value="~postID~">~title~</button>
					</form>
				</div>
				<ul>
					<li id = "slice">
						<form method = "GET" action = "slice.php">
						</form>
					</li>
					<li id = "userTime">
						<form method = "GET" action = "userHistory.php">
						</form>
					</li>
				</ul
				<ul>
				</ul>
			</div>
		</div>
	*/
	slice.append(sliceForm);
	userTime.append(userForm);

	/*<button type="submit" name="sliceID" value="~sliceID~">s/~sliceName~</button>*/
	let sliceButton = document.createElement("button");
	sliceButton.setAttribute('type', 'submit');
	sliceButton.setAttribute('name', 'sliceID');
	sliceButton.setAttribute('value', sliceID);
	sliceButton.innerHTML = 's/' + sliceName;

	/*<button type="submit" name="userID" value="~userID~">Posted by ~displayName~ ~days~ ago</button>*/
	let userButton = document.createElement("button");
	userButton.setAttribute('type', 'submit');
	userButton.setAttribute('name', 'userID');
	userButton.setAttribute('value', userID);
	userButton.innerHTML = 'Posted by ' + displayName + ' ' + days + ' ago';

	/* INSERT BUTTONS INTO EACH FORM
		<div class = "post">
			<div class = "votes">
				<button type="submit" id="breadvote">
					<img src = "images/breadstick.png" class = "breadStickImage" alt = "breadStick"/>
				</button> 
				<p>~votes~</p>
				<button type="submit" id="breadvote">
					<img src = "images/breadstick.png" class = "breadStickImage" alt = "breadStick"/>
				</button>
			</div>
			<div class = "post-content">
				<img src = "~image~" class = "post-image" alt = "Post Content"/>
				<div class = "title">
					<form method="GET" action="post.php">
						<button type="submit" name="postID" value="~postID~">~title~</button>
					</form>
				</div>
				<ul>
					<li id = "slice">
						<form method = "GET" action = "slice.php">
							<button type="submit" name="sliceID" value="~sliceID~">s/~sliceName~</button>
						</form>
					</li>
					<li id = "userTime">
						<form method = "GET" action = "userHistory.php">
							<button type="submit" name="userID" value="~userID~">Posted by ~displayName~ ~days~ ago</button>
						</form>
					</li>
				</ul
				<ul>
				</ul>
			</div>
		</div>
	*/
	sliceForm.append(sliceButton);
	userForm.append(userButton);

	/*<li id = "comments"> </li> */
	let comments = document.createElement("li");
	comments.setAttribute('id', 'comments');

	/*<li> </li> */
	let hide = document.createElement("li");


	/*<a href="Comments.php">COMMENTCOUNT Comments</a> */
	let commentsLink = document.createElement("a");
	commentsLink.setAttribute('href', 'post.php?postID=' + postID);
	commentsLink.innerHTML = commentCount + ' Comments';


	/*<button class = 'hideButton' value = POSTID >Hide</button> */
	let hideLink = document.createElement("button");
	hideLink.setAttribute('class', 'hideButton');
	hideLink.setAttribute('value', postID);
	hideLink.innerHTML = 'Hide';

	/*<a href="Comments.php">COMMENTCOUNT Comments</a> */
	let share = document.createElement("a");
	share.innerHTML = 'Share';

	/* INSERT BUTTONS INTO EACH FORM
		<div class = "post">
			<div class = "votes">
				<button type="submit" id="breadvote">
					<img src = "images/breadstick.png" class = "breadStickImage" alt = "breadStick"/>
				</button> 
				<p>~votes~</p>
				<button type="submit" id="breadvote">
					<img src = "images/breadstick.png" class = "breadStickImage" alt = "breadStick"/>
				</button>
			</div>
			<div class = "post-content">
				<img src = "~image~" class = "post-image" alt = "Post Content"/>
				<div class = "title">
					<form method="GET" action="post.php">
						<button type="submit" name="postID" value="~postID~">~title~</button>
					</form>
				</div>
				<ul>
					<li id = "slice">
						<form method = "GET" action = "slice.php">
							<button type="submit" name="sliceID" value="~sliceID~">s/~sliceName~</button>
						</form>
					</li>
					<li id = "userTime">
						<form method = "GET" action = "userHistory.php">
							<button type="submit" name="userID" value="~userID~">Posted by ~displayName~ ~days~ ago</button>
						</form>
					</li>
				</ul
				<ul>
					<li id="comments">
						<a href="Comments.php">COMMENTCOUNT Comments</a>
					</li>
					<li>
						<a href="Share.php">Share</a>
					</li>
					<li>
						<button class = 'hideButton' value = POSTID >Hide</button>
					</li>
				</ul>
			</div>
		</div>
	*/
	lowerList.append(comments);

	lowerList.append(share);
	share.style.cursor = "pointer";
	share.addEventListener("click", function () {
		alert('Link copied to clipboard');
		navigator.clipboard.writeText("localhost/360Project/Webpages/post.php?postID=" + postID);
	})
	hide.style.cursor = "pointer";
	hide.addEventListener("click", function () {
		postDiv.style.display = 'none';
	})
	lowerList.append(hide);
	comments.append(commentsLink);
	hide.append(hideLink);

	//Check if the current user is an admin, if they are, they are given access to delete posts.
	if (isAdmin || activeUser == userID && userID != -1) {

		/*<li id = "admin"> </li> */
		let admin = document.createElement("li");
		admin.setAttribute('id', 'admin');

		/*<button type="submit"></button>*/
		let adminButton = document.createElement("button");
		adminButton.setAttribute('type', 'button');
		adminButton.setAttribute('id', 'postID' + postID);
		adminButton.innerHTML = 'Delete Post';

		lowerList.append(admin);
		admin.append(adminButton);

		//After a timeout add the event listener so that there is an object to add to.
		setTimeout(function () {
			let deleteButton = document.getElementById('postID' + postID);
			deleteButton.addEventListener('click', function () {
				//When we delete the post, after a short delay we reprint the feed so that the post no longer is there.
				deletePost(postID);
				setTimeout(function () { printFeed(getCall, isAdmin); alert('Post Deleted'); }, 500);
				return;
			});
		}, 100);
	}
	console.log('Inner Log Lower: ' + postID);
	$(".feed").append(postDiv);
}
function calculateDateDifference(dateCreated) {
	//We get the current date, and the date the post was created. Then we calculate the difference between and output it as days.
	const postDate = new Date(dateCreated);
	const currentDate = new Date();
	let difference = currentDate.getTime() - postDate.getTime();
	let days = Math.ceil(difference / (1000 * 3600 * 24));
	if (days == 1) {
		days = days + " day";
	} else {
		days = days + " days";
	}
	return days;
}