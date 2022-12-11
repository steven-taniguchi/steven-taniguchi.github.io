<?php

require_once (__DIR__ . '/../db/MovieAccessor.php');
require_once (__DIR__ . '/../entity/Movie.php');
require_once (__DIR__ . '/../utils/ChromePhp.php');

/*
 * Important Note:
 * 
 * Even if the method is not GET, the $_GET array will still contain the movie ID. 
 * Why? Because the router (.htaccess) converts the URL from 
 *     movieService/moviess/N
 * to
 *     movieService.php?movieid=N
 * The syntax "?key=value" is interpreted as a GET parameter and is therefore
 * stored in the $_GET array.
 */

$method = $_SERVER['REQUEST_METHOD'];

//Determine what process to perform
if ($method === "GET") {
    doGet();
} else if ($method === "POST") {
    doPost();
} else if ($method === "DELETE") {
    doDelete();
} else if ($method === "PUT") {
    doPut();
}

//Get all or get one movie
function doGet() {
    // individual get
    if (isset($_GET['movieid'])) { 
        // Individual gets not implemented.
        ChromePhp::log("Sorry, individual gets not allowed!");
    }
    // get collection
    else {
        try {
            $ma = new MovieAccessor();
            $results = $ma->getAllMovies();
            $results = json_encode($results, JSON_NUMERIC_CHECK);
            echo $results;
        } catch (Exception $e) {
            echo "ERROR " . $e->getMessage();
        }
    }
}

//Single or multiple deletes
function doDelete() {
    if (isset($_GET['movieid'])) { 
        $movieID = $_GET['movieid']; 
        // Only the ID of the movie matters for a delete, but the accessor expects an object, so we need a dummy object.
        $movieObj = new Movie($movieID, "blank", 1, 1, 1);

        // delete the object from DB
        $ma = new MovieAccessor();
        $success = $ma->deleteMovie($movieObj);
        echo $success;
    } else {
        // Bulk deletes not implemented.
        ChromePhp::log("Sorry, bulk deletes not allowed!");
    }
}

//Single or bulk insert
function doPost() {
    if (isset($_GET['movieid'])) { //movieid from htaccess url mapping
        // The details of the movie to insert will be in the request body.
        $body = file_get_contents('php://input');
        $contents = json_decode($body, true);

        // create a Movie object and add to db
        $movieObj = new Movie($contents['movieID'], $contents['title'], $contents['releaseYear'], $contents['length'], $contents['rating']);
        $ma = new MovieAccessor();
        $success = $ma->insertMovie($movieObj);
        echo $success;
    } else {
        // Bulk inserts not implemented.
        ChromePhp::log("Sorry, bulk inserts not allowed!");
    }
}

//Bulk or single update
function doPut() {
    if (isset($_GET['movieid'])) { 
        // The details of the movie to update will be in the request body.
        $body = file_get_contents('php://input');
        $contents = json_decode($body, true);

        // create a Movie object
        $movieObj = new Movie($contents['movieID'], $contents['title'], $contents['releaseYear'], $contents['length'], $contents['rating']);

        // update the object in the  DB
        $ma = new MovieAccessor();
        $success = $ma->updateMovie($movieObj);
        echo $success;
    } else {
        // Bulk updates not implemented.
        ChromePhp::log("Sorry, bulk updates not allowed!");
    }
}
