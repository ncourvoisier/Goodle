# language: fr
Fonctionnalité: Test modifier date de cloture non valide
  
  Scénario: Modification de la date de cloture non valide
    Etant donné L'utilisateur modifie la date de cloture de l'événement
    Quand la nouvelle date de cloture est antérieure à la date du jour
    Alors un message d'erreur s'affiche