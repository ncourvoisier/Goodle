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

public class AjoutNouvelleDateExistanteErreur {

    /*private HtmlUnitDriver driver;
    private Connection con;
    private String urlPage = StaticConnection.localConnection;
    private int pastEvent;
    private int pastDateEvent;
    private int pastDate;

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

        sql = "INSERT INTO DateEvenement(ID, IDEevent, IDDate) VALUES ('0', '0', '0');";
        s = con.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS);

        s.executeUpdate(sql, Statement.RETURN_GENERATED_KEYS);
        generated = s.getGeneratedKeys();
        if (generated.next()) {
            pastDateEvent = generated.getInt(1);
        } else {
            throw new SQLException("Did not create event");
        }

        sql = "INSERT INTO Date(ID, Jour, Mois, Annee, Heure, Minute) VALUES ('0', '1', '12', '2020', '14', '0');";
        s = con.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS);

        s.executeUpdate(sql, Statement.RETURN_GENERATED_KEYS);
        generated = s.getGeneratedKeys();
        if (generated.next()) {
            pastDate = generated.getInt(1);
        } else {
            throw new SQLException("Did not create event");
        }

        driver.get(urlPage + "/src/PHP/login.php");
        driver.findElementByName("email").sendKeys("mailForTests@tests.fr");
        driver.findElementByName("password").sendKeys("Azerty1234!");
        driver.findElementByName("btnConnexion").click();
    }

    @Etantdonné("^l'utilisateur ajoute une proposition de date et heure$")
    public void lUtilisateurAjouteUnePropositionDeDateEtHeure() {
        driver.get(urlPage+"/src/PHP/ajouter_date_evenement.php?event="+pastEvent);
    }

    @Quand("^la date et l'heure sont déjà renseignés$")
    public void laDateEtLHeureSontDejaRenseignes() {
        {
            WebElement dropdown = driver.findElement(By.name("DateEvent_j"));
            dropdown.findElement(By.xpath("//option[. = '1']")).click();
        }
        {
            WebElement dropdown = driver.findElement(By.name("DateEvent_m"));
            dropdown.findElement(By.xpath("//option[. = 'Décembre']")).click();
        }
        {
            WebElement dropdown = driver.findElement(By.name("DateEvent_a"));
            dropdown.findElement(By.xpath("//option[. = '2020']")).click();
        }
        {
            WebElement dropdown = driver.findElement(By.name("DateEvent_hr"));
            dropdown.findElement(By.xpath("//option[. = '14']")).click();
        }
        {
            WebElement dropdown = driver.findElement(By.name("DateEvent_min"));
            dropdown.findElement(By.xpath("//option[. = '0']")).click();
        }
    }

    @Alors("^une message d'erreur s'affiche$")
    public void uneMessageDErreurSAffiche() {
        assertEquals(driver.getCurrentUrl(),urlPage+"/src/PHP/voir_event.php?event="+pastEvent);
        //Ajouter msg date ajoute ou erreur
        //driver.findElementById("error_ajout_date");
    }

    @After
    public void tearDown() throws SQLException {
        String sql = "DELETE FROM Date WHERE ID = " + pastDate + ";";
        Statement s = con.createStatement();
        s.executeUpdate(sql);

        sql = "DELETE FROM DateEvenement WHERE ID = " + pastDateEvent + ";";
        s = con.createStatement();
        s.executeUpdate(sql);

        sql = "DELETE FROM Evenement WHERE ID = " + pastEvent + ";";
        s = con.createStatement();
        s.executeUpdate(sql);

        driver.quit();
        con.close();
    }*/

}
