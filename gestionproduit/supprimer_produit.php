<?php
@include 'config.php';
$id = $_GET['delete'];
$nom_entreprise = $_SESSION['nom_entreprise'];
if(isset($_GET['delete'])){
    $update_data = "DELETE FROM product WHERE id = '$id'";
    $upload = $conn->exec($update_data);
    if($upload){
         header('location:stock.php');
    } else {
         $message[] = 'Champs obligatoire!'; 
    }
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
      foreach($message as $msg){
         echo '<span class="message">'.$msg.'</span>';
      }
   }
?>
<div class="container">
<div class="admin-product-form-container centered">
   <?php      
      $select = $conn->query("SELECT * FROM product WHERE id = '$id'");
      while($row = $select->fetch(PDO::FETCH_ASSOC)){
   ?>   
   <div>
      <h3 class="title">L'article a été supprimé</h3>
       <a href="index.php" class="btn">Retour!</a>
    </div>
   <?php }; ?>
</div>
</div>
</body>
</html>