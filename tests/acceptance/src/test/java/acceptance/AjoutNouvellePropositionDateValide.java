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
import java.util.Calendar;
import java.util.logging.Level;
import java.util.logging.Logger;

import static org.junit.Assert.assertEquals;

public class AjoutNouvellePropositionDateValide {

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

    @Etantdonné("^l'utilisateur ajoute une proposition de date et heure$")
    public void lUtilisateurAjouteUnePropositionDeDateEtHeure() {
        driver.get(urlPage+"/src/PHP/ajouter_date_evenement.php?event=8");
    }

    @Quand("^la date et l'heure sont suppérieure à la date actuelle$")
    public void laDateEtLHeureSontSuppérieureÀLaDateActuelle() {
        //Calendar calendar = Calendar.getInstance();
        //if ( calendar.get( Calendar.MONTH ) == Calendar.JANUARY ) {
        //    System.out.println("la date courante est en janvier");
        //}
        {
            WebElement dropdown = driver.findElement(By.name("DateEvent_j"));
            dropdown.findElement(By.xpath("//option[. = '3']")).click();
        }

        {
            WebElement dropdown = driver.findElement(By.name("DateEvent_m"));
            dropdown.findElement(By.xpath("//option[. = 'Novembre']")).click();
        }

        {
            WebElement dropdown = driver.findElement(By.name("DateEvent_a"));
            dropdown.findElement(By.xpath("//option[. = '2020']")).click();
        }
    }

    @Et("^la date et l'heure ne sont pas déjà renseigné$")
    public void laDateEtLHeureNeSontPasDéjàRenseigné() {
        //Ajouter msg date ajoute ou erreur
        //driver.findElementById("error_inscription");
        assert(true);
    }

    @Alors("^la date et l'heure sont ajouté à l'événement$")
    public void laDateEtLHeureSontAjoutéÀLÉvénement() {
        assertEquals(driver.getCurrentUrl(),urlPage+"/src/PHP/voir_event.php?event=8");
    }

    @After
    public void tearDown() throws SQLException {
        driver.quit();
        con.close();
    }
}
