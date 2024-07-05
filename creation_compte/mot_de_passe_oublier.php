<!DOCTYPE html>
<html>
<head>
    <title>Réinitialisation du mot de passe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
        }
        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 400px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #1877f2;
        }
        input[type="password"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #1877f2;
            color: white;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #166fe5;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Réinitialisation du mot de passe</h2>
        <form action="reset_password.php" method="post">
            <input type="password" name="old_password" placeholder="Ancien mot de passe" required>
            <input type="password" name="new_password" placeholder="Nouveau mot de passe" required>
            <input type="password" name="confirm_password" placeholder="Confirmer le nouveau mot de passe" required>
            <input type="submit" value="Réinitialiser le mot de passe">
        </form>
    </div>
</body>
</html>
<?php
// Connexion à la base de données SQLite3
$database = "nom_de_la_base_de_donnees.sqlite";
$conn = new PDO('sqlite:'.$database);

// Vérifier la connexion
if (!$conn) {
    die("Erreur de connexion à la base de données: " . $conn->lastErrorMsg());
}

// Récupérer les données du formulaire
$email = $_POST['email'];
$old_password = $_POST['old_password'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

// Vérifier si les mots de passe correspondent
if ($new_password !== $confirm_password) {
    echo "Les nouveaux mots de passe ne correspondent pas.";
    exit;
}

// Générer un code de réinitialisation de 6 caractères
$reset_code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);

// Requête SQL pour récupérer les informations de l'employé
$sql = "SELECT * FROM employes WHERE email = :email AND password = :old_password";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':email', $email, SQLITE3_TEXT);
$stmt->bindValue(':old_password', $old_password, SQLITE3_TEXT);
$result = $stmt->execute();

if ($result->fetchArray(SQLITE3_ASSOC)) {
    // L'utilisateur existe dans la base de données
    // Mettre à jour le mot de passe dans la base de données
    $sql = "UPDATE employes SET password = :new_password WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':new_password', $new_password, SQLITE3_TEXT);
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    if ($stmt->execute()) {
        echo "Mot de passe réinitialisé avec succès.";
        header("Location: login.php");
    } else {
        echo "Erreur lors de la mise à jour du mot de passe: " . $conn->lastErrorMsg();
    }
} else {
    // L'utilisateur n'existe pas dans la base de données
    echo "Adresse email ou ancien mot de passe incorrect.";
}

$conn->close();
?>