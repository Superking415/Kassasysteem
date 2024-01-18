<?php
namespace Acme;

use Acme\model\ProductModel;

require "../vendor/autoload.php";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="style/indexstyle.css">
    <title>Product</title>
</head>
<body>
    <!-- Formulier voor het toevoegen van producten aan de bestelling -->
    <form action="bestellingdoorvoeren.php" method="post">

        <?php
        // Controleer of idtafel is ingesteld in de GET-parameters
        $idTafel = $_GET['idtafel'] ?? false;

        if ($idTafel) {
            // Verborgen invoerveld om idtafel door te geven
            echo "<input type='hidden' name='idtafel' value='$idTafel'>";

            // Haal producten op uit de database
            $productModel = new ProductModel();
            $products = $productModel->getProducts();

            // Loop door elk product om een checkbox en invoerveld weer te geven
            foreach ($products as $product) {
                $idproduct = $product->getColumnValue('idproduct');
                $naam = $product->getColumnValue('naam');
                echo "<div>";
                echo "<label><input type='checkbox' name='products[]' value='{$idproduct}'> {$naam} </label>";
                echo "<label>Aantal: <input type='number' name='product{$idproduct}'></label>";
                echo "</div>";
            }

            // Knop voor het voltooien van de bestelling
            echo "<button>Volgende</button>";

        } else {
            // terugggggg als niet werkt
            http_response_code(404);
            include('error_404.php');
            die();
        }
        ?>
    </form>
</body>
</html>
