<?php
require "jaime_et_commentaire.php";
require "Chat/redirect.php";

$my_email =  $_SESSION['user']['email'];

function getEmailByCompanyName($db, $nom_entreprise) {
    $stmtEmail = $db->prepare("SELECT id FROM users WHERE nom_entreprise = :nom_entreprise");
    $stmtEmail->bindParam(':nom_entreprise', $nom_entreprise);
    $stmtEmail->execute();
    $emails = $stmtEmail->fetchAll(PDO::FETCH_COLUMN);

    // Si plusieurs emails existent, choisir celui de l'admin (ou le premier dans la liste)
    return !empty($emails) ? $emails[0] : 'Email non spécifié';
}
// URL de l'API des taux de change
$url = 'https://cdn.taux.live/api/latest.json';
// Récupération des données depuis l'API
$response = file_get_contents($url);
if ($response === FALSE) {
    die('Erreur lors de la récupération des données.');
}
// Décoder la réponse JSON
$data = json_decode($response, true);

if (isset($data['rates']['CDF'])) {
    // Récupérer le taux de change en CDF
    $usdToCdf = $data['rates']['CDF'];
} else {
    $message[] = 'Taux de change en franc congolais non disponible.';
}

$host = 'localhost'; // Remplacez par votre hôte MySQL
$dbname = 'system_vente'; // Remplacez par le nom de votre base de données
$username = 'root'; // Remplacez par votre nom d'utilisateur MySQL
$password = ''; // Remplacez par votre mot de passe MySQL

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erreur de connexion : ' . $e->getMessage();
    exit;
}


if (isset($_SESSION['focus'])) {
    $nom_entreprise = $_SESSION['focus'];

    $filter_category = isset($_GET['category']) ? $_GET['category'] : '';
    $search_query = isset($_GET['search']) ? $_GET['search'] : '';

    $sql = "SELECT * FROM product WHERE nom_entreprise = :nom_entreprise";
    $params = [':nom_entreprise' => $nom_entreprise];

    if ($filter_category) {
        $sql .= " AND category = :category";
        $params[':category'] = $filter_category;
    }

    if ($search_query) {
        $sql .= " AND (name LIKE :search OR SOUNDEX(name) = SOUNDEX(:search))";
        $params[':search'] = "%$search_query%";
    }

    

}else {
    $filter_category = isset($_GET['category']) ? $_GET['category'] : '';
    $search_query = isset($_GET['search']) ? $_GET['search'] : '';
    
    $sql = "SELECT * FROM product WHERE 1=1";
    $params = [];
    
    // Appliquer les filtres de catégorie et de recherche
    if ($filter_category) {
        $sql .= " AND category = :category";
        $params[':category'] = $filter_category;
    }
    
    if ($search_query) {
        $sql .= " AND (name LIKE :search OR nom_entreprise LIKE :search OR SOUNDEX(name) = SOUNDEX(:search) OR SOUNDEX(nom_entreprise) = SOUNDEX(:search))";
        $params[':search'] = "%$search_query%";
    }
    
}

$selected_devise = isset($_GET['devise']) ? $_GET['devise'] : 'USD';


$stmt = $db->prepare($sql);
$stmt->execute($params);

// Vérifier si la requête a réussi
$produits = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $produit = [
        'categorie' => $row['category'],
        'supplier' => $row['supplier'],
        'name' => $row['name'],
        'price' => $row['price'],
        'qty' => $row['qty'],
        'status' => $row['status'],
        'image_prod' => $row['image_prod'],
        'devise' => $row['devise'],
        'prix_normale'=>$row['prix_normale'],
        'discussion'=>$row['discussion'],
        'nom_entreprise'=>$row['nom_entreprise'],
        'description'=>$row['description']
    ];
    $produits[] = $produit;
}
require_once 'header.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Liste des produits</title>
    <link rel="stylesheet" href="produit_style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<!-- Menu déroulant pour trier par catégorie et sélectionner la devise -->
