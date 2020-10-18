package acceptance;

import acceptance.StaticConnection;
import cucumber.api.java.After;
import cucumber.api.java.Before;
import cucumber.api.java.fr.Alors;
import cucumber.api.java.fr.Etantdonné;
import cucumber.api.java.fr.Quand;
import org.openqa.selenium.By;
import org.openqa.selenium.htmlunit.HtmlUnitDriver;

import java.sql.*;
import java.util.logging.Level;
import java.util.logging.Logger;

import static org.junit.Assert.assertEquals;

public class FiltrerParDateRecente {
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

    @Quand("^l'utilisateur choisie le filtre par date de la plus récente à la plus ancienne$")
    public void lUtilisateurChoisieLeFiltreParDateDeLaPlusRécenteÀLaPlusAncienne() {
        driver.get(urlPage + "/src/PHP/voir_etat_vote.php?event="+pastEvent);
        driver.findElementByClassName("btn-group").findElement(By.className("btn btn-secondary")).click();
    }

    @Alors("^les résultats affiché sont dans triés par date de la plus récente à la plus ancienne$")
    public void lesRésultatsAffichéSontDansTriésParDateDeLaPlusRécenteÀLaPlusAncienne() {
        //driver.findElementByName("success");
        assertEquals(driver.getCurrentUrl(), urlPage + "/src/PHP/voir_etat_vote.php?IDEvent=&order=date&dir=asc");
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
