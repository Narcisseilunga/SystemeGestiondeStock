<?php
// Chemin vers le fichier log
$logFile = 'log.txt';

// Récupérer les données JSON envoyées via AJAX
$data = json_decode(file_get_contents('php://input'), true);

// Vérifier si les données sont valides
if (isset($data['email']) && isset($data['action'])) {
    $email = htmlspecialchars($data['email']);
    $action = htmlspecialchars($data['action']);
    $productName = htmlspecialchars($data['productName']);

    // Vérifier si un commentaire a été fourni
    $comment = isset($data['comment']) ? htmlspecialchars($data['comment']) : null;
    $commentId = isset($data['commentId']) ? htmlspecialchars($data['commentId']) : null; // Pour les commentaires sur un commentaire

    // Enregistrer l'action dans le fichier log
    logAction($email, $action, $productName, $comment, $commentId);
    
    // Répondre avec un statut d'OK
    http_response_code(200);
} else {
    // Répondre avec un statut d'erreur
    http_response_code(400);
}

// Fonction pour enregistrer les actions dans le fichier log
function logAction($email, $action, $productName, $comment = null, $commentId = null) {
    global $logFile;
    $action = html_entity_decode($action);
    $comment = html_entity_decode($comment);

    // Connexion à la base de données
    $host = 'localhost';
    $dbname = 'system_vente';
    $username = 'root';
    $password = '';

    try {
        $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo 'Erreur de connexion : ' . $e->getMessage();
        exit;
    }

    // Vérifiez d'abord si l'utilisateur a déjà aimé ce produit ou un commentaire
    if ($action == "j'aime") {
        $hasLiked = false;
        $logEntries = [];

        // Lire le fichier log pour vérifier les actions précédentes
        if (file_exists($logFile)) {
            $lines = file($logFile);
            foreach ($lines as $line) {
                $parts = explode(' | ', trim($line));
                if (count($parts) < 3) {
                    continue; // Ignorez les lignes qui n'ont pas assez de données
                }

                $loggedEmail = $parts[1];
                $loggedAction = $parts[2];
                $loggedProduct = isset($parts[3]) ? trim($parts[3]) : '';
                $loggedCommentId = isset($parts[4]) ? trim($parts[4]) : '';

                // Vérifiez si l'utilisateur a déjà aimé ce produit ou un commentaire
                if ($loggedEmail === $email && $loggedAction == "j'aime" && 
                    (strpos($loggedProduct, "Produit: $productName") !== false || 
                     ($commentId && $loggedCommentId == $commentId))) {
                    $hasLiked = true; // L'utilisateur a déjà aimé le produit ou le commentaire
                } else {
                    // Conservez les autres entrées
                    $logEntries[] = $line;
                }
            }
        }

        // Si l'utilisateur a aimé, supprimez l'entrée existante
        if ($hasLiked) {
            file_put_contents($logFile, implode(PHP_EOL, $logEntries) . PHP_EOL);
        } else {
            // Ajoutez l'action "J'aime" au log
            $timestamp = date('Y-m-d H:i:s');
            $entry = "$timestamp | $email | $action | Produit: $productName";
            if ($commentId) {
                $entry .= " | CommentId: $commentId"; // Ajout de l'ID du commentaire
            }
            if ($comment) {
                $entry .= " | Comment: $comment";
            }
            file_put_contents($logFile, $entry . PHP_EOL, FILE_APPEND);
        }
    } else {
        // Pour d'autres actions (comme commenter), ajoutez directement au log
        $timestamp = date('Y-m-d H:i:s');
        $entry = "$timestamp | $email | $action | Produit: $productName";
        if ($commentId) {
            $entry .= " | CommentId: $commentId";
        }
        if ($comment) {
            $entry .= " | Comment: $comment";
        }
        file_put_contents($logFile, $entry . PHP_EOL, FILE_APPEND);
    }
}

// Fonction pour afficher les "J'aime" et les commentaires
function displayLikesAndComments($productName) {
    global $logFile;
    $likesCount = 0;
    $comments = [];
    $commentLikes = [];

    // Connexion à la base de données
    $host = 'localhost';
    $dbname = 'system_vente';
    $username = 'root';
    $password = '';

    try {
        $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo 'Erreur de connexion : ' . $e->getMessage();
        exit;
    }

    // Lire le fichier log pour compter les "J'aime" et récupérer les commentaires
    if (file_exists($logFile)) {
        $lines = file($logFile);
        foreach ($lines as $line) {
            $parts = explode(' | ', trim($line));
            if (count($parts) < 3) {
                continue; // Ignorez les lignes qui n'ont pas assez de données
            }

            $action = html_entity_decode($parts[2]); // Décoder les entités HTML
            $productLog = isset($parts[3]) ? $parts[3] : '';

            // Vérifier si le produit correspond
            if (strpos($productLog, "Produit: $productName") !== false) {
                if ($action == "j'aime") { // Comparaison après décodage
                    $likesCount++;
                }
                if (strpos($line, 'Comment:') !== false) {
                    $commentPart = explode("Comment: ", $line);
                    $comment = isset($commentPart[1]) ? trim($commentPart[1]) : null; // Vérifiez si le commentaire existe
                    $commentEmail = $parts[1]; // Email de l'utilisateur qui a commenté
                    $commentId = uniqid(); // Générer un ID unique pour le commentaire
                    
                    global $db; // Assurez-vous que la connexion à la base de données est accessible
                    $stmt = $db->prepare("SELECT img, name FROM users WHERE email = :email");
                    $stmt->bindParam(':email', $commentEmail);
                    $stmt->execute();
                    
                    $result = $stmt->fetch(PDO::FETCH_ASSOC); // Récupérer toutes les colonnes
                    
                    if ($comment && $result) {
                        $comments[] = [
                            'text' => $comment,
                            'email' => $commentEmail,
                            'id' => $commentId,
                            'img' => $result['img'],
                            'name' => $result['name'] // Ajoutez le nom de l'utilisateur
                        ];
                    }
                }
            }
        }
    }

    return [$likesCount, $comments];
}

