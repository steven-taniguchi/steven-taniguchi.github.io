/*
 * This UI does not make any assumptions about the back end, 
 * except that JSON is the data exchange format.
 * Therefore, any back end will do - Java, PHP, etc.
 */
let addOrUpdate;

window.onload = function () {

    // add event handlers for buttons
    document.querySelector("#GetButton").addEventListener("click", getAllMovies);
    document.querySelector("#AddButton").addEventListener("click", addMovie);
    document.querySelector("#DeleteButton").addEventListener("click", deleteMovie);
    document.querySelector("#UpdateButton").addEventListener("click", updateMovie);
    document.querySelector("#DoneButton").addEventListener("click", processForm);
    document.querySelector("#CancelButton").addEventListener("click", cancelAddUpdate);

    // add event handler for selections on the table
    document.querySelector("table").addEventListener("click", handleRowClick);

    hideUpdatePanel();
};

function clearSelections() {
    let trs = document.querySelectorAll("tr");
    for (let i = 0; i < trs.length; i++) {
        trs[i].classList.remove("highlighted");
    }
}

function handleRowClick(e) {
    //add style to parent of clicked cell
    clearSelections();
    e.target.parentElement.classList.add("highlighted");

    // enable Delete and Update buttons
    document.querySelector("#DeleteButton").removeAttribute("disabled");
    document.querySelector("#UpdateButton").removeAttribute("disabled");
}

function cancelAddUpdate() {
    hideUpdatePanel();
}

// this function handles adds and updates
function processForm() {
    let movieID = document.querySelector("#movieIDInput").value;
    let title = document.querySelector("#titleInput").value;
    let releaseYear = document.querySelector("#releaseYearInput").value;
    let length = document.querySelector("#lengthInput").value;
    let rating = document.querySelector("#ratingInput").value;

    let obj = {
        "movieID": movieID,
        "title": title,
        "releaseYear": releaseYear,
        "length": length,
        "rating": rating
    };

    let url = "MovieService/movies/" + movieID;
    let method = (addOrUpdate === "add") ? "POST" : "PUT";
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            let resp = xmlhttp.responseText.trim();
            if (resp.search("ERROR") >= 0 || resp !== "true") {
                alert("could not complete " + addOrUpdate + " request");
            } else {
                alert(addOrUpdate + " request completed successfully");
                getAllMovies();
            }
        }
    };
    xmlhttp.open(method, url, true);
    xmlhttp.send(JSON.stringify(obj));
}

function deleteMovie() {
    let movieID = document.querySelector(".highlighted").querySelector("td").innerHTML;
    let url = "MovieService/movies/" + movieID; // entity, not action
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            let resp = xmlhttp.responseText.trim();
            if (resp.search("ERROR") >= 0 || resp !== "true") {
                alert("could not complete delete request");
            } else {
                alert("delete request completed successfully");
                getAllMovies();
            }
        }
    };
    xmlhttp.open("DELETE", url, true); // "DELETE" is the action, "url" is the entity
    xmlhttp.send();
}

function addMovie() {
    // Show panel, panel handler takes care of the rest
    addOrUpdate = "add";
    resetUpdatePanel();
    showUpdatePanel();
}

function updateMovie() {
    addOrUpdate = "update";
    resetUpdatePanel();
    populateUpdatePanelWithSelectedMovie();
    showUpdatePanel();
}

function showUpdatePanel() {
    document.getElementById("AddUpdatePanel").classList.remove("hidden");
}

function hideUpdatePanel() {
    document.getElementById("AddUpdatePanel").classList.add("hidden");
}

function resetUpdatePanel() {
    document.querySelector("#movieIDInput").value = 0;
    document.querySelectorAll("titleInput").value = "";
    document.querySelector("#releaseYearInput").value = 0;
    document.querySelector("#lengthInput").value = 0;
    document.querySelector("#ratingInput").value = 0;
}

function populateUpdatePanelWithSelectedMovie() {
    let tds = document.querySelector(".highlighted").querySelectorAll("td");
    document.querySelector("#movieIDInput").value = tds[0].innerHTML;
    document.querySelector("#titleInput").value = tds[1].innerHTML;
    document.querySelector("#releaseYearInput").value = tds[2].innerHTML;
    document.querySelector("#lengthInput").value = tds[3].innerHTML;
    document.querySelector("#ratingInput").value = tds[4].innerHTML;
}

//ADDED
function getMovie() {
    let movieID = document.querySelector(".highlighted").querySelector("td").innerHTML;
    let url = "MovieService/movies/" + movieID; // entity, not action
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            let resp = xmlhttp.responseText.trim();
            if (resp.search("ERROR") >= 0 || resp !== "true") {
                alert("could not complete get request");
            } else {
                alert("get request completed successfully");
                getAllMovies();
            }
        }
    };
    xmlhttp.open("GET", url, true); // "GET" is the action, "url" is the entity
    xmlhttp.send();
}
//ADDED

function getAllMovies() {
    let url = "MovieService/movies"; // REST-style: URL refers to an entity or collection, not an action
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            let resp = xmlhttp.responseText;
            console.log(resp);
            if (resp.search("ERROR") >= 0) {
                alert("could not complete request");
                console.log(resp);
            } else {
                buildTable(xmlhttp.responseText);
                clearSelections();
                resetUpdatePanel();
                hideUpdatePanel();
            }
        }
    };
    xmlhttp.open("GET", url, true); // HTTP verb says what action to take; URL says which item(s) to act upon
    xmlhttp.send();

    // disable Delete and Update buttons
    document.querySelector("#DeleteButton").setAttribute("disabled", "disabled");
    document.querySelector("#UpdateButton").setAttribute("disabled", "disabled");
}

function buildTable(text) {
    let data = JSON.parse(text);
    console.log(data);
    let theTable = document.querySelector("table");
    let html = theTable.querySelector("tr").innerHTML;
    for (let i = 0; i < data.length; i++) {
        let temp = data[i];
        html += "<tr>";
        html += "<td>" + temp.movieID + "</td>";
        html += "<td>" + temp.title + "</td>";
        html += "<td>" + temp.releaseYear + "</td>";
        html += "<td>" + temp.length + "</td>";
        html += "<td>" + temp.rating + "</td>";
        html += "</tr>";
    }
    theTable.innerHTML = html;
}
