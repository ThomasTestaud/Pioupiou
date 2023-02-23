let searchBar = document.querySelector('#search');
let target = document.getElementById("target");
let main = document.querySelector('main');

//display the seach in the search window
searchBar.addEventListener('keyup', function() {
    let search = searchBar.value;
    let myRequest = new Request('index.php?route=search', {
        method: 'POST',
        body: JSON.stringify({ textToFind: search })
    })
    fetch(myRequest)
        .then(res => res.text())
        .then(res => {
            target.innerHTML = res;
        })
});

//Empty the seach window if users click somewhere else on the page
main.addEventListener('click', function() {
    target.innerHTML = '';
})
