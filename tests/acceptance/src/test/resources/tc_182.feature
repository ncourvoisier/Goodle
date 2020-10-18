# language: fr
Fonctionnalité: Test de connexion au site valide

  Scénario: je me connecte au site
    Etant donné l'utilisateur est sur la page de login
    Quand il saisie un nom d'utilisateur valide
    Et il saisie le mot de passe valide
    Et il demande de valider le login
    Alors la page renvoie sur l'index

