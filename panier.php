<?php
// Dans toutes nos pages 
require 'Chat/redirect.php';

if (isset($_SESSION['id_utilisateur']))
{ $user_id = $_SESSION['id_utilisateur'];
  $nom_utilisateur = $_SESSION['nom_entreprise'];
}
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
            if (isset($_SESSION['panier_produit'][$index])) {
                unset($_SESSION['panier_produit'][$index]);
                $_SESSION['panier_produit'] = array_values($_SESSION['panier_produit']); // Réindexer
            }else {
                echo "impossible de supprimer ce produit au panier";
            }
            break;

        case 'update':
            // Mettre à jour la quantité d'un produit dans le panier
            $nouvelle_quantite = $_POST['nouvelle_quantite'];
            $_SESSION['panier_produit'][$index]['quantite'] = $nouvelle_quantite; // Correction ici
            break;
            case 'update_price':
            // Récupérer les informations du produit
            $article = $_SESSION['panier_produit'][$index];
        
            // Récupérer le prix normal et le prix actuel
            $prix_normal = $article['prix_normale'];
            $prix_actuel = $article['prix'];
        
            // Générer un prix aléatoire entre prix_normal et prix_actuel
            if (!isset($prix_random)) {
                $prix_random = round(rand($prix_normal * 100, $prix_actuel * 100) / 100, 2); // Conversion en centimes pour éviter les problèmes de précision
            }
        
            // Récupérer le nouveau prix proposé
            $nouveau_prix = $_POST['nouveau_prix'];
        
            // Vérifier si le nouveau prix est supérieur ou égal au prix normal
            if ($nouveau_prix < $prix_normal) {
                echo "Le prix proposé est inferieure \n";
                $difference = $prix_random -$nouveau_prix;

                echo "Vous avez proposé un prix de $nouveau_prix $. \n";
                echo "Pour atteindre le prix proposé par  $nom_utilisateur \n($prix_random $), vous devez ajouter $difference $.";
            }elseif ($prix_random == $prix_random) {
                $_SESSION['panier_produit'][$index]['prix'] = $nouveau_prix; // Met à jour le prix
                $_SESSION['panier_produit'][$index]['prix_propose'] = $nouveau_prix; // Stocke la proposition
            } 
            else {
                $difference = $nouveau_prix - $prix_random;

                echo "Vous avez proposé un prix de $nouveau_prix $. ";
                echo "Pour atteindre le prix proposé par $nom_utilisateur ($prix_random $), vous devez ajouter $difference $.";
            }
            $arde_core = true;
            break;
    }
}

$total = 0;
if (isset($_SESSION['panier_produit']) && is_array($_SESSION['panier_produit'])) {
    foreach ($_SESSION['panier_produit'] as $article) {
        // Vérifier si les clés existent avant de les utiliser
        if (isset($article['prix']) && isset($article['quantite'])) {
            $total += $article['prix'] * $article['quantite'];
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Panier</title>
    <link rel="icon" type="image/x-icon" href="stock.ico">
    <link rel="stylesheet" href="panier_style.css">
    <!-- Inclure SweetAlert -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        $total_dollars = 0;
        $total_francs = 0;

        foreach ($_SESSION['panier_produit'] as $article) {
            if ($article['devise'] == "CDF") {
                $devise = "Fc";
                $total_francs += $article['prix'] * $article['quantite'];
            } else {
                $devise = "$";
                $total_dollars += $article['prix'] * $article['quantite'];
            }

            echo "<tr>";
            echo "<td>" . htmlspecialchars($article['nom']) . "</td>";
            if ($article['discussion'] == "OUI") {
                $prix_normale = $article['prix_normale'];
                echo "<td>
                        <form method='post' action='panier.php'>
                            <input type='hidden' name='action_1' value='update_price'>
                            <input type='hidden' name='index' value='" . htmlspecialchars($index) . "'>
                            <input type='number' name='nouveau_prix' value='" . htmlspecialchars($article['prix']) . "' min='0.00001' step='0.00001' required>
                            <button type='submit'>Discuter le prix</button>
                        </form>
                      </td>";
            } else {
                echo "<td>" . htmlspecialchars($article['prix']) . " " . htmlspecialchars($devise) . "</td>";
            }
            echo "<td>
                <form method='post' action='panier.php'>
                    <input type='hidden' name='action_1' value='update'>
                    <input type='hidden' name='index' value='" . $index . "'>
                    <input type='number' name='nouvelle_quantite' value='" . htmlspecialchars($article['quantite']) . "' min='1'>
                    <button type='submit'>Mettre à jour</button>
                </form>
            </td>";
            echo "<td>" . ($article['prix'] * $article['quantite']) . " " . $devise . "</td>";
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

<h2>Total en Dollars: <?php echo $total_dollars; ?> $</h2>
<h2>Total en Francs: <?php echo $total_francs; ?> Fc</h2>

<a href="panier.php?nb_index=<?php echo $index; ?>">Passer la commande</a>
<a href="index.php">Continuer mes achats</a>

    <p>Total du panier : <?php echo $total ?> $</p>
    <?php
if (isset($_GET['nb_index']) || isset($_POST['nom_client'])) {
    if (isset($_GET['nb_index'])) {
        $nb_index = $_GET['nb_index'];
    }
    
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

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nom_client'])) {
        $nom_client = $_POST['nom_client'];
        $adresse_client = $_POST['adresse_client'];
        $numero_client = $_POST['numero_client'];
        $temp = date("Y-m-d H:i:s");
        
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
        $pdo = $con;


        foreach ($_SESSION['panier_produit'] as $article) {
            $sql = "INSERT INTO commandes (nom_produit, prix, quantite, nom_client, adresse_client, numero_client ,temp)
                    VALUES (:nom_produit, :prix, :quantite, :nom_client, :adresse_client, :numero_client, :temp)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nom_produit', $article['nom']);
            $stmt->bindParam(':prix', $article['prix']);
            $stmt->bindParam(':quantite', $article['quantite']);
            $stmt->bindParam(':nom_client', $nom_client);
            $stmt->bindParam(':adresse_client', $adresse_client);
            $stmt->bindParam(':numero_client', $numero_client);
            $stmt->bindParam(':temp', $temp);
            $stmt->execute();
        }
        unset($_SESSION['panier_produit']);
        header('Location: panier.php?success=true');
        exit;
    }
}
?>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Vérifier si le formulaire a été soumis
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('success')) {
            Swal.fire({
                title: 'Vente confirmée!',
                text: 'Votre commande a été enregistrée avec succès.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'index.php'; // Rediriger vers la page de confirmation
                }
            });
        }
    });
</script>
</html>