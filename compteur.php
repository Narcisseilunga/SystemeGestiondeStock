<?php
// Chemin vers le fichier de compteur
$counterFile = 'compteur.txt';

if (isset($_SESSION['nom_entreprise'])) {

    $nom_entreprise = $_SESSION['nom_entreprise'];

    $counterFile =  'compteur'.$nom_entreprise.'.txt';
    
    if (!file_exists($counterFile)) {
        file_put_contents($counterFile, '0');
    }
    
    $counter = file_get_contents($counterFile);
    
    $counter++;
    
    file_put_contents($counterFile, $counter);
    echo $counter;
    echo $counterFile;
    exit;
    
}else {
    if (!file_exists($counterFile)) {
        file_put_contents($counterFile, '0');
    }
    
    $counter = file_get_contents($counterFile);
    
    $counter++;
    
    file_put_contents($counterFile, $counter);
    exit;


}


?>