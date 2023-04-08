function validate() {
    let password = document.forms["passForm"]["Password"].value;
    let password2 = document.forms["passForm"]["Password2"].value;
    if (password != password2) {
        alert("Passwords must match");
        return false;
    }
}