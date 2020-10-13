# language: fr
Fonctionnalité: Ajouter une proposition de date
  
  Scénario: Proposition de date et heure valide
    Etant donné l'utilisateur ajoute une proposition de date et heure
    Quand la date et l'heure sont suppérieure à la date actuelle
    Et la date et l'heure ne sont pas déjà renseigné
    Alors la date et l'heure sont ajouté à l'événement