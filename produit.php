c<?php
session_start(); // Démarrer la session

// Connexion à la base de données SQLite (remplacez le chemin par le chemin correct vers votre fichier .db)
$db = new PDO('sqlite:'.'C:/Users/EMM/Documents/Application-de-Gestion-de-Stock/Stock_Management_System/system.db');

// Requête SQL pour récupérer les produits 
$sql = 'SELECT category, supplier, qty, price, name, status,image_prod FROM product';
// Exécuter la requête 
$result = $db->query($sql);

// Vérifier si la requête a réussi 
$produits = [];
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

    // Créer un tableau associatif pour représenter un produit 
    $produit = [
        'categorie' => $row['category'],
        'supplier' => $row['supplier'],
        'name' => $row['name'],
        'price' => $row['price'],
        'qty' => $row['qty'],
        'status' => $row['status'],
        'image_prod' => $row['image_prod']
    ];

    // Ajouter le produit au tableau 
    $produits[] = $produit;
require_once 'header.php';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Liste des produits</title>
    <link rel="stylesheet" href="produit_style.css">
</head>
</body>
<?php
// Boucle sur chaque catégorie de produits (assumant que 'category' est unique)
foreach (array_unique(array_column($produits, 'categorie')) as $unique_category) {
    // Début de la section HTML pour la catégorie courante 
    echo '<section class="product-section" id="' . strtolower($unique_category) . '">';
    echo '    <div class="product-container">';
    echo '        <h2 class="product-section-title">' . $unique_category . '</h2>';

    // Boucle pour afficher les produits de la catégorie courante
    echo '        <table class="product-table">';
    echo '            <thead>';
    echo '                <tr>';
    echo '                    <th>Image</th>';
    echo '                    <th>Nom</th>';
    echo '                    <th>Prix</th>';
    echo '                    <th>Action</th>';
    echo '                </tr>';
    echo '            </thead>';
    echo '            <tbody>';
    foreach ($produits as $produit) {
        if ($produit['categorie'] == $unique_category) {
            echo '                <tr>';
            echo '                    <td><img src="uploaded_img/' . $produit['image_prod'] . '" alt="' . $produit['name'] . '" width="100"></td>';
            echo '                    <td class="product-name">' . $produit['name'] . '</td>';
            echo '                    <td class="product-price">' . $produit['price'] . ' $</td>';
            echo '                    <td><a href="produit.php?name=' . $produit['name'] . '&price=' . $produit['price'] . '">Ajouter au panier</a></td>';
            echo '                </tr>';
        }
    }
    echo '            </tbody>';
    echo '        </table>';
    echo '    </div>';
    echo '</section>';
}


// Vérifier si le panier existe déjà dans la session
if (!isset($_SESSION['panier_produit'])) {
    $_SESSION['panier_produit'] = array(); // Initialiser le panier s'il n'existe pas
}

// Ajouter un article au panier
if (isset($_GET['name'] ) && isset($_GET['price'])) {
    $nom_produit = $_GET['name'];
    $prix_produit = $_GET['price'];

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
}
require_once 'footer.php';
?>
