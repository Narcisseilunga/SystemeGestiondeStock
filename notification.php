<?php
// Connexion à la base de données MySQLi
session_start();
require "db.php";
$db = $conn;
//$nom_entreprise = $_SESSION['nom_entreprise'];
$email_verify = $_SESSION['user']['email'];

// Récupération des messages pour l'entreprise spécifiée
$email_query = "SELECT id FROM users WHERE email = ?"; 
$stmt = $db->prepare($email_query);
$stmt->bind_param("s", $email_verify);
$stmt->execute();
$result = $stmt->get_result(); 

// Récupérer l'ID de l'utilisateur
$id_user = null;
if ($row = $result->fetch_assoc()) {
    $id_user = $row['id'];
}

$query = "SELECT * FROM messages WHERE user_msg_recep = ? or user_msg_recep = ?  ORDER BY date_msg DESC";
$stmt = $db->prepare($query);
$stmt->bind_param("ss", $id_user, $id_user);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}

// Suppression d'un message
if (isset($_GET['delete'])) {
    $id_msg_to_delete = $_GET['delete'];
    $delete_query = "DELETE FROM messages WHERE id_msg = ?";
    $delete_stmt = $db->prepare($delete_query);
    $delete_stmt->bind_param("s", $id_msg_to_delete);
    $delete_stmt->execute();
    header("Location: notification.php"); // Redirection après suppression
    exit();
}

if(isset($_GET['delete_all'])) {
    $id_msg_to_delete = $_GET['delete_all'];
    $delete_query = "DELETE FROM messages WHERE id_msg = ?";
    $delete_stmt = $db->prepare($delete_query);
    $delete_stmt->bind_param("s", $id_msg_to_delete);
    $delete_stmt->execute();
    header("Location: notification.php"); // Redirection après suppression
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>

    <title>Messages</title>
    <!-- Inclusion de Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Messages</h2>
        <!--<a href="?delete_all=<?php echo htmlspecialchars($message['id_msg']); ?> " onclick="return confirm('Êtes-vous sûr de vouloir supprimer tout les messages ?');">Supprimer tout les messages</a>-->
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Message</th>
                    <th>Date d'envoi</th>
                    <th>Lecture du message</th>
                    <th>Supprimer le message</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $message): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($message['id_msg'] ?? ''); ?></td>
                        <td><a href=""><?php echo htmlspecialchars($message['msg'] ?? ''); ?></a></td>
                        <td><?php echo htmlspecialchars($message['date_msg'] ?? ''); ?></td>
                        <?php if (isset($message['user_msg_send'])) {?>
                            <td><a href="../Chat/boxMessage.php?id_User=<?php echo $message['user_msg_send'] ?>">Lire le message</a></td>
                    
                        <?php }elseif ($message['user_msg_recep']) {?>
                            <td><a href="../Chat/boxMessage.php?id_User=<?php echo $message['user_msg_recep'] ?>">Lire le message</a></td>
                        <?php } ?>
                        <td>
                            <a href="?delete=<?php echo htmlspecialchars($message['id_msg']); ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?');">
                                <img src="https://img.icons8.com/material-outlined/24/000000/trash.png" alt="Supprimer" />
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- Inclusion de Bootstrap JS et de ses dépendances -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <?php
    include "footer_chat.php";
    ?>
</body>
</html>