package acceptance;

import com.gargoylesoftware.htmlunit.BrowserVersion;
import org.openqa.selenium.htmlunit.HtmlUnitDriver;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public class AcceptanceConnector {
    public static Connection getDatabaseConnector() throws SQLException {

        String dbUrl= "jdbc:mysql://localhost/m2test3?useUnicode=true&useJDBCCompliantTimezoneShift=true&useLegacyDatetimeCode=false&serverTimezone=UTC";
        String dbUsername= "m2test3";
        String dbPassword= "m2test3";

        return DriverManager.getConnection(dbUrl, dbUsername, dbPassword);
    }


    public static HtmlUnitDriver getHtmlDriver() {
        HtmlUnitDriver driver = new HtmlUnitDriver(BrowserVersion.CHROME);
        driver.get("http://m2gl.deptinfo-st.univ-fcomte.fr/~m2test3/preprod");
        return driver;
    }
}
