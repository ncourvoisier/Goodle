# language: fr
Fonctionnalité: Test ajouter un nom non valide
  
  Scénario: Ajouter un nom non valide
    Etant donné l'utilisateur ajoute le nom de l'événement
    Quand le nom contient moins de 3 caractères
    Alors un message d'erreur s'affiche