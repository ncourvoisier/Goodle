# language: fr
Fonctionnalité: Test visualisation des votes
  Scénario: voir les informations
Etant donné je suis sur connecté
    Et j'ai déja voté "oui"
Quand l'utilisateur est sur la page évènement
Alors le site renvoie tout les votes sur l'évènement
Et l'utilisateur reçoit les bonnes informations
