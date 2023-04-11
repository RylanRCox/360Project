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
    <title>Breadit - Post Creator</title>
    <link rel="stylesheet" href="styles/masterStyle.css">
    <script type="text/javascript" src="JS/postPageScript.js"></script>
    <script type="text/javascript" src="JS/displayUser.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
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
					echo '<li id = logout><a>Logout</a></li>';
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
            window.onload = (event) => {
                if($('#logout')){
							$('#logout').children().first().css({'cursor': 'pointer'});;
							$('#logout').on("click",function(){
								$.get("PHP/logOut.php");
								
								const myTimeout = setTimeout(logout, 500);
								function logout(){
									$('#prefs').remove();
                                    $('#userDiv').empty();
									$('#userDiv').remove();
									$('#notesLi').empty();
									$('#notesLi').remove();
									$('#logout').html('<a href="signIn.php">Login</a>');
									$('#logout').attr("id","signUp");
                                    window.location.replace('homepage.php');
								}
							})
						}
            }
		</script>
	</nav>
    <div id="newPostCreator">
        <form method="post" enctype='multipart/form-data' id="newPost" onsubmit="return false;" action="PHP/savePost.php">
            <input type="hidden" name = "MAX_FILE_SIZE" value = "1000000"/>
            <fieldset>
                <div id="titleContainer">
                    <input type="text" id="title" name="title" placeholder="Enter your post's Title" class="textfield" required />
                </div>
                <div id="sliceContainer">
                    <select id="sliceID" name = 'sliceID' required>
                        <option value="0"  >Choose a Slice</option>
                        <option value="1"  >Sourdough</option>
                        <option value="2" >Flatbread</option>
                        <option value="3" >Croissant</option>
                    </select>
                </div>
                <div id="newPostContentContainer">
                    <textarea id="content" name ="content" rows="15" cols="102" placeholder="Enter your content here" required></textarea>
                </div>
                <div id="photoContainer">
                    <input type="file" name="images" id="images" accept="image/*" />
                </div>
                <div id="newPostsubmitContainer">
                    <input type="submit" class="submitButton" id = "post" />
                </div>
            </fieldset>
        </form>
    </div>
    <script>
  $(document).ready(function (e) {
 $("#newPost").on('submit',(function(e) {

  e.preventDefault();
  /*
  var formData = {
            title: $('#title').val(),
            content: $('#content').val(),
            sliceID: document.getElementById('sliceID').selectedOptions[0].value,
            };
   */
   
  $.ajax({
                type: "POST",
                url: "PHP/savePost.php",
                data: new FormData(this),
                dataType: "json",
                contentType: false,
                cache: false,
                processData:false,
                }).done(function(data) {
                    console.log(data);
                        alert(data['message']);
                        if(data['success']){
                        function newWindow() {
                            window.location.href = "homepage.php";
                            }
                        }
                        newWindow();
                            
                        }).fail(function(data){
                            console.log(data);
                            alert("Cannot Connect to Server");
                        }); 


 }));
});
    </script>
</body>
</html>
