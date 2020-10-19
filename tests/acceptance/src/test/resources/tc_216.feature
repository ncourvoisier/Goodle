# language: fr
Fonctionnalité: TEST_CONS_USER_INVALID
# on ne voit pas comment vérifier ce scénario
  Scénario: Test de la liste des utilisateurs invalide
   Etant donné que la session courante est celle de l'administrateur
    Quand l'administrateur est sur la page de gestion des utilisateurs
    Et le tableau de la liste des utilisateurs n'est pas complet ou que les informations ne sont pas valides
    Alors un message d'erreur apparait "Une erreur s'est produite lors de l'affichage de la liste"