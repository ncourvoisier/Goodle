# language: fr
Fonctionnalité: Test date de cloture non passant
  
  Scénario: Date de cloture non valide
    Etant donné L'utilisateur renseigne la date de cloture de l'événement
    Quand la date de cloture est antérieure à la date du jour
    Alors un message d'erreur s'affiche