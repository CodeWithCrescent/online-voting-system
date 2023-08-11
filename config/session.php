<?php
if (!isset($_SESSION['login_id'])) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header("location:login.php");
    exit();
  }
?>