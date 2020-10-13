# language: fr
Fonctionnalité: Test modification vote participant
  Scénario: Modification de vote par un participant après la date de l'évenement passée
Etant donné le participant se rend sur la page de l'événement
Quand la date de l'évenement choisit est dépassé
Alors le participant ne peut plus modifier les votes.