<?php
session_start();
if (isset( $_SESSION['inscription_nom_entreprise'])) {
    $nom_entreprise = $_SESSION['inscription_nom_entreprise'];
}else {
    $nom_entreprise = "WISDOM SAAS";
}
try {
    $db = new PDO('mysql:host=localhost;dbname=system_vente;charset=utf8', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}

// Retrieve form data
$prenom = $_POST['prenom'];
$nom = $_POST['nom'];
$postnom = $_POST['postnom'];
//$genre = $_POST['genre'];
$telephone = $_POST['telephone'];
$email = $_POST['email'];
$motdepasse = $_POST['motdepasse'];
$temp = date("Y-m-d H:i:s");
$type = "client";


$sql = "INSERT INTO compte (prenom, nom, postnom,  contact, email, password, temp, nom_entreprise, type) 
        VALUES (:prenom, :nom, :postnom, :telephone, :email, :motdepasse, :temp, :nom_entreprise, :type)";
$stmt = $db->prepare($sql);

// Bind parameters and execute the statement
$stmt->bindParam(':prenom', $prenom);
$stmt->bindParam(':nom', $nom);
$stmt->bindParam(':postnom', $postnom);
//$stmt->bindParam(':genre', $genre);
$stmt->bindParam(':telephone', $telephone);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':motdepasse', $motdepasse);
$stmt->bindParam(':temp', $temp);
$stmt->bindParam(':nom_entreprise', $nom_entreprise);
$stmt->bindParam(':type', $type);

if ($stmt->execute()) {
    echo 'Données insérées avec succès dans la table compte.';
    header("Location : connexion.php");
} else {
    echo 'Erreur lors de l\'insertion des données.';
}
?>