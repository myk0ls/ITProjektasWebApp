<?php
   // Start the session
   session_start();

   if(!isset($_SESSION['login_user']) && !isset($_SESSION['login_user_id'])){
      header("location: index.php");
      die();
   }

   
   $login_session = $_SESSION['login_user'];
   $user_id = $_SESSION['login_user_id'];
?>