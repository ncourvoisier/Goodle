package acceptance;

import cucumber.api.java.After;
import cucumber.api.java.Before;
import cucumber.api.java.fr.Alors;
import cucumber.api.java.fr.Et;
import cucumber.api.java.fr.Etantdonné;
import cucumber.api.java.fr.Quand;
import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.htmlunit.HtmlUnitDriver;
import org.openqa.selenium.support.ui.Select;

import java.sql.*;
import java.util.logging.Level;
import java.util.logging.Logger;

import static org.junit.Assert.assertEquals;

public class AjoutDateAnterieur {

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

        String getEventCreatorSql = "SELECT ID FROM Personne WHERE Email = 'mailForTest@tests.fr';";
        PreparedStatement creatorStatement = con.prepareStatement(getEventCreatorSql);

        ResultSet creator = creatorStatement.executeQuery();

        int pastEventCreator;
        if (creator.next()) {
            pastEventCreator = creator.getInt("ID");
        } else {
            throw new SQLException("No creator");
        }

        String sql = "INSERT INTO Evenement(ID, Nom, Lieu, Referent, DateCloture) VALUES ('0', 'Evenement', 'Lieu', '"+ pastEventCreator +"', '2021-01-01 00:00');";
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

    @Etantdonné("^l'utilisateur ajoute une proposition de date et heure antérieure$")
    public void lUtilisateurAjouteUnePropositionDeDateEtHeureAntérieure() {
        driver.get(urlPage + "/src/PHP/ajouter_date_evenement.php?event=" + pastEvent);
    }


    @Quand("^la date et l'heure sont antérieur à la date du jour$")
    public void laDateEtLHeureSontAnterieurÀLaDateActuelle() {
        {
            Select dropdown = new Select(driver.findElement(By.name("DateEvent_j")));
            dropdown.selectByVisibleText("3");
        }
        {
            Select dropdown = new Select(driver.findElement(By.name("DateEvent_m")));
            dropdown.selectByVisibleText("Janvier");
        }
        {
            Select dropdown = new Select(driver.findElement(By.name("DateEvent_a")));
            dropdown.selectByVisibleText("2020");
        }
    }

    @Alors("^une message d'erreur s'affiche dans l'ajout de date$")
    public void uneMessageDErreurSAfficheDansLaAjoutDEvenement() {

        driver.findElementByName("btnValiderAjoutDate").click();
        driver.findElement(By.className("erreur"));
        assertEquals(driver.getCurrentUrl(), urlPage + "/src/PHP/ajouter_date_evenement.php?event=" + pastEvent);

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