let addOrUpdate; // need this because the same panel is used for adds and updates

window.onload = function () {

    // add event handlers for buttons
    document.querySelector("#GetButton").addEventListener("click", getAllMovies);
    document.querySelector("#DeleteButton").addEventListener("click", deleteMovie);
    document.querySelector("#AddButton").addEventListener("click", addMovie);
    document.querySelector("#UpdateButton").addEventListener("click", updateMovie);
    document.querySelector("#DoneButton").addEventListener("click", processForm);
    document.querySelector("#CancelButton").addEventListener("click", cancelAddUpdate);

    // add event handler for selections on the table
    document.querySelector("table").addEventListener("click", handleRowClick);
    
    //Hide updatepanel on load
    hideUpdatePanel();
};

// "Get Data" button
function getAllMovies() {
    let url = "movieService/movies"; // REST-style: URL refers to an entity or collection, not an action. Collection in this case
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            let resp = xmlhttp.responseText;
            console.log(resp);
            if (resp.search("ERROR") >= 0) {
                alert("oh no... see console for error");
                console.log(resp);
            } else {
                buildTable(xmlhttp.responseText);
                clearSelections();
                resetUpdatePanel();
                hideUpdatePanel();
            }
        }
    };
    xmlhttp.open("GET", url, true); // HTTP verb says what action to take; URL says which movie(s) to act upon
    xmlhttp.send();

    // disable Delete and Update buttons
    document.querySelector("#DeleteButton").setAttribute("disabled", "disabled");
    document.querySelector("#UpdateButton").setAttribute("disabled", "disabled");
}

// "Delete" button
function deleteMovie() {
    let id = document.querySelector(".highlighted").querySelector("td").innerHTML;
    let url = "movieService/movies/" + id; // entity, not action
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            let resp = xmlhttp.responseText;
            if (resp.search("ERROR") >= 0 || resp != 1) {
                alert("could not complete request");
                console.log(resp);
            } else {
                getAllMovies();
            }
        }
    };
    xmlhttp.open("DELETE", url, true); // "DELETE" is the action, "url" is the entity
    xmlhttp.send();
}

// "Add" button
function addMovie() {
    // Show panel, panel handler takes care of the rest
    addOrUpdate = "add";
    resetUpdatePanel();
    showUpdatePanel();
}

// "Update" button
function updateMovie() {
    addOrUpdate = "update";
    resetUpdatePanel();
    populateUpdatePanelWithSelectedMovie();
    showUpdatePanel();
}

// "Done" button (on the input panel)
function processForm() {
    // We need to send the data to the server. 
    // We will create a JSON string and pass it to the "send" method
    // of the HttpRequest object. Then if we send the request with POST or PUT,
    // the JSON string will be included as part of the message body 
    // (not a form parameter).
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

    let url = "movieService/movies/" + movieID;
    let method = (addOrUpdate === "add") ? "POST" : "PUT";
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            let resp = xmlhttp.responseText;
            if (resp.search("ERROR") >= 0 || resp != 1) {
                alert("could not complete request");
                console.log(resp);
            } else {
                getAllMovies();
            }
        }
    };
    xmlhttp.open(method, url, true); // method is either POST or PUT
    xmlhttp.send(JSON.stringify(obj));
}

// "Cancel" button (on the input panel)
function cancelAddUpdate() {
    hideUpdatePanel();
}

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

function showUpdatePanel() {
    document.getElementById("AddUpdatePanel").classList.remove("hidden");
}

function hideUpdatePanel() {
    document.getElementById("AddUpdatePanel").classList.add("hidden");
}

function resetUpdatePanel() {
    setIDFieldState(true);
    document.querySelector("#movieIDInput").value = 0;
    document.querySelector("#titleInput").value = "";
    document.querySelector("#releaseYearInput").value = 0;
    document.querySelector("#lengthInput").value = 0;
    document.querySelector("#ratingInput").value = 0;
}

function populateUpdatePanelWithSelectedMovie() {
    setIDFieldState(false)
    let tds = document.querySelector(".highlighted").querySelectorAll("td");
    document.querySelector("#movieIDInput").value = tds[0].innerHTML;
    document.querySelector("#titleInput").value = tds[1].innerHTML;
    document.querySelector("#releaseYearInput").value = tds[2].innerHTML;
    document.querySelector("#lengthInput").value = tds[3].innerHTML;
    document.querySelector("#ratingInput").value = tds[4].innerHTML;
}

function buildTable(text) {
    let data = JSON.parse(text);
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

function setIDFieldState(val) {
    let idInput = document.querySelector("#movieIDInput");
    if (val) {
        idInput.removeAttribute("disabled");
    } else {
        idInput.setAttribute("disabled", "disabled");
    }
}