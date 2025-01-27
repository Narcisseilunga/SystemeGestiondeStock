<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Fournisseur</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
session_start();
$nom_entreprise = $_SESSION['nom_entreprise'];

$host = 'localhost'; 
$dbname = 'system_vente'; 
$username = 'root'; 
$password = ''; 

try {
    $con = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erreur de connexion : ' . $e->getMessage();
    exit;
}

$id = $_GET['id'];

// Préparer la requête pour récupérer le fournisseur
$stmt = $con->prepare("SELECT * FROM supplier WHERE invoice = :id AND nom_entreprise = :nom_entreprise");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->bindParam(':nom_entreprise', $nom_entreprise, PDO::PARAM_STR);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['button'])) {
    $nom = $_POST['nom'];
    $contact = $_POST['contact'];
    $description = $_POST['desc'];

    if ($nom && $contact && $description) {
        // Préparer la requête pour mettre à jour le fournisseur
        $stmt = $con->prepare("UPDATE supplier SET name = :nom, contact = :contact, `desc` = :desc WHERE invoice = :id AND nom_entreprise = :nom_entreprise");
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':contact', $contact, PDO::PARAM_STR);
        $stmt->bindParam(':desc', $description, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nom_entreprise', $nom_entreprise, PDO::PARAM_STR);
        $result = $stmt->execute();

        if ($result) {
            header("location: fournisseur.php");
            exit();
        } else {
            $message = "Fournisseur non modifié";
        }
    } else {
        $message = "Veuillez remplir tous les champs !";
    }
}
?>

    <div class="form">
        <a href="fournisseur.php" class="back_btn"><img src="images/back.png"> Retour</a>
        <h2>Modifier Le Fournisseur : <?=$row['name']?> </h2>
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
            <label>Contact</label>
            <input type="number" name="contact" value="<?=$row['contact']?>">
            <label>Description</label>
            <input type="text" name="desc" value="<?=$row['desc']?>">
            <input type="submit" value="Modifier" name="button">
        </form>
    </div>
</body>
</html>