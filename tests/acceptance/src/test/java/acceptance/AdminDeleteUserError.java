package acceptance;

import cucumber.api.PendingException;
import cucumber.api.java.After;
import cucumber.api.java.Before;
import cucumber.api.java.fr.Alors;
import cucumber.api.java.fr.Et;
import cucumber.api.java.fr.Etantdonné;
import cucumber.api.java.fr.Quand;
import org.openqa.selenium.By;
import org.openqa.selenium.htmlunit.HtmlUnitDriver;

import java.sql.*;
import java.util.logging.Level;
import java.util.logging.Logger;

import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertTrue;

public class AdminDeleteUserError {
    private HtmlUnitDriver driver;
    private Connection con;
    private int pastEventCreator;
    private int pastEvent;
    private int pastEvent2;
    private int user;
    private int reponseEvent;
    private String urlPage = StaticConnection.urlPage;

    @Before
    public void setUp() throws SQLException {

        Logger logger = Logger.getLogger("");
        logger.setLevel(Level.OFF);

        driver = StaticConnection.getHtmlDriver();
        con = StaticConnection.getDatabaseConnector();

        //création d'un utilisateur à qui ont attribue un évenement et un vote qu'on supprimera après
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


        String getEventCreatorSql = "SELECT ID FROM Personne WHERE Email = 'mailForTest@tests.fr';";
        PreparedStatement creatorStatement = con.prepareStatement(getEventCreatorSql);

        ResultSet creator = creatorStatement.executeQuery();

        if (creator.next()) {
            pastEventCreator = creator.getInt("ID");
        } else {
            throw new SQLException("No creator");
        }

        String getEventUserSql = "SELECT ID FROM Personne WHERE Email = 'user@test.fr';";
        PreparedStatement userStatement = con.prepareStatement(getEventUserSql);

        ResultSet usr = userStatement.executeQuery();

        if (usr.next()) {
            user = usr.getInt("ID");
        } else {
            throw new SQLException("No User");
        }

        /*
        String sql = "INSERT INTO Evenement(Nom, Lieu, Referent, DateCloture) VALUES ('eventUserTest', 'lieuUserTest', '"+pastEventCreator+"', '2040-02-02 00:00');";
        PreparedStatement s = con.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS);

        s.executeUpdate(sql, Statement.RETURN_GENERATED_KEYS);
        ResultSet generated = s.getGeneratedKeys();
        if (generated.next()) {
            pastEvent = generated.getInt(1);
        } else {
            throw new SQLException("Did not create event");
        }

        int date;
        String sql2 = "INSERT INTO Date(Jour,Mois,Annee,Heure,Minute) VALUES ('1','1','2040','0','0');";
        PreparedStatement s2 = con.prepareStatement(sql2, Statement.RETURN_GENERATED_KEYS);

        s2.executeUpdate(sql2, Statement.RETURN_GENERATED_KEYS);
        ResultSet generated2 = s2.getGeneratedKeys();
        if (generated2.next()) {
            date = generated2.getInt(1);
        } else {
            throw new SQLException("Did not create date");
        }

        int dateEvent;
        String sql3 = "INSERT INTO DateEvenement(IDEvent,IDDate) VALUES ('"+pastEvent+"','"+date+"');";
        PreparedStatement s3 = con.prepareStatement(sql3, Statement.RETURN_GENERATED_KEYS);

        s3.executeUpdate(sql3, Statement.RETURN_GENERATED_KEYS);
        ResultSet generated3 = s3.getGeneratedKeys();
        if (generated3.next()) {
            dateEvent = generated3.getInt(1);
        } else {
            throw new SQLException("Did not create dateEvent");
        }

        String sql35 = "INSERT INTO Invite (IDEvent,IDPersonne) VALUES ('"+pastEvent+"','"+user+"');";
        PreparedStatement s35 = con.prepareStatement(sql35, Statement.RETURN_GENERATED_KEYS);
        int idInvite;
        s35.executeUpdate(sql35, Statement.RETURN_GENERATED_KEYS);
        ResultSet generated35 = s35.getGeneratedKeys();
        if (generated35.next()) {
            idInvite = generated35.getInt(1);
        } else {
            throw new SQLException("Did not create invite");
        }

        String sql4 = "INSERT INTO Reponse(IDDateEvent,IDInvite,Response) VALUES ('"+dateEvent+"', '"+idInvite+"','Oui');";
        PreparedStatement s4 = con.prepareStatement(sql4, Statement.RETURN_GENERATED_KEYS);

        s4.executeUpdate(sql4, Statement.RETURN_GENERATED_KEYS);
        ResultSet generated4 = s4.getGeneratedKeys();
        if (generated4.next()) {
            reponseEvent = generated4.getInt(1);
        } else {
            throw new SQLException("Did not create reponse");
        }


        */
        String sql5 = "INSERT INTO Evenement(Nom, Lieu, Referent, DateCloture) VALUES ('eventUserTest2', 'lieuUserTest2', '"+user+"', '2040-03-03 00:00');";
        PreparedStatement s5 = con.prepareStatement(sql5, Statement.RETURN_GENERATED_KEYS);

        s5.executeUpdate(sql5, Statement.RETURN_GENERATED_KEYS);
        ResultSet generated5 = s5.getGeneratedKeys();
        if (generated5.next()) {
            pastEvent2 = generated5.getInt(1);
        } else {
            throw new SQLException("Did not create event");
        }



    }


    @Alors("^un message d'erreur apparait \"([^\"]*)\"$")
    public void unMessageDErreurApparait(String arg0) throws Throwable {
        // Write code here that turns the phrase above into concrete actions
        driver.get(urlPage + "/src/PHP/login.php");
        driver.findElementByName("email").sendKeys("mailForTests@tests.fr");
        driver.findElementByName("password").sendKeys("Azerty1234!");
        driver.findElementByName("btnConnexion").click();
        driver.get(urlPage + "/src/PHP/admin_user_view.php");
        driver.findElementByName("userTest").click();
        driver.findElement(By.cssSelector("ul > a")).click();
        driver.findElementByClassName("alert-danger");
        //throw new PendingException();
    }

    @After
    public void tearDown() throws SQLException {
        Statement s = con.createStatement();
        String sql = "DELETE FROM Evenement WHERE ID = '" + pastEvent2 + "';";
        s.executeUpdate(sql);
        sql = "DELETE FROM Personne WHERE ID = '" + user + "';";
        s.executeUpdate(sql);
        driver.quit();
        con.close();
    }
}
