<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Breadit - Sign In</title>
    <link rel="stylesheet" href="styles/signInStyle.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>

<body>
    <?php session_start(); ?>
    <nav>
        <div id="homeButton">
            <a href="homepage.php"> &larr; Back to Homepage</a>
        </div>
    </nav>
    <div id="signInBox">
        <form method="POST" name="login" onsubmit="return false;">
            <fieldset>
                <p>
                    <b>Welcome to Breadit!</b>
                </p>
                <p>
                    <input type="email" id="email" placeholder="Email" class="textfield" required />
                </p>
                <p>
                    <input type="password" id="pass" placeholder="Password" class="textfield" required />
                </p>
                <p>
                    <input type="submit" value="Log In" id="logInButton" />
                </p>
                <p id="or">Or</p>
                <div id="roundButton">
                    <a href="signUp.php"> Sign Up </a>
                </div>
            </fieldset>
        </form>
    </div>
    <script>
        $(document).ready(function () {
            $('#logInButton').on('click', function () {


                var formData = {
                    email: $('#email').val(),
                    pass: $('#pass').val(),
                };
                console.log(formData);
                $.ajax({
                    type: "POST",
                    url: "PHP/checkLogin.php",
                    data: formData,
                    dataType: "json",
                    encode: true,
                }).done(function (data) {
                    //return message from server, wait, then redirect. 
                    var userID = data['userID'];
                    var displayName = data['displayName'];
                    if (data['success']) {
                        $('#roundButton').css("display", "none");
                        $('#logInButton').css("display", "none");
                        $("#or").html(data['message'] + "<br> Welcome back " + data['displayName'] + "!");
                        const myTimeout = setTimeout(newWindow, 2000);
                        function newWindow() {
                            window.location.href = "homepage.php";
                        }
                    } else {
                        $("#or").html(data['message']);
                    }
                }).fail(function (data) {
                    console.log(data);
                    $("#or").html(JSON.parse(JSON.stringify(data)).errors);
                });
            });

        });
    </script>


</body>

</html>