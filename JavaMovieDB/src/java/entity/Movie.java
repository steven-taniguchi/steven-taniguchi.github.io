package entity;

public class Movie {
    private int movieID;
    private String title;
    private int releaseYear;
    private int length;
    private int rating;

    public Movie(int movieID, String title, int releaseYear, int length, int rating) {
        this.movieID = movieID;
        this.title = title;
        this.releaseYear = releaseYear;
        this.length = length;
        this.rating = rating;
    }
    
    public int getMovieID() {
        return movieID;
    }

    public String getTitle() {
        return title;
    }

    public int getReleaseYear() {
        return releaseYear;
    }

    public int getLength() {
        return length;
    }

    public int getRating() {
        return rating;
    }
}
