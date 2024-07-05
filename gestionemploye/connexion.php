<?php
  // Connexion à la base de données SQLite
  try {
      $con = new PDO("sqlite:/path/to/your/database.db");
      // Définir le mode d'erreur de PDO sur Exception
      $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      echo "Vous êtes connecté à la base de donnée SQLite";
  } catch (PDOException $e) {
      echo "Vous n'êtes pas connecté à la base de donnée: " . $e->getMessage();
  }
?>