<?php
require __DIR__ . '/vendor/autoload.php'; // Incluez l'autoload de Composer

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

error_reporting(E_ALL & ~E_DEPRECATED);

class Chat implements MessageComponentInterface {
    protected $clients;
    private $db;

    public function __construct() {
        $this->clients = new \SplObjectStorage;

        // Connexion à la base de données
        $this->db = new PDO('mysql:host=localhost;dbname=system_vente;charset=utf8', 'root', '');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "Un nouveau client est connecté.\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        // Supposons que le message est au format JSON : {"username": "nom", "message": "texte"}
        $data = json_decode($msg, true);
        $username = $data['sender_id'];
        $recipient_id = $data['recipient_id'];
        $message = $data['message'];
        $temp = date("Y-m-d H:i:s");

        // Insertion dans la base de données
        $stmt = $this->db->prepare("INSERT INTO message (name, recipient_id, obj,temp) VALUES (:username, :recipient_id, :message,:temp)");
        $stmt->execute(['username' => $username,'recipient_id' => $recipient_id, 'message' => $message, 'temp' => $temp]);

        // Envoyer le message à tous les clients
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Un client s'est déconnecté.\n";
    }

    public function onError(ConnectionInterface $conn, $e) {
        echo "Une erreur s'est produite : {$e->getMessage()}\n";
        $conn->close();
    }
}

$server = Ratchet\Server\IoServer::factory(
    new Ratchet\Http\HttpServer(
        new Ratchet\WebSocket\WsServer(new Chat())
    ),
    8080
);
$server->run();