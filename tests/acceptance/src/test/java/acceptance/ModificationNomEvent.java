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
import static org.junit.Assert.assertTrue;

public class ModificationNomEvent {

    private HtmlUnitDriver driver;
    private Connection con;
    private String urlPage = StaticConnection.urlPage;

    private String nvNomEvent = "Soiree";
    private int pastEvent;

    @Before
    public void setUp() throws SQLException {
        Logger logger = Logger.getLogger("");
        logger.setLevel(Level.OFF);

        driver = StaticConnection.getHtmlDriver();
        con = StaticConnection.getDatabaseConnector();

        String getEventCreatorSql = "SELECT ID FROM Personne WHERE Email = 'mailForTest@tests.fr';";
        PreparedStatement creatorStatement = con.prepareStatement(getEventCreatorSql);

        ResultSet creator = creatorStatement.executeQuery();

        int pastEventCreator;
        if (creator.next()) {
            pastEventCreator = creator.getInt("ID");
            System.out.println("ID USER "  + pastEventCreator);
        } else {
            throw new SQLException("No creator");
        }

        String sql = "INSERT INTO Evenement(ID, Nom, Lieu, Referent, DateCloture) VALUES ('0', 'Evenement', 'Lieu', '" + pastEventCreator + "', '2020-01-01 00:00');";
        PreparedStatement s = con.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS);

        s.executeUpdate(sql, Statement.RETURN_GENERATED_KEYS);
        ResultSet generated = s.getGeneratedKeys();
        if (generated.next()) {
            pastEvent = generated.getInt(1);
        } else {
            throw new SQLException("Did not create event");
        }

        driver.get(urlPage + "/src/PHP/login.php");
        driver.findElementByName("email").sendKeys("mailForTest@tests.fr");
        driver.findElementByName("password").sendKeys("Azerty1234!");
        driver.findElementByName("btnConnexion").click();
    }

    @Etantdonné("^l'utilisateur modifie le nom de l'événement$")
    public void lutilisateurModifieLeNomDeLEvenement() {
        System.out.println(driver.getCurrentUrl());
        driver.get(urlPage + "/src/PHP/voir_event.php?event="+pastEvent);
        System.out.println(driver.getCurrentUrl());
        //driver.findElementByName("btnModifier").click();
        driver.get(urlPage + "/src/PHP/modif_evenement.php?event="+pastEvent);
        System.out.println(driver.getCurrentUrl());
        driver.findElementByName("NameEvent").sendKeys(nvNomEvent);
    }

    @Quand("^le nouveau nom contient au moins 3 caractères$")
    public void leNouveauNomContientAuMoins3Caracteres() {
        assertTrue(nvNomEvent.length() >= 3);
    }

    @Alors("^le nouveau nom est validé$")
    public void leNouveauNomEstValide() {
        assertEquals(driver.getCurrentUrl(), urlPage + "/src/PHP/evenement_ok.php");
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
