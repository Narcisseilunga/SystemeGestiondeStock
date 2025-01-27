<?php
require_once 'db.php';

session_start();

$email = $_SESSION['user']['email']; 
DataBaseAppChat::changeEtat($email, 0);

session_unset();

session_destroy();

header("location:login.php");
exit;
