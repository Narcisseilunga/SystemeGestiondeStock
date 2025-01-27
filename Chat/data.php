<?php 

global $allMsgData, $my_id, $id_Recep;

$allMsgData = '<div class="no-msg h-100 d-flex justify-content-center align-items-center">
    <div class="alert alert-danger mx-2 my-5 text-center">No Messages ....</div>
</div>';

if (isset($my_id, $id_Recep)) {
    $msgArray = DataBaseAppChat::getAllMsgSendRecep($my_id, $id_Recep);

    if (!empty($msgArray)) {
        $allMsgData = ""; // Réinitialiser $Allmsgggg
        foreach ($msgArray as $msg) {
            $how = ($msg->user_msg_send == $id_Recep) ? "his" : "my";
            $allMsgData .= '<div class="' . $how . ' msg"> 
            <p>' . htmlspecialchars($msg->msg, ENT_QUOTES, 'UTF-8') . '</p>  </div>';
        }
    }
}

?>