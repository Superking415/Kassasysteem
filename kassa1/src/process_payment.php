<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Betaling</title>
    <link rel="stylesheet" type="text/css" href="style/indexstyle.css">
</head>
<body>
    <?php

    use Acme\classes\Bestelling;
    require "../vendor/autoload.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $idTafel = $_POST['idtafel'] ?? false;
        if ($idTafel) {
            try {
                $pdo = new PDO('mysql:host=localhost;dbname=kassasysteem', 'Noah3', '1234');
                $bestelling = new Bestelling($idTafel, $pdo);

                // Update betaald column to 1 for the given idtafel
                $stmt = $pdo->prepare("UPDATE product_tafel SET betaald = 1 WHERE idtafel = ?");
                $stmt->execute([$idTafel]);

                echo "<h2>Betaling is gelukt! Bedankt en tot ziens!</h2>";
                echo "<a href='index.php'><button>Terug naar Homepagina</button></a>";
            } catch (\Exception $e) {
                echo "<h2>Error processing payment: " . $e->getMessage() . "</h2>";
            }
        } else {
            http_response_code(404);
            include('error_404.php');
            die();
        }
    } else {
        http_response_code(405);
        echo "<h2>Invalid request method</h2>";
    }
    ?>
</body>
</html>

