<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Catégorie</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = $_POST['nom'] ?? null;

        if ($nom) {
            $host = 'localhost';
            $dbname = 'system_vente';   
            $username = 'root';
            $password = '';

            try {
                $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt = $db->prepare("INSERT INTO entreprise (name) VALUES (:name)");
                $stmt->bindParam(':name', $nom);

                if ($stmt->execute()) {
                    $counterFile =  'compteur'. $nom.'.txt';
    
                    if (!file_exists($counterFile)) {
                        file_put_contents($counterFile, '0');
                    }
                
                    $counter = file_get_contents($counterFile);

                    header("Location: entreprises.php");
                    exit;
                } else {
                    $message = "Catégorie non ajoutée.";
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
        <a href="entreprises.php" class="back_btn"><img src="images/back.png" alt="Retour"> Retour</a>
        <h2>Ajouter une Entreprises</h2>
        <p class="erreur_message">
            <?php if (isset($message)) echo htmlspecialchars($message); ?>
        </p>
        <form action="" method="POST">
            <label for="nom">Nom</label>
            <input type="text" name="nom" id="nom" required>
            <input type="submit" value="Ajouter" name="button">
        </form>
    </div>
</body>
</html>