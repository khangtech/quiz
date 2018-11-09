<?php session_start() ; ?>

<?php

if (!empty($_SESSION['admin_info'])) {
  unset($_SESSION['admin_info']);
  //session_destroy();
  header('Location: login.php');
}

?>
