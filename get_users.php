<?php
$pdo = new PDO('mysql:host=localhost;dbname=system_vente', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $pdo->query("SELECT id, nom, prenom FROM compte");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($users);
?>