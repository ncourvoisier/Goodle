package acceptance;

import cucumber.api.PendingException;
import cucumber.api.java.After;
import cucumber.api.java.Before;
import cucumber.api.java.fr.Alors;
import cucumber.api.java.fr.Etantdonné;
import cucumber.api.java.fr.Quand;
import org.junit.AfterClass;
import org.openqa.selenium.htmlunit.HtmlUnitDriver;

import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.sql.*;
import java.util.Iterator;
import java.util.logging.Level;
import java.util.logging.Logger;

import static javax.swing.UIManager.getInt;
import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertNotNull;
import static org.junit.Assert.assertTrue;

public class ClotureAcceptance {
    private HtmlUnitDriver driver;
    private Connection con;

    private String urlPage = StaticConnection.urlPage;

    private int pastEvent;

    private int dateId;

    @Before
    public void setUp() throws SQLException {
        int pastEventCreator;
        Logger logger = Logger.getLogger("");
        logger.setLevel(Level.OFF);

        driver = StaticConnection.getHtmlDriver();
        con = StaticConnection.getDatabaseConnector();

        String getEventCreatorSql = "SELECT ID FROM Personne WHERE Email = 'mailForTest@tests.fr';";
        PreparedStatement creatorStatement = con.prepareStatement(getEventCreatorSql);

        ResultSet creator = creatorStatement.executeQuery();

        if (creator.next()) {
            pastEventCreator = creator.getInt("ID");
        } else {
            throw new SQLException("No creator");
        }

        String insertEventSql = "INSERT INTO Evenement(Nom, Lieu, Referent, DateCloture) VALUES ('Evenement', 'Lieu', '" +  pastEventCreator +"', '2020-01-01 00:00');";
        PreparedStatement s = con.prepareStatement(insertEventSql, Statement.RETURN_GENERATED_KEYS);

        s.executeUpdate();
        ResultSet generated = s.getGeneratedKeys();
        if (generated.next()) {
            pastEvent = generated.getInt(1);
        } else {
            throw new SQLException("Did not create event");
        }

        String insertDateSql = "INSERT INTO Date(Jour, Mois, Annee, Heure, Minute) VALUES ('1', '1', '2030', '18', '0')";
        PreparedStatement dateSql = con.prepareStatement(insertDateSql, Statement.RETURN_GENERATED_KEYS);

        dateSql.executeUpdate();
        generated = dateSql.getGeneratedKeys();
        if (generated.next()) {
            dateId = generated.getInt(1);
        } else {
            throw new SQLException("Did not create date");
        }

        String insertDateEventSql = "INSERT INTO DateEvenement(IDDate, IDEvent) VALUES ('" + dateId + "', '" + pastEvent + "');";
        PreparedStatement dateEventSql = con.prepareStatement(insertDateEventSql);

        dateEventSql.executeUpdate();

        driver.get(urlPage + "/src/PHP/login.php");
        driver.findElementByName("email").sendKeys("mailForTest@tests.fr");
        driver.findElementByName("password").sendKeys("Azerty1234!");
        driver.findElementByName("btnConnexion").click();

    }

    @After
    public void tearDown() throws SQLException {

        Statement s = con.createStatement();
        String sql = "DELETE FROM DateEvenement WHERE IDEvent = '" + pastEvent + "';";
        s.executeUpdate(sql);
        sql = "DELETE FROM Evenement WHERE ID = '" + pastEvent + "';";
        s.executeUpdate(sql);
        sql = "DELETE FROM Date WHERE ID = '" + dateId + "';";
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
        assertNotNull(driver.findElementById("error_message_too_late"));
    }

    @Etantdonné("^le créateur se rend sur la page de l'événement$")
    public void leCréateurSeRendSurLaPageDeLÉvénement() throws Throwable {
        driver.get(urlPage  + "/src/PHP/voir_event.php?event=" + pastEvent);
    }

    @Alors("^le créateur peut encore supprimé l'évenement$")
    public void leCréateurPeutEncoreSupprimeLÉvenement() throws Throwable {
        assertNotNull(driver.findElementByName("btnSupprimerEventUtilisateur"));
    }

    @Alors("^le créateur ne peut plus modifier l'évenement$")
    public void leCréateurNePeutPlusModifierLÉvenement() throws Throwable {
        driver.get(urlPage + "/src/PHP/modif_evenement.php?event=" + pastEvent);
        assertNotNull(driver.findElementById("error_message_too_late"));
    }
}
