<?php
//insere msg dans bd 

header('Content-Type: application/json'); // En-tête JSON

$resul = "Error ";
$stat = false;



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les valeurs des champs input
    $id_Recep = isset($_POST['id_Recep']) ?? ''; // Récupère le champ "name"
    $msg = isset($_POST['msg']) ?? '';   // Récupère le champ "msg" 

    // Sécuriser les données (prévenir les attaques XSS)
    $id_Recep = htmlspecialchars($id_Recep, ENT_QUOTES, 'UTF-8');
    $msg = htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');


    if (!empty($id_Recep) && !empty($msg)) {

        $stat = true;
        require_once 'db.php';

        session_start();
        $myEmail = $_SESSION['user']['email'];
        $resul = "No User Like This Name";

        $getUsersByEmail = DataBaseAppChat::checkUser($myEmail);
        $resul = "";

        if (is_null($getUsersByEmail)) {
            $resul = "<p class=' my-2 text-center text-danger' >No User Like this Email</p>";
            $stat = false;
        } else {

            $my_id = $getUsersByEmail->id;

            $resul   = "YEsss  my_id= $my_id   id_Recep= $id_Recep  msg= $msg";
        }
        echo json_encode([
            "message" => $resul,
            'ok' => $stat,
        ]);
        exit;
    } else {
        echo json_encode([
            "message" => "Input Is Null",
            'ok' => $stat,
        ]);
        exit;
    }
} else {
    echo json_encode([
        "message" => "Fallse no  POSt",
        'ok' => $stat,
    ]);
    exit;
}
echo json_encode([
    "message" => "Fiiiiiiiiiiiiin  ",
    'ok' => true,
]);
exit;
