package acceptance;

import cucumber.api.PendingException;
import cucumber.api.java.After;
import cucumber.api.java.Before;
import cucumber.api.java.fr.Alors;
import cucumber.api.java.fr.Et;
import cucumber.api.java.fr.Etantdonné;
import cucumber.api.java.fr.Quand;
import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.htmlunit.HtmlUnitDriver;

import java.sql.Connection;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.logging.Level;
import java.util.logging.Logger;

import static org.junit.Assert.assertEquals;

public class DateAcceptance {

    private HtmlUnitDriver driver;
    private Connection con;

    private String urlPage = StaticConnection.urlPage;

    @Before
    public void setUp() throws SQLException {
        Logger logger = Logger.getLogger("");
        logger.setLevel(Level.OFF);

        driver = StaticConnection.getHtmlDriver();
        con = StaticConnection.getDatabaseConnector();

        driver.get(urlPage+"/src/PHP/login.php");
        driver.findElementByName("email").sendKeys("mailForTest@tests.fr");
        driver.findElementByName("password").sendKeys("Azerty1234!");
        driver.findElementByName("btnConnexion").click();

    }

    @After
    public void tearDown() throws SQLException {
        driver.quit();

      /*  String sql = "DELETE FROM Evenement WHERE Nom='AcceptanceEvenementTest'";
        Statement s = con.createStatement();
        s.executeUpdate(sql);**/

        con.close();
    }

    @Etantdonné("^L'utilisateur renseigne la date de cloture de l'événement$")
    public void lUtilisateurRenseigneLaDateDeClotureDeLÉvénement() {
        driver.get(urlPage+"/src/PHP/ajout_evenement.php");
    }

    @Quand("^la date de cloture est antérieure à la date du jour$")
    public void laDateDeClotureEstAntérieureÀLaDateDuJour() {
        {
            WebElement dropdown = driver.findElement(By.name("DateCloture_j"));
            dropdown.findElement(By.xpath("//option[. = '1']")).click();
        }

        {
            WebElement dropdown = driver.findElement(By.name("DateCloture_m"));
            dropdown.findElement(By.xpath("//option[. = 'Janvier']")).click();
        }

        {
            WebElement dropdown = driver.findElement(By.name("DateCloture_a"));
            dropdown.findElement(By.xpath("//option[. = '2020']")).click();
        }
        driver.findElementByName("btnValiderEvent").click();
    }

    @Alors("^un message d'erreur s'affiche$")
    public void unMessageDErreurSAffiche() {
        driver.findElementById("error_add_event");
    }

    @Etantdonné("^l'utilisateur renseigne la date de cloture$")
    public void lUtilisateurRenseigneLaDateDeCloture() {
       /* driver.get(urlPage+"/src/PHP/ajout_evenement.php");
        driver.findElementByName("NameEvent").sendKeys("AcceptanceEvenementTest");
        driver.findElementByName("LieuEvent").sendKeys("Acceptance");
        {
            WebElement dropdown = driver.findElement(By.name("DateEvent1_j"));
            dropdown.findElement(By.xpath("//option[. = '1']")).click();
        }

        {
            WebElement dropdown = driver.findElement(By.name("DateEvent1_m"));
            dropdown.findElement(By.xpath("//option[. = 'Janvier']")).click();
        }

        {
            WebElement dropdown = driver.findElement(By.name("DateEvent1_a"));
            dropdown.findElement(By.xpath("//option[. = '2021']")).click();
        }*/
    }

    @Quand("^la date cloture est suppérieur à la date du jour$")
    public void laDateClotureEstSuppérieurÀLaDateDuJour() {
       /* {
            WebElement dropdown = driver.findElement(By.name("DateCloture_j"));
            dropdown.findElement(By.xpath("//option[. = '30']")).click();
        }

        {
            WebElement dropdown = driver.findElement(By.name("DateCloture_m"));
            dropdown.findElement(By.xpath("//option[. = 'Décembre']")).click();
        }

        {
            WebElement dropdown = driver.findElement(By.name("DateCloture_a"));
            dropdown.findElement(By.xpath("//option[. = '2020']")).click();
        }
        driver.findElementByName("btnValiderEvent").click();*/
    }

    @Alors("^la date cloture est validé$")
    public void laDateClotureEstValidé() {
        //assertEquals(urlPage+"/src/PHP/evenement_ok.php", driver.getCurrentUrl());
       /* System.out.println(driver.getCurrentUrl());
        driver.findElementById("error_add_event");*/
    }
}
