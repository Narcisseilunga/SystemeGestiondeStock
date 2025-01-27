<?php  
session_start();


if(! isset(  $_SESSION["user"]) ){
    header("location:Chat_main/login.php");
}

?> 
