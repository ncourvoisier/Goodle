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

public class ModificationDateClotureNonValdie {

    private HtmlUnitDriver driver;
    private Connection con;
    private String urlPage = StaticConnection.urlPage;
    private int pastEvent;
    private int pastDate;
    private int pastDateEvent;

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


        String sql = "INSERT INTO Evenement(Nom, Lieu, Referent, DateCloture) VALUES ('Evenement', 'Lieu', '" + pastEventCreator + "', '2050-01-01 00:00');";

        PreparedStatement s = con.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS);

        s.executeUpdate(sql, Statement.RETURN_GENERATED_KEYS);
        ResultSet generated = s.getGeneratedKeys();
        if (generated.next()) {
            pastEvent = generated.getInt(1);
        } else {
            throw new SQLException("Did not create event");
        }


        sql = "INSERT INTO Date(Jour, Mois, Annee, Heure, Minute) VALUES ('1', '12', '2051', '14', '0');";
        s = con.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS);

        s.executeUpdate(sql, Statement.RETURN_GENERATED_KEYS);
        generated = s.getGeneratedKeys();
        if (generated.next()) {
            pastDate = generated.getInt(1);
        } else {
            throw new SQLException("Did not create event");
        }

        sql = "INSERT INTO DateEvenement(IDEvent, IDDate) VALUES (" + pastEvent + ", " + pastDate + ");";
        s = con.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS);

        s.executeUpdate(sql, Statement.RETURN_GENERATED_KEYS);
        generated = s.getGeneratedKeys();
        if (generated.next()) {
            pastDateEvent = generated.getInt(1);
        } else {
            throw new SQLException("Did not create event");
        }

        driver.get(urlPage + "/src/PHP/login.php");
        driver.findElementByName("email").sendKeys("mailForTest@tests.fr");
        driver.findElementByName("password").sendKeys("Azerty1234!");
        driver.findElementByName("btnConnexion").click();
    }

    @Etantdonné("^L'utilisateur modifie la date de cloture de l'événement$")
    public void lUtilisateurModifieLaDateDeClotureDeLEvenement() {
        driver.get(urlPage + "/src/PHP/voir_event.php?event=" + pastEvent);
        driver.findElementByName("btnModifier").click();
        {
            WebElement dropdown = driver.findElement(By.name("DateCloture_j"));
            dropdown.findElement(By.xpath("//option[. = '20']")).click();
        }
        {
            WebElement dropdown = driver.findElement(By.name("DateCloture_m"));
            dropdown.findElement(By.xpath("//option[. = 'Janvier']")).click();
        }
        {
            WebElement dropdown = driver.findElement(By.name("DateCloture_a"));
            dropdown.findElement(By.xpath("//option[. = '2020']")).click();
        }
        driver.findElementByName("btnValiderEvent").click();
    }

    @Quand("^la nouvelle date de cloture est antérieure à la date du jour$")
    public void laNouvelleDateClotureEstAnterieurALaDateDuJour() {
    }

    @Alors("^un message d'erreur spécifique s'affiche$")
    public void unMessageDErreurSAffiche() {

        driver.findElementByClassName("erreur");
        assertEquals(driver.getCurrentUrl(), urlPage + "/src/PHP/modif_evenement.php?event=" + pastEvent);
    }

    @After
    public void tearDown() throws SQLException {
        String sql = "DELETE FROM DateEvenement WHERE ID = " + pastDateEvent + ";";
        Statement s = con.createStatement();
        s.executeUpdate(sql);

        sql = "DELETE FROM Date WHERE ID = " + pastDate + ";";
        s = con.createStatement();
        s.executeUpdate(sql);

        sql = "DELETE FROM Evenement WHERE ID = " + pastEvent + ";";
        s = con.createStatement();
        s.executeUpdate(sql);

        driver.quit();
        con.close();
    }


}
