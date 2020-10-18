package acceptance;

import cucumber.api.PendingException;
import cucumber.api.java.After;
import cucumber.api.java.Before;
import cucumber.api.java.fr.Alors;
import cucumber.api.java.fr.Et;
import cucumber.api.java.fr.Etantdonné;
import cucumber.api.java.fr.Quand;
import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.htmlunit.HtmlUnitDriver;

import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.logging.Level;
import java.util.logging.Logger;

import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertTrue;

public class ConnexionAcceptance {

    private HtmlUnitDriver driver;
    private Connection con;

    private String urlPage = StaticConnection.urlPage;

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

        con.close();
    }


    @Etantdonné("^l'utilisateur est sur la page de login$")
    public void lUtilisateurEstSurLaPageDeLogin() {
        driver.get(urlPage+"/src/PHP/login.php");
    }

    @Et("^l'utilisateur n'est pas connecté$")
    public void lUtilisateurNEstPasConnecté() {
        assertEquals(urlPage+"/src/PHP/login.php",driver.getCurrentUrl());
    }

    @Et("^il saisie le mot de passe valide$")
    public void lUtilisateurASaisieCommeMotDePasse(){
        driver.findElementByName("password").sendKeys("Azerty1234!");

    }
	@Et("^il demande de valider le login$")
    public void lUtilisateurClickSurLeBtnSeConnecter(){
        driver.findElementByName("btnConnexion").click();

    }
    /*@Alors("^la page renvoie sur l'index$")
    public void laPageRenvoieALIndex(){
         assertEquals(urlPage+"/index.php",driver.getCurrentUrl());
    }*/

    @Quand("^le mot de passe est \"([^\"]*)\"$")
    public void leMotDePasseEst(String arg0) throws Throwable {
        driver.findElementByName("password").sendKeys(arg0);
    }

    @Et("^l'utilisateur demande de valider le login$")
    public void lUtilisateurValideLeLogin(){
        driver.findElementByName("btnConnexion").click();

    }

    @Quand("^il saisie un nom d'utilisateur valide$")
    public void ilSaisieUnNomDUtilisateurValide() {
        driver.findElementByName("email").sendKeys("mailForTest@tests.fr");
    }


    @Alors("^un message d'erreur s'affiche sur la page login$")
    public void unMessageDErreurSAfficheSurLaPageLogin() {
        driver.findElement(By.className("erreur"));
    }
}
