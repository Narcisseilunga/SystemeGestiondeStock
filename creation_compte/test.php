<?php
session_start(); // Démarrer la session

$host = 'localhost'; // Remplacez par votre hôte MySQL
$dbname = 'system_vente'; // Remplacez par le nom de votre base de données
$username = 'root'; // Remplacez par votre nom d'utilisateur MySQL
$password = ''; // Remplacez par votre mot de passe MySQL

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erreur de connexion : ' . $e->getMessage();
    exit;
}
$db = $conn;

// Récupération des données POST
$email = $_POST['email'];
$password = $_POST['password'];

// Préparation de la requête SQL pour comparer l'email et le mot de passe
$query = $db->prepare("SELECT * FROM employee WHERE email = :email AND password = :password");
$query->bindParam(':email', $email);
$query->bindParam(':password', $password);

// Exécution de la requête
$query->execute();


/*$query2 = $db->prepare("SELECT * FROM compte WHERE email = :email AND password = :password");
$query2->bindParam(':email', $email);
$query2->bindParam(':password', $password);

// Exécution de la requête
$query2->execute();*/

// Vérification des résultats
if ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    echo "Utilisateur trouvé :<br>";
        $type_employe = $row['type'];
        // Rediriger l'utilisateur en fonction de son type d'employé
        if ($type_employe == 'Admin' || $type_employe == 'Super_Admin') {
            $_SESSION['id_utilisateur'] = $row['id'];
            $_SESSION['img_utilisateur'] = $row['image_emp'];
            $_SESSION['info_utilisateur'] = $row['info_emp'];
            $_SESSION['nom_utilisateur'] = $row['name'];
            $_SESSION['email_utilisateur'] = $row['email'];
            $_SESSION['nom_entreprise'] = $row['nom_entreprise'];
            $_SESSION['type'] = $row['type'];
            $_SESSION["user"] = ["email" =>  $row['email'], "img" =>  $$row['image_emp'], "etat" =>  1];

            header("Location: ../Dashboard/index.php");
            exit;
        } else {
            echo "Type d'employé non reconnu.";
        }
    
}/*elseif ($row2 = $query2->fetch(PDO::FETCH_ASSOC)) {
        # code...
        $type_client = $row2['type'];
        if ($type_client == 'client') {
        $_SESSION['id_utilisateur'] = $row2['id'];
        $_SESSION['nom_entreprise'] = $row2['nom_entreprise'];
        header("Location: ../panier.php");

        exit;}
}*/else {
    echo "Aucun utilisateur trouvé avec cet email et ce mot de passe.";
}

// Fermeture de la connexion
$db = null;
?>