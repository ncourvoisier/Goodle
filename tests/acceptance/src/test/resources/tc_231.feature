# language: fr
Fonctionnalité: TEST_DEL_EVENT_INVALID
  
Scénario: Test de la suppréssion d'un événement invalide
  Etant donné que la session courante est celle de srl'administrateur
  Quand l'administrateur est sur la page d'un événement
  Et l'administrateur clique sur le bouton "Supprimer"
  Et l'événement n'est pas supprimé
  Alors un message d'erreur apparait "Une erreur s'est produite lors de la suppression de l'événement"