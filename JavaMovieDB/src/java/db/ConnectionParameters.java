package db;

/**
 * It's better practice to store this information in a configuration file.
 */
public class ConnectionParameters {
    
    public static final String URL = "jdbc:derby://localhost:1527/Movies";
    public static final String USERNAME = "steven";
    public static final String PASSWORD = "steven";
    
    // no instantiation allowed
    private ConnectionParameters() {}
    
} // end class ConnectionParameters
