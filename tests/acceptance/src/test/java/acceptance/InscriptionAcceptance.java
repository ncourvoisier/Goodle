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
import java.sql.SQLException;
import java.util.logging.Level;
import java.util.logging.Logger;

import static org.junit.Assert.assertEquals;

public class InscriptionAcceptance {

    private HtmlUnitDriver driver;
    private Connection con;

    private String urlPage = StaticConnection.localConnection;

    @Before
    public void setUp() throws SQLException {
        Logger logger = Logger.getLogger("");
        logger.setLevel(Level.OFF);

        System.out.println("1");

        driver = StaticConnection.getHtmlDriver();
        con = StaticConnection.getDatabaseConnector();

        System.out.println("2");
    }

    @Etantdonné("^l'utilisateur est sur le formulaire d'inscription$")
    public void lUtilisateurEstSurLeFormulaireDInscription() {
        System.out.println("3");
        driver.get(urlPage+"/src/PHP/inscription.php");
    }

    @Quand("^l'utilisateur a saisie comme nom de compte \"([^\"]*)\"$")
    public void lUtilisateurASaisieCommeNomDeCompte(String arg0) throws Throwable {
        driver.findElementByName("username").sendKeys("mokos");

    }

    @Et("^l'utilisateur a saisie comme mot de passe \"([^\"]*)\"$")
    public void lUtilisateurASaisieCommeMotDePasse(String arg0) throws Throwable {
        driver.findElementByName("pass1").sendKeys("jean");

    }

    @Et("^l'utilisateur a saisie comme date de naissance \"([^\"]*)\"$")
    public void lUtilisateurASaisieCommeDateDeNaissance(String arg0) throws Throwable {
        {
            WebElement dropdown = driver.findElement(By.name("naiss_j"));
            dropdown.findElement(By.xpath("//option[. = '1']")).click();
        }

        {
            WebElement dropdown = driver.findElement(By.name("naiss_m"));
            dropdown.findElement(By.xpath("//option[. = 'Février']")).click();
        }

        {
            WebElement dropdown = driver.findElement(By.name("naiss_a"));
            dropdown.findElement(By.xpath("//option[. = '1998']")).click();
        }
    }

    @Et("^l'utilisateur a saisie comme mdp \"([^\"]*)\"$")
    public void lUtilisateurASaisieCommeMdp(String arg0) throws Throwable {
        driver.findElementByName("pass2").sendKeys("Jean2012**");

    }

    @Et("^l'utilisateur a saisie comme mail cddd@gmail\\.com$")
    public void lUtilisateurASaisieCommeMailCdddGmailCom() {
        driver.findElementByName("email").sendKeys("cddd@gmail.com!");
    }

    @Et("^l'utilisateur demande de valider l'inscription$")
    public void lUtilisateurDemandeDeValiderLInscription() {
        driver.findElementByName("btnSInscrire").click();
    }

    @Alors("^la page renvoie une erreur \"inscription non validée\"")
    public void laPageRenvoieUneErreur() throws Throwable {
        driver.findElementById("error_inscription");
    }

    @Et("^l'utilisateur retourne sur la page d'inscription$")
    public void lUtilisateurRetourneSurLaPageDInscription() {
        assertEquals(driver.getCurrentUrl(),urlPage+"/src/PHP/inscription.php");
    }

    @After
    public void tearDown() throws SQLException {
        driver.quit();
        con.close();
    }
}
