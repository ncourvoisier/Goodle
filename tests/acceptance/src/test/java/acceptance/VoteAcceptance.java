package acceptance;

import cucumber.api.PendingException;
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

import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertFalse;

public class VoteAcceptance {
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

    @After
    public void tearDown() throws SQLException {

        Statement s = con.createStatement();
        String sql = "DELETE FROM DateEvenement WHERE IDEvent = '" + event + "';";
        s.executeUpdate(sql);
        sql = "DELETE FROM Evenement WHERE ID = '" + event + "';";
        s.executeUpdate(sql);
        sql = "DELETE FROM Date WHERE ID = '" + dateId + "';";
        s.executeUpdate(sql);
        driver.quit();
        con.close();
    }


    @Etantdonné("^j'ai déja voté \"([^\"]*)\"$")
    public void queJAiDéjaVoté(String arg0) throws Throwable {
        driver.get(urlPage + "/src/PHP/repondre_invite.php?IDEvent=" + event);
        index = 2;
        if (arg0.equals("oui")) {
            index = 0;
        } else if (arg0.equals("non")) {
            index = 1;
        }
        driver.findElementsByName("20501100").get(index).click();
        driver.findElementByName("btnValiderRep").click();
    }

    @Quand("^la date de cloture des vote n'est pas dépassé$")
    public void laDateDeClotureDesVoteNEstPasDépassé() throws Throwable {

    }

    @Et("^l'utilisateur change son vote en \"([^\"]*)\" sur une des proposition du sondage$")
    public void lUtilisateurChangeSonVoteEnSurUneDesPropositionDuSondage(String arg0) throws Throwable {
        driver.get(urlPage + "/src/PHP/repondre_invite.php?IDEvent=" + event);
        newIndex = 2;
        if (arg0.equals("oui")) {
            newIndex = 0;
        } else if (arg0.equals("non")) {
            newIndex = 1;
        }
        driver.findElementsByName("20501100").get(newIndex).click();
        driver.findElementByName("btnValiderRep").click();
    }

    @Alors("^le site renvoie \"([^\"]*)\"$")
    public void leSiteRenvoie(String arg0) throws Throwable {
        driver.findElementById("message_okay");
    }

    @Et("^le vote est validé$")
    public void leVoteEstValidé() throws Throwable {
        PreparedStatement s =  con.prepareStatement("SELECT Response FROM Reponse, Invite, DateEvenement, Date WHERE Invite.ID = Reponse.IDInvite AND DateEvenement.ID = Reponse.IDDateEvent AND DateEvenement.IDDate = Date.ID AND Invite.IDPersonne = ? AND Date.ID = ? AND DateEvenement.IDEvent = ?;");
        s.setInt(1, pastEventCreator);
        s.setInt(2, dateId);
        s.setInt(3, event);
        ResultSet res = s.executeQuery();

        String value[] = {"Oui", "Non", "Peutetre"};

        if (res.next()) {
            assertEquals(value[newIndex], res.getString("Response"));
        } else {
            throw new Exception("Didn't add vote");
        }
        assertFalse(res.next());
    }

    @Etantdonné("^je suis sur connecté$")
    public void jeSuisSurConnecte() throws Throwable {

    }

    @Quand("^l'utilisateur est sur la page évènement$")
    public void lUtilisateurEstSurLaPageÉvènement() throws Throwable {
        driver.get(urlPage + "/src/PHP/repondre_invite.php?IDEvent=" + event);
    }

    @Alors("^le site renvoie tout les votes sur l'évènement$")
    public void leSiteRenvoieToutLesVotesSurLÉvènement() throws Throwable {
        driver.findElementById("205011000oui");
        driver.findElementById("205011000non");
        driver.findElementById("205011000peutetre");
    }

    @Et("^l'utilisateur reçoit les bonnes informations$")
    public void lUtilisateurReçoitLesBonnesInformations() throws Throwable {
        String values[] = {"0", "0", "0"};
        values[index] = "1";
        assertEquals(values[0], driver.findElementById("205011000oui").getText());
        assertEquals(values[1], driver.findElementById("205011000non").getText());
        assertEquals(values[2], driver.findElementById("205011000peutetre").getText());
    }

    @Et("^l'utilisateur vote \"([^\"]*)\" sur une des proposition du sondage$")
    public void lUtilisateurVoteSurUneDesPropositionDuSondage(String arg0) throws Throwable {
        driver.get(urlPage + "/src/PHP/repondre_invite.php?IDEvent=" + event);
        newIndex = 2;
        if (arg0.equals("Oui")) {
            newIndex = 0;
        } else if (arg0.equals("Non")) {
            newIndex = 1;
        }
        driver.findElementsByName("20501100").get(newIndex).click();
        driver.findElementByName("btnValiderRep").click();
    }
}
