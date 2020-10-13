# language: fr
Fonctionnalité: Test modifier date cloture valide
  
  Scénario: Modification de la date de cloture valide
    Etant donné l'utilisateur modifie la date de cloture
    Quand la nouvelle date cloture est suppérieur à la date du jour
    Alors la nouvelle date cloture est validé