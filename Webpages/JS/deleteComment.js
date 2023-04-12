function deleteComment(id) {
    let results = $.post("PHP/deleteComment.php", { commentID: id });
    results.done(function (data) {
        console.log(data);
    });
    results.fail(function (jqXHR) { console.log("Error: " + jqXHR.status); });
    results.always(function () { console.log("Comment Deleted"); });
}