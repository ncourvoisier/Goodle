package acceptance;

import cucumber.api.PendingException;
import cucumber.api.java.After;
import cucumber.api.java.Before;
import cucumber.api.java.fr.Alors;
import cucumber.api.java.fr.Et;
import cucumber.api.java.fr.Quand;
import org.openqa.selenium.htmlunit.HtmlUnitDriver;

import java.sql.*;
import java.util.logging.Level;
import java.util.logging.Logger;

import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertTrue;

public class AdminDeleteEvent {
    private HtmlUnitDriver driver;
    private Connection con;
    private String urlPage = StaticConnection.urlPage;
    private int pastEvent;

    @Before
    public void setUp() throws SQLException {
        Logger logger = Logger.getLogger("");
        logger.setLevel(Level.OFF);

        driver = StaticConnection.getHtmlDriver();
        con = StaticConnection.getDatabaseConnector();

        String sql = "INSERT INTO Evenement( Nom, Lieu, Referent, DateCloture) VALUES ('Evenement', 'Lieu', '1', '2020-01-01 00:00');";
        PreparedStatement s = con.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS);

        s.executeUpdate(sql, Statement.RETURN_GENERATED_KEYS);
        ResultSet generated = s.getGeneratedKeys();
        if (generated.next()) {
            pastEvent = generated.getInt(1);
        } else {
            throw new SQLException("Did not create event");
        }


    }

    @Quand("^l'administrateur est sur la page d'un événement$")
    public void lAdministrateurEstSurLaPageDUnÉvénement() {
        driver.get(urlPage + "/src/PHP/login.php");
        driver.findElementByName("email").sendKeys("mailForTests@tests.fr");
        driver.findElementByName("password").sendKeys("Azerty1234!");
        driver.findElementByName("btnConnexion").click();
        driver.get(urlPage + "/src/PHP/voir_event.php?event="+pastEvent);
    }

    @Et("^l'événement est supprimé$")
    public void lÉvénementEstSupprimé() throws SQLException {
        String sql = "SELECT * FROM Evenement WHERE ID =" + pastEvent + ";";
        PreparedStatement s = con.prepareStatement(sql);
        ResultSet evenement = s.executeQuery();
        if(evenement.next())
        {
            assertTrue(false);
        }
        else
        {
            assertTrue(true);
        }
    }

    @After
    public void tearDown() throws SQLException {
        String sql = "DELETE FROM Evenement WHERE ID = " + pastEvent + ";";
        Statement s = con.createStatement();
        s.executeUpdate(sql);
        driver.quit();
        con.close();
    }


    @Et("^l'adiministrateur clique sur le bouton \"([^\"]*)\"$")
    public void lAdiministrateurCliqueSurLeBouton(String arg0) throws Throwable {
        // Write code here that turns the phrase above into concrete actions
        driver.findElementByName("btnSupprimerEventUtilisateur").click();
        //throw new PendingException();
    }

    @Alors("^le message apparait \"([^\"]*)\"$")
    public void leMessageApparait(String arg0) throws Throwable {
        // Write code here that turns the phrase above into concrete actions
        driver.findElementByName("suppEvent").toString().equals("L'évènement a bien été supprimé");
    }
}
