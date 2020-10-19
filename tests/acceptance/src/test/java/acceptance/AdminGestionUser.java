package acceptance;

import cucumber.api.java.After;
import cucumber.api.java.Before;
import cucumber.api.java.fr.Alors;
import cucumber.api.java.fr.Et;
import cucumber.api.java.fr.Etantdonné;
import cucumber.api.java.fr.Quand;
import org.junit.Assert;
import org.openqa.selenium.By;
import org.openqa.selenium.htmlunit.HtmlUnitDriver;

import java.sql.*;
import java.util.logging.Level;
import java.util.logging.Logger;

import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertTrue;

public class AdminGestionUser {
    private HtmlUnitDriver driver;
    private Connection con;

    private String urlPage = StaticConnection.urlPage;

    @Before
    public void setUp() throws SQLException {

        Logger logger = Logger.getLogger("");
        logger.setLevel(Level.OFF);

        driver = StaticConnection.getHtmlDriver();
        con = StaticConnection.getDatabaseConnector();

        //création d'un utilisateur qu'on supprimera après
        driver.get(urlPage + "/src/PHP/inscription.php");
        driver.findElementByName("email").sendKeys("user@test.fr");
        driver.findElementByName("pass1").sendKeys("Azerty1234!");
        driver.findElementByName("pass2").sendKeys("Azerty1234!");
        driver.findElementByName("nom").sendKeys("userTest");
        driver.findElementByName("prenom").sendKeys("userTest");
        //on laisse la date de naissance par défaut
        driver.findElementByName("username").sendKeys("userTest");
        driver.findElementByName("btnSInscrire").submit();
        driver = StaticConnection.getHtmlDriver();



    }



    @Etantdonné("^la session courante est celle de l'administrateur$")
    public void laSessionCouranteEstCelleDeLAdministrateur() {
        driver.get(urlPage + "/src/PHP/login.php");
        driver.findElementByName("email").sendKeys("mailForTests@tests.fr");
        driver.findElementByName("password").sendKeys("Azerty1234!");
        driver.findElementByName("btnConnexion").click();
    }

    @Quand("^l'administrateur est sur la page de gestion des utilisateurs$")
    public void lAdministrateurEstSurLaPageDeGestionDesUtilisateurs() {
        driver.get(urlPage + "/src/PHP/admin_user_view.php");
    }

    @Et("^le tableau de la liste des utilisateurs est complet ou que les informations sont valides$")
    public void leTableauDeLaListeDesUtilisateursEstCompletOuQueLesInformationsSontValides() throws SQLException {
        // on va vérifier que chaques utilisateurs apparaisent dans la liste
        String sql = "SELECT Username FROM Personne;";

        PreparedStatement userStatement = con.prepareStatement(sql);
        ResultSet users = userStatement.executeQuery();

        while(users.next())
        {
            driver.findElementByName(users.getString("Username"));
        }
    }

    @Alors("^l'administrateur peut gérer les utilisateurs$")
    public void lAdministrateurPeutGérerLesUtilisateurs() throws SQLException{
        // on va supprimer l'utilisateur crée dans le setup

        driver.findElementByName("userTest").click();
        driver.findElement(By.cssSelector("ul > a")).click();

        // on vérifie que l'utilisateur n'apparait plus dans la bdd
        String sql = "SELECT * FROM Personne WHERE Username = 'usertTest';";
        PreparedStatement userStatement = con.prepareStatement(sql);
        ResultSet user = userStatement.executeQuery();
        if(user.next())
        {
            assertTrue(false);
        }
        else
        {
            assertTrue(true);
        }


    }

}
