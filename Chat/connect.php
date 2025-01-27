<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD']  == "POST") {



    if (!isset($_POST["form"]) ||  !isset($_POST["email"]) || !isset($_POST["pass"])) {
        echo json_encode([
            "message" => "Données incomplètes.",
            "ok" => false,
        ]);
        exit;
    }



    $email =    $_POST["email"];
    $password = $_POST["pass"];
    $form =     $_POST["form"];

    $response = [];




    // $img_name=$img['name'];
    // $img_size=$img['size'];
    // $img_path=$img['temp_path'];
    // $img_name=$img['error'];



    if ($form ==  "regester"):

        $namComp =  $_POST["nameComplt"];


        define('MAX_FILE_SIZE', 4 * 1024 * 1024); // Taille maximale de 4 Mo


        if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
            // Récupérer les informations sur le fichier
            $img = $_FILES['img'];
            $fileTmpPath = $img['tmp_name'];
            $fileName = $img['name'];
            $fileSize = $img['size'];
            $fileType = $img['type'];


            // Définir un répertoire de destination pour l'image (à ajuster selon vos besoins)


            if (!is_dir(UPLOAD_DIR)) {
                mkdir(UPLOAD_DIR, 0777, true);
            }



            if ($fileSize > MAX_FILE_SIZE) {
                echo json_encode([
                    "message" => "Size Image > 4 mb s",
                    "ok" => false,
                ]);
                exit;
            }


            $chek = DataBaseAppChat::checkUser($email);


            $ook = true;
            $msg = "Successfy Added";
            $nameIMG = uniqid() . "_" . basename($fileName);
            move_uploaded_file($fileTmpPath,   UPLOAD_DIR . "/" . $nameIMG);

            if (!is_null($chek)) { // si existe
                $ook = false;
                $msg = "This Account is Exist  ";
            } else {
                $password = password_hash($password, PASSWORD_DEFAULT);
                DataBaseAppChat::addUser($namComp, $email, $nameIMG, $password);
            }

            $response = array(
                'message' => $msg,
                "ok" => $ook,
            );
        } else {
            echo json_encode([
                "message" => "No Image To upload",
                "ok" => false,
            ]);
            exit;
        }
    endif;

    if ($form ==  "login"):

        $chek = DataBaseAppChat::checkUser($email);

        $ook = true;
        $msg = "Successfy Login";

        if (is_null($chek)) {
            $ook = false;
            $msg = "You Don't Have Account  ";
        } else {
            $isEquelPass = password_verify($password, $chek->password);

            if ($isEquelPass) {
                DataBaseAppChat::changeEtat($email, 1);
                
                session_start();
                $_SESSION["user"] = ["email" => $email, "img" =>  $chek->img, "etat" =>  1];

              
                
              
            } else {
                $ook = false;
                $msg = "   Password Incorect";
            }
        }

        $response = array(
            'message' => $msg,
            "ok" => $ook,
        );

    endif;

    echo json_encode($response);
    exit;
} else {

    echo json_encode([
        "message" => "Rederection Incorect",
        "ok" => false,
    ]);
    exit;
}
