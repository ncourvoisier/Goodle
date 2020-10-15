package acceptance;

import cucumber.api.PendingException;
import cucumber.api.java.After;
import cucumber.api.java.Before;
import cucumber.api.java.fr.Alors;
import cucumber.api.java.fr.Etantdonné;
import cucumber.api.java.fr.Quand;
import org.openqa.selenium.htmlunit.HtmlUnitDriver;

import java.sql.*;
import java.util.Iterator;
import java.util.logging.Level;
import java.util.logging.Logger;

import static javax.swing.UIManager.getInt;

public class ClotureAcceptance {
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

    @After
    public void tearDown() throws SQLException {
        String sql = "DELETE FROM Evenement WHERE ID = " + pastEvent + ";";
        Statement s = con.createStatement();
        s.executeUpdate(sql);

        driver.quit();

        con.close();
    }

    @Etantdonné("^le participant se rend sur la page de l'événement$")
    public void leParticipantSeRendSurLaPageDeLÉvénement() throws Throwable {
        driver.get(urlPage + "/src/PHP/repondre_invite.php?IDEvent=" + pastEvent);
    }

    @Quand("^la date de l'évenement choisit est dépassé$")
    public void laDateDeLÉvenementChoisitEstDépassé() throws Throwable {
    }

    @Alors("^le participant ne peut plus modifier les votes$")
    public void leParticipantNePeutPlusModifierLesVotes() throws Throwable {
        assert(driver.findElementById("error_message_too_late") != null);
    }
}
