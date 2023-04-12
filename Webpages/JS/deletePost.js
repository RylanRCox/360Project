function deletePost(id) {
    let results = $.post("PHP/deletePost.php", { postID: id });
    results.done(function (data) {
        console.log(data);
    });
    results.fail(function (jqXHR) { console.log("Error: " + jqXHR.status); });
    results.always(function () {
        console.log("Post Deleted");
    });
}