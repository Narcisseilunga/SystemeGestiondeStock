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
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require "../db.php";
    $db = $conn;

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    // Extract and sanitize POST data
    $nom_entreprise = $_SESSION['nom_entreprise'];
    $nom = $db->real_escape_string(trim($_POST['nom']));
    $email = $db->real_escape_string(trim($_POST['email']));
    $gender = $db->real_escape_string(trim($_POST['gender']));
    $contact = $db->real_escape_string(trim($_POST['contact']));
    $doj = $db->real_escape_string(trim($_POST['doj']));
    $dob = $db->real_escape_string(trim($_POST['dob']));
    $address = $db->real_escape_string(trim($_POST['address']));
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
    $type = $db->real_escape_string(trim($_POST['type']));
    $salary = $db->real_escape_string(trim($_POST['salary']));
    $statut_emp = $db->real_escape_string(trim($_POST['statut_emp']));
    $info_emp = $db->real_escape_string(trim($_POST['info_emp']));
    $image_emp = $_FILES['image_emp']['name'];
    $image_emp_tmp_name = $_FILES['image_emp']['tmp_name'];
    $image_emp_folder = '../uploaded_img/' . basename($image_emp);

    // Validate required fields
    if (empty($nom) || empty($email) || empty($contact)) {
        $message = 'Please fill out all required fields.';
    } else {
        // Prepare SQL statement for employee
        $stmt = $db->prepare("INSERT INTO employee (name, email, gender, contact, doj, dob, password, type, address, salary, statut_emp, image_emp, info_emp, nom_entreprise) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssssssss", $nom, $email, $gender, $contact, $doj, $dob, $password, $type, $address, $salary, $statut_emp, $image_emp, $info_emp, $nom_entreprise);

        if ($stmt->execute()) {
            // Move uploaded file
            if (move_uploaded_file($image_emp_tmp_name, $image_emp_folder)) {
                // Prepare SQL statement for user
                $password = password_hash($password, PASSWORD_DEFAULT);
                $stmtUser = $db->prepare("INSERT INTO `users` (`name`, `email`, `img`, `password`, `etat`) VALUES (?, ?, ?, ?, 0)");
                $stmtUser->bind_param("ssss", $nom, $email, $image_emp, $password);
                
                if ($stmtUser->execute()) {
                    header("Location: employe.php");
                    exit();
                } else {
                    $message = "Failed to add user.";
                }

                $stmtUser->close();
            } else {
                $message = "Failed to upload image.";
            }
        } else {
            $message = "Failed to add employee.";
        }

        $stmt->close();
    }

    $db->close();
}
?>
    <div class="form">
        <a href="employe.php" class="back_btn"><img src="images/back.png" alt="Back"> Retour</a>
        <h2>Ajouter un Employé</h2>
        <p class="erreur_message">
            <?php if (isset($message)) echo $message; ?>
        </p>
        <form action="" method="POST" enctype="multipart/form-data">
            <label>Nom</label>
            <input type="text" name="nom" required>
            <label>Email</label>
            <input type="email" name="email" required>
            <label>Genre</label>
            <input type="text" name="gender">
            <label>Contact</label>
            <input type="text" name="contact" required>
            <label>Date de Naissance</label>
            <input type="date" name="dob">
            <label>Date d'adhesion</label>
            <input type="date" name="doj">
            <label>Mot de Passe</label>
            <input type="password" name="password" required>
            <label>Type</label>
            <select name="type" required>
                <option value="">Sélectionnez le Type</option>
                <option value="Admin">Admin</option>
                <option value="Employée">Employée</option>
                <option value="Autres">Autres</option>
                <?php if ($_SESSION['type']=="Super_Admin") {?>
                    <option value="Super_Admin">Super Admin</option>
                <?php } ?>
            </select>
            <label>Adresse</label>
            <input type="text" name="address">
            <label>Salaire</label>
            <input type="number" name="salary" step="0.01">
            <label>Statut</label>
            <input type="text" name="statut_emp">
            <label>Ajoutez une image</label>
            <input type="file" accept="image/png, image/jpeg, image/jpg" name="image_emp">
            <label>Info Employé</label>
            <input type="text" name="info_emp"><br>
            <input type="submit" value="Ajouter" name="button">
            
        </form>
    </div>
</body>
</html>