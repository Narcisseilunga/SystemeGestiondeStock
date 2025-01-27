<?php
//insere msg dans bd 

header('Content-Type: application/json'); // En-tête JSON

$stat = false;
$allMsg = "Error To Send Msg  ";
$line=false;
 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id_Recep = $_POST['id_Recep'] ?? "";
    $msg = $_POST['msg'] ?? "";
    $isClick =  boolval($_POST['isClick']) ?? false;
 
    // Sécuriser les données (prévenir les attaques XSS)
    $id_Recep = htmlspecialchars($id_Recep, ENT_QUOTES, 'UTF-8');
    $msg = htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');


 
    if (!empty($id_Recep)) {

        $stat = true; 
        require_once 'db.php';

         
        $userRecep=DataBaseAppChat::checkUser(null,null,$id_Recep);
        if($userRecep){
            $line=  $userRecep->etat == 1 ? true : false ;
        }

        session_start();
        $myEmail = $_SESSION['user']['email'];
        $getUsersByEmail = DataBaseAppChat::checkUser($myEmail);


        if (is_null($getUsersByEmail)) {
            $stat = false;
        } else {
            $my_id = $getUsersByEmail->id;


            if ($isClick  && !empty($msg)) {
                $stat = DataBaseAppChat::addMsg($msg, $my_id, $id_Recep);
            }
            $stat = true;
 
            include 'data.php';

            $allMsg = $allMsgData;

            // echo json_encode([
            //     'allMsg' => $allMsg,
            //     'etatMsg' => $stat,
            //     'line' => $line,
            //     'ok' => true,
            // ]);
            // exit;
        }
    } else {
        $stat = false;
    }
} else {
    $stat = false;
} 

echo json_encode([
    'allMsg' => $allMsg,
    'etatMsg' => $stat,
    'line' => $line,
    'ok' => true,
]);
exit;
