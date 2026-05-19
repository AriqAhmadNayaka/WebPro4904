package pbo_10;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public class KoneksiDatabase {

    private static final String HOST = "localhost";
    private static final String PORT = "3306";
    private static final String USERNAME = "root";
    private static final String PASSWORD = "";
    private static final String DATABASE = "perpustakaan";

    public static Connection getKoneksiServer() throws SQLException {
        String url = "jdbc:mysql://" + HOST + ":" + PORT + "/?serverTimezone=Asia/Jakarta";
        return DriverManager.getConnection(url, USERNAME, PASSWORD);
    }

    public static Connection getKoneksiDatabase() throws SQLException {
        String url = "jdbc:mysql://" + HOST + ":" + PORT + "/" + DATABASE
                + "?serverTimezone=Asia/Jakarta";
        return DriverManager.getConnection(url, USERNAME, PASSWORD);
    }
}
