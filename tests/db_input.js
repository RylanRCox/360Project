fetch('../WebPages/PHP/checkLogin', {
    method: 'POST',
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({ 'email': 'test','pass':'test' })
})
   .then(response => response.json())
   .then(response => console.log(JSON.stringify(response)))