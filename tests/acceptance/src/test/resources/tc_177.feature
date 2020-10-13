# language: fr
Fonctionnalité: Test date de cloture passant
  
  Scénario: Date de cloture valide
    Etant donné l'utilisateur renseigne la date de cloture
    Quand la date cloture est suppérieur à la date du jour
    Alors la date cloture est validé
   