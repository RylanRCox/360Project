function displayUser(userID, displayName) {
	/* <div id = "userDiv" ></div> */
	let userDiv = document.createElement('div');
	userDiv.setAttribute('id', 'userDiv');

	/* <figure id = "userFig" ></figure> */
	let userFig = document.createElement('figure');
	userFig.setAttribute('id', 'userFig');

	/* <a href = "userHistory.php?=USERID"></a> */
	let userLink1 = document.createElement('a');
	userLink1.setAttribute('href', 'userHistory.php?userID=' + userID);
	/* <a href = "userHistory.php?=USERID"></a> */
	let userLink2 = document.createElement('a');
	userLink2.setAttribute('href', 'userHistory.php?userID=' + userID);

	/* <img id = "userImage"" alt = "Profile Image" > */
	let userImage = document.createElement('img');
	userImage.setAttribute('id', 'userImage');
	userImage.setAttribute('src', 'PHP/image.php?table=users&id=' + userID);

	/* <figcaption id = 'displayName' ></figcaption> */
	let figCap = document.createElement('figcaption');
	figCap.setAttribute('id', 'displayName');
	figCap.innerHTML = displayName;
	setNotifications();

	/* 
	<div id = "userDiv" >
		<figure id = "userFig" >
			<img id = "userImage"" alt = "Profile Image" >
			<figcaption id = 'displayName' > DISPLAYNAME</figcaption>
		</figure>
	</div>
	*/
	userDiv.append(userFig);
	userFig.append(userLink1);
	userFig.append(userLink2);
	userLink1.append(userImage);
	userLink2.append(figCap);
	return userDiv;
}
function setNotifications() {
	let results = $.post('./PHP/getNotifications.php');
	results.done(function (data) {
		console.log(data);

		let resultsArray = JSON.parse(data);

		let dropLi = document.createElement('li');
		dropLi.setAttribute('class', 'dropdown');
		dropLi.setAttribute('id', 'notesLi');

		$('#headerList').append(dropLi);

		let dropLink = document.createElement('a');
		dropLink.setAttribute('href', 'javascript:void(0)');
		dropLink.setAttribute('class', 'dropbtn');
		dropLink.innerHTML = 'Notes ' + resultsArray[0][0];
		dropLink.style.width = '4em';

		dropLi.append(dropLink);

		let noteDiv = document.createElement('div');
		noteDiv.setAttribute('class', 'dropdown-content');

		dropLink.append(noteDiv);

		for (let i = 1; i < resultsArray.length; i++) {

			let displayName = resultsArray[i][0];
			let postID = resultsArray[i][1];
			let commentID = resultsArray[i][2];

			let noteLink = document.createElement('button');
			noteLink.setAttribute('value', postID);
			noteLink.setAttribute('class', 'noteButton');
			if (commentID != null) {
				noteLink.innerHTML = displayName + " commented on one of your comments";
				noteDiv.append(noteLink);
			} else {
				noteLink.innerHTML = displayName + " commented on one of your posts";
				noteDiv.append(noteLink);
			}

		}
	});
	results.fail(function (jqXHR) { console.log("Error: " + jqXHR.status); });
	results.always(function () {
	});

	setTimeout(function () {
		let notes = document.getElementsByClassName('noteButton');
		for (let i = 0; i < notes.length; i++) {
			notes[i].addEventListener('click', function () {
				let results = $.post('./PHP/clickNote.php', { postID: notes[i].getAttribute('value') });
				results.done(function (data) {
					console.log(data);
					window.location.replace('./post.php?postID=' + notes[i].getAttribute('value'));
				});
				results.fail(function (jqXHR) { console.log("Error: " + jqXHR.status); });
				results.always(function () {
				});
			});
		}
	}, 500);
}