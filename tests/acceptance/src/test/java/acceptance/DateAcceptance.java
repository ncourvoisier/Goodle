package acceptance;

import cucumber.api.PendingException;
import cucumber.api.java.fr.Alors;
import cucumber.api.java.fr.Et;
import cucumber.api.java.fr.Etantdonné;
import cucumber.api.java.fr.Quand;

public class DateAcceptance {
    @Etantdonné("^l'utilisateur ajoute une proposition de date et heure$")
    public void lUtilisateurAjouteUnePropositionDeDateEtHeure() throws Throwable {
        System.out.println("true");
        assert(true);
    }

    @Quand("^la date et l'heure sont suppérieure à la date actuelle$")
    public void laDateEtLHeureSontSuppérieureÀLaDateActuelle() throws Throwable {
        // Write code here that turns the phrase above into concrete actions
        throw new PendingException();
    }

    @Et("^la date et l'heure ne sont pas déjà renseigné$")
    public void laDateEtLHeureNeSontPasDéjàRenseigné() throws Throwable {
        // Write code here that turns the phrase above into concrete actions
        throw new PendingException();
    }

    @Alors("^la date et l'heure sont ajouté à l'événement$")
    public void laDateEtLHeureSontAjoutéÀLÉvénement() throws Throwable {
        // Write code here that turns the phrase above into concrete actions
        throw new PendingException();
    }


}
