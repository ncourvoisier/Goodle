# language: fr
Fonctionnalité: Revalidation d'une autre date de l'évenement
Scénario: Nouveau choix de la date après cloture des votes
Etant donné le créateur se rend sur la page de l'événement
Quand la date de cloturation des votes est dépassé
Quand au moins l'une des dates proposée n'est pas dépassée
Quand une date a déjà été choisit pour l'évenement
Alors le créateur peut choisir l'une des dates entre l'instant T et la date la plus lointaine proposée pour définir une nouvelle date pour l'événement