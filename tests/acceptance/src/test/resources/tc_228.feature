# language: fr
Fonctionnalité: TEST_CONS_EVENT_VALID
  #il n'y a pas de liste des événements sur la page de gestion des utilisateurs
  Scénario: Test de la liste des utilisateurs invalide
    Etant donné la session courante est celle d'un administrateur
    Quand l'administrateur est sur la page de gestion des evenements
    Et le tableau de la liste des événement est complet ou que les informations sont valides
    Alors l'administrateur peut gérer les événements