# language: fr
Fonctionnalité: TEST_CONS_USER_VALID
  
  Scénario: Test de la liste des utilisateurs invalide
    Etant donné la session courante est celle de l'administrateur
    Quand l'administrateur est sur la page de gestion des utilisateurs
    Et le tableau de la liste des utilisateurs est complet ou que les informations sont valides
    Alors l'administrateur peut gérer les utilisateurs