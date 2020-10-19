package acceptance;

import cucumber.api.java.Before;
import org.openqa.selenium.htmlunit.HtmlUnitDriver;

import java.sql.*;
import java.util.logging.Level;
import java.util.logging.Logger;

public class FiltrerVote {
    private HtmlUnitDriver driver;
    private Connection con;

    private String urlPage = StaticConnection.urlPage;

    private int event;
    private int dateId;
    private int index;
    private int newIndex;
    private int pastEventCreator;

    @Before
    public void setUp() throws SQLException {
        Logger logger = Logger.getLogger("");
        logger.setLevel(Level.OFF);

        driver = StaticConnection.getHtmlDriver();
        con = StaticConnection.getDatabaseConnector();

        String getEventCreatorSql = "SELECT ID FROM Personne WHERE Email = 'mailForTest@tests.fr';";
        PreparedStatement creatorStatement = con.prepareStatement(getEventCreatorSql);

        ResultSet creator = creatorStatement.executeQuery();

        if (creator.next()) {
            pastEventCreator = creator.getInt("ID");
        } else {
            throw new SQLException("No creator");
        }

        String insertEventSql = "INSERT INTO Evenement(Nom, Lieu, Referent, DateCloture) VALUES ('Evenement', 'Lieu', '" +  pastEventCreator +"', '2050-01-01 00:00');";
        PreparedStatement s = con.prepareStatement(insertEventSql, Statement.RETURN_GENERATED_KEYS);

        s.executeUpdate();
        ResultSet generated = s.getGeneratedKeys();
        if (generated.next()) {
            event = generated.getInt(1);
        } else {
            throw new SQLException("Did not create event");
        }

        String insertDateSql = "INSERT INTO Date(Jour, Mois, Annee, Heure, Minute) VALUES ('1', '1', '2050', '0', '0')";
        PreparedStatement dateSql = con.prepareStatement(insertDateSql, Statement.RETURN_GENERATED_KEYS);

        dateSql.executeUpdate();
        generated = dateSql.getGeneratedKeys();
        if (generated.next()) {
            dateId = generated.getInt(1);
        } else {
            throw new SQLException("Did not create date");
        }

        String insertDateEventSql = "INSERT INTO DateEvenement(IDDate, IDEvent) VALUES ('" + dateId + "', '" + event + "');";
        PreparedStatement dateEventSql = con.prepareStatement(insertDateEventSql);

        dateEventSql.executeUpdate();

        driver.get(urlPage + "/src/PHP/login.php");
        driver.findElementByName("email").sendKeys("mailForTest@tests.fr");
        driver.findElementByName("password").sendKeys("Azerty1234!");
        driver.findElementByName("btnConnexion").click();

    }
}
