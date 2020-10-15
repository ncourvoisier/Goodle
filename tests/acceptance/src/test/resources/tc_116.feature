# language: fr
Fonctionnalité: Test Inscription Passant

  Scénario: Inscription ok
    Etant donné l'utilisateur est sur le formulaire d'inscription
    Quand l'utilisateur a saisie comme nom de compte "acceptation"
    Et l'utilisateur a saisie comme mot de passe 1 "Jean2012**"
    Et l'utilisateur a saisie comme date de naissance "1/02/1998"
    Et l'utilisateur a saisie comme mdp confirmation "Jean2012**"
    Et l'utilisateur a saisie comme mail "acceptance@gmail.com"
    Et l'utilisateur demande de valider l'inscription
    Alors la page ne renvoie pas erreur
    Et l'utilisateur retourne sur la page d'inscription