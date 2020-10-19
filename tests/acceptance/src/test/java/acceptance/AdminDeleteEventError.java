package acceptance;

import cucumber.api.java.After;
import cucumber.api.java.Before;
import cucumber.api.java.fr.Et;
import cucumber.api.java.fr.Quand;
import org.openqa.selenium.htmlunit.HtmlUnitDriver;

import java.sql.*;
import java.util.logging.Level;
import java.util.logging.Logger;

import static org.junit.Assert.assertTrue;

public class AdminDeleteEventError {
    /*private HtmlUnitDriver driver;
    private Connection con;
    private String urlPage = StaticConnection.urlPage;
    private int pastEvent;
    private int date;
    private int dateEvent;

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


        String sql2 = "INSERT INTO Date(Jour,Mois,Annee,Heure,Minute) VALUES ('1','1','2040','0','0');";
        PreparedStatement s2 = con.prepareStatement(sql2, Statement.RETURN_GENERATED_KEYS);

        s2.executeUpdate(sql2, Statement.RETURN_GENERATED_KEYS);
        ResultSet generated2 = s2.getGeneratedKeys();
        if (generated2.next()) {
            date = generated2.getInt(1);
        } else {
            throw new SQLException("Did not create date");
        }


        String sql3 = "INSERT INTO DateEvenement(IDEvent,IDDate) VALUES ('"+pastEvent+"','"+date+"');";
        PreparedStatement s3 = con.prepareStatement(sql3, Statement.RETURN_GENERATED_KEYS);

        s3.executeUpdate(sql3, Statement.RETURN_GENERATED_KEYS);
        ResultSet generated3 = s3.getGeneratedKeys();
        if (generated3.next()) {
            dateEvent = generated3.getInt(1);
        } else {
            throw new SQLException("Did not create dateEvent");
        }


    }

    @After
    public void tearDown() throws SQLException {

        String sql = "DELETE FROM Date WHERE ID =" + date + ";";
        Statement s = con.createStatement();
        s.executeUpdate(sql);
        sql = "DELETE FROM DateEvenement WHERE ID =" + dateEvent + ";";
        s.executeUpdate(sql);
        sql = "DELETE FROM Evenement WHERE ID = " + pastEvent + ";";
        s.executeUpdate(sql);
        driver.quit();
        con.close();
    }

    @Et("^l'événement n'est pas supprimé$")
    public void lÉvénementNEstPasSupprimé() throws SQLException {
        String sql = "SELECT * FROM Evenement WHERE ID = '"+pastEvent+"';";
        PreparedStatement s = con.prepareStatement(sql);
        ResultSet event = s.executeQuery();
        if(event.next())
        {
            assertTrue(false);
        }
        else
        {
            assertTrue(true);
        }
    }*/
}
