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