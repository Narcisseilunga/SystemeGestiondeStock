<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Fournisseurs</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <a href="ajouter_fournisseur.php" class="Btn_add"> <img src="images/plus.png"> Ajouter</a>
        
        <table>
            <tr id="items">
                <th>Nom</th>
                <th>Contact</th>
                <th>Description</th>
                <th>Modifier</th>
                <th>Supprimer</th>
            </tr>
            <?php 
                session_start(); 
                $nom_entreprise = $_SESSION['nom_entreprise'];
                $host = 'localhost';
                $dbname = 'system_vente';
                $username = 'root'; 
                $password = ''; 
                
                try {
                    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (PDOException $e) {
                    echo 'Erreur de connexion : ' . $e->getMessage();
                    exit;
                }

                // Requête pour récupérer les fournisseurs associés à l'entreprise
                $sql = "SELECT invoice, name, contact, `desc` FROM supplier WHERE nom_entreprise = :nom_entreprise";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':nom_entreprise', $nom_entreprise, PDO::PARAM_STR);
                $stmt->execute();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['contact']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['desc']) . "</td>";
            ?>
            <td><a href="modifier_fournisseur.php?id=<?=$row['invoice']?>"><img src="images/pen.png" alt="Modifier"></a></td>
            <td><a href="supprimer_fournisseur.php?id=<?=$row['invoice']?>"><img src="images/trash.png" alt="Supprimer"></a></td>
            </tr>
<?php 
                } 
                $db = null; // Fermer la connexion
?>
        </table>
    </div>
</body>
</html>