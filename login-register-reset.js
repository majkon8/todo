"use-strict";

let page;
let selectedPage;

localStorage.getItem("page") === null
    ? (page = "sign-in")
    : (page = localStorage.getItem("page"));

document.getElementById("sign-in").onclick = () => {
    if (selectedPage !== "sign-in") {
        selectedPage = "sign-in";
        document.getElementById("password-div").style.display = "block";
        document.getElementById("repeat-password-div").style.display = "none";
        document.getElementById("checkbox-container").style.display = "block";
        document.getElementById("arrow-sign-in").style.display = "block";
        document.getElementById("arrow-sign-up").style.display = "none";
        document.getElementById("arrow-reset").style.display = "none";
        document.getElementById("sign-up").style.color = "rgb(55, 145, 95)";
        document.getElementById("reset").style.color = "rgb(55, 145, 95)";
        document.getElementById("sign-in").style.color = "white";
        document.getElementById("hidden").setAttribute("name", "sign-in");
        document.getElementById("submit-button").innerHTML = "Sign In";
        if (localStorage.getItem("page") !== "sign-in") {
            document
                .querySelectorAll(".form__input--container div")
                .forEach(div => {
                    div.remove();
                });
        }
        localStorage.setItem("page", "sign-in");
        document.getElementById("password-input").required = true;
        document.getElementById("repeat-password-input").required = false;
    }
};

document.getElementById("sign-up").onclick = () => {
    if (selectedPage !== "sign-up") {
        selectedPage = "sign-up";
        document.getElementById("password-div").style.display = "block";
        document.getElementById("repeat-password-div").style.display = "block";
        document.getElementById("checkbox-container").style.display = "none";
        document.getElementById("arrow-sign-in").style.display = "none";
        document.getElementById("arrow-sign-up").style.display = "block";
        document.getElementById("arrow-reset").style.display = "none";
        document.getElementById("sign-in").style.color = "rgb(55, 145, 95)";
        document.getElementById("reset").style.color = "rgb(55, 145, 95)";
        document.getElementById("sign-up").style.color = "white";
        document.getElementById("hidden").setAttribute("name", "sign-up");
        document.getElementById("submit-button").innerHTML = "Sign Up";
        if (localStorage.getItem("page") !== "sign-up") {
            document
                .querySelectorAll(".form__input--container div")
                .forEach(div => {
                    div.remove();
                });
        }
        localStorage.setItem("page", "sign-up");
        document.getElementById("password-input").required = true;
        document.getElementById("repeat-password-input").required = true;
    }
};

document.getElementById("reset").onclick = () => {
    if (selectedPage !== "reset") {
        selectedPage = "reset";
        document.getElementById("password-div").style.display = "none";
        document.getElementById("repeat-password-div").style.display = "none";
        document.getElementById("checkbox-container").style.display = "none";
        document.getElementById("arrow-sign-in").style.display = "none";
        document.getElementById("arrow-sign-up").style.display = "none";
        document.getElementById("arrow-reset").style.display = "block";
        document.getElementById("sign-in").style.color = "rgb(55, 145, 95)";
        document.getElementById("sign-up").style.color = "rgb(55, 145, 95)";
        document.getElementById("reset").style.color = "white";
        document.getElementById("hidden").setAttribute("name", "reset");
        document.getElementById("submit-button").innerHTML = "Reset password";
        if (localStorage.getItem("page") !== "reset") {
            document
                .querySelectorAll(".form__input--container div")
                .forEach(div => {
                    div.remove();
                });
        }
        localStorage.setItem("page", "reset");
        document.getElementById("password-input").required = false;
        document.getElementById("repeat-password-input").required = false;
    }
};

document.getElementById(page).click();
