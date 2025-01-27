<?php

define('UPLOAD_DIR', 'uploads/'); // Dossier où enregistrer les images


class DataBaseAppChat
{

    // Propriétés privées pour la connexion à la base de données
    private $host = 'localhost'; // L'adresse de votre serveur de base de données
    private $db   = 'system_vente';
    private $user = 'root';       // Le nom d'utilisateur
    private $pass = '';           // Le mot de passe
    private $dsn;                 // La chaîne de connexion (DSN)
    private $cnx;                 // L'objet PDO pour la connexion

    // Constructeur pour initialiser la connexion
    public function __construct()
    {
        // Initialisation de la chaîne de connexion DSN
        $this->dsn = "mysql:host={$this->host};dbname={$this->db};charset=utf8";
    }

    // Méthode pour établir la connexion
    public function connect()
    {
        try {
            // Création de l'instance PDO et connexion à la base de données
            $this->cnx = new PDO($this->dsn, $this->user, $this->pass);

            // Configuration des options de PDO pour gérer les erreurs
            $this->cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->cnx->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

            // echo "Connexion réussie à la base de données.";
        } catch (PDOException $e) {
            // Gestion des erreurs en cas de problème de connexion
            // echo "Erreur de connexion : " . $e->getMessage();
        }
    }

    // Méthode pour fermer la connexion
    public function disconnect()
    {
        $this->cnx = null;
    }

    // Méthode pour obtenir l'objet PDO (connexion)
    public function getConnection()
    {
        return $this->cnx;
    }



    public static function checkUser($email, $pass = null, $id = null)
    {
        $connex = new DataBaseAppChat();
        $connex->connect();

        if ($email != null  and $pass != null) {
            $stmnt =  $connex->getConnection()->prepare('SELECT * FROM `users` WHERE email=? and password = ?');
            $stmnt->execute([$email, $pass]);
        } else if ($email != null) {
            $stmnt =  $connex->getConnection()->prepare('SELECT * FROM `users` WHERE email=?  ');
            $stmnt->execute([$email]);
        } else if ($id != null) {
            $stmnt =  $connex->getConnection()->prepare('SELECT * FROM `users` WHERE id=?  ');
            $stmnt->execute([$id]);
        }

        $u =  $stmnt->fetch();

        return $u ? $u : null;
    }

    public static function getAllUserWhere($email, $name, $id)
    {
        $connex = new DataBaseAppChat();
        $connex->connect();
        if ($email != "") {
            $stmnt =  $connex->getConnection()->prepare('SELECT * FROM `users` WHERE email=?  ');
            $stmnt->execute([$email]);
        } else if ($name != "*") {
            $stmnt =  $connex->getConnection()->prepare('SELECT * FROM `users` WHERE name like ?  ');
            $stmnt->execute(["%$name%"]);
        } else if ($name == "*") {
            $stmnt =  $connex->getConnection()->prepare('SELECT * FROM `users` order by name ');
            $stmnt->execute();
        } else if ($id != "") {
            $stmnt =  $connex->getConnection()->prepare('SELECT * FROM `users` WHERE id=?  ');
            $stmnt->execute([$id]);
        } else {
            $stmnt =  $connex->getConnection()->prepare('SELECT * FROM `users`  ');
            $stmnt->execute();
        }
        $u =  $stmnt->fetchAll();

        return $u ? $u : null;
    }


    public static function addUser($name, $email, $img, $pass)
    {
        $connx = new DataBaseAppChat();
        $connx->connect(); // Assurez-vous que la connexion est établie

        $connx = $connx->getConnection();

        try {
            $user = self::checkUser($email, $pass, null);
            if ($user != null) {
                return false;
            }
            // $pass=password_hash($pass,PASSWORD_DEFAULT);
            $stmt =   $connx->prepare("INSERT INTO `users`( `name`, `email`, `img`, `password`,`etat` ) VALUES (?,?,?,?,0) ");

            $stmt->execute([$name, $email, $img, $pass]);

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException) {
            return false;
        }
    }


    public static function changeEtat($email, $etat)
    {
        try {
            $cnx = new DataBaseAppChat();
            $cnx->connect();
            $stmt = $cnx->getConnection()->prepare('UPDATE  users  SET etat =? WHERE email=? ');

            $stmt->execute([$etat, $email]);
        } catch (PDOException $e) {
            return 'Erreur : ' . $e->getMessage();
        }
    }

    public static function maxCharText($txt, $lenght)
    {
        if (strlen($txt) > $lenght) {
            return substr($txt, 0, $lenght) . " ...";
        }
        return  $txt . " ...";
    }



    public static  function  getAllMsgSendRecep($send, $recep)
    {
        $cnx = new DataBaseAppChat();
        $cnx->connect();
        $pdo = $cnx->getConnection();
        $query = "where 
        user_msg_send = ? and user_msg_recep = ? 
    or  user_msg_send = ? and user_msg_recep = ?";
        $stmt = $pdo->prepare("SELECT * FROM `messages` $query order by id_msg ASC ");
        $stmt->execute([$send, $recep, $recep, $send]);

        $messages = $stmt->fetchAll();

        return $messages;
    }

    public static function addMsg($msg, $id_Send, $id_Recep)
    {
        try {

            $cnx = new DataBaseAppChat();
            $cnx->connect();
            $pdo = $cnx->getConnection();

            $stmnt = $pdo->prepare("INSERT INTO `messages`( `msg` , `user_msg_send`, `user_msg_recep`) VALUES (?,?,?)");
            $stmnt->execute([$msg, $id_Send, $id_Recep]);
            return true;
        } catch (PDOException $err) {
            return false;
        }

        /*
 try {
            $cnx = new DataBaseAppChat();
            $cnx->connect();
            $stmt = $cnx->getConnection()->prepare('UPDATE  users  SET etat =? WHERE email=? ');

            $stmt->execute([$etat, $email]);
        } catch (PDOException $e) {
            return 'Erreur : ' . $e->getMessage();
        }
        */
    }
}

//    DataBaseAppChat::addUser("1111", "2222", "3333", "4444") ;
