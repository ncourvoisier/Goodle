# language: fr
Fonctionnalité: Test consultation vote après cloturation des votes
Scénario: Consultation des votes après cloture des votes
Etant donné l'utilisateur se rend sur la page de l'événement
Quand la date de cloturation des votes est dépassé
Et au moins l'une des dates proposée n'est pas dépassée
Alors l'utilisateur peut consulter les informations de l'évenement.