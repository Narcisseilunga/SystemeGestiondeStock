<?php
session_start();
@include 'config.php';

$id = $_GET['edit'];
$nom_entreprise = $_SESSION['nom_entreprise'];

// Vérifiez si le produit appartient à l'entreprise
$stmt = $conn->prepare("SELECT * FROM product WHERE id = :id AND nom_entreprise = :nom_entreprise");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->bindParam(':nom_entreprise', $nom_entreprise, PDO::PARAM_STR);
$stmt->execute();

if ($stmt->rowCount() == 0) {
    echo "Produit non trouvé ou accès non autorisé.";
    exit;
}

if (isset($_POST['update_product'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_FILES['product_image']['name'];
    $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
    $product_image_folder = '../uploaded_img/' . $product_image;
    $product_description = $_POST['product_description'];
    $qty_product = $_POST['qty_product'];
    $devise = $_POST['devise'];
    $status = $_POST['status'];
    $unit_measure = $_POST['unit_measure'];
    $qty_min = $_POST['qty_min'];
    $qty_max = $_POST['qty_max'];
    $location = $_POST['location'];
    $category = $_POST['category'];
    $supplier = $_POST['supplier'];

    // Préparer la requête de mise à jour
    $update_data = "UPDATE product SET 
                    name = :name, 
                    price = :price, 
                    description = :description, 
                    qty = :qty, 
                    devise = :devise, 
                    status = :status, 
                    unite_mesure = :unit_measure, 
                    qty_min = :qty_min, 
                    qty_max = :qty_max, 
                    localisation = :location, 
                    category = :category, 
                    supplier = :supplier ";

    // Ajouter la mise à jour de l'image seulement si un fichier est téléchargé
    if (!empty($product_image)) {
        $update_data .= ", image_prod = :image_prod ";
    }

    $update_data .= " WHERE id = :id AND nom_entreprise = :nom_entreprise";

    $stmt = $conn->prepare($update_data);
    
    // Préparer les paramètres
    $params = [
        ':name' => $product_name,
        ':price' => $product_price,
        ':description' => $product_description,
        ':qty' => $qty_product,
        ':devise' => $devise,
        ':status' => $status,
        ':unit_measure' => $unit_measure,
        ':qty_min' => $qty_min,
        ':qty_max' => $qty_max,
        ':location' => $location,
        ':category' => $category,
        ':supplier' => $supplier,
        ':id' => $id,
        ':nom_entreprise' => $nom_entreprise, // Ajouter le nom de l'entreprise ici
    ];

    // Ajouter l'image aux paramètres si elle est téléchargée
    if (!empty($product_image)) {
        $params[':image_prod'] = $product_image;
    }

    // Exécuter la mise à jour
    $stmt->execute($params);

    // Déplacer le fichier téléchargé si une nouvelle image est fournie
    if (!empty($product_image)) {
        move_uploaded_file($product_image_tmp_name, $product_image_folder);
    }

    header('location:stock.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php
   if(isset($message)){
      foreach($message as $message){
         echo '<span class="message">'.$message.'</span>';
      }
   }
?>

<div class="container">


<div class="admin-product-form-container centered">

   <?php
      
      $select = $conn->query("SELECT * FROM product WHERE id = '$id'");
      while($row = $select->fetch(PDO::FETCH_ASSOC)){

   ?>
   
   <form action="" method="post">
      <h3 class="title">Mettre à jour le produit</h3>
      <input type="text" class="box" name="product_name" value="<?php echo $row['name']; ?>" placeholder="entrer le nom du produit">
      <input type="number" min="0" class="box" name="product_price" value="<?php echo $row['price']; ?>" placeholder="entrer le prix du produit">           
      <input type="text" placeholder="Entrer la description du produit" name="product_description" value="<?php echo $row['description']; ?>" class="box">
      <input type="number" placeholder="Entrer la quantite" name="qty_product" value="<?php echo $row['qty']; ?>" class="box">
      <select name="devise" class="box" required>
            <option value="<?php echo $row['devise']; ?>"><?php echo $row['devise']; ?></option>
            <option value="USD">Dollars (USD)</option>
            <option value="CDF">Franc Congolais (CDF)</option>
      </select>
            
            <select name="status" class="box">
                <option value="<?php echo $row['status']; ?>"><?php echo $row['status']; ?></option>
                <option value="actif">Disponible</option>
                <option value="inactif">Rupture de stock</option>
            </select>
            <select name="unit_measure" class="box">
                <option value="<?php echo $row['unite_mesure']; ?>"><?php echo $row['unite_mesure']; ?></option>
                <option value="kg">Kilogramme</option>
                <option value="L">Litre</option>
                <option value="pcs">Pièce</option>
            </select>
            <input type="number" value="<?php echo $row['qty_min']; ?>" placeholder="Entrer la quantité minimale" name="qty_min" class="box">
            <input type="number" value="<?php echo $row['qty_max']; ?>" placeholder="Entrer la quantité maximale" name="qty_max" class="box">
            <input type="text" value="<?php echo $row['localisation']; ?>" placeholder="Entrer l'emplacement" name="location" class="box">
            <select name="category" class="box" required>
                <option value="<?php echo $row['category']; ?>"><?php echo $row['category']; ?></option>
                <?php
                // Récupérer les catégories depuis la base de données
                $categories = $conn->query("SELECT * FROM category");
                while ($row2 = $categories->fetch(PDO::FETCH_ASSOC)) {?>
                    <option value="<?php echo  $row2['name'];?>"><?php  echo $row2['name'];?></option>
                <?php
                }
                ?>
            </select>
            <select name="supplier" class="box" required>
                <option value="<?php echo $row['supplier']; ?>"><?php echo $row['supplier']; ?></option>
                <?php
                // Récupérer les fournisseurs depuis la base de données
                $suppliers = $conn->query("SELECT * FROM supplier");
                while ($row2 = $suppliers->fetch(PDO::FETCH_ASSOC)) {?>
                  <option value="<?php $row2['name'] ?>"> <?php echo $row2['name'] ?></option>;
                <?php
                }
                ?>
            </select>
            

      <input type="file" class="box" name="product_image"  accept="image/png, image/jpeg, image/jpg">
      <input type="submit" value="Mettre à jour" name="update_product" class="btn">
      <a href="stock.php" class="btn">Retour!</a>
   </form>
   


   <?php }; ?>

   

</div>

</div>

</body>
</html>
