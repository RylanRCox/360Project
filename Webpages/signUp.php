<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Breadit - Sign Up</title>
	<link rel="stylesheet" href="styles/signUpStyle.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
	<?php
	session_start();
	?>
	<nav>
		<div id="homeButton">
			<a href="homepage.php"> &larr; Back to Homepage</a>
		</div>
	</nav>
	<div id="signUpBox">
		<form name = "signUpForm" method="post" id = "signup">
			<fieldset>
				<p>
					<b>Welcome to Breadit!</b>
				</p>
				<p>
					<label class="labelText">Please enter your email:</label>
					<input type="email" name="Email" placeholder="Email" class="textfield" id = "email" required/>
				</p>
				<p>
					<label class="labelText">Please enter your password:</label>
					<input type="password" name="Password" placeholder="Password" class="textfield" id = "password" required/>
				</p>
				<p>
					<label class="labelText">Please enter your password again:</label>
					<input type="password" name="Password2" placeholder="Password" class="textfield" id = "password2" required/>
				</p>
				<p>
					<label class="labelText">Please enter a display name:</label>
					<input type="text" name="Displayname" placeholder="Display Name" class="textfield" id = "displayName" required/>
				</p>
				<p id = "result">Your profile picture can be changed later</p>
				<p>
					<input type="submit" value="Sign Up!" id="signUpButton" />
				</p>
				<p id="or">Or</p>
				<div id="roundButton">
					<a href="signIn.php"> Back to Sign In </a>
				</div>
				
			</fieldset>
		</form>
	</div>
	<script>
		//make request with ajax
		$(document).ready(function() {
			let password = $('#password').val();
			let password2 = $('#password2').val();
			$('#signUpButton').on('click',function(){
				$('#signUpButton').attr('disabled','disabled');
				var formData = {
						email:$('#email').val(),
						pass: $('#password').val(),
						displayName: $('#displayName').val(),
					};
				if (password != password2){
					alert("Passwords must match");
				}else{ 
					$.ajax({
						type: "POST",
						url: "PHP/savesignup.php",
						data: formData,
						dataType: "json",
						encode: true,
						})
						.done(function(data) {
							//return message from server, wait, then redirect. 
						   $("#result").html(JSON.parse(JSON.stringify(data)).message);
						   if(data['success']){
							//if successfull call checklogin.php
							var formData = {
								email:$('#email').val(),
								pass: $('#password').val(),
								}
							   $.ajax({
									type: "POST",
									url: "PHP/checkLogin.php",
									data: formData,
									dataType: "json",
									encode: true,
									})
						   const myTimeout = setTimeout(newWindow, 2000);
							function newWindow() {
									window.location.href = "homepage.php";
							}
						}
						}).fail(function(data){
							$("#result").html(JSON.parse(JSON.stringify(data)).errors);
						});
					   
						
				   
				}  
			});
		});
	</script>
</body>
</html>