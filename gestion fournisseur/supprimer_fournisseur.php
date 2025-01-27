<?php
session_start(); 

$host = 'localhost'; // Remplacez par votre hôte MySQL
$dbname = 'system_vente'; // Remplacez par le nom de votre base de données
$username = 'root'; // Remplacez par votre nom d'utilisateur MySQL
$password = ''; // Remplacez par votre mot de passe MySQL
$nom_entreprise = $_SESSION['nom_entreprise'];

try {
    $con = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erreur de connexion : ' . $e->getMessage();
    exit;
}

$id = $_GET['id'];

// Préparer la requête pour supprimer le fournisseur
$stmt = $con->prepare('DELETE FROM supplier WHERE invoice = :id AND nom_entreprise = :nom_entreprise');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->bindValue(':nom_entreprise', $nom_entreprise, PDO::PARAM_STR);

if ($stmt->execute()) {
    header("Location: fournisseur.php");
    exit();
} else {
    echo "Erreur lors de la suppression du fournisseur.";
}
?>