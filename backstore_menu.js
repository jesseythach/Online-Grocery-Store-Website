
let frontMenuButtonsFile;
if (window.location.host.match("localhost")) {
    frontMenuButtonsFile = fetch('/Online-Grocery_Web-Programming/backstore-menu-buttons.json');
} else {
    frontMenuButtonsFile = fetch('/backstore-menu-buttons.json');
}


window.addEventListener('load', function () {
    frontMenuButtonsFile.then(response => {
        return response.json();
    }).then(jsonData => {

        action(jsonData);
    });
});


function action(data) {
    let isLocalhost = window.location.host.match("localhost");



    let frontMenuButtons = data;
    let mClass = "menu-button";
    let menu = document.getElementById("head");
    menu.innerHTML =
        "<label for='collapsible' class='menu-label'>Menu</label>";


    for (const i in frontMenuButtons) {
        if (isLocalhost) {

            menu.innerHTML += "<a href=" + "'/Online-Grocery_Web-Programming/" + frontMenuButtons[i]["linkUrl"] + "' class='" + mClass + "'>" + frontMenuButtons[i]["name"] + "</a>";

        } else {
            menu.innerHTML += "<a href='." + frontMenuButtons[i]["linkUrl"] + "' class='" + mClass + "'>" + frontMenuButtons[i]["name"] + "</a>";
        }



    }
}




