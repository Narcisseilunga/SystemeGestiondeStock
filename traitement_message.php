<?php
// Connexion à la base de données SQLite
$db = new PDO('sqlite:C:/Users/EMM/Documents/Application-de-Gestion-de-Stock/Stock_Management_System/system.db');

// Récupération des données du formulaire
$nom = $_POST['demo-name'];
$email = $_POST['demo-email'];
$numero = $_POST['demo-num'];
$message = $_POST['demo-message'];
$temp = date("Y-m-d H:i:s");
// Insertion des données dans la table "message"
$stmt = $db->prepare("INSERT INTO message (name, email, number, obj, temp) VALUES (:nom, :email, :numero, :message, :temp)");
$stmt->bindParam(':nom', $nom);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':numero', $numero);
$stmt->bindParam(':message', $message);
$stmt->bindParam(':temp', $temp);
$stmt->execute();

// Redirection vers une page de confirmation
header("Location: confirmation.html");
exit();
?>