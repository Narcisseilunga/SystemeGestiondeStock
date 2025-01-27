<?php

// Connexion à la base de données MySQL
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

// Vérifiez si l'ID de la commande est passé dans l'URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sécuriser l'ID

    // Vérifiez si le formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer la nouvelle validation
        $validation = $_POST['validation'];
        
        // Mettre à jour la validation dans la base de données
        $query = "UPDATE commandes SET validation = :validation WHERE id = :id";
        $stmt = $con->prepare($query);
        $stmt->bindValue(':validation', $validation);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT); // Spécifier le type comme entier
        
        if ($stmt->execute()) {
            echo "<p>La commande a été mise à jour avec succès.</p>";
        } else {
            echo "<p>Erreur lors de la mise à jour de la commande.</p>";
        }
    }

    // Récupérer la validation actuelle pour l'afficher
    $query = "SELECT validation FROM commandes WHERE id = :id";
    $stmt = $con->prepare($query);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT); // Spécifier le type comme entier
    $stmt->execute();
    $current_validation = $stmt->fetchColumn(); // Récupérer la première colonne du résultat
    
    // Afficher le formulaire de validation
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Validation</title>
        <style>
            /* Style général du formulaire */
body {
    font-family: 'Inter', sans-serif;
    background-color: #f9fafb;
    color: #374151;
    padding: 20px;
}

/* Titre */
h2 {
    color: #1f2937;
    margin-bottom: 20px;
}

/* Formulaire */
form {
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
}

/* Étiquettes */
label {
    font-weight: 600;
    margin-bottom: 8px;
    display: inline-block;
}

/* Sélecteur */
select {
    width: 100%;
    padding: 8px;
    margin-bottom: 20px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
}

/* Bouton */
input[type="submit"] {
    background-color: #4f46e5;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s;
}

input[type="submit"]:hover {
    background-color: #434190;
}

/* Lien de retour */
a {
    display: inline-block;
    margin-top: 10px;
    color: #4f46e5;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}
        </style>
    </head>
    <body>
    <h2>Modifier la validation de la commande</h2>
    <form method="POST">
        <label for="validation">Statut de validation :</label>
        <select name="validation" id="validation">
            <option value="OUI" <?php if ($current_validation === "OUI") echo 'selected'; ?>>OUI</option>
            <option value="NON" <?php if ($current_validation === "NON") echo 'selected'; ?>>NON</option>
        </select>
        <br><br>
        <input type="submit" value="Mettre à jour">
    </form>
    <a href="index.php">Retour à la liste des commandes</a>
    <?php
} else {
    echo "<p>ID de commande non spécifié.</p>";
}
?>
    </body>
    </html>