# language: fr
Fonctionnalité: Test Inscription Utilisateur Doublon
Scénario: Inscription avec un mail déjà utilisé
  Etant donné l'utilisateur est sur le formulaire d'inscription
  Quand l'utilisateur a saisie comme nom de compte "mailfortest"
  Et l'utilisateur a saisie comme mot de passe 1 "jean"
  Et l'utilisateur a saisie comme date de naissance "1/02/1998"
  Et l'utilisateur a saisie comme mdp confirmation "Jean2012**"
  Et l'utilisateur a saisie comme mail "mailForTest@tests.fr"
  Et l'utilisateur demande de valider l'inscription
  Et l'email "mailForTest@tests.fr" est déjà utilisé pour un autre utilisateur
  Alors la page renvoie un message d'erreur
  Et l'utilisateur retourne sur la page d'inscription
  
  
