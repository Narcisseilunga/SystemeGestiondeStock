<?php
// Connexion à la base de données SQLite
$database = 'chemin_vers_votre_base_de_donnees.sqlite';

try {
    $conn = new PDO("sqlite:$database");
    // Configurer PDO pour qu'il lance des exceptions en cas d'erreur
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}

// Récupérer les données du formulaire
$email = $_POST['email'];
$password = $_POST['password'];

// Requête SQL pour récupérer les informations de l'employé
$sql = "SELECT * FROM employes WHERE email = :email AND password = :password";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':password', $password);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    // L'utilisateur existe dans la base de données
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $type_employe = $row['type_employe'];

    // Rediriger l'utilisateur en fonction de son type d'employé
    if ($type_employe == 'admin') {
        header("Location: ../Dashboard/index.php");
        exit;
    } elseif ($type_employe == 'client') {
        header("Location: ../panier.php");
        exit;
    } elseif ($type_employe == 'passagers') {
        header("Location: ../transport/index.php");
        exit;
    } else {
        echo "Type d'employé non reconnu.";
    }
} else {
    // L'utilisateur n'existe pas dans la base de données
    echo "Adresse email ou mot de passe incorrect.";
}

$conn = null;
?>