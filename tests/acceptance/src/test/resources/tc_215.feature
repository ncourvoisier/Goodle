# language: fr
Fonctionnalité: TEST_CONS_USER_VALID
  
  Scénario: Test de la liste des utilisateurs invalide
    Etant donné que la session courante est celle de l'administrateur
    Quand l'administrateur est sur la page de gestion des utilisateurs
    Et que le tableau de la liste des utilisateurs est complet ou que les informations sont valides
    Alors l'administrateur peut gérer les utilisateurs