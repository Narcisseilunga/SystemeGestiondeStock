<?php

try {
    $conn = new PDO('sqlite:/path/to/your/database.db');
    // Définir le mode d'erreur PDO sur Exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion réussie à SQLite";
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}

?>