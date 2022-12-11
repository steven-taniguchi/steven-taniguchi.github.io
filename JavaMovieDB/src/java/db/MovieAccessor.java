package db;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;
import entity.Movie;

/**
 * This class provides a central location for accessing the Movie table.
 */
public class MovieAccessor {

    private static Connection conn = null;
    private static PreparedStatement selectAllStatement = null;
    private static PreparedStatement deleteStatement = null;
    private static PreparedStatement insertStatement = null;
    private static PreparedStatement updateStatement = null;

    // constructor is private - no instantiation allowed
    private MovieAccessor() {
    }

    /**
     * Used only by methods in this class to guarantee a database connection.
     *
     * @throws SQLException
     */
    private static void init() throws SQLException {
        if (conn == null) {
            conn = ConnectionManager.getConnection(ConnectionParameters.URL, ConnectionParameters.USERNAME, ConnectionParameters.PASSWORD);
            selectAllStatement = conn.prepareStatement("select * from Movies");
            deleteStatement = conn.prepareStatement("delete from Movies where MOVIEID = ?");
            insertStatement = conn.prepareStatement("insert into Movies values (?,?,?,?,?)");
            updateStatement = conn.prepareStatement("update Movies set TITLE = ?, RELEASEYEAR = ?, LENGTH = ?, RATING = ? where MOVIEID = ?");
        }
    }

    /**
     * Gets all movies.
     *
     * @return a List, possibly empty, of Movie objects
     */
    public static List<Movie> getAllMovies() {
        List<Movie> movies = new ArrayList();
        try {
            init();
            ResultSet rs = selectAllStatement.executeQuery();
            while (rs.next()) {
                int movieID = rs.getInt("MOVIEID");
                String title = rs.getString("TITLE");
                int releaseYear = rs.getInt("RELEASEYEAR");
                int length = rs.getInt("LENGTH");
                int rating = rs.getInt("RATING");
                Movie movie = new Movie(movieID, title, releaseYear, length, rating);
                movies.add(movie);
            }
        } catch (SQLException ex) {
            movies = new ArrayList();
        }
        return movies;
    }

    /**
     * Deletes the Movie with the same ID as the specified movie.
     *
     * @param movie the movie whose ID should be used to match the movie to delete
     * @return <code>true</code> if an movie was deleted; <code>false</code>
     * otherwise
     */
    public static boolean deleteMovie(Movie movie) {
        boolean res;

        try {
            init();
            deleteStatement.setInt(1, movie.getMovieID());
            int rowCount = deleteStatement.executeUpdate();
            res = (rowCount == 1);
        } catch (SQLException ex) {
            res = false;
        }
        return res;
    }

    /**
     * Deletes the Movie with the specified ID.
     *
     * @param id the ID of the movie to delete
     * @return <code>true</code> if an movie was deleted; <code>false</code>
     * otherwise
     */
    public static boolean deleteMovieById(int id) {
        Movie dummy = new Movie(id, "blank", 0, 0, 0);
        return deleteMovie(dummy);
    }
    
    public static boolean insertMovie(Movie movie) {
        boolean res;
        
        try {
            init();
            insertStatement.setInt(1, movie.getMovieID());
            insertStatement.setString(2, movie.getTitle());
            insertStatement.setInt(3, movie.getReleaseYear());
            insertStatement.setInt(4, movie.getLength());
            insertStatement.setInt(5, movie.getRating());
            int rowCount = insertStatement.executeUpdate();
            res = (rowCount == 1);
        }
        catch (SQLException ex) {
            res = false;
        }
        
        return res;
    }

    public static boolean updateMovie(Movie movie) {
        boolean res;
        
        try {
            init();
            updateStatement.setString(1, movie.getTitle());
            updateStatement.setInt(2, movie.getReleaseYear());
            updateStatement.setInt(3, movie.getLength());
            updateStatement.setInt(4, movie.getRating());
            updateStatement.setInt(5, movie.getMovieID());
            int rowCount = updateStatement.executeUpdate();
            res = (rowCount == 1);
        }
        catch (SQLException ex) {
            res = false;
        }
        
        return res;
    }

} // end MovieAccessor
