# language: fr
Fonctionnalité: Test edition du vote
  
Etant donné que j'ai déja voté "oui"
Quand la date de cloture des vote n'est pas dépassé
Et l'utilisateur change son vote en "Non" sur une des proposition du sondage
Alors le site renvoie "Votre vote Non a bien ete prit en compte"
Et le vote est validé
