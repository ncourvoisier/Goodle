# language: fr
Fonctionnalité: Mdp Manquant

Etant donné que l'utilisateur sur le formulaire d'inscription
Quand l'utilisateur a saisie comme nom de compte "mokos"
Et l'utilisateur a saisie comme mot de passe "jean"
Et l'utilisateur a saisie comme date de naissance "01/02/1998"
Et l'utilisateur a saisie entrer comme mdp ""
Et l'utilisateur a saisie comme mail "cddd@gmail.com"
Et l'utilisateur a saisie comme confirmation mail "cddd@gmail.com"
Et l'utilisateur demande de valider l'inscription
Alors la page renvoie une erreur "mot de passe incorrect"
Et l'utilisateur retourne sur la page d'inscription