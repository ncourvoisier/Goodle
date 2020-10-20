package acceptance;

import cucumber.api.java.After;
import cucumber.api.java.Before;
import cucumber.api.java.fr.Alors;
import cucumber.api.java.fr.Et;
import cucumber.api.java.fr.Etantdonné;
import cucumber.api.java.fr.Quand;
import org.openqa.selenium.htmlunit.HtmlUnitDriver;

import java.sql.*;
import java.util.logging.Level;
import java.util.logging.Logger;

public class AdminAddEvent {
    private HtmlUnitDriver driver;
    private Connection con;
    private String urlPage = StaticConnection.urlPage;
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

        sql = "INSERT INTO Date(ID, Jour, Mois, Annee, Heure, Minute) VALUES ('0', '1', '12', '2020', '14', '0');";
        s = con.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS);

        s.executeUpdate(sql, Statement.RETURN_GENERATED_KEYS);
        generated = s.getGeneratedKeys();
        if (generated.next()) {
            pastDate = generated.getInt(1);
        } else {
            throw new SQLException("Did not create event");
        }

        sql = "INSERT INTO DateEvenement(ID, IDEvent, IDDate) VALUES ('0', " + pastEvent + ", " + pastDate + ");";
        s = con.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS);

        s.executeUpdate(sql, Statement.RETURN_GENERATED_KEYS);
        generated = s.getGeneratedKeys();
        if (generated.next()) {
            pastDateEvent = generated.getInt(1);
        } else {
            throw new SQLException("Did not create event");
        }


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

    @Quand("^l'administrateur est sur la page de gestion des evenements$")
    public void lAdministrateurEstSurLaPageDeGestionDesEvenements() {
        driver.get(urlPage+"/src/PHP/evenement.php");
    }

    @Et("^le tableau de la liste des événement est complet ou que les informations sont valides$")
    public void leTableauDeLaListeDesÉvénementEstCompletOuQueLesInformationsSontValides() {

    }


    @Alors("^l'administrateur peut gérer les événements$")
    public void lAdministrateurPeutGérerLesÉvénements() {
        driver.findElementByLinkText("voir event");
    }

    @Etantdonné("^la session courante est celle d'un administrateur$")
    public void laSessionCouranteEstCelleDUnAdministrateur() {
        driver.get(urlPage + "/src/PHP/login.php");
        driver.findElementByName("email").sendKeys("testadmin@test.com");
        driver.findElementByName("password").sendKeys("12345678");
        driver.findElementByName("btnConnexion").click();
    }
}
