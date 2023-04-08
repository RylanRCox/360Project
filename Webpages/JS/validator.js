function validate() {
    let password = document.forms["signUpForm"]["Password"].value;
    let password2 = document.forms["signUpForm"]["Password2"].value;
    if (password != password2) {
        alert("Passwords must match");
        return false;
    }

}