<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Des entreprises abonnées</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <a href="ajouter_entreprise.php" class="Btn_add"> <img src="images/plus.png" alt="Ajouter"> Ajouter</a>
        
        <table>
            <tr id="items">
                <th>Nom</th>
                <th>Modifier</th>
                <th>Supprimer</th>
            </tr>
            <?php 
                // Database connection parameters
                $host = 'localhost';
                $dbname = 'system_vente';
                $username = 'root';
                $password = '';

                try {
                    $con = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
                    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Query to fetch categories
                    $sql = "SELECT id, name FROM entreprise";
                    $result = $con->query($sql);

                    // Display categories
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td><a href='modifier_entreprise.php?id=" . $row['id'] . "'><img src='images/pen.png' alt='Modifier'></a></td>";
                        echo "<td><a href='supprimer_entreprise.php?id=" . $row['id'] . "'><img src='images/trash.png' alt='Supprimer'></a></td>";
                        echo "</tr>";
                    }
                } catch (PDOException $e) {
                    echo 'Erreur de connexion : ' . $e->getMessage();
                } finally {
                    $con = null; // Close the connection
                }
            ?>
        </table>
    </div>
</body>
</html>