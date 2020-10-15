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

import java.sql.*;
import java.util.Calendar;
import java.util.logging.Level;
import java.util.logging.Logger;

import static org.junit.Assert.assertEquals;

public class AjoutNouvellePropositionDateValide {

    private HtmlUnitDriver driver;
    private Connection con;
    private String urlPage = StaticConnection.localConnection;
    private int pastEvent;

    @Before
    public void setUp() throws SQLException {
        Logger logger = Logger.getLogger("");
        logger.setLevel(Level.OFF);

        driver = StaticConnection.getHtmlDriver();
        con = StaticConnection.getDatabaseConnector();

        String sql = "INSERT INTO Evenement(ID, Nom, Lieu, Referent, DateCloture) VALUES ('0', 'Evenement', 'Lieu', '1', '2020-01-01 00:00');";
        PreparedStatement s = con.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS);

        s.executeUpdate(sql, Statement.RETURN_GENERATED_KEYS);
        ResultSet generated = s.getGeneratedKeys();
        if (generated.next()) {
            pastEvent = generated.getInt(1);
        } else {
            throw new SQLException("Did not create event");
        }

        driver.get(urlPage + "/src/PHP/login.php");
        driver.findElementByName("email").sendKeys("mailForTests@tests.fr");
        driver.findElementByName("password").sendKeys("Azerty1234!");
        driver.findElementByName("btnConnexion").click();
    }

    @Etantdonné("^l'utilisateur ajoute une proposition de date et heure$")
    public void lUtilisateurAjouteUnePropositionDeDateEtHeure() {
        driver.get(urlPage+"/src/PHP/ajouter_date_evenement.php?event="+pastEvent);
    }

    @Quand("^la date et l'heure sont suppérieure à la date actuelle$")
    public void laDateEtLHeureSontSuppérieureÀLaDateActuelle() {
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

    }

    @Alors("^la date et l'heure sont ajouté à l'événement$")
    public void laDateEtLHeureSontAjoutéÀLÉvénement() {
        assertEquals(driver.getCurrentUrl(),urlPage+"/src/PHP/voir_event.php?event="+pastEvent);
    }

    @After
    public void tearDown() throws SQLException {
        String sql = "DELETE FROM Evenement WHERE ID = " + pastEvent + ";";
        Statement s = con.createStatement();
        s.executeUpdate(sql);
        driver.quit();
        con.close();
    }
}
