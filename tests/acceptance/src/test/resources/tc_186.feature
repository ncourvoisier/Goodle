# language: fr
Fonctionnalité: Test de déconnexion

  Scénario: Je me deconnecte
    Etant donné l'utilisateur est connecté
    Quand l'utilisateur demande de se deconnecté
    Alors la page renvoie sur l'index
    Et l'utilisateur est deconnecté
