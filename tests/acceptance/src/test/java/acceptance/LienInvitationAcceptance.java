package acceptance;

import cucumber.api.java.After;
import cucumber.api.java.Before;
import cucumber.api.java.fr.Alors;
import cucumber.api.java.fr.Etantdonné;
import cucumber.api.java.fr.Quand;
import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.htmlunit.HtmlUnitDriver;

import java.sql.*;
import java.util.logging.Level;
import java.util.logging.Logger;

import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertTrue;

public class LienInvitationAcceptance {

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

    @Quand("^l'utilistateur crée un évènement pour lien$")
    public void lUtilistateurCréeUnÉvènement() {
       /* driver.get(urlPage+"/src/PHP/ajout_evenement.php");
        driver.findElementByName("NameEvent").sendKeys("AcceptanceTestEvenement");
        driver.findElementByName("LieuEvent").sendKeys("AcceptanceTestEvenement");
        {
            WebElement dropdown = driver.findElement(By.name("DateEvent1_j"));
            dropdown.findElement(By.xpath("//option[. = '1']")).click();
        }

        {
            WebElement dropdown = driver.findElement(By.name("DateEvent1_m"));
            dropdown.findElement(By.xpath("//option[. = 'Mars']")).click();
        }

        {
            WebElement dropdown = driver.findElement(By.name("DateEvent1_a"));
            dropdown.findElement(By.xpath("//option[. = '2022']")).click();
        }

        {
            WebElement dropdown = driver.findElement(By.name("DateCloture_j"));
            dropdown.findElement(By.xpath("//option[. = '25']")).click();
        }

        {
            WebElement dropdown = driver.findElement(By.name("DateCloture_m"));
            dropdown.findElement(By.xpath("//option[. = 'Novembre']")).click();
        }

        {
            WebElement dropdown = driver.findElement(By.name("DateCloture_a"));
            dropdown.findElement(By.xpath("//option[. = '2021']")).click();
        }

        driver.findElementByName("btnValiderEvent").click();*/
    }

    @Alors("^l'utilisateur obtient un lien qui redirige vers cette evenement$")
    public void lUtilisateurObtientUnLienQuiRedirigeVersCetteEvenement() throws SQLException {
       /* String getEventCreatorSql = "SELECT ID FROM Evenement WHERE Nom = 'AcceptanceTestEvenement';";
        PreparedStatement creatorStatement = con.prepareStatement(getEventCreatorSql);

        ResultSet creator = creatorStatement.executeQuery();

        long id = 0;
        if (creator.next()) {
            id = creator.getInt("ID");
        } else {
            throw new SQLException("No ID");
        }
        assertEquals(urlPage+"src/PHP/evenement_ok.php?event="+id, driver.getCurrentUrl());*/
        //driver.findElementById("lien_invitation");
        //System.out.println(driver.findElementById("error_add_event").getText());

    }
}
