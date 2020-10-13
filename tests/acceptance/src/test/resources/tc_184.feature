# language: fr
Fonctionnalité: Test de connexion invalid
Etant donné que L'utilisateur est sur la page de login
Quand le mot de passe est ""
Et l'utilisateur demande de valider le login
Alors un message d'erreur s'affiche
Et l'utilisateur n'est pas connecté