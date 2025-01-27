<?php

session_start();
$nom_entreprise = $_SESSION['nom_entreprise'];

$host = 'localhost'; // Remplacez par votre hôte MySQL
$dbname = 'system_vente'; // Remplacez par le nom de votre base de données
$username = 'root'; // Remplacez par votre nom d'utilisateur MySQL
$password = ''; // Remplacez par votre mot de passe MySQL

try {
    $con = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erreur de connexion : ' . $e->getMessage();
    exit;
}
$db = $con;

$id = $_GET['id'];

// Préparer la requête pour supprimer la catégorie
$stmt = $db->prepare('DELETE FROM category WHERE id = :id AND nom_entreprise = :nom_entreprise');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->bindValue(':nom_entreprise', $nom_entreprise, PDO::PARAM_STR);

if ($stmt->execute()) {
    header("Location: category.php");
    exit();
} else {
    echo "Erreur lors de la suppression de la catégorie.";
}
?>