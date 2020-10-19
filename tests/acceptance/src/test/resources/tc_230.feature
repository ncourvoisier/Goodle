# language: fr
Fonctionnalité: TEST_DEL_EVENT_VALID

Scénario: Test de la suppréssion d'un événement invalide
  Etant donné la session courante est celle de l'administrateur
  Quand l'administrateur est sur la page d'un événement
  Et l'adiministrateur clique sur le bouton "Supprimer"
  Et l'événement est supprimé
  Alors le message apparait "L'événement a été supprimé"