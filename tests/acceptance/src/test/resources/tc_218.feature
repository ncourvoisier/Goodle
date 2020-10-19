# language: fr
Fonctionnalité: TEST_DEL_USER_INVALID
  
Scénario: Test de la suppréssion d'un utilisateur valide
  Etant donné la session courante est celle de l'administrateur
  Quand l'administrateur est sur le profil d'un utilisateur
  Et l'administrateur clique sur le bouton "Supprimer"
  Et l'utilisateur, ses événements ou ses votes ne sont pas supprimés
  Alors un message d'erreur apparait "Une erreur s'est produite lors de la suppression du compte"