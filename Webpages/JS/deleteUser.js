function deleteUser(userID) {
    let results = $.get("php/deleteUser.php?userID=" + userID);
    results.done(function (data) {
        console.log(data);
    });
    results.fail(function (jqXHR) { console.log("Error: " + jqXHR.status); });
    results.always(function () { console.log("User Deleted"); });
}