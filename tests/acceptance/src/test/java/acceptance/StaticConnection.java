package acceptance;

import com.gargoylesoftware.htmlunit.BrowserVersion;
import org.openqa.selenium.htmlunit.HtmlUnitDriver;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public class StaticConnection {

    public static String distanteConnection = "http://m2gl.deptinfo-st.univ-fcomte.fr/~m2test3/preprod";
    public static String localConnection = "http://localhost/Goodle/Goodle"; //a changer pour chacun

    public static Connection getDatabaseConnector() throws SQLException {

        String dbUrl= "jdbc:mysql://localhost/m2test3?useUnicode=true&useJDBCCompliantTimezoneShift=true&useLegacyDatetimeCode=false&serverTimezone=UTC";
        String dbUsername= "root";
        String dbPassword= "";

        return DriverManager.getConnection(dbUrl, dbUsername, dbPassword);
    }


    public static HtmlUnitDriver getHtmlDriver() {
        HtmlUnitDriver driver = new HtmlUnitDriver(BrowserVersion.CHROME);
        driver.get("http://m2gl.deptinfo-st.univ-fcomte.fr/~m2test3/preprod");
        return driver;
    }
}
