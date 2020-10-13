# language: fr
Fonctionnalité: Test modifier nom non valide
  
  Scénario: Modification du nom non valide
    Etant donné l'utilisateur modifie le nom de l'événement
    Quand le nouveau nom contient moins de 3 caractères
    Alors un message d'erreur s'affiche