<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php

    // Connexion à la base de données SQLite via PDO
    $host = 'localhost'; // Remplacez par votre hôte MySQL
$dbname = 'system_vente'; // Remplacez par le nom de votre base de données
$username = 'root'; // Remplacez par votre nom d'utilisateur MySQL
$password = ''; // Remplacez par votre mot de passe MySQL

    try {
        $con = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        exit();
    }

    // Récupération de l'ID dans le lien
    $id = $_GET['id'];

    // Requête pour afficher les informations d'un employé
    $stmt = $con->prepare("SELECT * FROM entreprise WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier que le bouton "Modifier" a été cliqué
    if(isset($_POST['button'])){
        // Extraction des informations envoyées dans des variables par la méthode POST
        $nom = $_POST['nom'];
        // Vérifier que tous les champs ont été remplis
        if($nom){
            // Requête de modification
            $stmt = $con->prepare("UPDATE entreprise SET name = :nom WHERE id = :id");
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();

            if($result){
                // Redirection vers la page employe.php
                header("location: entreprises.php");
            } else {
                $message = "Entreprise non modifié";
            }
        } else {
            $message = "Veuillez remplir tous les champs !";
        }
    }
?>

    <div class="form">
        <a href="category.php" class="back_btn"><img src="images/back.png"> Retour</a>
        <h2>Modifier L'enprises : <?php echo $row['name']?> </h2>
        <p class="erreur_message">
           <?php 
              if(isset($message)){
                  echo $message ;
              }
           ?>
        </p>
        <form action="" method="POST">
            <label>Nom</label>
            <input type="text" name="nom" value="<?=$row['name']?>">
            <input type="submit" value="Modifier" name="button">
        </form>
    </div>
</body>
</html>