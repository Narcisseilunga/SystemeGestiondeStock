<?php
session_start();
$nom_entreprise = $_SESSION['nom_entreprise'];

// Connexion à la base de données MySQL via PDO
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
$db = $con;

// Récupération de l'ID dans le lien
$id = $_GET['id'];

// Requête pour afficher les informations d'un employé
$stmt = $db->prepare("SELECT * FROM employee WHERE id = :id AND nom_entreprise = :nom_entreprise");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->bindParam(':nom_entreprise', $nom_entreprise, PDO::PARAM_STR);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier que le bouton "Modifier" a été cliqué
if (isset($_POST['button'])) {
    // Extraction des informations envoyées
    $name = $_POST['nom'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $gender = $_POST['gender'];
    $doj = $_POST['doj'];
    $dob = $_POST['dob'];
    $password = $_POST['password'];
    $type = $_POST['type'];
    $address = $_POST['address'];
    $salary = $_POST['salary'];
    $statut_emp = $_POST['statut_emp'];
    $info_emp = $_POST['info_emp'];
    
    // Vérifiez si un fichier d'image a été téléchargé
    $image_emp = null;
    if (isset($_FILES['image_emp']) && $_FILES['image_emp']['error'] === UPLOAD_ERR_OK) {
        $image_emp = $_FILES['image_emp']['name'];
        $image_emp_tmp_name = $_FILES['image_emp']['tmp_name'];
        $image_emp_folder = '../uploaded_img/' . basename($image_emp);
        move_uploaded_file($image_emp_tmp_name, $image_emp_folder); // Déplacez le fichier téléchargé
    } else {
        // Si aucune image n'est téléchargée, utilisez une valeur par défaut ou laissez la colonne inchangée
        $image_emp = $row['image_emp']; // Gardez l'ancienne image
    }

    // Vérifier que tous les champs ont été remplis
    if ($name && $contact && $gender) {
        // Requête de modification pour la table employee
        $stmt = $db->prepare("UPDATE employee SET name = :name, email = :email, contact = :contact, gender = :gender, doj = :doj, dob = :dob, password = :password, type = :type, address = :address, salary = :salary, statut_emp = :statut_emp, info_emp = :info_emp, image_emp = :image_emp WHERE id = :id AND nom_entreprise = :nom_entreprise");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':contact', $contact, PDO::PARAM_STR);
        $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
        $stmt->bindParam(':doj', $doj, PDO::PARAM_STR);
        $stmt->bindParam(':dob', $dob, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        $stmt->bindParam(':address', $address, PDO::PARAM_STR);
        $stmt->bindParam(':salary', $salary, PDO::PARAM_STR);
        $stmt->bindParam(':statut_emp', $statut_emp, PDO::PARAM_STR);
        $stmt->bindParam(':info_emp', $info_emp, PDO::PARAM_STR);
        $stmt->bindParam(':image_emp', $image_emp, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nom_entreprise', $nom_entreprise, PDO::PARAM_STR);
        $result = $stmt->execute();

        if ($result) {
            // Mettre à jour la table users
            $stmtUser = $db->prepare("SELECT * FROM users WHERE email = :email");
            $stmtUser->bindParam(':email', $email, PDO::PARAM_STR);
            $stmtUser->execute();
            $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Si l'utilisateur existe, mettre à jour
                $password = password_hash($password, PASSWORD_DEFAULT);
                $stmtUpdateUser = $db->prepare("UPDATE users SET name = :name, img = :img, password = :password WHERE email = :email");
                $stmtUpdateUser->bindParam(':name', $name, PDO::PARAM_STR);
                $stmtUpdateUser->bindParam(':img', $image_emp, PDO::PARAM_STR); 
                $stmtUpdateUser->bindParam(':password', $password, PDO::PARAM_STR);
                $stmtUpdateUser->bindParam(':email', $email, PDO::PARAM_STR);
                $stmtUpdateUser->execute();
            } else {
                $password = password_hash($password, PASSWORD_DEFAULT);
                // Si l'utilisateur n'existe pas, insérer
                $stmtInsertUser = $db->prepare("INSERT INTO users (name, email, img, password, etat) VALUES (:name, :email, :img, :password, 0)");
                $stmtInsertUser->bindParam(':name', $name, PDO::PARAM_STR);
                $stmtInsertUser->bindParam(':email', $email, PDO::PARAM_STR);
                $stmtInsertUser->bindParam(':img', $image_emp, PDO::PARAM_STR);
                $stmtInsertUser->bindParam(':password', $password, PDO::PARAM_STR);
                $stmtInsertUser->execute();
            }

            // Redirection vers la page employe.php
            header("location: employe.php");
            exit();
        } else {
            $message = "Employé non modifié";
        }
    } else {
        $message = "Veuillez remplir tous les champs !";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Employé</title>
    <link rel="stylesheet" href="style.css">
</head>
<body><br>
    <div class="form">
        <a href="employe.php" class="back_btn"><img src="images/back.png"> Retour</a>
        <h2>Modifier L'EMPLOYE : <?=$row['name']?> </h2>
        <p class="erreur_message">
            <?php 
            if(isset($message)){
                echo $message;
            }
            ?>
        </p><br>
        <form action="" method="POST" enctype="multipart/form-data">
            <label>Nom</label>
            <input type="text" name="nom" value="<?=$row['name']?>" required>
            <label>Email</label>
            <input type="text" name="email" value="<?=$row['email']?>" required>
            <label>Genre</label>
            <input type="text" name="gender" value="<?=$row['gender']?>" required>
            <label>Contact</label>
            <input type="text" name="contact" value="<?=$row['contact']?>" required>
            <label>Date d'Adhesion</label>
            <input type="text" name="doj" value="<?=$row['doj']?>" required>
            <label>Date de Naissance</label>
            <input type="text" name="dob" value="<?=$row['dob']?>">
            <label>Mot de passe</label>
            <input type="password" name="password" value="<?=$row['password']?>" required>
            <label>Type</label>
            <input type="text" name="type" value="<?=$row['type']?>" required>
            <label>Adresse</label>
            <input type="text" name="address" value="<?=$row['address']?>" required>
            <label>Salaire</label>
            <input type="text" name="salary" value="<?=$row['salary']?>" required>
            <label>Statut</label>
            <input type="text" name="statut_emp" value="<?=$row['statut_emp']?>" required>
            <label>Fonction</label>
            <input type="text" name="info_emp" value="<?=$row['info_emp']?>" required>
            <label>Ajoutez une image</label>
            <input type="file" accept="image/png, image/jpeg, image/jpg" name="image_emp">
            <input type="submit" value="Modifier" name="button">
        </form>
    </div>
</body>
</html>