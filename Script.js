
let themeButton = document.createElement("button");

themeButton.innerText = "Dark Mode";
themeButton.style.margin = "10px";

document.querySelector("header").appendChild(themeButton);

themeButton.addEventListener("click", function () {

    document.body.classList.toggle("dark");

    if (document.body.classList.contains("dark")) {
        localStorage.setItem("theme", "dark");
        themeButton.innerText = "Light Mode";
    } else {
        localStorage.setItem("theme", "light");
        themeButton.innerText = "Dark Mode";
    }

});


// Remember theme after refresh

if (localStorage.getItem("theme") == "dark") {
    document.body.classList.add("dark");
    themeButton.innerText = "Light Mode";
}




let questions = document.querySelectorAll("article h3");

questions.forEach(function (question) {

    question.addEventListener("click", function () {

        let answer = question.nextElementSibling;

        if (answer.style.display == "none") {
            answer.style.display = "block";
        } else {
            answer.style.display = "none";
        }

    });

});


let notificationButton = document.createElement("button");

notificationButton.innerText = "Show Notification";
notificationButton.style.margin = "10px";

document.querySelector("main").prepend(notificationButton);

notificationButton.addEventListener("click", function () {

    alert("New StudentHub notification!");

});




let modalButton = document.createElement("button");

modalButton.innerText = "Open Popup";
modalButton.style.margin = "10px";

document.querySelector("main").prepend(modalButton);


let modal = document.createElement("div");

modal.innerHTML = `
    <h2>StudentHub</h2>
    <p>Welcome to StudentHub!</p>
    <button id="closeModal">Close</button>
`;

modal.style.display = "none";
modal.style.background = "white";
modal.style.padding = "20px";
modal.style.border = "2px solid black";

document.querySelector("main").prepend(modal);


modalButton.addEventListener("click", function () {

    modal.style.display = "block";

});


document.getElementById("closeModal").addEventListener("click", function () {

    modal.style.display = "none";

});




let menuButton = document.createElement("button");

menuButton.innerText = "☰ Menu";
menuButton.style.margin = "10px";

document.querySelector("header").appendChild(menuButton);


let nav = document.querySelector("nav");


menuButton.addEventListener("click", function () {

    if (nav.style.display == "none") {
        nav.style.display = "block";
    } else {
        nav.style.display = "none";
    }

});



let events = document.querySelectorAll("main article");

if (events.length > 0) {

    let current = 0;

    // Hide all events except first
    for (let i = 1; i < events.length; i++) {
        events[i].style.display = "none";
    }

    let nextButton = document.createElement("button");
    nextButton.innerText = "Next";

    let previousButton = document.createElement("button");
    previousButton.innerText = "Previous";

    document.querySelector("main").appendChild(previousButton);
    document.querySelector("main").appendChild(nextButton);


    nextButton.addEventListener("click", function () {

        events[current].style.display = "none";

        current++;

        if (current >= events.length) {
            current = 0;
        }

        events[current].style.display = "block";

    });


    previousButton.addEventListener("click", function () {

        events[current].style.display = "none";

        current--;

        if (current < 0) {
            current = events.length - 1;
        }

        events[current].style.display = "block";

    });

}