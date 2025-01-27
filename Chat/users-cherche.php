<?php
header('Content-Type: application/json'); // En-tête JSON


$resul = "Error ";
$stat = false;
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $name = isset($_POST['nameUser']) ? $_POST['nameUser'] : "";

    $stat = true;
    require_once 'db.php';

    session_start();
    $emailUser = $_SESSION['user']['email'];
    $resul = "No User Like This Name";

 

    if (empty($name)) {
        $getAllUsersByName = DataBaseAppChat::getAllUserWhere("", "*", "");
    } else {
        $getAllUsersByName = DataBaseAppChat::getAllUserWhere("", $name, "");
    }
    $resul = "";
 

    if (is_null($getAllUsersByName)) {
        $resul = "<p class=' my-2 text-center text-danger' >No User Like this Name</p>";
    } else {

        foreach ($getAllUsersByName as $user) {
            if ($user->email !=  $emailUser) {

                $etat =  ($user->etat == 1) ? "Online" : "Ofline";
                $class = ($etat == "Online") ? "text-success" : "text-danger";

                $resul .= " <a href='boxMessage.php?id_User=" . $user->id . "'>  
  <div class='d-flex align-items-center border-bottom mt-1'>
  <img class='rounded-circle m-1 me-4 border border-1 border-black' width='50px' height='50px' src='./" . UPLOAD_DIR . $user->img . "' alt='No Image Profile'>
  <div class='info-user d-flex   flex-column'>
      <h5>  $user->name   </h5>
      <p class='no-msg mb-1'> write a message 
      </p> 
  </div>
  <div class='etat ms-auto'>
  <p class='m-1  $class  fw-bolder'> $etat </p>
  </div>
  </div> <a> ";
            }
        }
    }
}



echo json_encode([
    "message" => $resul,
    'ok' => $stat,
]);
exit;







/*
  <div class='d-flex align-items-center'>
<img class='rounded-circle m-1 me-4 border border-1 border-black' width='50px' height='50px' src='./<?= $user->$img  ?>' alt='No Image Profile'>
<div class='info-user d-flex   flex-column'>
    <h5><?= $user->$name   ?></h5>
    <p class='no-msg mb-1'>no messages 
    </p>
    
</div>
<div class='etat ms-auto'>
<p class='m-1   fw-bolder'><?= $user->$etat  ?></p>
</div>
</div>  "; */