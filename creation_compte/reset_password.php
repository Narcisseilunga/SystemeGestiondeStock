<?php
// Connexion à la base de données SQLite3
$database = 'sqlite:C:/Users/EMM/Documents/Application-de-Gestion-de-Stock/Stock_Management_System/system.db';

$conn = new PDO('sqlite:'.$database);

// Vérifier la connexion
if (!$conn) {
    die("Erreur de connexion à la base de données: " . $conn->lastErrorMsg());
}

// Récupérer les données du formulaire
$email = $_POST['email'];
// Vérifier si les mots de passe correspondent
if ($new_password !== $confirm_password) {
    echo "Les nouveaux mots de passe ne correspondent pas.";
    exit;
}

// Générer un code de réinitialisation de 6 caractères
$reset_code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);

// Requête SQL pour récupérer les informations de l'employé
$sql = "SELECT * FROM compte WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // L'utilisateur existe dans la base de données
    
    // Envoyer un e-mail de confirmation avec le code de réinitialisation
    $to = $email;
    $subject = "Réinitialisation de votre mot de passe";
    $message = "Vous avez demandé à réinitialiser votre mot de passe. Voici votre code de réinitialisation : $reset_code";
    $headers = "From: noreply@votre-site.com";
    
    if (mail($to, $subject, $message, $headers)) {
        echo "Un e-mail de confirmation avec votre code de réinitialisation a été envoyé à votre adresse.";
    } else {
        echo "Une erreur s'est produite lors de l'envoi de l'e-mail.";
    }
    
    // Mettre à jour le mot de passe dans la base de données
    $sql = "UPDATE compte SET password = '$reset_code' WHERE email = '$email'";
    if ($conn->exec($sql)) {
        echo "Mot de passe réinitialisé avec succès.";
        header("mot_de_passe_oublier.php");
    } else {
        echo "Erreur lors de la mise à jour du mot de passe: " . $conn->lastErrorMsg();
    }
} else {
    // L'utilisateur n'existe pas dans la base de données
    echo "Adresse email ou ancien mot de passe incorrect.";
}

$conn->close();
?>