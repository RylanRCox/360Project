<?php
session_start();
include './PHP/isAdmin.php';
checkAdmin();
if($_SESSION['userID'] == -1){
    header('refresh:0;url=homepage.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Breadit - Settings</title>
    <link rel="stylesheet" href="styles/userSettingStyle.css">
    <script type="text/javascript" src="JS/scripts.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
    <nav>
        <ul id = 'headerList'>
            <li><a href="homepage.php"><img id="breadstick" src="images/Breadly.png"><div id="logo">Breadit</div></a></li>
            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn">Slices</a>
                <div class="dropdown-content">
                    <a href="sourdough.php">Sourdough</a>
                    <a href="flatbread.php">Flatbread</a>
                    <a href="croissant.php">Croissant</a>
                </div>
            </li>
            <form method = "GET" action = "searchResults.php">
				<li id = "searchbox">
					<input type="text" name="keyWord" placeHolder = "Search" id = "search" required>
				</li>
				<li>
					<button type="submit">Search</button>
				</li>
			</form>
            <li id = logout><a href ="signOut.php">Logout</a></li>
        </ul>
    </nav>
    <div class="centralDiv">
        <p class="title"><b>User Settings:</b></p>
        <form id = 'emailform' method="post">
            <fieldset>
                <legend>Email</legend>
                <p class="inputField">
                    <label>Enter your new Email:</label>
                    <input type="email" name="Email" id = 'email' placeholder="Email" class="textfield" required />
                </p>
                <p class="inputField">
                    <input type="submit" value="Change Email" class="submitButton" />
                </p>
            </fieldset>
        </form>
        <form method="post" name="passForm" id = "passForm" onsubmit="return validate()" action="">
            <fieldset>
                <legend>Password</legend>
                <p class="inputField">
                    <label>Enter your new password:</label>
                    <input type="password" name="Password" id = 'password' placeholder="Password" class="textfield" required />
                </p>
                <p class="inputField">
                    <label>Enter your new password again:</label>
                    <input type="password" name="Password2" id = 'password2' placeholder="Password" class="textfield" required />
                </p>
                <p class="inputField">
                    <input type="submit" value="Change Password" class="submitButton" />
                </p>
            </fieldset>
        </form>
    </div>
    <div class="centralDiv">
        <p class="title"><b>Profile Settings:</b></p>
        <form method="post" id = 'displayNameForm'>
            <fieldset>
                <legend>Display Name</legend>
                <p class="inputField">
                    <label>Enter your new display name:</label>
                    <input type="text" name="Displayname" id = 'displayName' placeholder="Display Name" class="textfield" required />
                </p>
                <p class="inputField">
                    <input type="submit" value="Change Display Name" class="submitButton" />
                </p>
            </fieldset>
        </form>
        <form method="post" id = 'bioForm'>
            <fieldset>
                <legend>Update User Biography</legend>
                <p class="inputField">
                    <label>Enter your new user bio:</label>
                    <textarea  style = "width:80%; resize:none; height:8em;" form="bioForm" maxlength="200" name="userBio" id = 'userBio' placeholder="userBio" class="textfield" required ></textarea>
                </p>
                <p class="inputField">
                    <input type="submit" value="Change User Bio" class="submitButton" />
                </p>
            </fieldset>
        </form>
        <form method="post" enctype="multipart/form-data" id = "userImageForm" onsubmit="return false;" action="PHP/updateUserImage.php">
        <fieldset>
            <input type="hidden" name = "MAX_FILE_SIZE" value = "1000000"/>
                <legend>Profile Image</legend>
                <?php echo '<p id = "image"><image src = "PHP/image.php?table=users&id='.$_SESSION['userID'].'" style = "width:80%; margin:0em 2em 0em 2em"  ></p>';  ?>
                <p class="inputField">
                    <label>Upload a new Profile Picture:</label>
                    <input type="file" name = "images" id = "images" accept="image/*" required />
                </p>
                <p class="inputField">
                    <input type="submit" value="Change Photo" class="submitButton" />
                </p>
            </fieldset>
        </form>
    </div>

<script>
                
$(document).ready(function (e) {




 $("#emailform").on('submit',(function(e) {
  e.preventDefault();
  let formData = {
            email: $('#email').val(),
            };
  console.log(formData);
  $.ajax({
                type: "POST",
                url: "PHP/updateEmail.php",
                data: formData,
                dataType: "json",
                encode: true,
                }).done(function(data) {
                        alert(data['message']);
                        }).fail(function(data){
                            console.log(data);
                        }); 
 }));
 $("#passForm").on('submit',(function(e) {
  e.preventDefault();
  let formData = {
            pass: $('#password').val(),
            };
  console.log(formData);
  $.ajax({
                type: "POST",
                url: "PHP/updatePassword.php",
                data: formData,
                dataType: "json",
                encode: true,
                }).done(function(data) {
                        alert(data['message']);
                        }).fail(function(data){
                            console.log(data);
                        }); 
 }));
 $("#displayNameForm").on('submit',(function(e) {
  e.preventDefault();
  let formData = {
            displayName: $('#displayName').val(),
            };
  console.log(formData);
  $.ajax({
                type: "POST",
                url: "PHP/updateDisplayName.php",
                data: formData,
                dataType: "json",
                encode: true,
                }).done(function(data) {
                        alert(data['message']);
                        }).fail(function(data){
                            console.log(data);
                        }); 
 }));

 $("#bioForm").on('submit',(function(e) {
    
  e.preventDefault();
  let formData = {
            userBio: $('#userBio').val(),
            };
  console.log(formData);
  $.ajax({
                type: "POST",
                url: "PHP/updateUserBio.php",
                data: formData,
                dataType: "json",
                encode: true,
                }).done(function(data) {
                        alert(data['message']);
                        }).fail(function(data){
                            console.log(data);
                        }); 
 }));

 $("#userImageForm").on('submit',(function(e) {
  e.preventDefault();
 
  $.ajax({
                type: "POST",
                url: "PHP/updateUserImage.php",
                data: new FormData(this),
                dataType: "json",
                contentType: false,
                cache: false,
                processData:false,
                }).done(function(data) {
                        alert(data['message']);
                        if(data['success'])
                            location.reload();
                       console.log(data);
                        }).fail(function(data){
                            alert("Server Not available.");
                        }); 
}));    

});
</script>
</body>
</html>