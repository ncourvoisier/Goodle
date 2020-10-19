package acceptance;

import cucumber.api.java.After;
import cucumber.api.java.Before;
import cucumber.api.java.fr.Alors;
import cucumber.api.java.fr.Etantdonné;
import cucumber.api.java.fr.Quand;
import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.htmlunit.HtmlUnitDriver;
import org.openqa.selenium.support.ui.Select;

import java.sql.*;
import java.util.List;
import java.util.logging.Level;
import java.util.logging.Logger;

import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertTrue;

public class AjoutEvenementAcceptance {

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

        driver.get(urlPage + "/src/PHP/login.php");
        driver.findElementByName("email").sendKeys("mailForTests@tests.fr");
        driver.findElementByName("password").sendKeys("Azerty1234!");
        driver.findElementByName("btnConnexion").click();

        driver.get(urlPage + "/src/PHP/ajout_evenement.php");

        {
            Select dropdown = new Select(driver.findElement(By.name("DateEvent1_j")));
            dropdown.selectByVisibleText("3");
            //System.out.println(dropdown.findElement(By.xpath("//option[. = '3']")));
        }
        {
            Select dropdown = new Select(driver.findElement(By.name("DateEvent1_m")));
            dropdown.selectByVisibleText("Décembre");
        }
        {
            Select dropdown = new Select(driver.findElement(By.name("DateEvent1_a")));
            dropdown.selectByVisibleText("2021");
        }
        {
            Select dropdown = new Select(driver.findElement(By.name("DateCloture_j")));
            dropdown.selectByVisibleText("1");

        }
        {
            Select dropdown = new Select(driver.findElement(By.name("DateCloture_m")));
            dropdown.selectByVisibleText("Décembre");
        }
        {
            Select dropdown = new Select(driver.findElement(By.name("DateCloture_a")));
            dropdown.selectByVisibleText("2021");

        }

    }

    @After
    public void tearDown() throws SQLException {
        String sql = "DELETE FROM Evenement WHERE Nom = \" SoireeTest \";";
        Statement s = con.createStatement();
        s.executeUpdate(sql);
        driver.quit();
        con.close();
    }

    @Etantdonné("^l'utilisateur ajoute le lieu$")
    public void lUtilisateurAjouteLeLieu() {
        driver.findElementByName("LieuEvent").sendKeys("Besancon");

    }

    @Alors("^le lieu est validé dans l'ajout d'événement$")
    public void leLieuEstValidéDansLAjoutDÉvénement() {
        driver.findElementByName("btnValiderEvent").click();
        System.out.println(driver.findElement(By.id("error_add_event")).getText());
    }

    /*@Alors("^le lieu est validé$")
    public void leLieuEstValide() {
        assertEquals(driver.getCurrentUrl(), urlPage + "/src/PHP/evenement_ok.php");
    }*/



    @Etantdonné("^l'utilisateur ajoute le nom de l'événement$")
    public void lUtilisateurAjouteLeNomDeLÉvénement() {
        driver.findElementByName("NameEvent").sendKeys("SoireeTesttest");
    }

    @Quand("^le nom contient au moins (\\d+) caractères$")
    public void leNomContientAuMoinsCaractères(int arg0) {
        assertTrue(nvNomEvent.length() >= arg0);
        lUtilisateurAjouteLeLieu();
    }

    @Alors("^le nom est validé$")
    public void leNomEstValidé() throws SQLException {
        driver.findElementByName("LieuEvent").sendKeys("Besancon");
        driver.findElementByName("btnValiderEvent").click();
        //System.out.println(driver.findElement(By.id("error_add_event")).getText());
        String sql="SELECT Max(ID) FROM Evenement";
        PreparedStatement creatorStatement = con.prepareStatement(sql);

        ResultSet creator = creatorStatement.executeQuery();
        int pastEventCreator=0;
        if (creator.next()) {
            pastEventCreator = creator.getInt("Max(ID)");
        }
        pastEvent=pastEventCreator;
        assertEquals(driver.getCurrentUrl(), urlPage + "/src/PHP/evenement_ok.php?event="+pastEvent);
    }


    @Alors("^le lieu est valide$")
    public void leLieuEstValide() throws SQLException {
        driver.findElementByName("NameEvent").sendKeys("SoireeTest");
        driver.findElementByName("btnValiderEvent").click();

        String sql="SELECT Max(ID) FROM Evenement";
        PreparedStatement creatorStatement = con.prepareStatement(sql);

        ResultSet creator = creatorStatement.executeQuery();
        int pastEventCreator=0;
        if (creator.next()) {
            pastEventCreator = creator.getInt("Max(ID)");
        }
        pastEvent=pastEventCreator;

        assertEquals(driver.getCurrentUrl(), urlPage + "/src/PHP/evenement_ok.php?event="+pastEvent);
    }
}

