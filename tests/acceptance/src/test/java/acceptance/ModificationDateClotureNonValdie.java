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

public class ModificationDateClotureNonValdie {

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

    @Etantdonné("^L'utilisateur modifie la date de cloture de l'événement$")
    public void lUtilisateurModifieLaDateDeClotureDeLEvenement() {
        driver.get(urlPage + "/src/PHP/voir_event.php?event=8");
        driver.findElementByName("btnModifier").click();
        {
            WebElement dropdown = driver.findElement(By.name("DateEvent_j"));
            dropdown.findElement(By.xpath("//option[. = '20']")).click();
        }
        {
            WebElement dropdown = driver.findElement(By.name("DateEvent_m"));
            dropdown.findElement(By.xpath("//option[. = 'Janvier']")).click();
        }
        {
            WebElement dropdown = driver.findElement(By.name("DateEvent_a"));
            dropdown.findElement(By.xpath("//option[. = '2020']")).click();
        }
    }

    @Quand("^la nouvelle date de cloture est antérieure à la date du jour$")
    public void laNouvelleDateClotureEstAnterieurALaDateDuJour() {
        driver.findElementById("erreur");
    }

    @Alors("^un message d'erreur s'affiche$")
    public void unMessageDErreurSAffiche() {
        assertEquals(driver.getCurrentUrl(), urlPage + "/src/PHP/modif_evenement.php?event=8");
    }

    @After
    public void tearDown() throws SQLException {
        driver.quit();
        con.close();
    }


}
