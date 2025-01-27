<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Fournisseur</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
session_start();
$nom_entreprise = $_SESSION['nom_entreprise'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? null;
    $contact = $_POST['contact'] ?? null;
    $desc = $_POST['desc'] ?? null;

    if ($nom && $contact && $desc) {
        $host = 'localhost';
        $dbname = 'system_vente';
        $username = 'root';
        $password = '';

        try {
            $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Préparation de la requête pour insérer le fournisseur
            $stmt = $db->prepare("INSERT INTO supplier (name, contact, description, nom_entreprise) VALUES (:name, :contact, :description, :nom_entreprise)");
            $stmt->bindParam(':name', $nom);
            $stmt->bindParam(':contact', $contact);
            $stmt->bindParam(':description', $desc);
            $stmt->bindParam(':nom_entreprise', $nom_entreprise); // Lier le nom de l'entreprise

            if ($stmt->execute()) {
                header("Location: fournisseur.php");
                exit;
            } else {
                $message = "Erreur lors de l'ajout du fournisseur.";
            }
        } catch (PDOException $e) {
            $message = 'Erreur de connexion : ' . $e->getMessage();
        }
    } else {
        $message = "Veuillez remplir tous les champs !";
    }
}
?>
    <div class="form">
        <a href="fournisseur.php" class="back_btn"><img src="images/back.png" alt="Retour"> Retour</a>
        <h2>Ajouter un Fournisseur</h2>
        <p class="erreur_message">
            <?php if (isset($message)) echo htmlspecialchars($message); ?>
        </p>
        <form action="" method="POST">
            <label for="nom">Nom</label>
            <input type="text" name="nom" id="nom" required>
            <label for="contact">Contact</label>
            <input type="text" name="contact" id="contact" required>
            <label for="desc">Description</label>
            <input type="text" name="desc" id="desc" required>
            <input type="submit" value="Ajouter" name="button">
        </form>
    </div>
</body>
</html>