<?php
require "jaime_et_commentaire.php";
require "Chat/redirect.php";

$my_email = $_SESSION['user']['email'];

function getEmailByCompanyName($db, $nom_entreprise) {
    $stmtEmail = $db->prepare("SELECT id FROM users WHERE nom_entreprise = :nom_entreprise");
    $stmtEmail->bindParam(':nom_entreprise', $nom_entreprise);
    $stmtEmail->execute();
    $emails = $stmtEmail->fetchAll(PDO::FETCH_COLUMN);
    return !empty($emails) ? $emails[0] : 'Email non spécifié';
}

$url = 'https://cdn.taux.live/api/latest.json';
$response = file_get_contents($url);
if ($response === FALSE) {
    die('Erreur lors de la récupération des données.');
}
$data = json_decode($response, true);

$usdToCdf = isset($data['rates']['CDF']) ? $data['rates']['CDF'] : null;

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

$sql = "SELECT * FROM product";
$params = [];

$stmt = $db->prepare($sql);
$stmt->execute($params);

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
        'prix_normale' => $row['prix_normale'],
        'discussion' => $row['discussion'],
        'nom_entreprise' => $row['nom_entreprise'],
        'description' => $row['description']
    ];
    $produits[] = $produit;
}

require_once 'header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produits</title>
    <link rel="stylesheet" href="accueil_style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body><br>
<section class="product-section">
<div class="posts-container">
    
    <?php foreach ($produits as $produit): ?>
        
        <div class="post" id="<?php echo htmlspecialchars($produit['name']); ?>">
            <div class="post-header">
                <img src="uploaded_img/<?php echo htmlspecialchars($produit['image_prod']); ?>" alt="<?php echo htmlspecialchars($produit['name']); ?>" class="post-image">
                <h2 class="post-title"><?php echo htmlspecialchars($produit['name']); ?></h2>
            </div>
            <div class="post-body">
                <p class="post-description"><?php echo htmlspecialchars($produit['description']); ?></p>
                <p class="post-price"><?php echo number_format($produit['price'], 2) . ' ' . $produit['devise']; ?></p>
            </div>
            <?php
            list($likesCount, $comments) = displayLikesAndComments($produit['name']);
            ?>
            <div class="post-footer">
            <button class="like-button" data-product-name="<?php echo htmlspecialchars($produit['name']); ?>" data-email="<?php echo htmlspecialchars($my_email); ?>"><?php echo $likesCount;?>❤️ J'aime</button>
            <button class="comment-button" data-product-name="<?php echo htmlspecialchars($produit['name']); ?>" data-email="<?php echo htmlspecialchars($my_email); ?>">💬 Commenter</button>
            </div>
            <div class="post-footer">
            <button class="toggle-comments" data-product-name="<?php echo htmlspecialchars($produit['name']); ?>">Afficher les commentaires</button>
            </div>
            <!-- Conteneur des commentaires (initialement masqué) -->
                    <div class="comments-container">
            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <a href="">
                        <img src="uploaded_img/<?php echo htmlspecialchars($comment['img']); ?>" alt="<?php echo htmlspecialchars($comment['name']); ?>'s image" class="comment-image">
                    </a>
                    <div class="comment-content">
                        <strong><?php echo htmlspecialchars($comment['name']); ?></strong><br>
                        <p><?php echo nl2br(htmlspecialchars($comment['text'])); ?></p>
                    </div>
                    <!-- <<div class="post-footer"> 
                        <button class="like-comment-button" data-product-name="<?php echo htmlspecialchars($produit['name']); ?>" data-email="<?php echo htmlspecialchars($my_email); ?>">
                            <?php echo $likesCount; ?> ❤️ J'aime
                        </button>
                        <button class="reply-button" data-product-name="<?php echo htmlspecialchars($produit['name']); ?>" data-email="<?php echo htmlspecialchars($my_email); ?>">💬 Répondre</button>
                    </div>-->
                </div>
            <?php endforeach; ?>
        </div>                  
        </div><br>
    <?php
    
    ?>
    <?php endforeach; ?>
</div>
</section><br>
<?php require_once 'commentaire_like.php'; ?>
<?php require_once 'footer.php'; ?>

</body>
</html>