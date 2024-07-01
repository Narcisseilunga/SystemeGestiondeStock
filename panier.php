<?php
session_start();
// Vérifier si le panier existe déjà dans la session
if (!isset($_SESSION['panier_produit'])) {
    $_SESSION['panier_produit'] = array(); // Initialiser le panier s'il n'existe pas
}

// Traiter les actions sur le panier (ajout, suppression, mise à jour)
if (isset($_POST['action_1'])) {
    $action = $_POST['action_1'];
    $index = $_POST['index'];

    switch ($action) {
        case 'add':
            // Ajouter un produit au panier
            $nom_produit = $_POST['nom'];
            $prix_produit = $_POST['prix'];

            // Vérifier si le produit est déjà dans le panier
            $produit_existe = false;
            foreach ($_SESSION['panier_produit'] as &$article) {
                if ($article['nom'] == $nom_produit) {
                    $article['quantite']++; // Incrémenter la quantité si le produit est déjà dans le panier
                    $produit_existe = true;
                    break;
                }
            }

            // Ajouter le produit au panier s'il n'y est pas déjà
            if (!$produit_existe) {
                $_SESSION['panier_produit'][] = array(
                    'nom' => $nom_produit,
                    'prix' => $prix_produit,
                    'quantite' => 1
                );
            }
            break;

        case 'remove':
            // Supprimer un produit du panier
            unset($_SESSION['panier_produit'][$index]);
            break;

        case 'update':
            // Mettre à jour la quantité d'un produit dans le panier
            $nouvelle_quantite = $_POST['nouvelle_quantite'];
            $_SESSION['panier'][$index]['quantite'] = $nouvelle_quantite;
            break;
    }
}

// Calculer le total du panier
$total = 0;
foreach ($_SESSION['panier_produit'] as $article) {
    $total += $article['prix'] * $article['quantite'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Panier</title>
    <link rel="icon" type="image/x-icon" href="stock.ico">
    <link rel="stylesheet" href="panier_style.css">
</head>
<body>
    <h1>Votre panier</h1>

    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Prix</th>
                <th>Quantité</th>
                <th>Total</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $index = 0;
            foreach ($_SESSION['panier_produit'] as $article) {
                echo "<tr>";
                echo "<td>" . $article['nom'] . "</td>";
                echo "<td>" . $article['prix'] . " $</td>";
                echo "<td>
                    <form method='post' action='panier.php'>
                        <input type='hidden' name='action_1' value='update'>
                        <input type='hidden' name='index' value='" . $index . "'>
                        <input type='number' name='nouvelle_quantite' value='" . $article['quantite'] . "' min='1'>
                        <button type='submit'>Mettre à jour</button>
                    </form>
                </td>";
                echo "<td>" . ($article['prix'] * $article['quantite']) . " $</td>";
                echo "<td>
                    <form method='post' action='panier.php'>
                        <input type='hidden' name='action_1' value='remove'>
                        <input type='hidden' name='index' value='" . $index . "'>
                        <button type='submit'>Supprimer</button>
                    </form>
                </td>";
                echo "</tr>";
                $index++;
            }
            ?>
        </tbody>
    </table>
    <a href="panier.php?nb_index=<?php echo $index;?>">Passer la commande</a>


    <p>Total du panier : <?php echo $total ?> $</p>
    <?php
if (isset($_GET['nb_index'])) {
    $nb_index = $_GET['nb_index'];
    ?>
    <div class="main-content">
        <div>
            <div class="form-box">
                <h2>Informations du client</h2>
                <form method="post" action="panier.php">
                    <input type="hidden" name="action" value="order">
                    <label for="nom_client">Nom :</label>
                    <input type="text" id="nom_client" name="nom_client" required>
                    <label for="adresse_client">Adresse :</label>
                    <input type="text" id="adresse_client" name="adresse_client" required>
                    <label for="numero_client">Numéro de téléphone :</label>
                    <input type="text" id="numero_client" name="numero_client" required>
                    <button type="submit">Payer et Commander</button>
                </form>
            </div>
        </div>
    </div>
    <?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom_client = $_POST['nom_client'];
        $adresse_client = $_POST['adresse_client'];
        $numero_client = $_POST['numero_client'];

        // Connexion à la base de données "article"
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "article";

        try {
            $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connexion échouée : " . $e->getMessage();
        }

        foreach ($_SESSION['panier_produit'] as $article) {
            $sql = "INSERT INTO commandes (nom_produit, prix, quantite, nom_client, adresse_client, numero_client)
                    VALUES (:nom_produit, :prix, :quantite, :nom_client, :adresse_client, :numero_client)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nom_produit', $article['nom']);
            $stmt->bindParam(':prix', $article['prix']);
            $stmt->bindParam(':quantite', $article['quantite']);
            $stmt->bindParam(':nom_client', $nom_client);
            $stmt->bindParam(':adresse_client', $adresse_client);
            $stmt->bindParam(':numero_client', $numero_client);
            $stmt->execute();
        }
        header('Location: confirmation.php');
        exit;
    }
}
?>
</body>
</html>