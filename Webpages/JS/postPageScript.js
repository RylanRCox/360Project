function validate() {
    let slice = document.forms["newPost"]["slice"].value;
    if (slice == 0) {
        alert("Please pick a slice");
        return false;
    }
}