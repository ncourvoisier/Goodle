# language: fr
Fonctionnalité: TEST_DEL_USER_VALID
  
Scénario: Test de la suppréssion d'un utilisateur valide
  Etant donné que la session courante est celle de l'administrateur
  Quand l'administrateur est sur le profil d'un utilisateur
  Et que l'administrateur clique sur le bouton "Supprimer" 
  Et que l'utilisateur, ses événements ou ses votes ne sont pas supprimés
  Alors un message apparait "La suppression a bien été effectuée"