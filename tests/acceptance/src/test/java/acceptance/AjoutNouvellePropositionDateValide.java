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

import java.sql.*;
import java.util.Calendar;
import java.util.logging.Level;
import java.util.logging.Logger;

import static org.junit.Assert.assertEquals;

public class AjoutNouvellePropositionDateValide {

    private HtmlUnitDriver driver;
    private Connection con;
    private String urlPage = StaticConnection.urlPage;
    private int pastEvent;


    int dateId;

    @Before
    public void setUp() throws SQLException {
        Logger logger = Logger.getLogger("");
        logger.setLevel(Level.OFF);

        driver = StaticConnection.getHtmlDriver();
        con = StaticConnection.getDatabaseConnector();

        int pastEventCreator = 0;

        String getEventCreatorSql = "SELECT ID FROM Personne WHERE Email = 'mailForTest@tests.fr';";
        PreparedStatement creatorStatement = con.prepareStatement(getEventCreatorSql);

        ResultSet creator = creatorStatement.executeQuery();

        if (creator.next()) {
            pastEventCreator = creator.getInt("ID");
        } else {
            throw new SQLException("No creator");
        }


        String sql = "INSERT INTO Evenement(ID, Nom, Lieu, Referent, DateCloture) VALUES ('0', 'Evenement', 'Lieu', '" + pastEventCreator + "', '2050-01-01 00:00');";
        PreparedStatement s = con.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS);

        s.executeUpdate(sql, Statement.RETURN_GENERATED_KEYS);
        ResultSet generated = s.getGeneratedKeys();
        if (generated.next()) {
            pastEvent = generated.getInt(1);
            System.out.println(pastEvent);
        } else {
            throw new SQLException("Did not create event");
        }

        String insertDateSql = "INSERT INTO Date(Jour, Mois, Annee, Heure, Minute) VALUES ('1', '1', '2050', '0', '1')";
        PreparedStatement dateSql = con.prepareStatement(insertDateSql, Statement.RETURN_GENERATED_KEYS);

        dateSql.executeUpdate();
        generated = dateSql.getGeneratedKeys();
        if (generated.next()) {
            dateId = generated.getInt(1);
        } else {
            throw new SQLException("Did not create date");
        }

        String insertDateEventSql = "INSERT INTO DateEvenement(IDDate, IDEvent) VALUES ('" + dateId + "', '" + pastEvent + "');";
        PreparedStatement dateEventSql = con.prepareStatement(insertDateEventSql);

        dateEventSql.executeUpdate();


        driver.get(urlPage + "/src/PHP/login.php");
        driver.findElementByName("email").sendKeys("mailForTest@tests.fr");
        driver.findElementByName("password").sendKeys("Azerty1234!");
        driver.findElementByName("btnConnexion").click();
    }

    @Etantdonné("^l'utilisateur ajoute une proposition de date et heure$")
    public void lUtilisateurAjouteUnePropositionDeDateEtHeure() {
        driver.get(urlPage+"/src/PHP/ajouter_date_evenement.php?event="+pastEvent);
    }

    @Quand("^la date et l'heure sont suppérieure à la date actuelle$")
    public void laDateEtLHeureSontSuppérieureÀLaDateActuelle() {
        {
            WebElement dropdown = driver.findElement(By.name("DateEvent_j"));
            dropdown.findElement(By.xpath("//option[. = '3']")).click();
        }

        {
            WebElement dropdown = driver.findElement(By.name("DateEvent_m"));
            dropdown.findElement(By.xpath("//option[. = 'Novembre']")).click();
        }

        {
            WebElement dropdown = driver.findElement(By.name("DateEvent_a"));
            dropdown.findElement(By.xpath("//option[. = '2050']")).click();
        }

        driver.findElementByName("btnValiderAjoutDate").click();
    }

    @Et("^la date et l'heure ne sont pas déjà renseigné$")
    public void laDateEtLHeureNeSontPasDéjàRenseigné() {

    }

    @Alors("^la date et l'heure sont ajouté à l'événement$")
    public void laDateEtLHeureSontAjoutéÀLÉvénement() {
        assertEquals(driver.getCurrentUrl(),urlPage+"/src/PHP/voir_event.php?event="+pastEvent);
    }

    @Quand("^la date et l'heure sont déjà renseignés$")
    public void laDateEtLHeureSontDejaRenseignes() {
        {
            WebElement dropdown = driver.findElement(By.name("DateEvent_j"));
            dropdown.findElement(By.xpath("//option[. = '1']")).click();
        }
        {
            WebElement dropdown = driver.findElement(By.name("DateEvent_m"));
            dropdown.findElement(By.xpath("//option[. = 'Janvier']")).click();
        }
        {
            WebElement dropdown = driver.findElement(By.name("DateEvent_a"));
            dropdown.findElement(By.xpath("//option[. = '2050']")).click();
        }
        {
            WebElement dropdown = driver.findElement(By.name("DateEvent_hr"));
            dropdown.findElement(By.xpath("//option[. = '0']")).click();
        }
        {
            WebElement dropdown = driver.findElement(By.name("DateEvent_min"));
            dropdown.findElement(By.xpath("//option[. = '1']")).click();
        }
        driver.findElementByName("btnValiderAjoutDate").click();
    }

    @Alors("^une message d'erreur s'affiche$")
    public void uneMessageDErreurSAffiche() {
        assertEquals(driver.getCurrentUrl(),urlPage+"/src/PHP/ajouter_date_evenement.php?event="+pastEvent);
        //Ajouter msg date ajoute ou erreur
        //driver.findElementById("error_ajout_date");
    }


    @After
    public void tearDown() throws SQLException {
        Statement s = con.createStatement();
        String sql = "DELETE FROM DateEvenement WHERE IDEvent = '" + pastEvent + "';";
        s.executeUpdate(sql);
        sql = "DELETE FROM Evenement WHERE ID = '" + pastEvent + "';";
        s.executeUpdate(sql);
        sql = "DELETE FROM Date WHERE ID = '" + dateId + "';";
        s.executeUpdate(sql);
        driver.quit();
        con.close();
    }
}
