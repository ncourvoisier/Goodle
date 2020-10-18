# language: fr
Fonctionnalité: Je vote peut-être

  Scénario: je vote peut-être
    Etant donné je suis sur connecté
    Quand la date de cloture des vote n'est pas dépassé
    Et l'utilisateur vote "Peut-être" sur une des proposition du sondage
    Alors le site renvoie "Votre vote peut-être a bien ete prit en compte"
    Et le vote est validé

