<?php

//Movie class

class Movie implements JsonSerializable {
    private $movieID;
    private $title;
    private $releaseYear;
    private $length;
    private $rating;
    
    public function __construct($movieID, $title, $releaseYear, $length, $rating) {
        $this->movieID = $movieID;
        $this->title = $title;
        $this->releaseYear = $releaseYear;
        $this->length = $length;
        $this->rating = $rating;
    }

    public function getMovieID() {
        return $this->movieID;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getReleaseYear() {
        return $this->releaseYear;
    }

    public function getLength() {
        return $this->length;
    }

    public function getRating() {
        return $this->rating;
    }

    public function jsonSerialize() {
        return get_object_vars($this);
    }
}
// end class Movie