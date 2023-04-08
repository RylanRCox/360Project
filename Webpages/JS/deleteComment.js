function deleteComment(commentID) {
    let results = $.get("php/deleteComment.php?commentID=" + commentID);
    results.done(function (data) {
        console.log(data);
    });
    results.fail(function (jqXHR) { console.log("Error: " + jqXHR.status); });
    results.always(function () { console.log("Comment Deleted"); });
}