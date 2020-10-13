# language: fr
Fonctionnalité: TEST_CONS_EVENT_INVALID
  
  Scénario: Test de la liste des utilisateurs invalide
    Etant donné que la session courante est celle de l'administrateur
    Quand l'administrateur est sur la page de gestion des utilisateurs
    Et que le tableau de la liste des événement n'est pas complet ou que les informations ne sont pas valides
    Alors un message d'erreur apparait "Une erreur s'est produite lors de l'affichage des événements"