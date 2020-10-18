# language: fr
Fonctionnalité: Je vote oui

  Scénario: je vote oui
    Etant donné je suis sur connecté
    Quand la date de cloture des vote n'est pas dépassé
    Et l'utilisateur vote "Oui" sur une des proposition du sondage
    Alors le site renvoie "Votre vote oui a bien ete prit en compte"
    Et le vote est validé

