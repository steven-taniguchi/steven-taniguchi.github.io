<?php

require_once 'ConnectionManager.php';
require_once (__DIR__ . '/../entity/Movie.php');

class MovieAccessor {

    private $getByIDStatementString = "select * from MOVIES where movieID = :movieID";
    private $deleteStatementString = "delete from Movies where movieID = :movieID";
    private $insertStatementString = "insert INTO Movies values (:movieID, :title, :releaseYear, :length, :rating)";
    private $updateStatementString = "update Movies set movieID = :movieID, title = :title, releaseYear = :releaseYear, rating = :rating where movieID = :movieID";
    private $conn = NULL;
    private $getByIDStatement = NULL;
    private $deleteStatement = NULL;
    private $insertStatement = NULL;
    private $updateStatement = NULL;

    // Constructor will throw exception if there is a problem with ConnectionManager, or with the prepared statements.
    public function __construct() {
        $cm = new ConnectionManager();

        $this->conn = $cm->connect_db();
        if (is_null($this->conn)) {
            throw new Exception("no connection");
        }
        $this->getByIDStatement = $this->conn->prepare($this->getByIDStatementString);
        if (is_null($this->getByIDStatement)) {
            throw new Exception("bad statement: '" . $this->getAllStatementString . "'");
        }

        $this->deleteStatement = $this->conn->prepare($this->deleteStatementString);
        if (is_null($this->deleteStatement)) {
            throw new Exception("bad statement: '" . $this->deleteStatementString . "'");
        }

        $this->insertStatement = $this->conn->prepare($this->insertStatementString);
        if (is_null($this->insertStatement)) {
            throw new Exception("bad statement: '" . $this->getAllStatementString . "'");
        }

        $this->updateStatement = $this->conn->prepare($this->updateStatementString);
        if (is_null($this->updateStatement)) {
            throw new Exception("bad statement: '" . $this->updateStatementString . "'");
        }
    }

    /**
     * Gets movies by executing a SQL "select" statement. An empty array
     * is returned if there are no results, or if the query contains an error.
     * 
     * @param String $selectString a valid SQL "select" statement
     * @return array Movie objects
     */
    private function getMoviesByQuery($selectString) {
        $result = [];

        try {
            $stmt = $this->conn->prepare($selectString);
            $stmt->execute();
            $dbresults = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($dbresults as $r) {
                $movieID = $r['movieID'];
                $title = $r['title'];
                $releaseYear = $r['releaseYear'];
                $length = $r['length'];
                $rating = $r['rating'];
                $obj = new Movie($movieID, $title, $releaseYear, $length, $rating);
                array_push($result, $obj);
            }
        }
        catch (Exception $e) {
            $result = [];
        }
        finally {
            if (!is_null($stmt)) {
                $stmt->closeCursor();
            }
        }
        return $result;
    }

    /**
     * Gets all movies.
     * 
     * @return array Movie objects, possibly empty
     */
    public function getAllMovies() {
        return $this->getMoviesByQuery("select * from MOVIES");
    }

    /**
     * Gets the movie with the specified ID.
     * 
     * @param Integer $id the ID of the movie to retrieve 
     * @return the Movie object with the specified ID, or NULL if not found
     */
    private function getMovieByID($id) {
        $result = NULL;

        try {
            $this->getByIDStatement->bindParam(":movieID", $id);
            $this->getByIDStatement->execute();
            $dbresults = $this->getByIDStatement->fetch(PDO::FETCH_ASSOC); // not fetchAll

            if ($dbresults) {
                $movieID = $dbresults['movieID'];
                $title = $dbresults['title'];
                $releaseYear = $dbresults['releaseYear'];
                $length = $dbresults['length'];
                $rating = $dbresults['rating'];
                $result = new Movie($movieID, $title, $releaseYear, $length, $rating);
            }
        }
        catch (Exception $e) {
            $result = NULL;
        }
        finally {
            if (!is_null($this->getByIDStatement)) {
                $this->getByIDStatement->closeCursor();
            }
        }
        return $result;
    }

    /**
     * Deletes a movie.
     * @param Movie $movie an object whose ID is EQUAL TO the ID of the movie to delete
     * @return boolean indicates whether the movie was deleted
     */
    public function deleteMovie($movie) {
        $success = false;

        $movieID = $movie->getMovieID(); // only the ID is needed

        try {
            $this->deleteStatement->bindParam(":movieID", $movieID);
            $success = $this->deleteStatement->execute(); // this doesn't mean what you think it means
            $rc = $this->deleteStatement->rowCount();
        }
        catch (PDOException $e) {
            $success = false;
        }
        finally {
            if (!is_null($this->deleteStatement)) {
                $this->deleteStatement->closeCursor();
            }
            return $success;
        }
    }

    /**
     * Inserts a movie into the database.
     * 
     * @param Movie $movie an object of type Movie
     * @return boolean indicates if the movie was inserted
     */
    public function insertMovie($movie) {
        $success = false;

        $movieID = $movie->getMovieID();
        $title = $movie->getTitle();
        $releaseYear = $movie->getReleaseYear();
        $length = $movie->getLength();
        $rating = $movie->getRating();

        try {
            $this->insertStatement->bindParam(":movieID", $movieID);
            $this->insertStatement->bindParam(":title", $title);
            $this->insertStatement->bindParam(":releaseYear", $releaseYear);
            $this->insertStatement->bindParam(":length", $length);
            $this->insertStatement->bindParam(":rating", $rating);
            $success = $this->insertStatement->execute();// this doesn't mean what you think it means
        }
        catch (PDOException $e) {
            $success = false;
        }
        finally {
            if (!is_null($this->insertStatement)) {
                $this->insertStatement->closeCursor();
            }
            return $success;
        }
    }

    /**
     * Updates a movie in the database.
     * 
     * @param Movie $movie an object of type Movie, the new values to replace the database's current values
     * @return boolean indicates if the movie was updated
     */
    public function updateMovie($movie) {
        $success = false;

        $movieID = $movie->getMovieID();
        $title = $movie->getTitle();
        $releaseYear = $movie->getReleaseYear();
        $length = $movie->getLength();
        $rating = $movie->getRating();

        try {
            $this->updateStatement->bindParam(":movieID", $movieID);
            $this->updateStatement->bindParam(":title", $title);
            $this->updateStatement->bindParam(":releaseYear", $releaseYear);
            $this->updateStatement->bindParam(":length", $length);
            $this->updateStatement->bindParam(":rating", $rating);
            $success = $this->updateStatement->execute();// this doesn't mean what you think it means
            
        }
        catch (PDOException $e) {
            $success = false;
        }
        finally {
            if (!is_null($this->updateStatement)) {
                $this->updateStatement->closeCursor();
            }
            return $success;
        }
    }

}
// end class movieAccessor

