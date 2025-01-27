<?php
session_start();
if (isset($_GET['recipient_id'])) {
    $recipientId = $_GET['recipient_id'];
} else {
    die("ID du destinataire manquant.");
}

require "db.php";
$stmt = $conn->prepare("SELECT name, obj, temp FROM message WHERE name=? and recipient_id=? ORDER BY temp DESC LIMIT 10");
$stmt->bind_param("ss", $_SESSION['nom_utilisateur'],$recipientId ); // Utiliser bind_param pour éviter les injections SQL
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

// Retourner les messages au format JSON
header('Content-Type: application/json');
echo json_encode($messages);
?>
