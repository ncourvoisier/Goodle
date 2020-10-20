# language: fr
Fonctionnalité: Test ajout proposition de date antérieure
  
  Scénario: Proposition de date et heure existante
    Etant donné l'utilisateur ajoute une proposition de date et heure antérieure
    Quand la date et l'heure sont antérieur à la date du jour
    Alors une message d'erreur s'affiche dans l'ajout de date