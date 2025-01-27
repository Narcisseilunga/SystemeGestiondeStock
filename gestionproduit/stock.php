<?php
@include 'config.php';
session_start();
$nom_entreprise = $_SESSION['nom_entreprise'];

if (isset($_POST['add_product'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_description = $_POST['product_description'];
    $product_image = $_FILES['product_image']['name'];
    $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
    $product_image_folder = 'uploaded_img/' . $product_image;
    $unit_measure = $_POST['unit_measure'];
    $category = $_POST['category'];
    $supplier = $_POST['supplier'];
    $qty = $_POST['qty_product'];
    $devise = $_POST['devise'];
    $status = $_POST['status'];
    $qty_min = $_POST['qty_min'];
    $qty_max = $_POST['qty_max'];
    $location = $_POST['location'];

    // Vérification des champs
    if (empty($product_name)) {
        $message[] = 'Veuillez remplir tous les champs';
    } else {
        // Préparation de la requête pour insérer toutes les données
        $insert = "INSERT INTO product(name, price, image_prod, description, unite_mesure, category, supplier, status, qty_min, qty_max, localisation, devise, qty) VALUES(:name, :price, :image_prod, :description, :unite_mesure, :category, :supplier, :status, :qty_min, :qty_max, :localisation, :devise, :qty, :nom_entreprise)";
        
        $stmt = $conn->prepare($insert);
        
        // Liaison des paramètres avec les valeurs
        $stmt->bindParam(':name', $product_name);
        $stmt->bindParam(':price', $product_price);
        $stmt->bindParam(':image_prod', $product_image);
        $stmt->bindParam(':description', $product_description);
        $stmt->bindParam(':unite_mesure', $unit_measure);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':supplier', $supplier);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':qty_min', $qty_min);
        $stmt->bindParam(':qty_max', $qty_max);
        $stmt->bindParam(':localisation', $location);
        $stmt->bindParam(':devise', $devise);
        $stmt->bindParam(':qty', $qty);
        $stmt->bindParam(':nom_entreprise', $nom_entreprise);

        if ($stmt->execute()) {
            move_uploaded_file($product_image_tmp_name, $product_image_folder);
            $message[] = 'Produit ajouté avec succès';
        } else {
            $message[] = 'Erreur : produit non ajouté';
        }
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM product WHERE id = $id");
    header('location:index.php');
    exit(); // Ajoutez exit() après header pour arrêter l'exécution
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Produits</title>
    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="css/style.css">
    <style>
    
    </style>
</head>
<body>

<?php
if (isset($message)) {
    foreach ($message as $msg) {
        echo '<span class="message">' . $msg . '</span>';
    }
}
?>

<div class="container">
    <div class="admin-product-form-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <h3>Ajouter un nouveau produit</h3>
            <input type="text" placeholder="Entrer le nom du produit" name="product_name" class="box" required>
            <select name="devise" class="box" required>
                <option value="">Choisir la devise</option>
                <option value="USD">Dollars (USD)</option>
                <option value="CDF">Franc Congolais (CDF)</option>
            </select>
            <input type="number" placeholder="Entrer le prix du produit" name="product_price" class="box" required>
            <input type="text" placeholder="Entrer la description du produit" name="product_description" class="box" required>
            <input type="number" placeholder="Entrer la quantite" name="qty_product" class="box" required>
            <select name="status" class="box" required>
                <option value="">Sélectionnez le statut</option>
                <option value="actif">Disponible</option>
                <option value="inactif">Rupture de stock</option>
            </select>
            <select name="unit_measure" class="box" required>
                <option value="">Sélectionnez l'unité de mesure</option>
                <option value="kg">Kilogramme</option>
                <option value="L">Litre</option>
                <option value="pcs">Pièce</option>
            </select>
            <input type="number" placeholder="Entrer la quantité minimale" name="qty_min" class="box" required>
            <input type="number" placeholder="Entrer la quantité maximale" name="qty_max" class="box" required>
            <input type="text" placeholder="Entrer l'emplacement" name="location" class="box" required>
            <select name="category" class="box" required>
                <option value="">Sélectionnez la catégorie</option>
                <?php
                // Récupérer les catégories depuis la base de données
                $categories = $conn->query("SELECT * FROM category");
                while ($row = $categories->fetch(PDO::FETCH_ASSOC)) {?>
                    <option value="<?php echo  $row['name'];?>"><?php  echo $row['name'];?></option>
                <?php
                }
                ?>
            </select>
            <select name="supplier" class="box" required>
                <option value="">Sélectionnez le fournisseur</option>
                <?php
                // Récupérer les fournisseurs depuis la base de données
                $suppliers = $conn->query("SELECT * FROM supplier");
                while ($row = $suppliers->fetch(PDO::FETCH_ASSOC)) {?>
                  <option value="<?php $row['name'] ?>"> <?php echo $row['name'] ?></option>;
                <?php
                }
                ?>
            </select>
            <input type="file" accept="image/png, image/jpeg, image/jpg" name="product_image" class="box" required>
            <input type="submit" class="btn" name="add_product" value="Ajouter le produit">
            
        </form>
    </div>

    <?php
    // URL de l'API des taux de change
    $url = 'https://cdn.taux.live/api/latest.json';
    // Récupération des données depuis l'API
    $response = file_get_contents($url);
    if ($response === FALSE) {
        
        die('Erreur lors de la récupération des données.');
    }
    // Décoder la réponse JSON
    $data = json_decode($response, true);
    $select = $conn->query("SELECT * FROM product");
    ?>
    <div class="product-display">
        <table class="product-display-table">
            <thead>
            <tr>
                <th>Image du produit</th>
                <th>Nom du produit</th>
                <th>Prix du produit</th>
                <th>Description du produit</th>
                <th>Unité de mesure</th>
                <th>Catégorie</th>
                <th>Fournisseur</th>
                <th>Statut</th>
                <th>Quantité</th>
                <th>Quantité minimale</th>
                <th>Quantité maximale</th>
                <th>Emplacement</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php 

            while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
            ?>
                <tr>
                    <td><img src="<?php echo 'uploaded_img/'.$row['image_prod']; ?>" height="100" alt=""></td>
                    <td><?php echo htmlspecialchars($row['name'] ?? 'N/A'); ?></td>
                    <td><?php
                        if (isset($data['rates']['CDF'])) {
                             // Récupérer le taux de change en CDF
                             $usdToCdf = $data['rates']['CDF'];
                             $money =  $row['price'] / $usdToCdf; 
                            if ($row['devise'] == "CDF") {
                                echo "Fc ".htmlspecialchars(number_format($row['price'],0) ?? '0'); ?><?php echo "\n $".number_format($money,2)?>
                            <?php }
                            else {echo "$ ".htmlspecialchars(number_format($row['price'],0) ?? '0'); ?><?php echo "\n Fc".number_format($usdToCdf* $row['price'] , 2)?> 
                            <?php } 
                        } else {
                            $message[] = 'taux de change en franc congolais non disponible';

                        }
                        ?></td>
                    <td><?php echo htmlspecialchars($row['description'] ?? 'Aucune description'); ?></td>
                    <td><?php echo htmlspecialchars($row['unite_mesure'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($row['category'] ?? 'N/A'); // Remplacer par le nom de la catégorie ?></td>
                    <td><?php echo htmlspecialchars($row['supplier'] ?? 'N/A'); // Remplacer par le nom du fournisseur ?></td>
                    <td><?php echo htmlspecialchars($row['status'] ?? 'Inconnu'); ?></td>
                    <td><?php echo htmlspecialchars($row['qty'] ?? '0'); ?></td>
                    <td><?php echo htmlspecialchars($row['qty_min'] ?? '0'); ?></td>
                    <td><?php echo htmlspecialchars($row['qty_max'] ?? '0'); ?></td>
                    <td><?php echo htmlspecialchars($row['localisation'] ?? 'Non spécifié'); ?></td>
                    <td>
                        <a href="editer_produit.php?edit=<?php echo $row['id']; ?>" class="btn"> <i class="fas fa-edit"></i> Éditer </a>
                        <a href="supprimer_produit.php?delete=<?php echo $row['id']; ?>" class="btn"> <i class="fas fa-trash"></i> Supprimer </a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    </div>
    
    </body>
    </html>