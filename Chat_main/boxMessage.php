<?php

require 'redirect.php';
include 'db.php';

$page_title = "Page Chat White Frend";

include 'header.php';


if (!isset($_GET['id_User'])) {
    echo '<div class="text-center alert alert-danger m-5 ">No User Here </div>';
    exit;
} else {




    $my_email =  $_SESSION['user']['email'];
    $my_user = DataBaseAppChat::checkUser($my_email);


    $my_img = UPLOAD_DIR . $my_user->img;


    $idUserResept = intval($_GET['id_User']);
    $user = DataBaseAppChat::checkUser(NULL, NULL, $idUserResept);
    $img = UPLOAD_DIR . $user->img;
    $etat =   $user->etat  == 1 ? "Online" : "Ofline";// sup
    $class = ($etat == "Online") ? "text-success" : "text-danger";// sup
    $name = $user->name;
    $name = DataBaseAppChat::maxCharText($name, 16);
    // var_dump($my_user);
    // echo '<br>-----------------<br>'; 
    // var_dump($user);
?>

    <div class="msg-page d-flex align-items-center justify-content-center ">



        <div class="box-chat rounded  rounded-4  ">

            <header class="d-flex  align-items-center border-bottom border-white px-3 py-2">
                <div class="me-auto d-flex align-items-center justify-content-center ">
                    <a href="./chat_page.php">
                        <i class="fa-solid fa-arrow-left-long fs-2 "></i>
                    </a>
                    <img class="rounded-circle mx-2 my-1   border border-1 border-black" width="50px" height="50px" src="./<?= $img  ?>" alt="No Image Profile">
                    <div class="info-user ">
                        <h5><?= $name   ?></h5>
                        <p class="m-1  line fw-bolder"> </p>
                    </div>
                </div>
                <a href="logout.php" class="btn  btn-dark ms-auto  rounded-end-5   ">logout</a>
            </header>

            <div class="body-msg ">
            </div>

            <form class="form p-3  chat-form" method="POST">
                <div class="btn-serhc d-flex ">
                    <div class="input-group me-2">
                        <input type="hidden" name="id_Recep" value="<?= $idUserResept ?>">
                        <input class="form-control inpt-msg" value="new msg here" type="tetx" name="msg" placeholder="Rechercher..." aria-label="Search">
                    </div>

                    <button class="btn btn-outline-danger me-2" type="button" onclick="videChamp('.inpt-msg')"><i class="fa-solid fa-delete-left"></i></button>

                    <button class="btn btn-outline-primary btn-send-msg " type="submit">
                        <i class="fa-regular fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>

    </div><br>

   
<?php
}
include 'footer.php';

?>