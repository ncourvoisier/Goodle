package acceptance;

import acceptance.LienInvitationAcceptance;

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

public class DeconnexionAcceptance {
    private HtmlUnitDriver driver;
    private Connection con;
    private String urlPage = StaticConnection.urlPage;

    @Before
    public void setUp() throws SQLException {
        Logger logger = Logger.getLogger("");
        logger.setLevel(Level.OFF);

        driver = StaticConnection.getHtmlDriver();
        con = StaticConnection.getDatabaseConnector();

        driver.get(urlPage+"/src/PHP/login.php");
        driver.findElementByName("email").sendKeys("mailForTest@tests.fr");
        driver.findElementByName("password").sendKeys("Azerty1234!");
        driver.findElementByName("btnConnexion").click();

    }

    @After
    public void tearDown() throws SQLException {
        driver.quit();

        con.close();
    }

    /*@Etantdonné("^l'utilisateur est connecté$")
    public void lUtilisateurEstConnecté() {
        driver.get(urlPage+"/src/PHP/login.php");
        driver.findElementByName("email").sendKeys("mailForTest@tests.fr");
        driver.findElementByName("password").sendKeys("Azerty1234!");
        driver.findElementByName("btnConnexion").click();
    }*/

    @Quand("^l'utilisateur demande de se deconnecté$")
        public void lUtilisateurDemandeDeSeDeconnecte(){
            //driver.get(urlPage+"/index.php");
            //assertEquals(driver.getCurrentUrl(),"3");
            driver.findElement(By.id("btnDeconnection")).click();
        }



    @Alors("^la page renvoie sur l'index$")
    public void laPageRenvoieALIndex(){
        assertEquals(urlPage+"/index.php",driver.getCurrentUrl());
    }




}
