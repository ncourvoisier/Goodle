# language: fr
Fonctionnalité: Test du lien d'invitation sans être connecté
  
    Scénario: Lien invitation en étant déconnecté
      Etant donné l'utilisateur est deconnecté
      Quand l'utilisateur veut rejoindre un lien
      Alors la page renvoie un message d'erreur connexion