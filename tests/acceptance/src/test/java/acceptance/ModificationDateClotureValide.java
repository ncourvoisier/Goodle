package acceptance;

import cucumber.api.java.After;
import cucumber.api.java.Before;
import cucumber.api.java.fr.Alors;
import cucumber.api.java.fr.Etantdonné;
import cucumber.api.java.fr.Quand;
import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.htmlunit.HtmlUnitDriver;

import java.sql.Connection;
import java.sql.SQLException;
import java.util.logging.Level;
import java.util.logging.Logger;

import static org.junit.Assert.assertEquals;

public class ModificationDateClotureValide {

    private HtmlUnitDriver driver;
    private Connection con;

    private String urlPage = StaticConnection.localConnection;

    @Before
    public void setUp() throws SQLException {
        Logger logger = Logger.getLogger("");
        logger.setLevel(Level.OFF);

        driver = StaticConnection.getHtmlDriver();
        con = StaticConnection.getDatabaseConnector();
    }

    @Etantdonné("^l'utilisateur modifie la date de cloture$")
    public void lUtilisateurModifieLaDateDeCloture() {
        driver.get(urlPage + "/src/PHP/voir_event.php?event=8");
        driver.findElementByName("btnModifier").click();
        {
            WebElement dropdown = driver.findElement(By.name("DateEvent_j"));
            dropdown.findElement(By.xpath("//option[. = '20']")).click();
        }
        {
            WebElement dropdown = driver.findElement(By.name("DateEvent_m"));
            dropdown.findElement(By.xpath("//option[. = 'Octobre']")).click();
        }
        {
            WebElement dropdown = driver.findElement(By.name("DateEvent_a"));
            dropdown.findElement(By.xpath("//option[. = '2020']")).click();
        }
    }

    @Quand("^la nouvelle date cloture est suppérieur à la date du jour$")
    public void laNouvelleDateClotureEstSupperieurALaDateDuJour() {
        driver.findElementByName("btnValiderEvent").click();
    }

    @Alors("^la nouvelle date cloture est validé$")
    public void laNouvelleDateClotureEstValide() {
        assertEquals(driver.getCurrentUrl(),urlPage+"/src/PHP/evenement_ok.php");
    }

    @After
    public void tearDown() throws SQLException {
        driver.quit();
        con.close();
    }
}
