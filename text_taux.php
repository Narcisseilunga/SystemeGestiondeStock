<?php
// URL de l'API des taux de change
$url = 'https://cdn.taux.live/api/latest.json';

// Récupération des données depuis l'API
$response = file_get_contents($url);
if ($response === FALSE) {
    die('Erreur lors de la récupération des données.');
}

// Décoder la réponse JSON
$data = json_decode($response, true);

// Vérifiez si la réponse contient des données
if (isset($data['rates']['CDF'])) {
    // Récupérer le taux de change en CDF
    $usdToCdf = $data['rates']['CDF'];
    echo $usdToCdf;
    $money =  2500.00 / $usdToCdf;
    echo "\n 10 Dollar Américain (USD) équivaut à " . number_format($usdToCdf*10, 2) . " Francs Congolais (CDF)\n";
    echo "2500Fc équivaut à ". number_format($money,2)  ." Dollar Américain (USD) ";

} else {
    echo "Taux de change en Franc Congolais (CDF) non disponible.";
}
?>