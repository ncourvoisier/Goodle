<?php
ob_start('ob_gzhandler'); //démarre la bufferisation, compression du tampon si le client supporte gzip
session_start();

require_once 'bibli_generale.php';

function contenu(){
    if (! isset($_SESSION['cliID'])){ //Si non connecté, on demande à l'utilisateur de se connecter

            echo '<p>Vous n\'êtes pas connecté<p>',
            '<a id="lien_connect" href="./login.php" title="Se Connecter">Connection</a>';

        } else {
            echo '<p>Vous êtes connecté</p>';

            echo '<div id="ajout_evenement">';

            echo '<form method="POST">';

            echo '<span>Nom de l\'évènement : </span>';

            echo form_input('text', 'NomEvent', '', 60);

            echo '<br><span>Lieu de l\'évènement : </span>';

            echo form_input('text', 'Lieu', '', 60);

            echo '<br><span>Entrez une première date pour l\'évènement :</span>';

            echo form_input('date', 'DateEvent', '', );

            echo '<br><span>Entrez la date de clotûre : </span>';

            echo form_input('date', 'DateCloture', '', );

            echo '<br>';

            echo form_input('submit', 'Valider', 'Valider', );

            echo '</form>';

            echo '</div>';
        }
}


//MAIN
contenu();


?>