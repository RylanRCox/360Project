
fetch("../Webpages/PHP/checkLogin.php", {
  method: "POST",
  body: JSON.stringify({
    email: "test",
    pass: "test"
  }),
  headers: {
    "Content-type": "application/json; charset=UTF-8"
  }
})
  .then((response) => response.json())
  .then((json) => console.log(json));

/*
let xhr = new XMLHttpRequest();
xhr.open("POST", "http://localhost/360Project/Webpages/PHP/checkLogin.php");
xhr.setRequestHeader("Accept", "application/json");
xhr.setRequestHeader("Content-Type", "application/json");

xhr.onreadystatechange = function () {
  if (xhr.readyState === 4) {
    console.log(xhr.status);
    console.log(xhr.responseText);
  }};

let data = `{
  "email": "test",
  "pass": "test"
}`;

xhr.send(data);
console.log(data);
*/