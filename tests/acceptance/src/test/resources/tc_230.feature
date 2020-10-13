# language: fr
Fonctionnalité: TEST_DEL_EVENT_VALID
  
Scénario: Test de la suppréssion d'un événement invalide
  Etant donné que la session courante est celle de l'administrateur
  Quand l'administrateur est sur la page d'un événement
  Et que l'administrateur clique sur le bouton "Supprimer" 
  Et que l'événement est supprimé
  Alors un message apparait "L'événement a été supprimé"