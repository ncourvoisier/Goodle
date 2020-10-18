# language: fr
Fonctionnalité: Test de connexion invalid

  Scénario: Connexion invalide au site
    Etant donné l'utilisateur est sur la page de login
    Quand le mot de passe est "123"
    Et l'utilisateur demande de valider le login
    Alors un message d'erreur s'affiche sur la page login
    Et l'utilisateur n'est pas connecté
