package acceptance;

import cucumber.api.java.Before;
import cucumber.api.java.fr.Alors;
import cucumber.api.java.fr.Quand;
import org.openqa.selenium.By;
import org.openqa.selenium.htmlunit.HtmlUnitDriver;

import java.sql.*;
import java.util.logging.Level;
import java.util.logging.Logger;

import static org.junit.Assert.assertEquals;

public class DeconnexionAcceptance {
    private HtmlUnitDriver driver;
    private Connection con;
    private String urlPage = StaticConnection.urlPage;


    /*@Etantdonné("^Etant donné l'utilisateur est sur la page de login$")
        public void lUtilisateurEstSurLaPageLogin() {
            driver.get(urlPage+"/src/PHP/login.php");
      }*/

    @Quand("^l'utilisateur demande de se deconnecté$")
        public void lUtilisateurDemandeDeSeDeconnecte(){
           driver.findElement(By.id("btnDeconnection")).click();
        }

    /*@Alors("^la page renvoie sur l'index$")
    public void laPageRenvoieALIndex(){
        assertEquals(urlPage+"index.php",driver.getCurrentUrl());
    }*/




}
