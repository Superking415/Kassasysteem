<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style/indexstyle.css">
    <title>Rekening</title>
</head>
<body>

<?php
use Acme\classes\Bestelling;
require "../vendor/autoload.php";

// Controleer of idtafel is ingesteld in de GET-parameters
$idTafel = $_GET['idtafel'] ?? false;

if ($idTafel) {
    try {
        // de enige echte waanzinnige verbinding met de database
        $pdo = new PDO('mysql:host=localhost;dbname=kassasysteem', 'Noah3', '1234');
        $bestelling = new Bestelling($idTafel, $pdo);

        // Haal de besteldetails op voor producten die nog niet zijn betaald
        $stmt = $pdo->prepare("SELECT idproduct FROM product_tafel WHERE idtafel = ? AND betaald = 0");
        $stmt->execute([$idTafel]);
        $orderDetails = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Initialiseer arrays om itemhoeveelheden en prijzen op te slaan
        $itemHoeveelheden = [];
        $itemPrijzen = [];
        $totaalAantal = 0;
        $totaalPrijs = 0.0;

        // Verwerk elk product in de bestelling
        foreach ($orderDetails as $productId) {
            $productDetails = $bestelling->fetchProductDetails($productId);

            // Als productdetails beschikbaar zijn, verwerk ze dan
            if ($productDetails !== false) {
                $productNaam = $productDetails['naam'];
                $numeriekePrijs = str_replace(',', '.', $productDetails['prijs']);
                $itemPrijs = floatval($numeriekePrijs);

                // Werk itemhoeveelheden en prijzen bij
                if (!isset($itemHoeveelheden[$productNaam])) {
                    $itemHoeveelheden[$productNaam] = 1;
                    $itemPrijzen[$productNaam] = $itemPrijs;
                } else {
                    $itemHoeveelheden[$productNaam]++;
                    $itemPrijzen[$productNaam] += $itemPrijs;
                }

                // Werk totaal aantal en prijs bij
                $totaalAantal++;
                $totaalPrijs += $itemPrijs;
            } else {
                echo "<p>Productdetails niet beschikbaar voor ID: {$productId}</p>";
            }
        }

        // Toon itemdetails
        foreach ($itemHoeveelheden as $productNaam => $hoeveelheid) {
            echo "<p>{$productNaam} (X: {$hoeveelheid}) - {$itemPrijzen[$productNaam]}</p>";
        }

        // Toon totaal aantal en totale prijs
        echo "<p>Totale items: {$totaalAantal}</p>";
        echo "<p>Totale prijs: {$totaalPrijs}</p>";

        // Toon een formulier voor bevestiging van betaling
        echo "<form action='process_payment.php' method='post'>";
        echo "<input type='hidden' name='idtafel' value='{$idTafel}'>";
        echo "<a href='keuze.php?idtafel={$idTafel}'>Terug</a>";
        echo "<input type='submit' name='confirmPayment' value='Bevestig Betaling'>";
        echo "</form>";

    } catch (\Exception $e) {
        echo "Fout bij ophalen van besteldetails: " . $e->getMessage();
        die();
    }
} else {
    // foutje? NOAH GA DAN TERUGGGG
    http_response_code(404);
    include('error_404.php');
    die();
}
?>

</body>
</html>
