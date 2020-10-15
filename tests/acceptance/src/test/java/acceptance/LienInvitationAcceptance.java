package acceptance;

import cucumber.api.java.After;
import cucumber.api.java.Before;
import cucumber.api.java.fr.Alors;
import cucumber.api.java.fr.Etantdonné;
import cucumber.api.java.fr.Quand;
import org.openqa.selenium.htmlunit.HtmlUnitDriver;

import java.sql.Connection;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.logging.Level;
import java.util.logging.Logger;

import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertTrue;

public class LienInvitationAcceptance {

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

    @After
    public void tearDown() throws SQLException {
        driver.quit();

        String sql = "DELETE FROM Evenement WHERE Nom = 'AcceptanceTestEvenement';";
        Statement s = con.createStatement();
        s.executeUpdate(sql);

        con.close();
    }

    @Etantdonné("^l'utilisateur est deconnecté$")
    public void lUtilisateurEstDeconnecté() {
        driver.get(urlPage+"/src/PHP/deconnexion.php");
    }

    @Quand("^l'utilisateur veut rejoindre un lien$")
    public void lUtilisateurVeutRejoindreUnLien() {
        driver.get(urlPage+"/src/PHP/voir_event.php?event=1");
    }

    @Alors("^la page renvoie un message d'erreur connexion$")
    public void laPageRenvoieUnMessageDerreurConnexion() {
        driver.findElementById("error_connexion_ve");
    }

    @Etantdonné("^l'utilisateur est connecté$")
    public void lUtilisateurEstConnecté() {
        driver.get(urlPage+"/src/PHP/login.php");
        driver.findElementByName("email").sendKeys("mailForTest@tests.fr");
        driver.findElementByName("password").sendKeys("Azerty1234!");
        driver.findElementByName("btnConnexion").click();
    }

    @Alors("^l'utilisateur est redirigé sur la page de l'evenement correspondant au lien$")
    public void lUtilisateurEstRedirigéSurLaPageDeLEvenementCorrespondantAuLien() {
        assertEquals(urlPage+"/src/PHP/voir_event.php?event=1", driver.getCurrentUrl());
    }
}