<form method="get" action="">
    <label for="category">Filtrer par catégorie :</label>
    <select name="category" id="category" onchange="this.form.submit()">
        <option value="">Toutes les catégories</option>
        <?php
        // Récupérer les catégories uniques
        $categories = array_unique(array_column($produits, 'categorie'));
        foreach ($categories as $category) {
            echo '<option value="' . $category . '"' . ($category == $filter_category ? ' selected' : '') . '>' . $category . '</option>';
        }
        ?>
    </select>

    <!-- Barre de recherche -->
    <label for="search">Recherche :</label>
    <input type="text" name="search" id="search" value="<?php echo htmlspecialchars($search_query); ?>">
    <input type="submit" value="Rechercher">

    <label for="devise">Sélectionnez la devise :</label>
    <select name="devise" id="devise" onchange="this.form.submit()">
        <option value="USD" <?= ($selected_devise == 'USD') ? 'selected' : '' ?>>USD</option>
        <option value="CDF" <?= ($selected_devise == 'CDF') ? 'selected' : '' ?>>CDF</option>
    </select>
</form>

<?php
// Boucle sur chaque catégorie de produits
$categories_affichees = array_unique(array_column($produits, 'categorie'));

foreach ($categories_affichees as $unique_category) {
    // Vérifier si des produits de cette catégorie existent
    $produits_par_categorie = array_filter($produits, function($produit) use ($unique_category) {
        return $produit['categorie'] == $unique_category;
    });

    if (!empty($produits_par_categorie)) {
        echo '<section class="product-section" id="' . strtolower($unique_category) . '">';
        echo '    <div class="product-container">';
        echo '        <h2 class="product-section-title">' . $unique_category . '</h2>';
        echo '        <table class="product-table">';
        /*echo '            <thead>';
        echo '                <tr>';
        echo '                    <th>Image</th>';
        echo '                    <th>Nom</th>';
        echo '                    <th>Prix</th>';
        echo '                    <th>Action</th>';
        echo '                </tr>';
        echo '            </thead>';*/
        echo '            <tbody>';
    
        foreach ($produits_par_categorie as $produit) {
            
            $imageClass = "no-hover";
           
            echo '                <tr>';
            echo '                    <td rowspan="3"><a href="#" class="product-image ' . $imageClass . '" data-name="' . htmlspecialchars($produit['name']) . '" data-price="' . htmlspecialchars($produit['price']) . '" data-seller="' . htmlspecialchars($produit['nom_entreprise']) . '" data-image="uploaded_img/' . htmlspecialchars($produit['image_prod']) . '" data-discussion="' . htmlspecialchars($produit['discussion']).'"data-devise="' . htmlspecialchars($produit['devise']).'"data-client="' . htmlspecialchars(getEmailByCompanyName($db, $produit['nom_entreprise'])).'"><img src="uploaded_img/' . htmlspecialchars($produit['image_prod']) . '" alt="' . htmlspecialchars($produit['name']) . '" width="100"></a></td>';
            echo '                    <td colspan="2" class="product-name">' . htmlspecialchars($produit['name']) . '</td>';
    
            // Conversion des prix selon la devise sélectionnée
            if ($selected_devise == 'CDF' && $produit['devise'] == 'CDF') {
                $devise = "CDF";
                echo '                    <td class="product-price">' . number_format($produit['price'], 2) . ' Fc</td>';
                echo '                </tr>';
            
            echo '                <tr>';
            echo '                    <td colspan="3" class="product-name">' . htmlspecialchars($produit['description']) . '</td>';

            echo '                </tr>';

            echo '                <tr>';
            echo '                    <td colspan="2">Ajouter au panier</td>';
            echo '                    <td><a class="ajout-panier" href="?name=' . htmlspecialchars($produit['name']) . '&price=' . htmlspecialchars($produit['price']) . '&devise=' . $devise . '&discussion=' . htmlspecialchars($produit['discussion']) . '&prix_normale=' . htmlspecialchars($produit['prix_normale']) . '">🛒</a></td>';
            
            } elseif ($selected_devise == 'USD' && $produit['devise'] == 'USD') {
                $devise = "USD";
                echo '                    <td class="product-price">' . number_format($produit['price'], 2) . ' $</td>';
                echo '                </tr>';
            
                echo '                <tr>';
                echo '                    <td colspan="3" class="product-name">' . htmlspecialchars($produit['description']) . '</td>';

                echo '                </tr>';

                echo '                <tr>';
                echo '                    <td colspan="2">Ajouter au panier</td>';
                echo '                    <td><a class="ajout-panier" href="?name=' . htmlspecialchars($produit['name']) . '&price=' . htmlspecialchars($produit['price']) . '&devise=' . $devise . '&discussion=' . htmlspecialchars($produit['discussion']) . '&prix_normale=' . htmlspecialchars($produit['prix_normale']) . '">🛒</a></td>';
            
            } elseif ($selected_devise == 'CDF' && $produit['devise'] == 'USD') {
                $converted_price = $produit['price'] * $usdToCdf; // Conversion de USD à CDF
                $converted_normale_price = $produit['prix_normale'] * $usdToCdf;
    
                $devise = "CDF";
                echo '                    <td class="product-price">' . number_format($converted_price, 2) . ' Fc</td>';
                echo '                </tr>';
            
                echo '                <tr>';
                echo '                    <td colspan="3" class="product-name">' . htmlspecialchars($produit['description']) . '</td>';

                echo '                </tr>';

                echo '                <tr>';
                echo '                    <td colspan="2">Ajouter au panier</td>';
                echo '<td><a class="ajout-panier" href="?name=' . htmlspecialchars($produit['name']) . '&price=' . number_format($converted_price, 2) . '&devise=' . $devise . '&discussion=' . htmlspecialchars($produit['discussion']) . '&prix_normale=' . number_format($converted_normale_price, 2) . '">🛒</a></td>';
            
            } elseif ($selected_devise == 'USD' && $produit['devise'] == 'CDF') {
                $converted_price = $produit['price'] / $usdToCdf; // Conversion de CDF à USD
                $converted_normale_price = $produit['prix_normale'] / $usdToCdf;
                $devise = "USD";
                echo '                    <td class="product-price">' . number_format($converted_price, 2) . ' $</td>';
                echo '                </tr>';
            
                echo '                <tr>';
                echo '                    <td colspan="3" class="product-name">' . htmlspecialchars($produit['description']) . '</td>';

                echo '                </tr>';

                echo '                <tr>';
                echo '                    <td colspan="2">Ajouter au panier</td>';
                echo '<td><a class="ajout-panier" href="?name=' . htmlspecialchars($produit['name']) . '&price=' . number_format($converted_price, 2) . '&devise=' . $devise . '&discussion=' . htmlspecialchars($produit['discussion']) . '&prix_normale=' . number_format($converted_normale_price, 2) . '">🛒</a></td>';
            
            }
            list($likesCount, $comments) = displayLikesAndComments($produit['name']);
            /*echo "<pre>Nombre de J'aime: $likesCount</pre>";
            echo '<pre>Commentaires: ';
            print_r($comments);
            echo '</pre>';*/
            echo '                </tr>';
            echo '<tr>';

            echo '<td colspan="2"><a href="#" class="toggle-comments no-hover" data-product-name="' . htmlspecialchars($produit['name']) . '">Afficher les commentaires</a></td>';            
            echo '<td><a href="#" class="like-button" data-product-name="' . htmlspecialchars($produit['name']) . '" data-email="' . htmlspecialchars($my_email) . '">❤️ (' . $likesCount . ')</a></td>';
            echo '<td><a href="" class="comment-button" data-product-name="' . htmlspecialchars($produit['name']) . '" data-email="' . htmlspecialchars($my_email) . '">💬</a></td>';            
            
            echo '</tr>';
                        // Ajouter un bouton pour afficher ou masquer les commentaires
                    // Ajouter un bouton pour afficher ou masquer les commentaires

            // Conteneur pour les commentaires
            echo '<tr class="comments-container" style="display: none;">'; // Masquer par défaut
            echo '<td colspan="3">';
            
echo '</td>';
echo '</tr>';
            
        }
    
        echo '            </tbody>';
        echo '        </table>';
        echo '    </div>';
        echo '</section>';
    }?>
    <script>
        document.querySelectorAll('.product-image').forEach(item => {
            item.addEventListener('click', event => {
                event.preventDefault(); // Empêche le lien de naviguer
                const name = item.dataset.name;
                const price = item.dataset.price;
                const seller = item.dataset.seller || "Vendeur non spécifié"; // Assurez-vous que le vendeur est défini
                const image = item.dataset.image;
                const discussion = item.dataset.discussion;
                const devise = item.dataset.devise;
                const client = item.dataset.client;

                Swal.fire({
                    title: name,
                    html: `
                        <img src="${image}" alt="${name}" style="width: 100%; max-width: 300px; margin-bottom: 15px;">
                        <p><strong>Prix :</strong> ${price +" "+ devise} </p>
                        <p><strong>Vendeur(se) :</strong> ${seller}</p>
                        <p><strong>Discussion :</strong> ${discussion}</p>
                        <p><strong><a href="Chat_main/boxMessage.php?id_User=${client}&nom_produit=${name}&prix_produit=${price +" "+ devise}&image_produit=${image}">Discuter avec le vendeur</p>
                    `,
                    showCloseButton: true,
                    focusConfirm: false,
                    confirmButtonText: 'Fermer'
                });
            });
        });
        </script>
        

<?php
}
?>
<script>
        document.querySelectorAll('.like-button').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const productName = this.getAttribute('data-product-name');
                const email = this.getAttribute('data-email');
                //const email = prompt("Entrez votre email pour aimer cet article:");
                if (email) {
                    // Enregistrez l'action "J'aime" dans le fichier log via AJAX
                    fetch('jaime_et_commentaire.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ email: email, action: 'j\'aime', productName: productName })
                    }).then(response => {
                        if (response.ok) {
                            location.reload(); // Rechargez la page pour mettre à jour le nombre de "J'aime"
                        }
                    });
                }
            });
        });

        document.querySelectorAll('.comment-button').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const productName = this.getAttribute('data-product-name');
                const email = this.getAttribute('data-email');
                //const email = prompt("Entrez votre email:");
                const comment = prompt("Entrez votre commentaire:");
                if (email && comment) {
                    // Enregistrez le commentaire dans le fichier log via AJAX
                    fetch('jaime_et_commentaire.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ email: email, action: 'commenter', comment: comment, productName: productName })
                    }).then(response => {
                        if (response.ok) {
                            location.reload(); // Rechargez la page pour mettre à jour les commentaires
                        }
                    });
                }
            });
        });
    </script>
    <script>
            document.addEventListener('DOMContentLoaded', function() {
                const toggleButtons = document.querySelectorAll('.toggle-comments');

                toggleButtons.forEach(button => {
                    button.addEventListener('click', function(event) {
                        event.preventDefault(); // Empêche le comportement par défaut du lien
                        const productName = this.dataset.productName;
                        const commentsContainer = this.closest('tr').nextElementSibling; // Sélectionner le conteneur des commentaires

                        // Basculez l'affichage des commentaires
                        if (commentsContainer.style.display === 'none') {
                            commentsContainer.style.display = 'table-row'; // Affiche les commentaires
                            this.textContent = 'Masquer'; // Change le texte du bouton
                        } else {
                            commentsContainer.style.display = 'none'; // Masque les commentaires
                            this.textContent = 'Afficher les commentaires'; // Change le texte du bouton
                        }
                    });
                });
            });
    </script>
   
    <?php
    if (!isset($_SESSION['panier_produit'])) {
    $_SESSION['panier_produit'] = array(); // Initialiser le panier s'il n'existe pas
}

// Ajouter un article au panier
if ((isset($_GET['name']) && isset($_GET['price']))) {
    $nom_produit = $_GET['name'];
    $prix_produit = $_GET['price'];
    $devise_produit = $_GET['devise'];
    $discussion_etat = $_GET['discussion'];
    $prix_normal = $_GET['prix_normale'];


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
            'quantite' => 1,
            'devise' => $devise_produit,
            'discussion' => $discussion_etat,
            'prix_normale'=> $prix_normal
        );
    }

    // Alerte SweetAlert
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo '<script type="text/javascript">';
    echo 'Swal.fire("Succès", "L\'article a bien été ajouté au panier", "success");';
    echo '</script>';
    //header("Location: index.php");
}

require_once 'compteur.php';
require_once 'footer.php';
?>
 
</body>
</html>