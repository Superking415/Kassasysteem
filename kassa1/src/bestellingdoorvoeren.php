<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style/indexstyle.css">
    <title>Bestellingen</title>
</head>
<body>

<?php

use Acme\classes\Bestelling;
use Acme\system\Database;

// Laad de autoload.php file om de classes te kunnen gebruiken
require "../vendor/autoload.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idTafel = $_POST['idtafel'] ?? false;

    if ($idTafel) {
        // Set the path to your .env file
        $envPath = '../.env'; 
        $pdo = Database::getInstance($envPath);
        $bestelling = new Bestelling($idTafel, $pdo);

        // Haal de geselecteerde producten op uit het formulier
        $products = $_POST['products'] ?? [];

        // Voeg de geselecteerde producten toe aan de bestelling
        foreach ($products as $productId) {
            $quantity = $_POST['product' . $productId] ?? 1;
            for ($i = 0; $i < $quantity; $i++) {
                $bestelling->addProduct($productId);
            }
        }

        // Sla de bestelling op in de database kassasysteem als dat nu wel lukt
        $bestelling->saveBestelling();

        // Terug naar de index
        header("Location: index.php");
        exit();
    } else {
        http_response_code(404);
        include('error_404.php');
        die();
    }
} else {
    http_response_code(405);
    echo "<h2>Invalid request method</h2>";
    die();
}

?>
    
</body>
</html>
