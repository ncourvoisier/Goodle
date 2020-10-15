# language: fr
Fonctionnalité: Inscription

  Scénario: inscription mdp différents
    Etant donné l'utilisateur est sur le formulaire d'inscription
    Quand l'utilisateur a saisie comme nom de compte "mokos"
    Et l'utilisateur a saisie comme mot de passe 1 "jean"
    Et l'utilisateur a saisie comme date de naissance "1/02/1998"
    Et l'utilisateur a saisie comme mdp confirmation "Jean2012**"
    Et l'utilisateur a saisie comme mail "cddd@gmail.com"
    Et l'utilisateur demande de valider l'inscription
    Alors la page renvoie un message d'erreur
    Et l'utilisateur retourne sur la page d'inscription