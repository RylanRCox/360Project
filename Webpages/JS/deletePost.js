function deletePost(postID) {
    let results = $.get("php/deletePost.php?postID=" + postID);
    results.done(function (data) {
        console.log(data);
    });
    results.fail(function (jqXHR) { console.log("Error: " + jqXHR.status); });
    results.always(function () { console.log("Post Deleted"); });
}