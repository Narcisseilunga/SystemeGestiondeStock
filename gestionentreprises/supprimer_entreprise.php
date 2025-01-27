<?php
session_start();
$host = 'localhost'; 
$dbname = 'system_vente'; 
$username = 'root'; 
$password = '';
try {
    $con = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erreur de connexion : ' . $e->getMessage();
    exit;
}
if (!isset($_GET['id'])) {
  die("vous n'etes pas autorisé à accéder à cette page");
}
$db = $con;
  $id = $_GET['id'];
  $stmt = $db->prepare('DELETE FROM entreprise WHERE id = :id');
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  if ($stmt->execute()) {
    $counterFile = 'compteur'.$_SESSION['$nom_entreprise'].'.txt';
    if (file_exists($counterFile)) {
        // Supprimer le fichier
        if (unlink($counterFile)) {
            echo "Le fichier a été supprimé avec succès.";
        } else {
            echo "Erreur lors de la suppression du fichier.";
        }
    } else {
        echo "Le fichier n'existe pas.";
    }
    header("Location: entreprises.php");
    exit;
  } else {
    echo "Erreur lors de la suppression du fournisseur.";
  }
?>