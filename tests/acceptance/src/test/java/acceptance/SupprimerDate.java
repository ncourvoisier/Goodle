package acceptance;

import cucumber.api.java.After;
import cucumber.api.java.Before;
import cucumber.api.java.fr.Alors;
import cucumber.api.java.fr.Etantdonné;
import cucumber.api.java.fr.Quand;
import org.openqa.selenium.htmlunit.HtmlUnitDriver;

import java.sql.*;
import java.util.logging.Level;
import java.util.logging.Logger;

import static org.junit.Assert.assertEquals;

public class SupprimerDate {
    private HtmlUnitDriver driver;
    private Connection con;
    private String urlPage = StaticConnection.localConnection;
    private int pastEvent;
    private int pastDateEvent;
    private int pastDate;

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

        sql = "INSERT INTO DateEvenement(ID, IDEevent, IDDate) VALUES ('0', '0', '0');";
        s = con.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS);

        s.executeUpdate(sql, Statement.RETURN_GENERATED_KEYS);
        generated = s.getGeneratedKeys();
        if (generated.next()) {
            pastDateEvent = generated.getInt(1);
        } else {
            throw new SQLException("Did not create event");
        }

        sql = "INSERT INTO Date(ID, Jour, Mois, Annee, Heure, Minute) VALUES ('0', '1', '12', '2020', '14', '0');";
        s = con.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS);

        s.executeUpdate(sql, Statement.RETURN_GENERATED_KEYS);
        generated = s.getGeneratedKeys();
        if (generated.next()) {
            pastDate = generated.getInt(1);
        } else {
            throw new SQLException("Did not create event");
        }

        driver.get(urlPage + "/src/PHP/login.php");
        driver.findElementByName("email").sendKeys("mailForTests@tests.fr");
        driver.findElementByName("password").sendKeys("Azerty1234!");
        driver.findElementByName("btnConnexion").click();
    }

    @Etantdonné("^l'utilisateur edite un evenement crée$")
    public void lutilisateurEditeUnEvenementCree() {
        driver.get(urlPage + "/src/PHP/voir_event.php?event="+pastEvent);
    }

    @Quand("^l'utilisateur supprime une proposition de date$")
    public void lUtilisateurSupprimeUnePropositionDeDate() {
        assertEquals(driver.getCurrentUrl(), urlPage + "/src/PHP/supprimer_date_event.php?dateEvent="+pastDate+"&event="+pastEvent);
        driver.findElementByName("btnValiderSupprDate");
    }

    @Alors("^la date supprimé n'apparait plus$")
    public void laDateSupprimeNApparaitPlus() {
        assertEquals(driver.getCurrentUrl(), urlPage + "/src/PHP/supprimer_date_event.php?dateEvent="+pastDate+"&event="+pastEvent);
    }

    @After
    public void tearDown() throws SQLException {
        String sql = "DELETE FROM Date WHERE ID = " + pastDate + ";";
        Statement s = con.createStatement();
        s.executeUpdate(sql);

        sql = "DELETE FROM DateEvenement WHERE ID = " + pastDateEvent + ";";
        s = con.createStatement();
        s.executeUpdate(sql);

        sql = "DELETE FROM Evenement WHERE ID = " + pastEvent + ";";
        s = con.createStatement();
        s.executeUpdate(sql);

        driver.quit();
        con.close();
    }
}
