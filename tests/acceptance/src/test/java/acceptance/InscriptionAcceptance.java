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

public class InscriptionAcceptance {

    private HtmlUnitDriver driver;
    private Connection con;

    private String urlPage = StaticConnection.localConnection;

    private String getMois(String moisNB){
        switch (moisNB){
            case "01":
                return "Janvier";
            case "02":
                return "Février";
            case "03":
                return "Mars";
            case "04":
                return "Avril";
            case "05":
                return "Mai";
            case "06":
                return "Juin";
            case "07":
                return "Juillet";
            case "08":
                return "Août";
            case "09":
                return "Septembre";
            case "10":
                return "Octobre";
            case "11":
                return "Novembre";
            case "12":
                return "Décembre";
        }
        return null;
    }

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

        String sql = "DELETE FROM Personne WHERE Email = 'acceptance@gmail.com';";
        Statement s = con.createStatement();
        s.executeUpdate(sql);

        con.close();
    }

    @Etantdonné("^l'utilisateur est sur le formulaire d'inscription$")
    public void lUtilisateurEstSurLeFormulaireDInscription() {
        driver.get(urlPage+"/src/PHP/inscription.php");
    }

    @Quand("^l'utilisateur a saisie comme nom de compte \"([^\"]*)\"$")
    public void lUtilisateurASaisieCommeNomDeCompte(String arg0) throws Throwable {
        driver.findElementByName("username").sendKeys(arg0);

    }

    @Et("^l'utilisateur a saisie comme mot de passe 1 \"([^\"]*)\"$")
    public void lUtilisateurASaisieCommeMotDePasse(String arg0) throws Throwable {
        driver.findElementByName("pass1").sendKeys(arg0);

    }

    @Et("^l'utilisateur a saisie comme date de naissance \"([^\"]*)\"$")
    public void lUtilisateurASaisieCommeDateDeNaissance(String arg0) throws Throwable {
        String[] date = arg0.split("/");

        {
            WebElement dropdown = driver.findElement(By.name("naiss_j"));
            dropdown.findElement(By.xpath("//option[. = '"+date[0]+"']")).click();
        }

        {
            String mois = getMois(date[1]);
            WebElement dropdown = driver.findElement(By.name("naiss_m"));
            dropdown.findElement(By.xpath("//option[. = '"+mois+"']")).click();
        }

        {
            WebElement dropdown = driver.findElement(By.name("naiss_a"));
            dropdown.findElement(By.xpath("//option[. = '"+date[2]+"']")).click();
        }
    }

    @Et("^l'utilisateur a saisie comme mdp confirmation \"([^\"]*)\"$")
    public void lUtilisateurASaisieCommeMdp(String arg0) throws Throwable {
        driver.findElementByName("pass2").sendKeys(arg0);

    }

    @Et("^l'utilisateur a saisie comme mail \"([^\"]*)\"$")
    public void lUtilisateurASaisieCommeMailCdddGmailCom(String arg0) {
        driver.findElementByName("email").sendKeys(arg0);
    }

    @Et("^l'utilisateur demande de valider l'inscription$")
    public void lUtilisateurDemandeDeValiderLInscription() {
        driver.findElementByName("btnSInscrire").click();
    }

    @Alors("^la page renvoie un message d'erreur$")
    public void laPageRenvoieUneErreur() throws Throwable {
        driver.findElementById("error_inscription");
    }

    @Et("^l'utilisateur retourne sur la page d'inscription$")
    public void lUtilisateurRetourneSurLaPageDInscription() {
        assertEquals(urlPage+"/src/PHP/inscription.php",driver.getCurrentUrl());
    }

    @Et("^l'email \"([^\"]*)\" est déjà utilisé pour un autre utilisateur$")
    public void lEmailEstDéjàUtiliséPourUnAutreUtilisateur(String arg0) throws Throwable {
        String sql = "SELECT ID FROM Personne WHERE Email = '"+arg0+"';";
        Statement s = con.createStatement();
        ResultSet res = s.executeQuery(sql);

        int count = 0;
        while (res.next()){
            count++;
        }
        assertEquals(1, count);
    }

    @Alors("^la page ne renvoie pas erreur$")
    public void laPageNeRenvoiePasErreur() {

    }
}
