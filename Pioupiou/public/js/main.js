window.addEventListener('DOMContentLoaded', (event) => {

    let newPost = document.querySelector('#new-post');
    let articleForm = document.querySelector('.article-form');
    let backlay = document.querySelector('.backlay');
    let target = document.getElementById("target");
    let main = document.querySelector('main');
    let nav = document.querySelector('nav');

    /************************* SEARCH BAR AJAX ****************************/
    let searchform = document.querySelector('#search-form');
    let searchBar = document.querySelector('#search');


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

                backlay.classList.remove('none');

            })
    });

    //Empty the seach window if users click somewhere else on the page
    main.addEventListener('click', function() {
        target.innerHTML = '';
    })
    nav.addEventListener('click', function() {
        target.innerHTML = '';
    })


    /************************* COMMENTS AJAX ****************************/

    let displayComment = document.querySelectorAll('.display-comment');

    for (let i = 0; i < displayComment.length; i++) {
        displayComment[i].addEventListener('click', displayAllComments)
    }

    function displayAllComments(e) {

        //get the id of the article
        let articleId = (e.target.id).replace("button-", "");

        //target the right element in the html by add the article id
        let divComment = document.querySelector('#comment-target-' + (e.target.id).replace("button-", ""));

        //get all the comments from that article the AJAX
        let fetchcomments = new Request('index.php?route=getcomments', {
            method: 'POST',
            body: JSON.stringify({ id: articleId })
        })

        fetch(fetchcomments)
            .then(res => res.text())
            .then(res => {
                divComment.innerHTML = res;


            })
        //scroll the the bottom of the element
        divComment.scrollTop += 1000;
    }




    /************************* ARTICLE FORM ****************************/

    newPost.addEventListener('click', function() {
        articleForm.classList.remove('none');
        backlay.classList.remove('none');
    });
    backlay.addEventListener('click', function() {
        articleForm.classList.add('none');
        backlay.classList.add('none');
    });
    searchform.addEventListener('click', function() {
        articleForm.classList.add('none');
        backlay.classList.add('none');
    });

    /************************* FLYING NOTIFICATIONS ****************************/

    let flyingNots = document.querySelectorAll('.flying-notifications');

    for (let i = 0; i < flyingNots.length; i++) {
        flyingNots[i].addEventListener('click', function() {
            flyingNots[i].classList.add('none');
        });

    }

    /************************* MODIFY PROFILE ****************************/
    let editIcons = document.querySelectorAll(".edit-profile");
    let activateEdit = document.querySelector(".activate-edit-profile");
    let desactivateEdit = document.querySelector(".desactivate-edit-profile");

    //Display all modify icons on click
    activateEdit.addEventListener('click', function() {
        for (const element of editIcons) {
            element.classList.remove('none');
        }
        activateEdit.classList.add('none');
        desactivateEdit.classList.remove('none');
    });

    //hide all modify icons on click
    desactivateEdit.addEventListener('click', function() {
        for (const element of editIcons) {
            element.classList.add('none');
        }
        activateEdit.classList.remove('none');
        desactivateEdit.classList.add('none');
    })




});
