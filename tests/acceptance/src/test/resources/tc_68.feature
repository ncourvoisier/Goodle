# language: fr
Fonctionnalité: Test Inscription NOMDECOMPTE Manquant

  Scénario: Inscription nom de compte manquant
    Etant donné que l'utilisateur est sur le formulaire d'inscription
    Quand l'utilisateur a saisie comme nom de compte ""
    Et l'utilisateur a saisie comme mot de passe "jean"
    Et l'utilisateur a saisie comme date de naissance "01/02/1998"
    Et l'utilisateur a saisie comme mdp "Jean2012**"
    Et l'utilisateur a saisie comme mail cddd@gmail.com
    Et l'utilisateur a saisie comme confirmation mail "cddd@gmail.com"
    Et l'utilisateur demande de valider l'inscription
    Alors la page renvoie une erreur "nom de compte incorrect"
    Et l'utilisateur retourne sur la page d'inscription