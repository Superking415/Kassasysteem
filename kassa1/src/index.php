<?php

namespace Acme;

use Acme\model\TafelModel;

require "../vendor/autoload.php";

?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>tafels</title>
    <link rel="stylesheet" type="text/css" href="style/indexstyle.css">
</head>
<body>
    <h2>Welkom! Kies uw tafel</h2>

    <?php
        // Instantie van TafelModel om tafelgegevens op te halen uit de databassseeee
        $tafelModel = new TafelModel();
        // Alle tafels ophalen
        $alleTafels = $tafelModel->getAllTafels();

        // De Tafels weergeven
        foreach ($alleTafels as $tafel) {
            $idTafel = $tafel['idtafel'];
            $omschrijving = $tafel['omschrijving'];
            echo "<h3 class='tafelnr'><a href='keuze.php?idtafel={$idTafel}'>Tafel Nummer: {$omschrijving}</a></h3>";
        }
    ?>

</body>
</html>
