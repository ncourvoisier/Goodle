package acceptance;

import cucumber.api.java.After;
import cucumber.api.java.Before;
import org.openqa.selenium.htmlunit.HtmlUnitDriver;

import java.sql.Connection;
import java.sql.SQLException;
import java.util.logging.Level;
import java.util.logging.Logger;

import static org.junit.Assert.assertTrue;

public class userInscription {

    private HtmlUnitDriver driver;
    private Connection con;

    @Before
    public void setUp() throws SQLException {
        Logger logger = Logger.getLogger("");
        logger.setLevel(Level.OFF);

        driver = StaticConnection.getHtmlDriver();
        con = StaticConnection.getDatabaseConnector();
    }



    @After
    public void tearDown() throws SQLException {
        driver.quit();
        con.close();
    }
}
