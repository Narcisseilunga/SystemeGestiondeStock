<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Employés</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <a href="ajouter_employe.php" class="Btn_add"> <img src="images/plus.png"> Ajouter</a>
        
        <table>
            <tr id="items">
                <th>ID</th>
                <th>Image</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Genre</th>
                <th>Contact</th>
                <th>date de Naissance</th>
                <th>Date d'adhésion</th>
                <th>Mot de Passe</th>
                <th>Type</th>
                <th>Adresse</th>
                <th>Salaire</th>
                <th>Statut</th>
                <th>Fonction</th>
                <th>Modifier</th>
                <th>Supprimer</th>
            </tr>
            <?php
             
$nom_entreprise = $_SESSION['nom_entreprise'];

// Inclure la page de connexion
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

$db = $con;

// Requête pour afficher la liste des employés filtrés par nom d'entreprise
$sql = "SELECT * FROM employee WHERE nom_entreprise = :nom_entreprise";
$stmt = $db->prepare($sql);
$stmt->bindParam(':nom_entreprise', $nom_entreprise);
$stmt->execute();

// Affichons la liste des employés
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td><img class=\"agrandir\" src=\"../uploaded_img/" . $row['image_emp'] . "\"></td>";
    echo "<td>" . $row['name'] . "</td>";
    echo "<td>" . $row['email'] . "</td>";
    echo "<td>" . $row['gender'] . "</td>";
    echo "<td>" . $row['contact'] . "</td>";
    echo "<td>" . $row['dob'] . "</td>";
    echo "<td>" . $row['doj'] . "</td>";
    echo "<td>" . $row['password'] . "</td>";
    echo "<td>" . $row['type'] . "</td>";
    echo "<td>" . $row['address'] . "</td>";
    echo "<td>" . $row['salary'] . "$</td>";
    echo "<td>" . $row['statut_emp'] . "</td>";
    echo "<td>" . $row['info_emp'] . "</td>";
?>
    <td><a href="modifier_employe.php?id=<?=$row['id']?>"><img src="images/pen.png"></a></td>
    <td><a href="supprimer_employe.php?id=<?=$row['id']?>"><img src="images/trash.png"></a></td>
</tr>
<?php 
} 
$db = null; 
?>
        </table>
    </div>
</body>
</html>
<?php
?>