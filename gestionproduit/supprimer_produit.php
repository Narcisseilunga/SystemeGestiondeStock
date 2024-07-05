<?php

@include 'config.php';

$id = $_GET['delete'];

if(isset($_GET['delete'])){
 
    $update_data = "DELETE from products WHERE id = '$id'";
    $upload = mysqli_query($conn, $update_data);

    if($upload){
         move_uploaded_file($product_image_tmp_name, $product_image_folder);
         header('location:stock.php');
    }else{
         $$message[] = 'Champs obligatoire!'; 
      }
};

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
      
      $select = mysqli_query($conn, "SELECT * FROM products WHERE id = '$id'");
      while($row = mysqli_fetch_assoc($select)){

   ?>
   
   <div>
      <h3 class="title">L'article a été supprimer</h3>
       <a href="index.php" class="btn">Retour!</a>
    </div>
   


   <?php }; ?>

   

</div>

</div>

</body>
</html>
